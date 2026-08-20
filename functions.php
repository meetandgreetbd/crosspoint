<?php
/**
 * CrossPoint theme loader.
 *
 * This file does one thing: require the files in /inc. Every piece of behaviour
 * lives in its own file there. Nothing else belongs in functions.php.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

/**
 * Theme version, used for asset cache busting.
 */
define( 'CPF_VERSION', '1.0.0' );

/**
 * Include files, in load order.
 *
 * @var string[] $cpf_includes
 */
$cpf_includes = array(
	'inc/helpers.php',
	'inc/setup.php',
	'inc/enqueue.php',
	'inc/walker-mega-menu.php',
	'inc/cpt-packages.php',
	'inc/cpt-faq.php',
	'inc/seed-packages.php',
	'inc/scaffold.php',
	'inc/settings.php',
	'inc/leads-storage.php',
	'inc/emails.php',
	'inc/rest-lead.php',
	'inc/rest-name-check.php',
	'inc/rest-checkout.php',
	'inc/rest-chat.php',
	'inc/seo.php',
	'inc/performance.php',
);

foreach ( $cpf_includes as $cpf_include ) {
	$cpf_include_path = get_theme_file_path( $cpf_include );

	if ( file_exists( $cpf_include_path ) ) {
		require_once $cpf_include_path;
	}
}

unset( $cpf_includes, $cpf_include, $cpf_include_path );
