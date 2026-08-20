<?php
/**
 * Lead storage.
 *
 * One function is the only place in the theme that knows how a lead is stored.
 * Everything else calls cpf_store_lead(). Swapping the backend later means
 * editing one function, or adding one filter.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the private fallback lead post type.
 *
 * Used when Fluent Forms is missing, deactivated, or no form ID is configured.
 * Its only job is that a lead is never lost.
 *
 * @return void
 */
function cpf_register_lead_cpt() {
	register_post_type(
		'cpf_lead',
		array(
			'label'           => __( 'Leads (fallback)', 'crosspoint' ),
			'labels'          => array(
				'name'          => __( 'Leads (fallback)', 'crosspoint' ),
				'singular_name' => __( 'Lead', 'crosspoint' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => 'cpf-settings',
			'show_in_rest'    => false,
			'capability_type' => 'post',
			'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'    => true,
			'supports'        => array( 'title' ),
		)
	);
}
add_action( 'init', 'cpf_register_lead_cpt' );

/**
 * Store one lead.
 *
 * @param array $lead Sanitized lead fields.
 * @return int|string Entry identifier. 0 when nothing could be stored.
 */
function cpf_store_lead( array $lead ) {
	// Pluggable: return anything other than null to replace storage entirely.
	$external = apply_filters( 'cpf_store_lead_override', null, $lead );

	if ( null !== $external ) {
		return $external;
	}

	$form_id = (int) cpf_get_setting( 'ff_form_id' );

	if ( function_exists( 'wpFluent' ) && $form_id > 0 ) {
		$entry_id = cpf_store_lead_fluentforms( $lead, $form_id );

		if ( $entry_id ) {
			// Mirror into the fallback store as well, so a Fluent Forms outage or
			// schema change can never cost a lead.
			cpf_store_lead_fallback( $lead, $entry_id );

			return $entry_id;
		}
	}

	return cpf_store_lead_fallback( $lead );
}

/**
 * Write a lead into Fluent Forms' submission tables.
 *
 * Honest caveat: this couples to Fluent Forms' internal schema, which its free
 * version does not expose through a public API. The coupling is pinned to this
 * one function - if a Fluent Forms update changes the schema, exactly one
 * function needs editing, and the fallback store below still holds every lead.
 *
 * @param array $lead    Sanitized lead fields.
 * @param int   $form_id Fluent Forms form ID.
 * @return int Submission ID, or 0 on failure.
 */
function cpf_store_lead_fluentforms( array $lead, $form_id ) {
	try {
		$response = wp_json_encode( $lead );

		if ( false === $response ) {
			return 0;
		}

		$now = current_time( 'mysql' );

		$data = array(
			'form_id'        => (int) $form_id,
			'serial_number'  => 0,
			'response'       => $response,
			'source_url'     => isset( $lead['source_url'] ) ? esc_url_raw( $lead['source_url'] ) : home_url( '/' ),
			'user_id'        => 0,
			'status'         => 'unread',
			'is_favourite'   => 0,
			'browser'        => '',
			'device'         => '',
			'ip'             => cpf_client_ip(),
			'city'           => '',
			'country'        => '',
			'payment_status' => '',
			'payment_method' => '',
			'payment_type'   => '',
			'currency'       => '',
			'payment_total'  => 0,
			'total_paid'     => 0,
			'created_at'     => $now,
			'updated_at'     => $now,
		);

		$submission_id = (int) wpFluent()->table( 'fluentform_submissions' )->insertGetId( $data );

		if ( ! $submission_id ) {
			return 0;
		}

		// Entry details power the Entries table columns and the CSV export.
		foreach ( $lead as $key => $value ) {
			if ( is_array( $value ) ) {
				$value = implode( ', ', array_map( 'strval', $value ) );
			}

			wpFluent()->table( 'fluentform_entry_details' )->insert(
				array(
					'form_id'        => (int) $form_id,
					'submission_id'  => $submission_id,
					'field_name'     => sanitize_key( $key ),
					'sub_field_name' => '',
					'field_value'    => (string) $value,
				)
			);
		}

		wpFluent()->table( 'fluentform_submissions' )
			->where( 'id', $submission_id )
			->update( array( 'serial_number' => $submission_id ) );

		return $submission_id;
	} catch ( Exception $e ) {
		cpf_log( 'Fluent Forms insert failed: ' . $e->getMessage() );

		return 0;
	}
}

/**
 * Store a lead in the private cpf_lead post type.
 *
 * @param array      $lead     Sanitized lead fields.
 * @param int|string $entry_id Fluent Forms entry id when the lead was mirrored.
 * @return int Post ID, or 0 on failure.
 */
function cpf_store_lead_fallback( array $lead, $entry_id = 0 ) {
	$name  = isset( $lead['full_name'] ) ? $lead['full_name'] : __( 'Unknown', 'crosspoint' );
	$src   = isset( $lead['source'] ) ? $lead['source'] : 'unknown';
	$title = sprintf( '%s — %s — %s', $src, $name, current_time( 'Y-m-d H:i' ) );

	$post_id = wp_insert_post(
		array(
			'post_type'   => 'cpf_lead',
			'post_status' => 'publish',
			'post_title'  => $title,
		),
		true
	);

	if ( is_wp_error( $post_id ) ) {
		cpf_log( 'Fallback lead insert failed: ' . $post_id->get_error_message() );

		return 0;
	}

	update_post_meta( $post_id, '_cpf_lead', wp_json_encode( $lead ) );
	update_post_meta( $post_id, '_cpf_lead_source', sanitize_key( $src ) );

	if ( $entry_id ) {
		update_post_meta( $post_id, '_cpf_ff_entry_id', $entry_id );
	}

	return $post_id;
}

/**
 * Read a stored lead back for the email worker.
 *
 * The lead is also cached in a short transient when it is received, so the
 * queued email job does not depend on how storage happened to go.
 *
 * @param int|string $entry_id Entry identifier returned by cpf_store_lead().
 * @return array|null
 */
function cpf_get_lead( $entry_id ) {
	$cached = get_transient( 'cpf_lead_' . md5( (string) $entry_id ) );

	if ( is_array( $cached ) ) {
		return $cached;
	}

	$posts = get_posts(
		array(
			'post_type'      => 'cpf_lead',
			'posts_per_page' => 1,
			'no_found_rows'  => true,
			'meta_key'       => '_cpf_ff_entry_id', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Single indexed lookup on a small, private post type.
			'meta_value'     => (string) $entry_id, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value -- See above.
		)
	);

	if ( empty( $posts ) ) {
		$post = get_post( (int) $entry_id );

		if ( $post instanceof WP_Post && 'cpf_lead' === $post->post_type ) {
			$posts = array( $post );
		}
	}

	if ( empty( $posts ) ) {
		return null;
	}

	$json = (string) get_post_meta( $posts[0]->ID, '_cpf_lead', true );
	$lead = json_decode( $json, true );

	return is_array( $lead ) ? $lead : null;
}

/**
 * Cache a lead so the queued email job always has its data.
 *
 * @param int|string $entry_id Entry identifier.
 * @param array      $lead     Lead fields.
 * @return void
 */
function cpf_cache_lead( $entry_id, array $lead ) {
	set_transient( 'cpf_lead_' . md5( (string) $entry_id ), $lead, DAY_IN_SECONDS );
}

/**
 * The requesting IP address, sanitized.
 *
 * @return string
 */
function cpf_client_ip() {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';

	return preg_replace( '/[^0-9a-fA-F:.]/', '', $ip );
}

/**
 * Write a line to the PHP error log when debugging is on.
 *
 * @param string $message Message to log.
 * @return void
 */
function cpf_log( $message ) {
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( '[crosspoint] ' . $message ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- Guarded by WP_DEBUG.
	}
}

/**
 * Show the stored payload on the fallback lead screen.
 *
 * @return void
 */
function cpf_lead_meta_box() {
	add_meta_box(
		'cpf_lead_payload',
		__( 'Lead payload', 'crosspoint' ),
		function ( $post ) {
			$lead = json_decode( (string) get_post_meta( $post->ID, '_cpf_lead', true ), true );

			if ( ! is_array( $lead ) ) {
				echo '<p>' . esc_html__( 'No payload stored.', 'crosspoint' ) . '</p>';

				return;
			}

			echo '<table class="widefat striped"><tbody>';

			foreach ( $lead as $key => $value ) {
				printf(
					'<tr><th style="width:220px">%1$s</th><td>%2$s</td></tr>',
					esc_html( $key ),
					esc_html( is_array( $value ) ? implode( ', ', array_map( 'strval', $value ) ) : (string) $value )
				);
			}

			echo '</tbody></table>';
		},
		'cpf_lead',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes_cpf_lead', 'cpf_lead_meta_box' );
