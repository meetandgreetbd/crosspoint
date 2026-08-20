<?php
/**
 * The desktop mega menu walker.
 *
 * Structure expected in Appearance -> Menus:
 *   depth 0  with children -> mega panel trigger
 *   depth 0  no children   -> plain header link
 *   depth 1                -> column heading
 *   depth 2                -> link inside a column
 *
 * Item options:
 *   Description -> renders as a small badge, e.g. NEW
 *   CSS class   -> mega-feature turns a column into the promo column
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

/**
 * Desktop mega menu walker.
 */
class CPF_Mega_Menu_Walker extends Walker_Nav_Menu {

	/**
	 * Parent item of the level currently being rendered.
	 *
	 * @var WP_Post|null
	 */
	protected $parent_item = null;

	/**
	 * Open a sub level.
	 *
	 * @param string   $output Menu HTML, by reference.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   Menu arguments.
	 * @return void
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( 0 === $depth ) {
			$output .= '<div class="mega-panel">';
		}
	}

	/**
	 * Close a sub level.
	 *
	 * @param string   $output Menu HTML, by reference.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   Menu arguments.
	 * @return void
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( 0 === $depth ) {
			$output .= '</div>';
		}
	}

	/**
	 * Whether a menu item has children.
	 *
	 * @param WP_Post $item Menu item.
	 * @return bool
	 */
	protected function has_children( $item ) {
		return in_array( 'menu-item-has-children', (array) $item->classes, true );
	}

	/**
	 * The badge markup for a menu item, from its description field.
	 *
	 * @param WP_Post $item Menu item.
	 * @return string
	 */
	protected function badge( $item ) {
		$text = trim( (string) $item->description );

		if ( '' === $text ) {
			return '';
		}

		return ' <span class="cpf-mega-badge">' . esc_html( $text ) . '</span>';
	}

	/**
	 * Open one menu item.
	 *
	 * @param string   $output Menu HTML, by reference.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   Menu arguments.
	 * @param int      $id     Menu item ID.
	 * @return void
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$title   = apply_filters( 'the_title', $item->title, $item->ID );
		$url     = $item->url ? $item->url : '#';
		$classes = (array) $item->classes;

		if ( 0 === $depth ) {
			if ( $this->has_children( $item ) ) {
				$output .= sprintf(
					'<div class="nav-mega" data-mega="%1$s"><button type="button" class="nav-mega-trigger" aria-expanded="false">%2$s <i class="fa-solid fa-chevron-down" aria-hidden="true"></i></button>',
					esc_attr( sanitize_title( $title ) ),
					esc_html( $title )
				);
			} else {
				$output .= sprintf(
					'<a href="%1$s"%2$s>%3$s%4$s</a>',
					esc_url( $url ),
					$item->target ? ' target="' . esc_attr( $item->target ) . '" rel="noopener"' : '',
					esc_html( $title ),
					$this->badge( $item )
				);
			}

			return;
		}

		if ( 1 === $depth ) {
			$feature = in_array( 'mega-feature', $classes, true );

			if ( $feature ) {
				$output .= '<div class="mega-col mega-feature"><span class="mega-fh">' . esc_html( $title ) . '</span>';

				if ( '' !== trim( (string) $item->description ) ) {
					$output .= '<p>' . esc_html( $item->description ) . '</p>';
				}

				return;
			}

			$output .= '<div class="mega-col"><span class="mega-head">' . esc_html( $title ) . '</span>';

			return;
		}

		$parent_classes = array();

		if ( isset( $this->parent_item ) && $this->parent_item instanceof WP_Post ) {
			$parent_classes = (array) $this->parent_item->classes;
		}

		$is_feature = in_array( 'mega-feature', $parent_classes, true );
		$link_class = $is_feature ? ' class="mega-fbtn"' : '';

		// The promo column's call to action carries a trailing arrow, as on the
		// live site. It stays an anchor rather than the live <button>, so the
		// item actually navigates - which is the point of rebuilding the mega
		// menu as a real WordPress menu.
		$icon = $is_feature ? ' <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>' : '';

		$output .= sprintf(
			'<a href="%1$s"%2$s%3$s>%4$s%5$s%6$s</a>',
			esc_url( $url ),
			$link_class,
			$item->target ? ' target="' . esc_attr( $item->target ) . '" rel="noopener"' : '',
			esc_html( $title ),
			$icon,
			$this->badge( $item )
		);
	}

	/**
	 * Close one menu item.
	 *
	 * @param string   $output Menu HTML, by reference.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Current depth.
	 * @param stdClass $args   Menu arguments.
	 * @return void
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		if ( 0 === $depth && $this->has_children( $item ) ) {
			$output .= '</div>';
		}

		if ( 1 === $depth ) {
			$output .= '</div>';
		}
	}

	/**
	 * Remember the parent item so depth-2 links know which column they sit in.
	 *
	 * @param object $element           Menu item.
	 * @param array  $children_elements Child items.
	 * @param int    $max_depth         Maximum depth.
	 * @param int    $depth             Current depth.
	 * @param array  $args              Menu arguments.
	 * @param string $output            Menu HTML, by reference.
	 * @return void
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		if ( 1 === $depth ) {
			$this->parent_item = $element;
		}

		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}
}
