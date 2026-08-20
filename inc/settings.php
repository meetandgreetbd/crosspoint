<?php
/**
 * CrossPoint Settings admin page.
 *
 * One menu page, three tabs, one option array (cpf_settings): one DB row and
 * one autoload decision. Native Settings API only - no ACF, no page builder.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

/**
 * Field definitions for every tab.
 *
 * @return array<string,array<string,array<string,mixed>>>
 */
function cpf_settings_fields() {
	return array(
		'contact'  => array(
			'label'  => __( 'Contact', 'crosspoint' ),
			'fields' => array(
				'whatsapp_number'   => array(
					'label' => __( 'WhatsApp number', 'crosspoint' ),
					'type'  => 'text',
					'desc'  => __( 'Digits only, including country code. Used by every WhatsApp link on the site.', 'crosspoint' ),
				),
				'whatsapp_text'     => array(
					'label' => __( 'WhatsApp prefilled message', 'crosspoint' ),
					'type'  => 'text',
				),
				'contact_email'     => array(
					'label' => __( 'Contact email', 'crosspoint' ),
					'type'  => 'email',
				),
				'calendly_url'      => array(
					'label' => __( 'Calendly booking URL', 'crosspoint' ),
					'type'  => 'url',
					'desc'  => __( 'Used by the "Book a free 15-min call" buttons and the Calendly embed on /contact-us/.', 'crosspoint' ),
				),
				'stripe_login_url'  => array(
					'label' => __( 'Stripe billing login URL', 'crosspoint' ),
					'type'  => 'url',
					'desc'  => __( 'Target of the header Login button.', 'crosspoint' ),
				),
				'brand_blurb'       => array(
					'label' => __( 'Footer brand blurb', 'crosspoint' ),
					'type'  => 'textarea',
				),
				'address_card'      => array(
					'label' => __( 'Footer address card', 'crosspoint' ),
					'type'  => 'textarea',
					'desc'  => __( 'Basic HTML allowed.', 'crosspoint' ),
				),
				'support_line'      => array(
					'label' => __( 'Footer support line', 'crosspoint' ),
					'type'  => 'text',
				),
				'footer_disclaimer' => array(
					'label' => __( 'Footer disclaimer', 'crosspoint' ),
					'type'  => 'textarea',
					'rows'  => 8,
					'desc'  => __( 'One disclaimer for every page. Basic HTML allowed.', 'crosspoint' ),
				),
			),
		),
		'leads'    => array(
			'label'  => __( 'Leads &amp; Email', 'crosspoint' ),
			'fields' => array(
				'ff_form_id'          => array(
					'label' => __( 'Fluent Forms form ID', 'crosspoint' ),
					'type'  => 'number',
					'desc'  => __( 'The form leads are written into. Leave 0 to store leads in the theme fallback instead.', 'crosspoint' ),
				),
				'notify_default'      => array(
					'label' => __( 'Default notification recipients', 'crosspoint' ),
					'type'  => 'emails',
					'desc'  => __( 'One email address per line. Used when a source has no list of its own.', 'crosspoint' ),
				),
				'notify_start_wizard' => array(
					'label' => __( 'Recipients: /start/ wizard leads', 'crosspoint' ),
					'type'  => 'emails',
				),
				'notify_home_quiz'    => array(
					'label' => __( 'Recipients: homepage quiz leads', 'crosspoint' ),
					'type'  => 'emails',
				),
				'notify_contact'      => array(
					'label' => __( 'Recipients: contact form leads', 'crosspoint' ),
					'type'  => 'emails',
				),
				'autoreply_enabled'   => array(
					'label' => __( 'Send an auto-reply to the lead', 'crosspoint' ),
					'type'  => 'checkbox',
				),
				'autoreply_subject'   => array(
					'label' => __( 'Auto-reply subject', 'crosspoint' ),
					'type'  => 'text',
				),
				'autoreply_body'      => array(
					'label' => __( 'Auto-reply body', 'crosspoint' ),
					'type'  => 'textarea',
					'rows'  => 8,
					'desc'  => __( 'Basic HTML allowed. {name} is replaced with the lead name.', 'crosspoint' ),
				),
			),
		),
		'tracking' => array(
			'label'  => __( 'Tracking', 'crosspoint' ),
			'fields' => array(
				'ga4_id'              => array(
					'label' => __( 'GA4 measurement ID', 'crosspoint' ),
					'type'  => 'text',
					'desc'  => __( 'e.g. G-XXXXXXX. Leave empty to load nothing.', 'crosspoint' ),
				),
				'gads_id'             => array(
					'label' => __( 'Google Ads conversion ID', 'crosspoint' ),
					'type'  => 'text',
				),
				'gads_label_form'     => array(
					'label' => __( 'Google Ads label: form submit', 'crosspoint' ),
					'type'  => 'text',
				),
				'gads_label_whatsapp' => array(
					'label' => __( 'Google Ads label: WhatsApp click', 'crosspoint' ),
					'type'  => 'text',
				),
				'gads_label_purchase' => array(
					'label' => __( 'Google Ads label: purchase', 'crosspoint' ),
					'type'  => 'text',
				),
				'bing_uet_id'         => array(
					'label' => __( 'Microsoft UET tag ID', 'crosspoint' ),
					'type'  => 'text',
				),
				'chat_enabled'        => array(
					'label' => __( 'Show the CrossPoint chat widget', 'crosspoint' ),
					'type'  => 'checkbox',
				),
				'chat_knowledge'      => array(
					'label' => __( 'Chat assistant knowledge', 'crosspoint' ),
					'type'  => 'textarea',
					'rows'  => 14,
					'desc'  => __( 'System prompt for the chat assistant. The current price list is appended automatically from the Packages screen, so prices are never written here.', 'crosspoint' ),
				),
				'promo_bar_text'      => array(
					'label' => __( 'Promo bar text', 'crosspoint' ),
					'type'  => 'text',
					'desc'  => __( 'Shown above the header when not empty.', 'crosspoint' ),
				),
			),
		),
	);
}

/**
 * Register the option and its sanitizer.
 *
 * @return void
 */
function cpf_register_settings() {
	register_setting(
		'cpf_settings_group',
		'cpf_settings',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'cpf_sanitize_settings',
			'default'           => array(),
		)
	);
}
add_action( 'admin_init', 'cpf_register_settings' );

/**
 * Add the CrossPoint menu page.
 *
 * @return void
 */
function cpf_settings_menu() {
	add_menu_page(
		__( 'CrossPoint Settings', 'crosspoint' ),
		__( 'CrossPoint', 'crosspoint' ),
		'manage_options',
		'cpf-settings',
		'cpf_render_settings_page',
		'dashicons-admin-site-alt3',
		// A float keeps this clear of the core positions: 59 is the separator
		// above Appearance and 60 is Appearance itself, and colliding with
		// either pushes the menu somewhere unpredictable.
		58.9
	);

	// Without this, WordPress labels the first child with the parent's menu
	// title, giving a "CrossPoint > CrossPoint" entry.
	add_submenu_page(
		'cpf-settings',
		__( 'CrossPoint Settings', 'crosspoint' ),
		__( 'Settings', 'crosspoint' ),
		'manage_options',
		'cpf-settings',
		'cpf_render_settings_page'
	);
}
// Priority 8: the parent must exist before core attaches the post type
// submenus, which it does on admin_menu at priority 9.
add_action( 'admin_menu', 'cpf_settings_menu', 8 );

/**
 * Flag a fresh activation so the next admin page load can greet the user.
 *
 * WordPress does not send anyone anywhere after a theme is activated, so a new
 * install gives no hint that the packages, pages and menus still need seeding.
 * The flag is a 60 second transient: if the user navigates elsewhere first it
 * simply expires and never fires.
 *
 * @return void
 */
function cpf_flag_activation_redirect() {
	if ( is_network_admin() ) {
		return;
	}

	set_transient( 'cpf_activation_redirect', 1, MINUTE_IN_SECONDS );
}
add_action( 'after_switch_theme', 'cpf_flag_activation_redirect' );

/**
 * Send the user to CrossPoint Settings once, right after activation.
 *
 * @return void
 */
function cpf_maybe_activation_redirect() {
	if ( ! get_transient( 'cpf_activation_redirect' ) ) {
		return;
	}

	delete_transient( 'cpf_activation_redirect' );

	if ( wp_doing_ajax() || is_network_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Never hijack a bulk theme activation.
	if ( isset( $_GET['activate-multi'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Presence check only, no data is read.
		return;
	}

	wp_safe_redirect( admin_url( 'admin.php?page=cpf-settings' ) );
	exit;
}
add_action( 'admin_init', 'cpf_maybe_activation_redirect' );

/**
 * Sanitize a newline or comma separated list of email addresses.
 *
 * @param string $raw Raw textarea value.
 * @return string One valid address per line.
 */
function cpf_sanitize_email_list( $raw ) {
	$emails = array_filter( array_map( 'sanitize_email', preg_split( '/[\r\n,]+/', (string) $raw ) ) );

	return implode( "\n", array_unique( $emails ) );
}

/**
 * Sanitize the whole settings array, field by field.
 *
 * @param mixed $input Raw submitted values.
 * @return array<string,mixed>
 */
function cpf_sanitize_settings( $input ) {
	$input  = is_array( $input ) ? $input : array();
	$out    = array();
	$groups = cpf_settings_fields();

	foreach ( $groups as $group ) {
		foreach ( $group['fields'] as $key => $field ) {
			$raw = isset( $input[ $key ] ) ? $input[ $key ] : '';

			switch ( $field['type'] ) {
				case 'email':
					$out[ $key ] = sanitize_email( $raw );
					break;
				case 'url':
					$out[ $key ] = esc_url_raw( $raw );
					break;
				case 'number':
					$out[ $key ] = absint( $raw );
					break;
				case 'checkbox':
					$out[ $key ] = rest_sanitize_boolean( $raw );
					break;
				case 'emails':
					$out[ $key ] = cpf_sanitize_email_list( $raw );
					break;
				case 'textarea':
					$out[ $key ] = wp_kses_post( $raw );
					break;
				default:
					$out[ $key ] = sanitize_text_field( $raw );
					break;
			}
		}
	}

	// The WhatsApp number keeps digits and a leading plus only.
	if ( isset( $out['whatsapp_number'] ) ) {
		$out['whatsapp_number'] = preg_replace( '/[^0-9+]/', '', (string) $out['whatsapp_number'] );
	}

	return apply_filters( 'cpf_sanitized_settings', $out, $input );
}

/**
 * Render one field control.
 *
 * @param string $key   Setting key.
 * @param array  $field Field definition.
 * @return void
 */
function cpf_render_settings_field( $key, $field ) {
	$value = cpf_get_setting( $key );
	$name  = 'cpf_settings[' . $key . ']';
	$rows  = isset( $field['rows'] ) ? (int) $field['rows'] : 4;

	switch ( $field['type'] ) {
		case 'checkbox':
			printf(
				'<label><input type="checkbox" name="%1$s" value="1" %2$s> %3$s</label>',
				esc_attr( $name ),
				checked( (bool) $value, true, false ),
				esc_html__( 'Enabled', 'crosspoint' )
			);
			break;

		case 'textarea':
		case 'emails':
			printf(
				'<textarea name="%1$s" id="%2$s" rows="%3$d" class="large-text code">%4$s</textarea>',
				esc_attr( $name ),
				esc_attr( $key ),
				(int) $rows,
				esc_textarea( (string) $value )
			);
			break;

		case 'number':
			printf(
				'<input type="number" min="0" step="1" name="%1$s" id="%2$s" value="%3$s" class="small-text">',
				esc_attr( $name ),
				esc_attr( $key ),
				esc_attr( (string) $value )
			);
			break;

		default:
			printf(
				'<input type="%1$s" name="%2$s" id="%3$s" value="%4$s" class="regular-text">',
				esc_attr( 'email' === $field['type'] ? 'email' : ( 'url' === $field['type'] ? 'url' : 'text' ) ),
				esc_attr( $name ),
				esc_attr( $key ),
				esc_attr( (string) $value )
			);
			break;
	}

	if ( ! empty( $field['desc'] ) ) {
		printf( '<p class="description">%s</p>', esc_html( $field['desc'] ) );
	}
}

/**
 * Setup status: what is ready and what still needs doing.
 *
 * This is the first thing shown after activation, so a new install says plainly
 * which of the one-time steps are outstanding instead of leaving the user to
 * guess why the site looks empty.
 *
 * @return void
 */
function cpf_render_setup_status() {
	$packages = wp_count_posts( 'cpf_package' );
	$packages = isset( $packages->publish ) ? (int) $packages->publish : 0;

	$faqs = wp_count_posts( 'cpf_faq' );
	$faqs = isset( $faqs->publish ) ? (int) $faqs->publish : 0;

	$front     = (int) get_option( 'page_on_front' );
	$posts_pg  = (int) get_option( 'page_for_posts' );
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$menus     = count( array_filter( (array) $locations ) );
	$ff_form   = (int) cpf_get_setting( 'ff_form_id' );
	$ff_active = function_exists( 'wpFluent' );

	$rows = array(
		array(
			'label' => __( 'Packages', 'crosspoint' ),
			'ok'    => $packages > 0,
			/* translators: %d: number of published packages. */
			'text'  => sprintf( _n( '%d published', '%d published', $packages, 'crosspoint' ), $packages ),
			'help'  => __( 'Every price on the site comes from these.', 'crosspoint' ),
			'link'  => admin_url( 'admin.php?page=cpf-seed-packages' ),
			'cta'   => __( 'Seed live packages', 'crosspoint' ),
		),
		array(
			'label' => __( 'FAQs', 'crosspoint' ),
			'ok'    => $faqs > 0,
			/* translators: %d: number of published FAQs. */
			'text'  => sprintf( _n( '%d published', '%d published', $faqs, 'crosspoint' ), $faqs ),
			'help'  => __( 'Shown on the homepage and in the FAQ schema.', 'crosspoint' ),
			'link'  => admin_url( 'edit.php?post_type=cpf_faq' ),
			'cta'   => __( 'Manage FAQs', 'crosspoint' ),
		),
		array(
			'label' => __( 'Pages and templates', 'crosspoint' ),
			'ok'    => $front > 0 && $posts_pg > 0,
			'text'  => ( $front > 0 && $posts_pg > 0 )
				? __( 'Homepage and guides page set', 'crosspoint' )
				: __( 'Not set up yet', 'crosspoint' ),
			'help'  => __( 'Creates every live URL at the same slug.', 'crosspoint' ),
			'link'  => admin_url( 'admin.php?page=cpf-scaffold' ),
			'cta'   => __( 'Set up pages & menus', 'crosspoint' ),
		),
		array(
			'label' => __( 'Menus', 'crosspoint' ),
			'ok'    => $menus >= 3,
			/* translators: %d: number of assigned menu locations. */
			'text'  => sprintf( __( '%d of 3 locations assigned', 'crosspoint' ), $menus ),
			'help'  => __( 'Header mega menu, footer navigate, footer legal.', 'crosspoint' ),
			'link'  => admin_url( 'nav-menus.php?action=locations' ),
			'cta'   => __( 'Menu locations', 'crosspoint' ),
		),
		array(
			'label' => __( 'Lead storage', 'crosspoint' ),
			'ok'    => $ff_active && $ff_form > 0,
			'text'  => ( $ff_active && $ff_form > 0 )
				? __( 'Fluent Forms', 'crosspoint' )
				: __( 'Theme fallback (no lead is lost)', 'crosspoint' ),
			'help'  => $ff_active
				? __( 'Set the form ID on the Leads & Email tab.', 'crosspoint' )
				: __( 'Install Fluent Forms to store leads in its Entries screen.', 'crosspoint' ),
			'link'  => admin_url( 'edit.php?post_type=cpf_lead' ),
			'cta'   => __( 'View stored leads', 'crosspoint' ),
		),
	);

	$outstanding = count(
		array_filter(
			$rows,
			function ( $r ) {
				return ! $r['ok'];
			}
		)
	);
	?>
	<div class="card" style="max-width:100%;padding:4px 20px 16px;margin-top:16px">
		<h2 style="margin-bottom:4px"><?php esc_html_e( 'Setup status', 'crosspoint' ); ?></h2>

		<p class="description" style="margin-bottom:14px">
			<?php
			if ( $outstanding ) {
				printf(
					/* translators: %d: number of outstanding setup steps. */
					esc_html( _n( '%d step still needs attention.', '%d steps still need attention.', $outstanding, 'crosspoint' ) ),
					(int) $outstanding
				);
			} else {
				esc_html_e( 'Everything is set up.', 'crosspoint' );
			}
			?>
		</p>

		<table class="widefat striped">
			<tbody>
			<?php foreach ( $rows as $row ) : ?>
				<tr>
					<td style="width:26px">
						<span style="color:<?php echo $row['ok'] ? '#157A4A' : '#B5730E'; ?>;font-weight:700">
							<?php echo $row['ok'] ? '&#10003;' : '&#33;'; ?>
						</span>
					</td>
					<th scope="row" style="width:190px"><?php echo esc_html( $row['label'] ); ?></th>
					<td>
						<strong><?php echo esc_html( $row['text'] ); ?></strong><br>
						<span class="description"><?php echo esc_html( $row['help'] ); ?></span>
					</td>
					<td style="width:190px;text-align:right">
						<a class="button" href="<?php echo esc_url( $row['link'] ); ?>"><?php echo esc_html( $row['cta'] ); ?></a>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php
}

/**
 * Render the settings screen.
 *
 * @return void
 */
function cpf_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to manage CrossPoint settings.', 'crosspoint' ) );
	}

	$groups  = cpf_settings_fields();
	$tabs    = array_keys( $groups );
	$current = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : $tabs[0]; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only tab switch.

	if ( ! isset( $groups[ $current ] ) ) {
		$current = $tabs[0];
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'CrossPoint Settings', 'crosspoint' ); ?></h1>

		<?php cpf_render_setup_status(); ?>

		<h2 class="nav-tab-wrapper">
			<?php foreach ( $groups as $slug => $group ) : ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=cpf-settings&tab=' . $slug ) ); ?>"
					class="nav-tab <?php echo $slug === $current ? 'nav-tab-active' : ''; ?>">
					<?php echo esc_html( wp_specialchars_decode( $group['label'] ) ); ?>
				</a>
			<?php endforeach; ?>
		</h2>

		<form action="options.php" method="post">
			<?php settings_fields( 'cpf_settings_group' ); ?>

			<?php foreach ( $groups as $slug => $group ) : ?>
				<div <?php echo $slug === $current ? '' : 'style="display:none"'; ?>>
					<table class="form-table" role="presentation">
						<tbody>
						<?php foreach ( $group['fields'] as $key => $field ) : ?>
							<tr>
								<th scope="row">
									<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
								</th>
								<td><?php cpf_render_settings_field( $key, $field ); ?></td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php endforeach; ?>

			<?php submit_button(); ?>
		</form>

		<p class="description">
			<?php esc_html_e( 'Every tab is saved together, so switching tabs before saving never loses a value.', 'crosspoint' ); ?>
		</p>
	</div>
	<?php
}
