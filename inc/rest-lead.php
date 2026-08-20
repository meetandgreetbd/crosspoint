<?php
/**
 * REST endpoint: POST crosspoint/v1/lead
 *
 * Public by design - every visitor is logged out. Security comes from the REST
 * nonce, a honeypot, a time trap, a per-IP rate limit and a strict argument
 * schema, not from authentication.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

/**
 * Lead sources the endpoint accepts.
 *
 * @return string[]
 */
function cpf_lead_sources() {
	return apply_filters( 'cpf_lead_sources', array( 'start-wizard', 'home-quiz', 'contact' ) );
}

/**
 * Register the lead route.
 *
 * @return void
 */
function cpf_register_lead_route() {
	register_rest_route(
		'crosspoint/v1',
		'/lead',
		array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => 'cpf_rest_lead',
			'permission_callback' => '__return_true',
			'args'                => array(
				'full_name'         => array(
					'required'          => true,
					'sanitize_callback' => 'sanitize_text_field',
				),
				'email'             => array(
					'required'          => true,
					'sanitize_callback' => 'sanitize_email',
					'validate_callback' => 'is_email',
				),
				'whatsapp'          => array( 'sanitize_callback' => 'sanitize_text_field' ),
				'residence_country' => array( 'sanitize_callback' => 'sanitize_text_field' ),
				'business_type'     => array( 'sanitize_callback' => 'sanitize_text_field' ),
				'business_goal'     => array( 'sanitize_callback' => 'sanitize_text_field' ),
				'destination'       => array( 'sanitize_callback' => 'sanitize_text_field' ),
				'structure'         => array( 'sanitize_callback' => 'sanitize_text_field' ),
				'state'             => array( 'sanitize_callback' => 'sanitize_text_field' ),
				'company_name'      => array( 'sanitize_callback' => 'sanitize_text_field' ),
				'backup_name'       => array( 'sanitize_callback' => 'sanitize_text_field' ),
				'package'           => array( 'sanitize_callback' => 'sanitize_text_field' ),
				'addons'            => array( 'sanitize_callback' => 'sanitize_text_field' ),
				'message'           => array( 'sanitize_callback' => 'sanitize_textarea_field' ),
				'source_url'        => array( 'sanitize_callback' => 'esc_url_raw' ),
				'source'            => array(
					'required'          => true,
					'validate_callback' => function ( $value ) {
						return in_array( $value, cpf_lead_sources(), true );
					},
					'sanitize_callback' => 'sanitize_text_field',
				),
				'website'           => array( 'sanitize_callback' => 'sanitize_text_field' ),
				'ts'                => array( 'sanitize_callback' => 'absint' ),
			),
		)
	);
}
add_action( 'rest_api_init', 'cpf_register_lead_route' );

/**
 * Handle a lead submission.
 *
 * @param WP_REST_Request $request Incoming request.
 * @return WP_REST_Response|WP_Error
 */
function cpf_rest_lead( WP_REST_Request $request ) {
	// 1. Nonce.
	if ( ! wp_verify_nonce( (string) $request->get_header( 'X-WP-Nonce' ), 'wp_rest' ) ) {
		return new WP_Error(
			'cpf_bad_nonce',
			__( 'Session expired. Reload the page and try again.', 'crosspoint' ),
			array( 'status' => 403 )
		);
	}

	// 2. Honeypot and time trap: a form filled in under three seconds is a bot.
	$elapsed = time() - absint( $request['ts'] );

	if ( '' !== (string) $request['website'] || ( absint( $request['ts'] ) > 0 && $elapsed < 3 ) ) {
		return rest_ensure_response( array( 'ok' => true ) ); // Silent discard.
	}

	// 3. Rate limit.
	if ( ! cpf_rate_limit_ok( 'lead' ) ) {
		return new WP_Error(
			'cpf_rate_limited',
			__( 'Too many requests. Try again shortly.', 'crosspoint' ),
			array( 'status' => 429 )
		);
	}

	// 4. Assemble and let anything enrich the lead before it is stored.
	$lead = $request->get_params();
	unset( $lead['website'], $lead['ts'] );

	$lead['submitted_at'] = current_time( 'mysql' );

	if ( empty( $lead['source_url'] ) ) {
		$lead['source_url'] = esc_url_raw( (string) $request->get_header( 'referer' ) );
	}

	$lead = apply_filters( 'cpf_lead_data', $lead, $request );

	// 5. Store.
	$entry_id = cpf_store_lead( $lead );

	cpf_cache_lead( $entry_id, $lead );

	do_action( 'cpf_lead_stored', $entry_id, $lead );

	// 6. Queue the emails so the visitor's response returns immediately.
	if ( ! wp_next_scheduled( 'cpf_send_lead_emails_event', array( $entry_id ) ) ) {
		wp_schedule_single_event( time(), 'cpf_send_lead_emails_event', array( $entry_id ) );
	}

	do_action( 'cpf_lead_received', $lead, $entry_id );

	return rest_ensure_response(
		array(
			'ok'    => true,
			'entry' => is_numeric( $entry_id ) ? (int) $entry_id : (string) $entry_id,
		)
	);
}

/**
 * Per-IP rate limiter.
 *
 * @param string $bucket Bucket name, e.g. lead or namecheck.
 * @return bool True while the caller is under the limit.
 */
function cpf_rate_limit_ok( $bucket ) {
	$key   = 'cpf_rl_' . sanitize_key( $bucket ) . '_' . md5( cpf_client_ip() );
	$count = (int) get_transient( $key );
	$max   = (int) apply_filters( 'cpf_rate_limit_max', 5, $bucket );

	if ( $count >= $max ) {
		return false;
	}

	set_transient( $key, $count + 1, 10 * MINUTE_IN_SECONDS );

	return true;
}
