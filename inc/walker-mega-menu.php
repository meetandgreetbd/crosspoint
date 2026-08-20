<?php
/**
 * Navigation walkers for the shared header.
 *
 * The mega menu is a real WordPress menu: every panel, column and link is a
 * menu item in Appearance -> Menus, so every item navigates. (On the static
 * site the panels were hand-written markup and several items went nowhere.)
 *
 * WordPress Coding Standards want one class per file, named class-*.php, so the
 * two walkers live beside this file and it loads them. The build spec names this
 * file, so it stays as the entry point.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

require_once get_theme_file_path( 'inc/class-cpf-mega-menu-walker.php' );
require_once get_theme_file_path( 'inc/class-cpf-mobile-menu-walker.php' );
require_once get_theme_file_path( 'inc/class-cpf-link-list-walker.php' );
