<?php
/**
 * REST endpoint: POST crosspoint/v1/chat
 *
 * Port of the static site's tools/chat-api.php: the browser sends the
 * conversation, the server adds the system prompt and calls the Anthropic API,
 * so the key never reaches the browser.
 *
 *     define( 'CPF_ANTHROPIC_KEY', 'sk-ant-xxx' );
 *
 * Without that constant the endpoint reports the assistant as unavailable and
 * the widget falls back to its built-in answers - the same behaviour the static
 * site had while the key was unset.
 *
 * The price list in the system prompt is generated from the Packages CPT, so the
 * assistant can never quote a price the site no longer charges.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the chat route.
 *
 * @return void
 */
function cpf_register_chat_route() {
	register_rest_route(
		'crosspoint/v1',
		'/chat',
		array(
			'methods'             => WP_REST_Server::CREATABLE,
			'callback'            => 'cpf_rest_chat',
			'permission_callback' => '__return_true',
			'args'                => array(
				'messages' => array(
					'required' => true,
					'type'     => 'array',
				),
			),
		)
	);
}
add_action( 'rest_api_init', 'cpf_register_chat_route' );

/**
 * The assistant's system prompt: editable knowledge plus generated prices.
 *
 * @return string
 */
function cpf_chat_system_prompt() {
	$lines = array();

	foreach ( array( 'us', 'us-ecommerce', 'canada', 'bundle' ) as $group ) {
		foreach ( cpf_get_packages( $group ) as $package ) {
			$price = cpf_package_price_label( $package->ID );

			if ( '' === $price ) {
				continue;
			}

			$note    = (string) get_post_meta( $package->ID, '_cpf_fee_note', true );
			$lines[] = '- ' . get_the_title( $package ) . ': ' . $price . ( '' !== $note ? ' (' . $note . ')' : '' );
		}
	}

	$prompt = (string) cpf_get_setting( 'chat_knowledge' );

	if ( ! empty( $lines ) ) {
		$prompt .= "\n\nCURRENT PRICE LIST (authoritative - never quote a price that is not on this list):\n" . implode( "\n", $lines );
	}

	return apply_filters( 'cpf_chat_system_prompt', $prompt );
}

/**
 * Proxy one chat turn to the assistant.
 *
 * @param WP_REST_Request $request Incoming request.
 * @return WP_REST_Response|WP_Error
 */
function cpf_rest_chat( WP_REST_Request $request ) {
	if ( ! wp_verify_nonce( (string) $request->get_header( 'X-WP-Nonce' ), 'wp_rest' ) ) {
		return new WP_Error( 'cpf_bad_nonce', __( 'Session expired. Reload the page and try again.', 'crosspoint' ), array( 'status' => 403 ) );
	}

	if ( ! cpf_chat_enabled() ) {
		return new WP_Error( 'cpf_chat_off', __( 'Chat is switched off.', 'crosspoint' ), array( 'status' => 403 ) );
	}

	if ( ! cpf_rate_limit_ok( 'chat' ) ) {
		return new WP_Error( 'cpf_rate_limited', __( 'Too many messages. Try again shortly.', 'crosspoint' ), array( 'status' => 429 ) );
	}

	if ( ! defined( 'CPF_ANTHROPIC_KEY' ) || '' === CPF_ANTHROPIC_KEY ) {
		return new WP_Error( 'cpf_chat_unavailable', __( 'Assistant unavailable.', 'crosspoint' ), array( 'status' => 503 ) );
	}

	$messages = array();

	foreach ( array_slice( (array) $request['messages'], -12 ) as $message ) {
		if ( ! is_array( $message ) || ! isset( $message['role'], $message['content'] ) ) {
			continue;
		}

		$text = is_string( $message['content'] ) ? trim( sanitize_textarea_field( $message['content'] ) ) : '';

		if ( '' === $text ) {
			continue;
		}

		$messages[] = array(
			'role'    => 'assistant' === $message['role'] ? 'assistant' : 'user',
			'content' => mb_substr( $text, 0, 2000 ),
		);
	}

	// The API requires the first message to come from the user.
	while ( ! empty( $messages ) && 'user' !== $messages[0]['role'] ) {
		array_shift( $messages );
	}

	if ( empty( $messages ) ) {
		return new WP_Error( 'cpf_empty_conversation', __( 'Empty conversation.', 'crosspoint' ), array( 'status' => 400 ) );
	}

	$response = wp_remote_post(
		'https://api.anthropic.com/v1/messages',
		array(
			'timeout' => 30,
			'headers' => array(
				'Content-Type'      => 'application/json',
				'x-api-key'         => CPF_ANTHROPIC_KEY,
				'anthropic-version' => '2023-06-01',
			),
			'body'    => wp_json_encode(
				array(
					'model'      => apply_filters( 'cpf_chat_model', 'claude-haiku-4-5-20251001' ),
					'max_tokens' => 400,
					'system'     => cpf_chat_system_prompt(),
					'messages'   => $messages,
				)
			),
		)
	);

	if ( is_wp_error( $response ) ) {
		cpf_log( 'chat transport error: ' . $response->get_error_message() );

		return new WP_Error( 'cpf_chat_unavailable', __( 'Assistant unavailable.', 'crosspoint' ), array( 'status' => 502 ) );
	}

	$body = json_decode( wp_remote_retrieve_body( $response ), true );
	$code = (int) wp_remote_retrieve_response_code( $response );

	if ( $code >= 200 && $code < 300 && ! empty( $body['content'] ) && is_array( $body['content'] ) ) {
		$reply = '';

		foreach ( $body['content'] as $block ) {
			if ( isset( $block['type'], $block['text'] ) && 'text' === $block['type'] ) {
				$reply .= $block['text'];
			}
		}

		$reply = trim( $reply );

		if ( '' !== $reply ) {
			return rest_ensure_response( array( 'reply' => wp_strip_all_tags( $reply ) ) );
		}
	}

	cpf_log( 'chat API error ' . $code );

	return new WP_Error( 'cpf_chat_unavailable', __( 'Assistant unavailable.', 'crosspoint' ), array( 'status' => 502 ) );
}
