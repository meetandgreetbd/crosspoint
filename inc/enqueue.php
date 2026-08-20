<?php
/**
 * Conditional front-end asset loading.
 *
 * Every stylesheet and script the theme owns is registered here. Templates never
 * print inline <script> or <style>; the wizard's weight never reaches other
 * pages, and the homepage quiz never reaches the wizard.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue front-end styles and scripts.
 *
 * @return void
 */
function cpf_enqueue() {
	$ver = CPF_VERSION;

	wp_enqueue_style(
		'cpf-fonts',
		'https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600..800&family=Inter:wght@400;500;600;700&display=swap',
		array(),
		null // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- The Google Fonts URL is already versioned.
	);

	wp_enqueue_style(
		'cpf-icons',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
		array(),
		'6.5.1'
	);

	wp_enqueue_style( 'cpf-main', get_theme_file_uri( 'assets/css/main.css' ), array( 'cpf-fonts', 'cpf-icons' ), $ver );

	wp_enqueue_script( 'cpf-main', get_theme_file_uri( 'assets/js/main.js' ), array(), $ver, true );
	wp_localize_script( 'cpf-main', 'cpfSite', cpf_site_config() );

	if ( is_front_page() ) {
		wp_enqueue_script( 'cpf-quiz', get_theme_file_uri( 'assets/js/quiz.js' ), array( 'cpf-main' ), $ver, true );
		cpf_localize_lead_config( 'cpf-quiz', 'home-quiz' );
	}

	if ( is_page_template( 'page-templates/template-start.php' ) ) {
		wp_enqueue_style( 'cpf-start', get_theme_file_uri( 'assets/css/start.css' ), array( 'cpf-main' ), $ver );
		wp_enqueue_script( 'cpf-start', get_theme_file_uri( 'assets/js/start.js' ), array( 'cpf-main' ), $ver, true );
		cpf_localize_lead_config( 'cpf-start', 'start-wizard' );
	}

	if ( cpf_chat_enabled() ) {
		wp_enqueue_script( 'cpf-chat', get_theme_file_uri( 'assets/js/chat.js' ), array( 'cpf-main' ), $ver, true );
	}
}
add_action( 'wp_enqueue_scripts', 'cpf_enqueue' );

/**
 * Site-wide values main.js and chat.js need.
 *
 * Tracking ids, contact details and the REST handshake all come from settings,
 * so none of them is written into a script file.
 *
 * @return array
 */
function cpf_site_config() {
	return array(
		'restBase'          => esc_url_raw( rest_url( 'crosspoint/v1/' ) ),
		'nonce'             => wp_create_nonce( 'wp_rest' ),
		'ts'                => time(),
		'waBase'            => cpf_whatsapp_url( '' ),
		'calendlyUrl'       => esc_url_raw( (string) cpf_get_setting( 'calendly_url' ) ),
		'ga4Id'             => (string) cpf_get_setting( 'ga4_id' ),
		'gadsId'            => (string) cpf_get_setting( 'gads_id' ),
		'gadsLabelForm'     => (string) cpf_get_setting( 'gads_label_form' ),
		'gadsLabelWhatsapp' => (string) cpf_get_setting( 'gads_label_whatsapp' ),
		'gadsLabelPurchase' => (string) cpf_get_setting( 'gads_label_purchase', '' ),
		'bingUetId'         => (string) cpf_get_setting( 'bing_uet_id' ),
	);
}

/**
 * Hand a script the REST base, nonce, lead source and live package prices.
 *
 * Prices come from the Packages CPT, so the wizard and the quiz can never
 * disagree with /pricing/.
 *
 * @param string $handle Registered script handle.
 * @param string $source Lead source identifier, e.g. 'start-wizard'.
 * @return void
 */
function cpf_localize_lead_config( $handle, $source ) {
	wp_localize_script(
		$handle,
		'cpfConfig',
		array(
			'restBase' => esc_url_raw( rest_url( 'crosspoint/v1/' ) ),
			'nonce'    => wp_create_nonce( 'wp_rest' ),
			'ts'       => time(),
			'source'   => $source,
			'packages' => cpf_get_packages_for_js(),
		)
	);
}

/**
 * Preconnect to the font hosts, so the first paint is not held up.
 *
 * @param array  $urls          URLs to print.
 * @param string $relation_type Link relation.
 * @return array
 */
function cpf_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
		$urls[] = array( 'href' => 'https://cdnjs.cloudflare.com' );
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'cpf_resource_hints', 10, 2 );

/**
 * Drop the default block library stylesheet. The theme ships no block content.
 *
 * @return void
 */
function cpf_dequeue_block_styles() {
	if ( is_admin() ) {
		return;
	}

	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'classic-theme-styles' );
	wp_dequeue_style( 'global-styles' );
}
add_action( 'wp_enqueue_scripts', 'cpf_dequeue_block_styles', 100 );
