<?php
/**
 * REST endpoints: POST crosspoint/v1/checkout and POST crosspoint/v1/verify-checkout
 *
 * Port of the static site's tools/create-checkout.php and verify-checkout.php.
 * The wizard creates a server-side Stripe Checkout Session, so the restricted
 * key stays on the server:
 *
 *     define( 'CPF_STRIPE_SECRET', 'rk_live_xxx' );
 *
 * Prices are authoritative on the server and are read from the Packages CPT.
 * The browser sends only ids; any amount it sends is ignored entirely.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register both checkout routes.
 *
 * @return void
 */
function cpf_register_checkout_routes() {
	register_rest_route(
		'crosspoint/v1',
		'/checkout',
		array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => 'cpf_rest_checkout',
			'permission_callback' => '__return_true',
			'args'                => array(
				'plan'         => array(
					'required'          => true,
					'sanitize_callback' => 'sanitize_key',
				),
				'email'        => array(
					'required'          => true,
					'sanitize_callback' => 'sanitize_email',
					'validate_callback' => 'is_email',
				),
				'name'         => array( 'sanitize_callback' => 'sanitize_text_field' ),
				'phone'        => array( 'sanitize_callback' => 'sanitize_text_field' ),
				'country'      => array( 'sanitize_callback' => 'sanitize_text_field' ),
				'company_name' => array( 'sanitize_callback' => 'sanitize_text_field' ),
				'backup_name'  => array( 'sanitize_callback' => 'sanitize_text_field' ),
				'entity'       => array( 'sanitize_callback' => 'sanitize_text_field' ),
				'state'        => array( 'sanitize_callback' => 'sanitize_text_field' ),
				'state_key'    => array( 'sanitize_callback' => 'sanitize_key' ),
				'form_country' => array( 'sanitize_callback' => 'sanitize_key' ),
				'business'     => array( 'sanitize_callback' => 'sanitize_text_field' ),
				'offer'        => array( 'sanitize_callback' => 'rest_sanitize_boolean' ),
				'lead_id'      => array( 'sanitize_callback' => 'sanitize_text_field' ),
				'addons'       => array(
					'sanitize_callback' => function ( $value ) {
						$value = is_array( $value ) ? $value : array();

						return array_slice( array_map( 'sanitize_key', $value ), 0, 12 );
					},
				),
			),
		)
	);

	register_rest_route(
		'crosspoint/v1',
		'/verify-checkout',
		array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => 'cpf_rest_verify_checkout',
			'permission_callback' => '__return_true',
			'args'                => array(
				'session_id' => array(
					'required'          => true,
					'sanitize_callback' => 'sanitize_text_field',
					'validate_callback' => function ( $value ) {
						return (bool) preg_match( '/^cs_[A-Za-z0-9_]+$/', (string) $value );
					},
				),
			),
		)
	);
}
add_action( 'rest_api_init', 'cpf_register_checkout_routes' );

/**
 * Add-on keys that are never charged on the page. They are recorded in the
 * session metadata for manual follow-up instead.
 *
 * @return string[]
 */
function cpf_on_request_addons() {
	$keys = array();

	foreach ( cpf_get_packages( 'addon' ) as $addon ) {
		if ( '' !== (string) get_post_meta( $addon->ID, '_cpf_flag', true ) ) {
			$keys[] = sanitize_key( (string) get_post_meta( $addon->ID, '_cpf_key', true ) );
		}
	}

	return array_filter( $keys );
}

/**
 * Create a Stripe Checkout Session.
 *
 * @param WP_REST_Request $request Incoming request.
 * @return WP_REST_Response|WP_Error
 */
function cpf_rest_checkout( WP_REST_Request $request ) {
	if ( ! wp_verify_nonce( (string) $request->get_header( 'X-WP-Nonce' ), 'wp_rest' ) ) {
		return new WP_Error( 'cpf_bad_nonce', __( 'Session expired. Reload the page and try again.', 'crosspoint' ), array( 'status' => 403 ) );
	}

	if ( ! defined( 'CPF_STRIPE_SECRET' ) || '' === CPF_STRIPE_SECRET ) {
		cpf_log( 'checkout: CPF_STRIPE_SECRET is not defined' );

		return new WP_Error( 'cpf_no_key', __( 'Payments are temporarily unavailable.', 'crosspoint' ), array( 'status' => 503 ) );
	}

	if ( ! cpf_rate_limit_ok( 'checkout' ) ) {
		return new WP_Error( 'cpf_rate_limited', __( 'Too many attempts. Please try again in a few minutes.', 'crosspoint' ), array( 'status' => 429 ) );
	}

	$plan_key = (string) $request['plan'];
	$plan     = cpf_get_package_by_key( $plan_key );

	if ( ! $plan instanceof WP_Post ) {
		return new WP_Error( 'cpf_unknown_plan', __( 'Unknown plan.', 'crosspoint' ), array( 'status' => 400 ) );
	}

	$plan_price = cpf_package_price( $plan->ID );

	if ( $plan_price['amount'] <= 0 ) {
		return new WP_Error( 'cpf_unknown_plan', __( 'That plan cannot be purchased online.', 'crosspoint' ), array( 'status' => 400 ) );
	}

	$state     = (string) $request['state'];
	$state_key = (string) $request['state_key'];
	$is_canada = ( 0 === strpos( $plan_key, 'ca' ) ) || 'ca' === (string) $request['form_country'];

	// Canada federal never checks out on the page: it needs the resident-director
	// service and routes to WhatsApp. Enforced here as well as in the wizard, so a
	// stale cached page cannot get past it.
	if ( $is_canada && ( 'federal' === $state_key || false !== stripos( $state, 'federal' ) ) ) {
		return new WP_Error( 'federal_whatsapp', __( 'Federal incorporation is arranged personally.', 'crosspoint' ), array( 'status' => 409 ) );
	}

	// Add-ons: unknown ids dropped, ids not offered on this plan dropped,
	// on-request items never charged.
	$allowed         = array_filter( array_map( 'sanitize_key', explode( ',', (string) get_post_meta( $plan->ID, '_cpf_addon_keys', true ) ) ) );
	$on_request_keys = cpf_on_request_addons();
	$requested       = (array) $request['addons'];
	$billable        = array();
	$on_request      = array();

	foreach ( array_unique( $requested ) as $addon_key ) {
		if ( ! in_array( $addon_key, $allowed, true ) ) {
			continue;
		}

		$addon = cpf_get_package_by_key( $addon_key );

		if ( ! $addon instanceof WP_Post ) {
			continue;
		}

		if ( in_array( $addon_key, $on_request_keys, true ) ) {
			$on_request[] = get_the_title( $addon );
			continue;
		}

		$billable[ $addon_key ] = $addon;
	}

	$company = (string) $request['company_name'];
	$desc    = trim( ( '' !== $company ? $company : __( 'New company', 'crosspoint' ) ) . ( '' !== $state ? ' — ' . $state : '' ) );

	$params = array(
		'mode'                       => 'payment',
		'success_url'                => add_query_arg(
			array(
				'paid'       => 1,
				'session_id' => '{CHECKOUT_SESSION_ID}',
			),
			cpf_page_url( 'start' )
		),
		'cancel_url'                 => add_query_arg( 'canceled', 1, cpf_page_url( 'start' ) ),
		'customer_email'             => (string) $request['email'],
		'client_reference_id'        => (string) $request['lead_id'],
		'billing_address_collection' => 'auto',
		'expires_at'                 => time() + HOUR_IN_SECONDS,
		'payment_intent_data'        => array(
			'description' => mb_substr( 'CrossPoint setup — ' . $desc . ' (' . $plan_key . ')', 0, 500 ),
		),
		'metadata'                   => array(
			'lead_id'                      => (string) $request['lead_id'],
			'source'                       => $is_canada ? 'guided_setup_canada' : 'guided_setup_us',
			'plan'                         => $plan_key,
			'company_name'                 => $company,
			'backup_name'                  => (string) $request['backup_name'],
			'state'                        => $state,
			'entity'                       => (string) $request['entity'],
			'business_type'                => (string) $request['business'],
			'customer_name'                => (string) $request['name'],
			'whatsapp'                     => (string) $request['phone'],
			'country'                      => (string) $request['country'],
			'free_offer_domain_email_site' => $request['offer'] ? 'yes' : 'no',
			'requested_on_request_items'   => mb_substr( implode( '; ', $on_request ), 0, 480 ),
		),
	);

	$params['line_items'] = array(
		array(
			'quantity'   => 1,
			'price_data' => array(
				'currency'     => 'usd',
				'unit_amount'  => (int) round( $plan_price['amount'] * 100 ),
				'product_data' => array(
					'name'        => get_the_title( $plan ),
					'description' => mb_substr( $desc . "\nIncludes:\n• " . implode( "\n• ", cpf_package_features( $plan->ID ) ), 0, 950 ),
				),
			),
		),
	);

	$total = $plan_price['amount'];

	foreach ( $billable as $addon ) {
		$addon_price = cpf_package_price( $addon->ID );
		$total      += $addon_price['amount'];

		$params['line_items'][] = array(
			'quantity'   => 1,
			'price_data' => array(
				'currency'     => 'usd',
				'unit_amount'  => (int) round( $addon_price['amount'] * 100 ),
				'product_data' => array( 'name' => 'Add-on: ' . get_the_title( $addon ) ),
			),
		);
	}

	$params = apply_filters( 'cpf_checkout_params', $params, $plan, $request );

	// Idempotency: a double click inside the same five-minute bucket returns the
	// same session; a genuine second purchase later still gets a fresh one.
	$idempotency = 'cpf_' . md5(
		strtolower( (string) $request['email'] ) . '|' . $plan_key . '|' . implode( ',', array_keys( $billable ) )
		. '|' . (string) $request['lead_id'] . '|' . floor( time() / 300 )
	);

	$response = wp_remote_post(
		'https://api.stripe.com/v1/checkout/sessions',
		array(
			'timeout' => 15,
			'headers' => array(
				'Authorization'   => 'Bearer ' . CPF_STRIPE_SECRET,
				'Content-Type'    => 'application/x-www-form-urlencoded',
				'Idempotency-Key' => $idempotency,
				'Stripe-Version'  => '2024-06-20',
			),
			'body'    => http_build_query( $params ),
		)
	);

	if ( is_wp_error( $response ) ) {
		cpf_log( 'checkout transport error: ' . $response->get_error_message() );

		return new WP_Error( 'cpf_stripe_unreachable', __( 'Could not reach the payment provider.', 'crosspoint' ), array( 'status' => 502 ) );
	}

	$body = json_decode( wp_remote_retrieve_body( $response ), true );
	$code = (int) wp_remote_retrieve_response_code( $response );

	if ( $code >= 200 && $code < 300 && ! empty( $body['url'] ) ) {
		do_action( 'cpf_checkout_created', $body, $plan_key, $total );

		return rest_ensure_response( array( 'url' => esc_url_raw( $body['url'] ) ) );
	}

	// Stripe's message goes to the log only; the browser gets a safe generic one.
	cpf_log( 'Stripe ' . $code . ': ' . ( isset( $body['error']['message'] ) ? $body['error']['message'] : 'unknown' ) );

	return new WP_Error( 'cpf_checkout_failed', __( 'Checkout could not be created.', 'crosspoint' ), array( 'status' => 502 ) );
}

/**
 * Verify a Checkout Session after the visitor returns from Stripe.
 *
 * @param WP_REST_Request $request Incoming request.
 * @return WP_REST_Response|WP_Error
 */
function cpf_rest_verify_checkout( WP_REST_Request $request ) {
	if ( ! defined( 'CPF_STRIPE_SECRET' ) || '' === CPF_STRIPE_SECRET ) {
		return new WP_Error( 'cpf_no_key', __( 'Payments are temporarily unavailable.', 'crosspoint' ), array( 'status' => 503 ) );
	}

	if ( ! cpf_rate_limit_ok( 'verify' ) ) {
		return new WP_Error( 'cpf_rate_limited', __( 'Too many attempts.', 'crosspoint' ), array( 'status' => 429 ) );
	}

	$session_id = (string) $request['session_id'];
	$cache_key  = 'cpf_cs_' . md5( $session_id );
	$cached     = get_transient( $cache_key );

	if ( is_array( $cached ) ) {
		return rest_ensure_response( $cached );
	}

	$response = wp_remote_get(
		'https://api.stripe.com/v1/checkout/sessions/' . rawurlencode( $session_id ),
		array(
			'timeout' => 15,
			'headers' => array(
				'Authorization'  => 'Bearer ' . CPF_STRIPE_SECRET,
				'Stripe-Version' => '2024-06-20',
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		return new WP_Error( 'cpf_stripe_unreachable', __( 'Could not reach the payment provider.', 'crosspoint' ), array( 'status' => 502 ) );
	}

	$session = json_decode( wp_remote_retrieve_body( $response ), true );

	if ( ! is_array( $session ) || empty( $session['id'] ) ) {
		return new WP_Error( 'cpf_unknown_session', __( 'Unknown session.', 'crosspoint' ), array( 'status' => 404 ) );
	}

	$paid   = ( isset( $session['payment_status'] ) && 'paid' === $session['payment_status'] );
	$meta   = isset( $session['metadata'] ) && is_array( $session['metadata'] ) ? $session['metadata'] : array();
	$result = array(
		'ok'           => true,
		'paid'         => $paid,
		'status'       => isset( $session['status'] ) ? sanitize_text_field( $session['status'] ) : '',
		'amount_total' => isset( $session['amount_total'] ) ? (int) $session['amount_total'] : 0,
		'currency'     => isset( $session['currency'] ) ? sanitize_text_field( $session['currency'] ) : 'usd',
		'plan'         => isset( $meta['plan'] ) ? sanitize_text_field( $meta['plan'] ) : '',
		'lead_id'      => isset( $meta['lead_id'] ) ? sanitize_text_field( $meta['lead_id'] ) : '',
	);

	if ( $paid ) {
		// Only a confirmed payment is cached; an unpaid session may still be paid.
		set_transient( $cache_key, $result, HOUR_IN_SECONDS );

		do_action( 'cpf_checkout_paid', $session, $result );
	}

	return rest_ensure_response( $result );
}
