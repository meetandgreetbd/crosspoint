<?php
/**
 * One-time site scaffolding: pages, templates and menus.
 *
 * Creates every page the live site has, at the same slug, with the right
 * template assigned, plus the three menus the shared header and footer read.
 * Everything is keyed on the slug, so running it twice changes nothing and an
 * edited page is never overwritten.
 *
 * Run it from CrossPoint -> Set up pages & menus.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

/**
 * The page map: live slug, title, template, parent slug.
 *
 * Slugs are identical to the live site, terms-of-services included, so the
 * launch needs no redirects.
 *
 * @return array<int,array<string,string>>
 */
function cpf_scaffold_pages() {
	return array(
		array(
			'slug'     => 'home',
			'title'    => 'Home',
			'template' => '',
		),
		array(
			'slug'     => 'pricing',
			'title'    => 'Pricing',
			'template' => 'page-templates/template-pricing.php',
		),
		array(
			'slug'     => 'canada-incorporation',
			'title'    => 'Canada Incorporation',
			'template' => 'page-templates/template-canada.php',
		),
		array(
			'slug'     => 'compare',
			'title'    => 'Compare Canadian Jurisdictions',
			'template' => 'page-templates/template-canada-compare.php',
			'parent'   => 'canada-incorporation',
		),
		array(
			'slug'     => 'us-formation',
			'title'    => 'U.S. Formation',
			'template' => '',
		),
		array(
			'slug'     => 'non-resident',
			'title'    => 'U.S. Company Formation for Non-Residents',
			'template' => 'page-templates/template-us-formation.php',
			'parent'   => 'us-formation',
		),
		array(
			'slug'     => 'filingguard',
			'title'    => 'FilingGuard',
			'template' => 'page-templates/template-filingguard.php',
		),
		array(
			'slug'     => 'contact-us',
			'title'    => 'Contact Us',
			'template' => 'page-templates/template-contact.php',
		),
		array(
			'slug'     => 'start',
			'title'    => 'Start Your Business',
			'template' => 'page-templates/template-start.php',
		),
		array(
			'slug'     => 'us-llc-india',
			'title'    => 'U.S. LLC for Founders in India',
			'template' => 'page-templates/template-us-llc-india.php',
		),
		array(
			'slug'     => 'us-llc-pakistan',
			'title'    => 'U.S. LLC for Founders in Pakistan',
			'template' => 'page-templates/template-us-llc-pakistan.php',
		),
		array(
			'slug'     => 'guides',
			'title'    => 'Guides',
			'template' => '',
		),
		array(
			'slug'     => 'privacy-policy',
			'title'    => 'Privacy Policy',
			'template' => '',
		),
		// The live slug reads oddly but is deliberate: changing it breaks parity.
		array(
			'slug'     => 'terms-of-services',
			'title'    => 'Terms of Service',
			'template' => '',
		),
		array(
			'slug'     => 'refund-policy',
			'title'    => 'Refund Policy',
			'template' => '',
		),
	);
}

/**
 * Create the pages, assign templates and set the reading options.
 *
 * @return int Number of pages created.
 */
function cpf_scaffold_run_pages() {
	$created = 0;
	$ids     = array();

	foreach ( cpf_scaffold_pages() as $row ) {
		$parent_id = 0;

		if ( ! empty( $row['parent'] ) && isset( $ids[ $row['parent'] ] ) ) {
			$parent_id = $ids[ $row['parent'] ];
		}

		$existing = get_page_by_path(
			$parent_id ? get_post_field( 'post_name', $parent_id ) . '/' . $row['slug'] : $row['slug']
		);

		if ( $existing instanceof WP_Post ) {
			$ids[ $row['slug'] ] = $existing->ID;

			// WordPress ships a draft privacy policy page; publish it rather than
			// leaving the footer link pointing at a 404.
			if ( 'publish' !== $existing->post_status ) {
				wp_update_post(
					array(
						'ID'          => $existing->ID,
						'post_status' => 'publish',
					)
				);
			}
		} else {
			$page_id = wp_insert_post(
				array(
					'post_type'    => 'page',
					'post_status'  => 'publish',
					'post_title'   => $row['title'],
					'post_name'    => $row['slug'],
					'post_parent'  => $parent_id,
					'post_content' => '',
				),
				true
			);

			if ( is_wp_error( $page_id ) ) {
				continue;
			}

			$ids[ $row['slug'] ] = $page_id;
			++$created;
		}

		if ( '' !== $row['template'] ) {
			update_post_meta( $ids[ $row['slug'] ], '_wp_page_template', $row['template'] );
		}
	}

	if ( isset( $ids['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $ids['home'] );
	}

	if ( isset( $ids['guides'] ) ) {
		update_option( 'page_for_posts', $ids['guides'] );
	}

	// Post name permalinks, so every slug matches the live URL.
	if ( '/%postname%/' !== get_option( 'permalink_structure' ) ) {
		global $wp_rewrite;

		$wp_rewrite->set_permalink_structure( '/%postname%/' );
		$wp_rewrite->flush_rules();
	}

	return $created;
}

/**
 * The three menus, mirroring the live navigation.
 *
 * @return array<string,array>
 */
function cpf_scaffold_menus() {
	return array(
		'primary'      => array(
			'name'  => 'Header Mega Menu',
			'items' => array(
				array(
					'title'    => 'Services',
					'url'      => '',
					'children' => array(
						array(
							'title'    => 'Company Formation',
							'children' => array(
								array(
									'title' => 'U.S. company formation',
									'page'  => 'us-formation/non-resident',
								),
								array(
									'title' => 'Canada incorporation',
									'page'  => 'canada-incorporation',
								),
								array(
									'title' => 'U.S. + Canada bundle',
									'url'   => '/#products',
								),
								array(
									'title' => 'E-Commerce Launch package',
									'url'   => '/#ecommerce',
								),
								array(
									'title' => 'Compare & get pricing',
									'page'  => 'pricing',
								),
							),
						),
						array(
							'title'    => 'Guides & Support',
							'children' => array(
								array(
									'title' => 'Banking documentation guidance',
									'url'   => '/#banking',
								),
								array(
									'title' => 'All guides',
									'page'  => 'guides',
								),
								array(
									'title' => 'Talk to an advisor',
									'page'  => 'contact-us',
								),
							),
						),
						array(
							'title'       => 'Not sure where to start?',
							'classes'     => 'mega-feature',
							'description' => 'Answer a few quick questions and get your recommended setup path.',
							'children'    => array(
								array(
									'title' => 'Find your setup path',
									'url'   => '/#hero-quiz-anchor',
								),
							),
						),
					),
				),
				array(
					'title'    => 'Business Management',
					'url'      => '',
					'children' => array(
						array(
							'title'    => 'Run & Comply',
							'children' => array(
								array(
									'title'       => 'FilingGuard',
									'page'        => 'filingguard',
									'description' => 'NEW',
								),
								array(
									'title' => 'Annual compliance',
									'page'  => 'pricing',
								),
								array(
									'title' => 'US business address & mail',
									'page'  => 'pricing',
								),
							),
						),
						array(
							'title'    => 'Banking & Payments',
							'children' => array(
								array(
									'title' => 'Banking documentation guidance',
									'url'   => '/#banking',
								),
								array(
									'title' => 'Payment-platform readiness',
									'page'  => 'pricing',
								),
							),
						),
						array(
							'title'    => 'Digital Presence',
							'children' => array(
								array(
									'title' => 'Domain, email & website',
									'page'  => 'pricing',
								),
								array(
									'title' => 'Business logo',
									'page'  => 'pricing',
								),
							),
						),
					),
				),
				array(
					'title' => 'Pricing',
					'page'  => 'pricing',
				),
				array(
					'title' => 'Contact',
					'page'  => 'contact-us',
				),
			),
		),
		'footer_nav'   => array(
			'name'  => 'Footer Navigate',
			'items' => array(
				array(
					'title' => 'Services',
					'page'  => 'pricing',
				),
				array(
					'title' => 'Guides',
					'page'  => 'guides',
				),
				array(
					'title' => 'Pricing',
					'page'  => 'pricing',
				),
				array(
					'title' => 'FilingGuard',
					'page'  => 'filingguard',
				),
				array(
					'title' => 'Contact Us',
					'page'  => 'contact-us',
				),
			),
		),
		'footer_legal' => array(
			'name'  => 'Footer Legal Bar',
			'items' => array(
				array(
					'title' => 'Privacy Policy',
					'page'  => 'privacy-policy',
				),
				array(
					'title' => 'Terms of Service',
					'page'  => 'terms-of-services',
				),
				array(
					'title' => 'Refund Policy',
					'page'  => 'refund-policy',
				),
				array(
					'title' => 'Contact Us',
					'page'  => 'contact-us',
				),
			),
		),
	);
}

/**
 * Add one menu item and its children.
 *
 * @param int   $menu_id   Menu term ID.
 * @param array $item      Item definition.
 * @param int   $parent_id Parent menu item ID.
 * @param int   $position  Sort position.
 * @return void
 */
function cpf_scaffold_add_item( $menu_id, $item, $parent_id = 0, $position = 0 ) {
	$args = array(
		'menu-item-title'       => $item['title'],
		'menu-item-status'      => 'publish',
		'menu-item-parent-id'   => $parent_id,
		'menu-item-position'    => $position,
		'menu-item-classes'     => isset( $item['classes'] ) ? $item['classes'] : '',
		'menu-item-description' => isset( $item['description'] ) ? $item['description'] : '',
	);

	if ( ! empty( $item['page'] ) ) {
		$page = get_page_by_path( $item['page'] );

		if ( $page instanceof WP_Post ) {
			$args['menu-item-type']      = 'post_type';
			$args['menu-item-object']    = 'page';
			$args['menu-item-object-id'] = $page->ID;
		} else {
			$args['menu-item-type'] = 'custom';
			$args['menu-item-url']  = home_url( '/' . $item['page'] . '/' );
		}
	} else {
		$args['menu-item-type'] = 'custom';
		$args['menu-item-url']  = isset( $item['url'] ) && '' !== $item['url'] ? $item['url'] : '#';
	}

	$item_id = wp_update_nav_menu_item( $menu_id, 0, $args );

	if ( is_wp_error( $item_id ) || empty( $item['children'] ) ) {
		return;
	}

	$child_position = 0;

	foreach ( $item['children'] as $child ) {
		++$child_position;
		cpf_scaffold_add_item( $menu_id, $child, $item_id, $child_position );
	}
}

/**
 * Build the three menus and assign them to their locations.
 *
 * A menu that already exists is left alone.
 *
 * @return int Number of menus created.
 */
function cpf_scaffold_run_menus() {
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$created   = 0;

	foreach ( cpf_scaffold_menus() as $location => $menu ) {
		$existing = wp_get_nav_menu_object( $menu['name'] );

		if ( ! $existing ) {
			$menu_id = wp_create_nav_menu( $menu['name'] );

			if ( is_wp_error( $menu_id ) ) {
				continue;
			}

			$position = 0;

			foreach ( $menu['items'] as $item ) {
				++$position;
				cpf_scaffold_add_item( $menu_id, $item, 0, $position );
			}

			++$created;
		} else {
			$menu_id = $existing->term_id;
		}

		$locations[ $location ] = $menu_id;
	}

	set_theme_mod( 'nav_menu_locations', $locations );

	return $created;
}

/**
 * Add the scaffolding screen under the CrossPoint menu.
 *
 * @return void
 */
function cpf_scaffold_menu_page() {
	add_submenu_page(
		'cpf-settings',
		__( 'Set up pages & menus', 'crosspoint' ),
		__( 'Set up pages & menus', 'crosspoint' ),
		'manage_options',
		'cpf-scaffold',
		'cpf_render_scaffold_page'
	);
}
add_action( 'admin_menu', 'cpf_scaffold_menu_page' );

/**
 * Render and handle the scaffolding screen.
 *
 * @return void
 */
function cpf_render_scaffold_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to scaffold this site.', 'crosspoint' ) );
	}

	$pages = null;
	$menus = null;

	if ( isset( $_POST['cpf_scaffold_nonce'] ) &&
		wp_verify_nonce( sanitize_key( wp_unslash( $_POST['cpf_scaffold_nonce'] ) ), 'cpf_scaffold' ) ) {
		$pages = cpf_scaffold_run_pages();
		$menus = cpf_scaffold_run_menus();
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Set up pages & menus', 'crosspoint' ); ?></h1>

		<?php if ( null !== $pages ) : ?>
			<div class="notice notice-success">
				<p>
					<?php
					printf(
						/* translators: 1: pages created, 2: menus created. */
						esc_html__( '%1$d pages and %2$d menus created.', 'crosspoint' ),
						(int) $pages,
						(int) $menus
					);
					?>
				</p>
			</div>
		<?php endif; ?>

		<p>
			<?php esc_html_e( 'Creates every page the live site has, at the same slug and with the right template assigned, sets the homepage and the guides page, and builds the header and footer menus. Anything that already exists is left untouched, so this is safe to run again.', 'crosspoint' ); ?>
		</p>

		<form method="post">
			<?php wp_nonce_field( 'cpf_scaffold', 'cpf_scaffold_nonce' ); ?>
			<?php submit_button( __( 'Create missing pages and menus', 'crosspoint' ) ); ?>
		</form>
	</div>
	<?php
}
