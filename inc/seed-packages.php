<?php
/**
 * One-time seeding of the Packages CPT from the live price list.
 *
 * Runs once on theme activation and can be re-run from Packages -> Seed live
 * packages. Seeding is keyed on the machine key, so re-running never creates a
 * duplicate and never overwrites a price an editor has since changed: existing
 * packages are left exactly as they are.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

/**
 * The package list as published on the live static site (19 August 2026).
 *
 * @return array<int,array<string,mixed>>
 */
function cpf_seed_package_data() {
	$start   = '/start/';
	$started = __( 'Get started', 'crosspoint' );

	return array(
		// ---------- U.S. formation ----------
		array(
			'key'      => 'starter',
			'title'    => 'Starter',
			'groups'   => array( 'us' ),
			'price'    => '299',
			'prefix'   => 'from',
			'fee_note' => '+ state fees',
			'tagline'  => 'Everything to form and stay compliant.',
			'features' => array(
				'Company formation filing',
				'Registered agent — 1 year included',
				'Live name availability check',
				'Digital company documents',
				'Business logo included',
				'Ongoing compliance reminders (state-required)',
			),
			'addons'   => 'ein,itin,address,rush,salestax,tax',
			'cta'      => $started,
			'cta_url'  => $start,
			'order'    => 10,
		),
		array(
			'key'      => 'growth',
			'title'    => 'Growth',
			'groups'   => array( 'us' ),
			'price'    => '399',
			'prefix'   => 'from',
			'fee_note' => '+ state fees',
			'tagline'  => 'Adds banking guidance and getting paid in USD.',
			'badge'    => 'Most chosen',
			'featured' => true,
			'features' => array(
				'Everything in Starter',
				'Post-formation compliance guidance included',
				'US business banking setup guidance',
				'Set up to invoice & get paid in USD',
				'Operating agreement template',
				'Priority email support',
			),
			'addons'   => 'itin,address,rush,salestax,tax',
			'cta'      => $started,
			'cta_url'  => $start,
			'order'    => 20,
		),
		array(
			'key'      => 'premium',
			'title'    => 'Premium',
			'groups'   => array( 'us' ),
			'price'    => '699',
			'prefix'   => 'from',
			'fee_note' => '+ state fees',
			'tagline'  => 'Fully handled, with dedicated support.',
			'features' => array(
				'Everything in Growth',
				'Annual compliance planning session',
				'Dedicated account manager',
				'US business mail handling',
				'Priority filing',
			),
			'addons'   => 'cogs,salestax,tax',
			'cta'      => $started,
			'cta_url'  => $start,
			'order'    => 30,
		),

		// ---------- U.S. e-commerce ----------
		array(
			'key'      => 'ecom',
			'title'    => 'E-Commerce Launch',
			'groups'   => array( 'us-ecommerce' ),
			'price'    => '749',
			'prefix'   => 'from',
			'fee_note' => '+ state fees',
			'tagline'  => 'Get a seller-ready U.S. company live.',
			'features' => array(
				'U.S. LLC formation — any state',
				'Post-formation document guidance',
				'Amazon seller documentation guidance',
				'Shopify & payment-platform readiness',
				'U.S. address support for seller applications',
				'1–5 page business website',
				'One dedicated setup advisor',
				'Live name availability check',
				'Business logo included',
			),
			'addons'   => 'ein,itin,address,ra,cogs,rush,salestax,tax',
			'cta'      => $started,
			'cta_url'  => $start,
			'order'    => 40,
		),
		array(
			'key'      => 'egrowth',
			'title'    => 'E-Commerce Growth',
			'groups'   => array( 'us-ecommerce' ),
			'price'    => '849',
			'prefix'   => 'from',
			'fee_note' => '+ state fees',
			'tagline'  => 'Adds banking and USD invoicing.',
			'badge'    => 'Most chosen',
			'featured' => true,
			'features' => array(
				'Everything in E-Commerce Launch',
				'US business banking setup guidance',
				'Set up to invoice & get paid in USD',
				'Operating agreement template',
				'Priority email support',
			),
			'addons'   => 'itin,address,ra,cogs,rush,salestax,tax',
			'cta'      => $started,
			'cta_url'  => $start,
			'order'    => 50,
		),
		array(
			'key'      => 'epremium',
			'title'    => 'E-Commerce Premium',
			'groups'   => array( 'us-ecommerce' ),
			'price'    => '1,149',
			'prefix'   => 'from',
			'fee_note' => '+ state fees',
			'tagline'  => 'The full seller setup, fully managed.',
			'features' => array(
				'Everything in E-Commerce Growth',
				'Annual compliance planning session',
				'Dedicated account manager',
				'US business mail handling',
				'Priority filing',
			),
			'addons'   => 'cogs,rush,salestax,tax',
			'cta'      => $started,
			'cta_url'  => $start,
			'order'    => 60,
		),

		// ---------- Canada ----------
		array(
			'key'      => 'castarter',
			'title'    => 'Starter Setup',
			'groups'   => array( 'canada' ),
			'price'    => '199',
			'fee_note' => '+ gov fee, charged separately',
			'tagline'  => 'Incorporate provincially or federally.',
			'features' => array(
				'Government fee charged separately at cost',
				'Incorporation in a supported province (ON, BC, AB, SK) or federally',
				'Certificate of Incorporation & Articles',
				'NUANS name search (named companies)',
				'Named or numbered company option',
				'Digital document delivery',
				'Filing status updates by email',
				'Jurisdiction selection guidance',
				'Next-step checklist after incorporation',
			),
			'cta'      => $started,
			'cta_url'  => $start,
			'order'    => 70,
		),
		array(
			'key'      => 'canonres',
			'title'    => 'Non-Resident Setup',
			'groups'   => array( 'canada' ),
			'price'    => '1,069',
			'fee_note' => '+ gov fee, charged separately',
			'tagline'  => 'Built for founders outside Canada.',
			'badge'    => 'For non-residents',
			'featured' => true,
			'features' => array(
				'Government fee charged separately at cost',
				'Everything in Starter Setup',
				'Non-resident founder onboarding call',
				'Bank-ready document pack',
				'Corporate resolution pack for banks',
				'Jurisdiction fit consultation',
				'Director residency guidance',
				'Registered office for non-residents',
				'Banking documentation guidance',
				'Cross-border founder FAQ walkthrough',
				'Dedicated advisor check-in',
			),
			'cta'      => $started,
			'cta_url'  => $start,
			'order'    => 80,
		),
		array(
			'key'      => 'cagrowth',
			'title'    => 'Growth Setup',
			'groups'   => array( 'canada' ),
			'price'    => '1,464',
			'fee_note' => '+ gov fee, charged separately',
			'tagline'  => 'Full corporate records & first-year compliance.',
			'features' => array(
				'Government fee charged separately at cost',
				'Everything in Starter Setup',
				'Digital minute book & organizational resolutions',
				'Corporate bylaws, share certificates & seal',
				'Physical minute book binder kit',
				'.ca domain name registration',
				'Registered office address — 12 months',
				'Agent for service of process',
				'First annual corporate return filing',
				'Canadian tax account setup guidance',
				'GST/HST setup guidance',
				'Compliance reminders & deadline alerts',
				'Legal templates (employment, NDA, shareholder & more)',
				'Dedicated first-year support line',
			),
			'cta'      => $started,
			'cta_url'  => $start,
			'order'    => 90,
		),

		// ---------- Bundle + homepage cards ----------
		array(
			'key'      => 'bundle',
			'title'    => 'U.S. + Canada',
			'groups'   => array( 'bundle', 'home' ),
			'price'    => '419',
			'compare'  => '498',
			'corner'   => 'Save $79',
			'fee_note' => 'USD · one-time · + Canada gov fee · + U.S. state fees',
			'tagline'  => 'Canada and U.S. company setup — one provider',
			'perk'     => 'Free basic website (1–5 pages), domain & email — 1 year',
			'features' => array(
				'Registered agent (U.S.) + registered office (Canada) — year 1 included',
				'All formation documents delivered',
				'Banking guidance for both countries',
				'One dedicated point of contact',
			),
			'cta'      => 'Choose U.S. + Canada Bundle',
			'cta_url'  => '',
			'order'    => 130,
		),
		array(
			'key'        => 'home-canada',
			'title'      => 'Canada',
			'groups'     => array( 'home' ),
			'price_from' => 'castarter',
			'prefix'     => 'from',
			'fee_note'   => 'USD · one-time · + gov fee, charged separately',
			'tagline'    => 'Corporation setup — ON, BC, AB, SK or federal options',
			'perk'       => 'Free basic website (1–5 pages), domain & email — 1 year',
			'features'   => array(
				'Filing coordination and document preparation',
				'Registered office — year 1 included',
				'Banking setup guidance',
				'Annual compliance plans available',
			),
			'cta'        => 'Choose Canada Setup',
			'cta_url'    => '/canada-incorporation/',
			'order'      => 110,
		),
		array(
			'key'        => 'home-us',
			'title'      => 'United States',
			'groups'     => array( 'home' ),
			'price_from' => 'starter',
			'prefix'     => 'from',
			'fee_note'   => 'USD · one-time · + state fees',
			'tagline'    => 'LLC or C-Corp setup — non-resident friendly',
			'badge'      => 'Recommended',
			'featured'   => true,
			'perk'       => 'Free basic website (1–5 pages), domain & email — 1 year',
			'features'   => array(
				'Registered agent — year 1 included · all 50 states',
				'No U.S. residency required for company formation',
				'Banking documentation guidance',
				'Mail forwarding & compliance available',
			),
			'cta'        => 'Choose U.S. Setup',
			'cta_url'    => $start,
			'order'      => 120,
		),

		// ---------- Add-ons ----------
		array(
			'key'    => 'ein',
			'title'  => 'Federal tax account setup',
			'groups' => array( 'addon' ),
			'price'  => '149',
			'order'  => 200,
		),
		array(
			'key'    => 'itin',
			'title'  => 'Personal US tax setup support',
			'groups' => array( 'addon' ),
			'price'  => '199',
			'flag'   => 'on request',
			'order'  => 210,
		),
		array(
			'key'    => 'address',
			'title'  => 'US address + mail forwarding (1 yr)',
			'groups' => array( 'addon' ),
			'price'  => '199',
			'order'  => 220,
		),
		array(
			'key'    => 'ra',
			'title'  => 'Registered agent — extra year',
			'groups' => array( 'addon' ),
			'price'  => '199',
			'order'  => 230,
		),
		array(
			'key'    => 'cogs',
			'title'  => 'Certificate of Good Standing',
			'groups' => array( 'addon' ),
			'price'  => '79',
			'order'  => 240,
		),
		array(
			'key'    => 'rush',
			'title'  => 'Expedited / rush filing',
			'groups' => array( 'addon' ),
			'price'  => '99',
			'order'  => 250,
		),
		array(
			'key'    => 'salestax',
			'title'  => 'Sales-tax setup (per state)',
			'groups' => array( 'addon', 'us-ecommerce' ),
			'price'  => '99',
			'order'  => 260,
		),
		array(
			'key'    => 'tax',
			'title'  => 'Annual US tax filing',
			'groups' => array( 'addon' ),
			'price'  => '499',
			'flag'   => 'on request',
			'order'  => 270,
		),

		// ---------- Renewals ----------
		array(
			'key'      => 'renewal',
			'title'    => 'Registered agent & compliance renewal',
			'groups'   => array( 'addon' ),
			'price'    => '199',
			'fee_note' => 'per year',
			'order'    => 280,
		),
		array(
			'key'      => 'renewal-plus',
			'title'    => 'Renewal with annual-filing support',
			'groups'   => array( 'addon' ),
			'price'    => '398',
			'fee_note' => 'per year',
			'order'    => 290,
		),

		// ---------- FilingGuard ----------
		array(
			'key'      => 'fg-basic',
			'title'    => 'FilingGuard Basic',
			'groups'   => array( 'filingguard' ),
			'price'    => '12',
			'fee_note' => 'per month',
			'order'    => 300,
		),
		array(
			'key'      => 'fg-pro',
			'title'    => 'FilingGuard Pro',
			'groups'   => array( 'filingguard' ),
			'price'    => '29',
			'fee_note' => 'per month',
			'order'    => 310,
		),
		array(
			'key'      => 'fg-scale',
			'title'    => 'FilingGuard Scale',
			'groups'   => array( 'filingguard' ),
			'price'    => '99',
			'fee_note' => 'per month',
			'order'    => 320,
		),
	);
}

/**
 * Create any package that does not exist yet.
 *
 * @return int Number of packages created.
 */
function cpf_seed_packages() {
	cpf_ensure_package_groups();

	$existing = cpf_get_package_key_map();
	$created  = 0;

	foreach ( cpf_seed_package_data() as $row ) {
		if ( isset( $existing[ $row['key'] ] ) ) {
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_type'   => 'cpf_package',
				'post_status' => 'publish',
				'post_title'  => $row['title'],
				'menu_order'  => isset( $row['order'] ) ? (int) $row['order'] : 0,
			),
			true
		);

		if ( is_wp_error( $post_id ) ) {
			continue;
		}

		$meta = array(
			'_cpf_key'           => $row['key'],
			'_cpf_price'         => isset( $row['price'] ) ? $row['price'] : '',
			'_cpf_price_from'    => isset( $row['price_from'] ) ? $row['price_from'] : '',
			'_cpf_price_prefix'  => isset( $row['prefix'] ) ? $row['prefix'] : '',
			'_cpf_compare_price' => isset( $row['compare'] ) ? $row['compare'] : '',
			'_cpf_fee_note'      => isset( $row['fee_note'] ) ? $row['fee_note'] : '',
			'_cpf_tagline'       => isset( $row['tagline'] ) ? $row['tagline'] : '',
			'_cpf_perk'          => isset( $row['perk'] ) ? $row['perk'] : '',
			'_cpf_features'      => isset( $row['features'] ) ? implode( "\n", $row['features'] ) : '',
			'_cpf_badge'         => isset( $row['badge'] ) ? $row['badge'] : '',
			'_cpf_corner'        => isset( $row['corner'] ) ? $row['corner'] : '',
			'_cpf_featured'      => ! empty( $row['featured'] ) ? 1 : 0,
			'_cpf_flag'          => isset( $row['flag'] ) ? $row['flag'] : '',
			'_cpf_addon_keys'    => isset( $row['addons'] ) ? $row['addons'] : '',
			'_cpf_cta_label'     => isset( $row['cta'] ) ? $row['cta'] : '',
			'_cpf_cta_url'       => isset( $row['cta_url'] ) ? $row['cta_url'] : '',
		);

		foreach ( $meta as $meta_key => $meta_value ) {
			update_post_meta( $post_id, $meta_key, $meta_value );
		}

		wp_set_object_terms( $post_id, $row['groups'], 'cpf_package_group' );

		++$created;
		$existing[ $row['key'] ] = $post_id;
	}

	cpf_flush_package_cache();

	return $created;
}

/**
 * Seed once when the theme is activated.
 *
 * @return void
 */
function cpf_seed_on_activation() {
	if ( get_option( 'cpf_packages_seeded' ) ) {
		return;
	}

	cpf_seed_packages();
	update_option( 'cpf_packages_seeded', 1, false );
}
add_action( 'after_switch_theme', 'cpf_seed_on_activation' );

/**
 * Add the manual re-seed screen under Packages.
 *
 * @return void
 */
function cpf_seed_menu() {
	add_submenu_page(
		'cpf-settings',
		__( 'Seed live packages', 'crosspoint' ),
		__( 'Seed live packages', 'crosspoint' ),
		'manage_options',
		'cpf-seed-packages',
		'cpf_render_seed_page'
	);
}
add_action( 'admin_menu', 'cpf_seed_menu' );

/**
 * Render and handle the manual seeding screen.
 *
 * @return void
 */
function cpf_render_seed_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to seed packages.', 'crosspoint' ) );
	}

	$created = null;

	if ( isset( $_POST['cpf_seed_nonce'] ) &&
		wp_verify_nonce( sanitize_key( wp_unslash( $_POST['cpf_seed_nonce'] ) ), 'cpf_seed_packages' ) ) {
		$created = cpf_seed_packages();
	}

	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Seed live packages', 'crosspoint' ); ?></h1>

		<?php if ( null !== $created ) : ?>
			<div class="notice notice-success">
				<p>
					<?php
					printf(
						/* translators: %d: number of packages created. */
						esc_html( _n( '%d package created.', '%d packages created.', (int) $created, 'crosspoint' ) ),
						(int) $created
					);
					?>
				</p>
			</div>
		<?php endif; ?>

		<p>
			<?php esc_html_e( 'Creates any package from the live price list that does not exist yet. Packages that already exist are never touched, so this is safe to run at any time and will not overwrite an edited price.', 'crosspoint' ); ?>
		</p>

		<form method="post">
			<?php wp_nonce_field( 'cpf_seed_packages', 'cpf_seed_nonce' ); ?>
			<?php submit_button( __( 'Create missing packages', 'crosspoint' ) ); ?>
		</form>
	</div>
	<?php
}
