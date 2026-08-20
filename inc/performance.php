<?php
/**
 * Front-end performance pass.
 *
 * The static site scored well because it shipped almost nothing it did not use.
 * This file keeps that true under WordPress: no emoji script, no oEmbed script,
 * no jQuery on the front end, no REST link tags, and lazy images below the fold.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

/**
 * Strip head output the static site never had.
 *
 * @return void
 */
function cpf_clean_head() {
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
	remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
	remove_action( 'template_redirect', 'rest_output_link_header', 11 );
}
add_action( 'init', 'cpf_clean_head' );

/**
 * Drop the block editor's oEmbed script from the front end.
 *
 * @return void
 */
function cpf_dequeue_embeds() {
	if ( ! is_admin() ) {
		wp_deregister_script( 'wp-embed' );
	}
}
add_action( 'wp_footer', 'cpf_dequeue_embeds' );

/**
 * Fail loudly in the log if a plugin drags jQuery onto the front end.
 *
 * The port is vanilla JS by design; this is the tripwire that keeps it that way.
 *
 * @return void
 */
function cpf_warn_on_jquery() {
	if ( is_admin() || ! ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ) {
		return;
	}

	if ( wp_script_is( 'jquery', 'enqueued' ) ) {
		cpf_log( 'jQuery was enqueued on the front end by a plugin: ' . esc_url_raw( home_url( add_query_arg( array() ) ) ) );
	}
}
add_action( 'wp_print_footer_scripts', 'cpf_warn_on_jquery', 1 );

/**
 * Preload the two font files the first paint needs.
 *
 * Only fires when the fonts have been self-hosted into assets/fonts/; with the
 * Google Fonts stylesheet the preconnect hints in enqueue.php do the work.
 *
 * @return void
 */
function cpf_preload_fonts() {
	$fonts = apply_filters(
		'cpf_preload_fonts',
		array(
			'assets/fonts/inter-var.woff2',
			'assets/fonts/bricolage-grotesque-var.woff2',
		)
	);

	foreach ( $fonts as $font ) {
		if ( ! file_exists( get_theme_file_path( $font ) ) ) {
			continue;
		}

		printf(
			'<link rel="preload" as="font" type="font/woff2" href="%s" crossorigin>' . "\n",
			esc_url( get_theme_file_uri( $font ) )
		);
	}
}
add_action( 'wp_head', 'cpf_preload_fonts', 1 );

/**
 * Keep the first image on a page eager; lazy-load everything after it.
 *
 * @param string $content Post content.
 * @return string
 */
function cpf_first_image_eager( $content ) {
	static $done = false;

	if ( $done || is_admin() ) {
		return $content;
	}

	$done = true;

	return preg_replace( '/loading="lazy"/', 'loading="eager" fetchpriority="high"', $content, 1 );
}
add_filter( 'the_content', 'cpf_first_image_eager', 20 );

/**
 * Tell LiteSpeed Cache to leave the wizard's JavaScript alone.
 *
 * Combining start.js with other files has broken the step logic before, so the
 * exclusion ships with the theme rather than living only in a plugin setting.
 *
 * @param array $excludes Existing exclusions.
 * @return array
 */
function cpf_litespeed_js_excludes( $excludes ) {
	$excludes   = is_array( $excludes ) ? $excludes : array();
	$excludes[] = 'assets/js/start.js';

	return $excludes;
}
add_filter( 'litespeed_optimize_js_excludes', 'cpf_litespeed_js_excludes' );
