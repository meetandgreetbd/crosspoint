<?php
/**
 * Theme supports, navigation menus and image sizes.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme supports, menus and image sizes.
 *
 * @return void
 */
function cpf_setup() {
	load_theme_textdomain( 'crosspoint', get_theme_file_path( 'languages' ) );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support(
		'html5',
		array( 'search-form', 'gallery', 'caption', 'style', 'script', 'comment-form', 'comment-list', 'navigation-widgets' )
	);

	register_nav_menus(
		array(
			'primary'      => __( 'Header Mega Menu', 'crosspoint' ),
			'footer_nav'   => __( 'Footer Navigate', 'crosspoint' ),
			'footer_legal' => __( 'Footer Legal Bar', 'crosspoint' ),
		)
	);

	// Guide cards on /guides/ and related-guide strips.
	add_image_size( 'cpf-guide-card', 720, 420, true );
	// Guide hero on single posts.
	add_image_size( 'cpf-guide-hero', 1280, 640, true );
}
add_action( 'after_setup_theme', 'cpf_setup' );

/**
 * Set the content width used by embeds and wide media.
 *
 * @return void
 */
function cpf_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'cpf_content_width', 760 );
}
add_action( 'after_setup_theme', 'cpf_content_width', 0 );

/**
 * Expose the theme's custom image sizes in the media picker.
 *
 * @param array $sizes Registered size names keyed by slug.
 * @return array
 */
function cpf_custom_image_size_names( $sizes ) {
	$sizes['cpf-guide-card'] = __( 'Guide card', 'crosspoint' );
	$sizes['cpf-guide-hero'] = __( 'Guide hero', 'crosspoint' );

	return $sizes;
}
add_filter( 'image_size_names_choose', 'cpf_custom_image_size_names' );

/**
 * Remove the WordPress emoji scripts. The static site never loaded them.
 *
 * @return void
 */
function cpf_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'cpf_disable_emojis' );
