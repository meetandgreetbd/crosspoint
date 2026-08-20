<?php
/**
 * A walker that renders a menu as plain anchors, with no list markup.
 *
 * The footer legal bar is a flex row of links on the live site, not a list.
 * Rendering it with the default walker produced bare <li> elements inside a
 * <div>, which is invalid and breaks the row.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

/**
 * Menu walker that outputs anchors only.
 */
class CPF_Link_List_Walker extends Walker_Nav_Menu {

	/**
	 * No sub-level wrapper.
	 *
	 * @param string   $output Menu HTML, by reference.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   Menu arguments.
	 * @return void
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
	}

	/**
	 * No sub-level wrapper.
	 *
	 * @param string   $output Menu HTML, by reference.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   Menu arguments.
	 * @return void
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
	}

	/**
	 * Render one item as an anchor.
	 *
	 * @param string   $output Menu HTML, by reference.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   Menu arguments.
	 * @param int      $id     Menu item ID.
	 * @return void
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		$output .= sprintf(
			'<a href="%1$s"%2$s>%3$s</a>',
			esc_url( $item->url ? $item->url : '#' ),
			$item->target ? ' target="' . esc_attr( $item->target ) . '" rel="noopener"' : '',
			esc_html( $title )
		);
	}

	/**
	 * Items need no closing markup.
	 *
	 * @param string   $output Menu HTML, by reference.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   Menu arguments.
	 * @return void
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
	}
}
