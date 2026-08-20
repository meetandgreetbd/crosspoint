<?php
/**
 * The mobile menu walker: the same menu rendered as a flat list with headings.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

/**
 * Mobile menu walker: flat list with group headings.
 */
class CPF_Mobile_Menu_Walker extends Walker_Nav_Menu {

	/**
	 * Sub levels need no wrapper in the mobile menu.
	 *
	 * @param string   $output Menu HTML, by reference.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   Menu arguments.
	 * @return void
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
	}

	/**
	 * Sub levels need no wrapper in the mobile menu.
	 *
	 * @param string   $output Menu HTML, by reference.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   Menu arguments.
	 * @return void
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
	}

	/**
	 * Render one item.
	 *
	 * @param string   $output Menu HTML, by reference.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   Menu arguments.
	 * @param int      $id     Menu item ID.
	 * @return void
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$title    = apply_filters( 'the_title', $item->title, $item->ID );
		$has_kids = in_array( 'menu-item-has-children', (array) $item->classes, true );
		$badge    = trim( (string) $item->description );
		$badge    = '' === $badge ? '' : ' <span class="cpf-mega-badge">' . esc_html( $badge ) . '</span>';

		if ( $has_kids && $depth < 2 ) {
			$output .= '<span class="mm-head">' . esc_html( $title ) . '</span>';

			return;
		}

		$output .= sprintf(
			'<a href="%1$s"%2$s>%3$s%4$s</a>',
			esc_url( $item->url ? $item->url : '#' ),
			$item->target ? ' target="' . esc_attr( $item->target ) . '" rel="noopener"' : '',
			esc_html( $title ),
			$badge
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
