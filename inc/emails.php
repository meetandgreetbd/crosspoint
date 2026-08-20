<?php
/**
 * Lead notification emails.
 *
 * Multiple recipients, a different list per lead source, an optional auto-reply
 * to the lead, and a non-blocking send: the REST callback queues this job so the
 * visitor's response never waits on SMTP.
 *
 * Everything goes through wp_mail(); WP Mail SMTP carries it. mail() is never
 * called directly.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

/**
 * Send the notification and auto-reply emails for one lead.
 *
 * @param int|string $entry_id Entry identifier from cpf_store_lead().
 * @return void
 */
function cpf_send_lead_emails( $entry_id ) {
	$lead = cpf_get_lead( $entry_id );

	if ( ! $lead ) {
		cpf_log( 'Email job found no lead for entry ' . $entry_id );

		return;
	}

	$source     = isset( $lead['source'] ) ? sanitize_key( $lead['source'] ) : '';
	$recipients = (string) cpf_get_setting( 'notify_' . str_replace( '-', '_', $source ), '' );

	if ( '' === trim( $recipients ) ) {
		$recipients = (string) cpf_get_setting( 'notify_default' );
	}

	$recipients = apply_filters(
		'cpf_lead_email_recipients',
		array_values( array_filter( array_map( 'trim', explode( "\n", $recipients ) ) ) ),
		$lead
	);

	$subject = apply_filters(
		'cpf_lead_email_subject',
		sprintf(
			'[CrossPoint Lead] %1$s — %2$s',
			isset( $lead['source'] ) ? $lead['source'] : '',
			isset( $lead['full_name'] ) ? $lead['full_name'] : ''
		),
		$lead
	);

	$body = apply_filters( 'cpf_lead_email_body', cpf_render_lead_email( $lead ), $lead );

	$headers = array( 'Content-Type: text/html; charset=UTF-8' );

	if ( ! empty( $lead['email'] ) && is_email( $lead['email'] ) ) {
		// A reply from the inbox goes straight back to the lead.
		$headers[] = 'Reply-To: ' . sanitize_email( $lead['email'] );
	}

	$headers = apply_filters( 'cpf_lead_email_headers', $headers, $lead );

	foreach ( $recipients as $to ) {
		$to = sanitize_email( $to );

		if ( $to ) {
			wp_mail( $to, $subject, $body, $headers );
		}
	}

	if ( cpf_get_setting( 'autoreply_enabled' ) && ! empty( $lead['email'] ) && is_email( $lead['email'] ) ) {
		wp_mail(
			sanitize_email( $lead['email'] ),
			apply_filters( 'cpf_autoreply_subject', html_entity_decode( (string) cpf_get_setting( 'autoreply_subject' ), ENT_QUOTES, 'UTF-8' ), $lead ),
			apply_filters( 'cpf_autoreply_body', cpf_render_autoreply( $lead ), $lead ),
			array( 'Content-Type: text/html; charset=UTF-8' )
		);
	}

	do_action( 'cpf_lead_emails_sent', $lead, $recipients );
}
add_action( 'cpf_send_lead_emails_event', 'cpf_send_lead_emails' );

/**
 * Build the internal notification body: every lead field in a table.
 *
 * Every value is escaped, so visitor input never lands raw in an email body.
 *
 * @param array $lead Lead fields.
 * @return string
 */
function cpf_render_lead_email( array $lead ) {
	$hidden = array( 'website', 'ts', 'nonce' );

	$rows = '';

	foreach ( $lead as $key => $value ) {
		if ( in_array( $key, $hidden, true ) ) {
			continue;
		}

		if ( is_array( $value ) ) {
			$value = implode( ', ', array_map( 'strval', $value ) );
		}

		if ( '' === trim( (string) $value ) ) {
			continue;
		}

		$rows .= sprintf(
			'<tr><th align="left" style="padding:8px 14px;border-bottom:1px solid #E3E8EF;background:#F6F8FB;width:210px">%1$s</th>'
			. '<td style="padding:8px 14px;border-bottom:1px solid #E3E8EF">%2$s</td></tr>',
			esc_html( ucwords( str_replace( '_', ' ', $key ) ) ),
			esc_html( (string) $value )
		);
	}

	return '<div style="font-family:Arial,Helvetica,sans-serif;color:#1F2937">'
		. '<h2 style="color:#0E1B2E;font-size:18px;margin:0 0 14px">' . esc_html__( 'New CrossPoint lead', 'crosspoint' ) . '</h2>'
		. '<table cellpadding="0" cellspacing="0" style="border-collapse:collapse;width:100%;max-width:640px;border:1px solid #E3E8EF">'
		. $rows
		. '</table>'
		. '<p style="font-size:12px;color:#5B6472;margin-top:16px">'
		. esc_html__( 'Sent by the CrossPoint theme. Reply to this email to answer the lead directly.', 'crosspoint' )
		. '</p></div>';
}

/**
 * Build the auto-reply body from the configured template.
 *
 * @param array $lead Lead fields.
 * @return string
 */
function cpf_render_autoreply( array $lead ) {
	$body = (string) cpf_get_setting( 'autoreply_body' );
	$name = isset( $lead['full_name'] ) ? $lead['full_name'] : '';

	return str_replace( '{name}', esc_html( $name ), $body );
}

/**
 * Send from the site domain so SPF and DMARC line up.
 *
 * @param string $email Default from address.
 * @return string
 */
function cpf_mail_from( $email ) {
	$configured = sanitize_email( (string) cpf_get_setting( 'contact_email' ) );

	return $configured ? $configured : $email;
}
add_filter( 'wp_mail_from', 'cpf_mail_from' );

/**
 * Use the site name as the sender name.
 *
 * @param string $name Default from name.
 * @return string
 */
function cpf_mail_from_name( $name ) {
	unset( $name );

	return get_bloginfo( 'name' );
}
add_filter( 'wp_mail_from_name', 'cpf_mail_from_name' );
