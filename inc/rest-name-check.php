<?php
/**
 * REST endpoint: POST crosspoint/v1/name-check
 *
 * Port of the static site's tools/name-check.php. The upstream API key now
 * lives in a wp-config.php constant, never in the database and never in the
 * theme source.
 *
 *     define( 'CPF_OSD_API_KEY', 'osd_xxx' );
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

/**
 * Wizard state slug to USPS state code.
 *
 * @return array<string,string>
 */
function cpf_namecheck_state_codes() {
	return apply_filters(
		'cpf_namecheck_state_codes',
		array(
			'wyoming'    => 'WY',
			'delaware'   => 'DE',
			'new_mexico' => 'NM',
			'florida'    => 'FL',
			'texas'      => 'TX',
			'california' => 'CA',
			'new_york'   => 'NY',
			'nevada'     => 'NV',
		)
	);
}

/**
 * Register the name-check route.
 *
 * @return void
 */
function cpf_register_name_check_route() {
	register_rest_route(
		'crosspoint/v1',
		'/name-check',
		array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => 'cpf_rest_name_check',
			'permission_callback' => '__return_true',
			'args'                => array(
				'company_name' => array(
					'required'          => true,
					'sanitize_callback' => 'sanitize_text_field',
					'validate_callback' => function ( $value ) {
						$len = strlen( (string) $value );

						return $len >= 2 && $len <= 120;
					},
				),
				'state'        => array(
					'required'          => true,
					'sanitize_callback' => 'sanitize_text_field',
				),
				'suffix'       => array( 'sanitize_callback' => 'sanitize_text_field' ),
			),
		)
	);
}
add_action( 'rest_api_init', 'cpf_register_name_check_route' );

/**
 * Handle a name availability check.
 *
 * @param WP_REST_Request $request Incoming request.
 * @return WP_REST_Response|WP_Error
 */
function cpf_rest_name_check( WP_REST_Request $request ) {
	if ( ! wp_verify_nonce( (string) $request->get_header( 'X-WP-Nonce' ), 'wp_rest' ) ) {
		return new WP_Error(
			'cpf_bad_nonce',
			__( 'Session expired. Reload the page and try again.', 'crosspoint' ),
			array( 'status' => 403 )
		);
	}

	if ( ! cpf_rate_limit_ok( 'namecheck' ) ) {
		return new WP_Error(
			'cpf_rate_limited',
			__( 'Too many checks. Try again shortly.', 'crosspoint' ),
			array( 'status' => 429 )
		);
	}

	if ( ! defined( 'CPF_OSD_API_KEY' ) || '' === CPF_OSD_API_KEY ) {
		return rest_ensure_response(
			array(
				'available' => null,
				'error'     => 'not_configured',
			)
		);
	}

	$name   = trim( (string) $request['company_name'] );
	$slug   = (string) $request['state'];
	$suffix = trim( (string) $request['suffix'] );
	$codes  = cpf_namecheck_state_codes();
	$code   = isset( $codes[ $slug ] ) ? $codes[ $slug ] : strtoupper( $slug );

	if ( 2 !== strlen( $code ) ) {
		return new WP_Error(
			'cpf_bad_state',
			__( 'A two-letter state code is required.', 'crosspoint' ),
			array( 'status' => 400 )
		);
	}

	if ( '' === $suffix ) {
		$suffix = 'LLC';
	}

	// The provider matches full legal names only, so a suffix is always appended.
	$suffixes   = array( ' llc', ' l.l.c.', ' inc', ' inc.', ' incorporated', ' corp', ' corp.', ' corporation', ' ltd', ' ltd.', ' limited', ' company' );
	$lower      = strtolower( $name );
	$has_suffix = false;

	foreach ( $suffixes as $candidate ) {
		if ( substr( $lower, -strlen( $candidate ) ) === $candidate ) {
			$has_suffix = true;
			break;
		}
	}

	$query_name = $has_suffix ? $name : $name . ' ' . $suffix;
	$cache_key  = 'cpf_nc_' . md5( strtolower( $query_name ) . '|' . $code );
	$cached     = get_transient( $cache_key );

	if ( is_array( $cached ) ) {
		return rest_ensure_response( $cached );
	}

	$data = cpf_namecheck_lookup( $query_name, $code );

	if ( null === $data ) {
		// The scrapers are flaky: the same query can fail once and succeed a
		// moment later. One retry, then the inconclusive fallback.
		$data = cpf_namecheck_lookup( $query_name, $code );
	}

	if ( null === $data ) {
		return rest_ensure_response(
			array(
				'available' => null,
				'error'     => 'provider_error',
			)
		);
	}

	$found = cpf_namecheck_found( $data );

	// A cross-suffix clash is still a real clash: "Acme LLC" free while
	// "Acme Inc." is registered.
	if ( ! $found && ! $has_suffix ) {
		$alt  = ( false !== stripos( $suffix, 'inc' ) ) ? 'LLC' : 'Inc.';
		$data = cpf_namecheck_lookup( $name . ' ' . $alt, $code );

		if ( null !== $data && cpf_namecheck_found( $data ) ) {
			$found = true;
		}
	}

	$result = array(
		'available' => ! $found,
		'state'     => $code,
		'checked'   => $query_name,
	);

	set_transient( $cache_key, $result, 10 * MINUTE_IN_SECONDS );

	return rest_ensure_response( $result );
}

/**
 * Call the upstream availability provider.
 *
 * @param string $name Full legal name to query.
 * @param string $code Two-letter state code.
 * @return array|null Decoded response, or null on any failure.
 */
function cpf_namecheck_lookup( $name, $code ) {
	$endpoint = apply_filters( 'cpf_namecheck_endpoint', 'https://api.opensosdata.com/v1/lookup' );

	$response = wp_remote_post(
		$endpoint,
		array(
			'timeout' => (int) apply_filters( 'cpf_namecheck_timeout', 60 ),
			'headers' => array(
				'Content-Type' => 'application/json',
				'x-api-key'    => CPF_OSD_API_KEY,
			),
			'body'    => wp_json_encode(
				array(
					'entity_name' => $name,
					'state'       => $code,
				)
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		cpf_log( 'name-check transport error: ' . $response->get_error_message() );

		return null;
	}

	$code_http = (int) wp_remote_retrieve_response_code( $response );

	if ( $code_http < 200 || $code_http >= 300 ) {
		return null;
	}

	$data = json_decode( wp_remote_retrieve_body( $response ), true );

	if ( ! is_array( $data ) ) {
		return null;
	}

	if ( ( isset( $data['success'] ) && false === $data['success'] ) || isset( $data['error'] ) ) {
		return null;
	}

	return $data;
}

/**
 * Decide whether the provider found a matching entity.
 *
 * The provider's own status field comes back empty, so total_results and the
 * presence of an entity name are what count.
 *
 * @param array $data Provider response.
 * @return bool
 */
function cpf_namecheck_found( array $data ) {
	$found = false;

	if ( isset( $data['total_results'] ) ) {
		$found = ( (int) $data['total_results'] ) > 0;
	}

	if ( ! $found && isset( $data['data'] ) && is_array( $data['data'] ) ) {
		if ( ! empty( $data['data']['entityName'] ) ) {
			$found = true;
		} elseif ( ! empty( $data['data']['relatedResults'] ) && is_array( $data['data']['relatedResults'] ) ) {
			$found = count( $data['data']['relatedResults'] ) > 0;
		}
	}

	if ( isset( $data['available'] ) ) {
		$found = ! $data['available'];
	}

	return $found;
}
