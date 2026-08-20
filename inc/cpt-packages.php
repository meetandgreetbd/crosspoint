<?php
/**
 * Packages custom post type: registration, taxonomy, meta boxes and save.
 *
 * The Packages CPT is the single source of every price on the site. Templates,
 * the homepage quiz, the /start/ wizard and the Stripe checkout endpoint all
 * read from here, so a price can only ever be changed in one place.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the CPT and its group taxonomy.
 *
 * @return void
 */
function cpf_register_package_cpt() {
	register_post_type(
		'cpf_package',
		array(
			'label'         => __( 'Packages', 'crosspoint' ),
			'labels'        => array(
				'name'          => __( 'Packages', 'crosspoint' ),
				'singular_name' => __( 'Package', 'crosspoint' ),
				'add_new_item'  => __( 'Add package', 'crosspoint' ),
				'edit_item'     => __( 'Edit package', 'crosspoint' ),
				'search_items'  => __( 'Search packages', 'crosspoint' ),
			),
			'public'        => false,
			'show_ui'       => true,
			'show_in_menu'  => true,
			'show_in_rest'  => false,
			'supports'      => array( 'title', 'page-attributes' ),
			'menu_icon'     => 'dashicons-portfolio',
			'menu_position' => 58,
		)
	);

	register_taxonomy(
		'cpf_package_group',
		'cpf_package',
		array(
			'label'             => __( 'Package groups', 'crosspoint' ),
			'public'            => false,
			'show_ui'           => true,
			'show_in_rest'      => false,
			'hierarchical'      => true,
			'show_admin_column' => true,
		)
	);
}
add_action( 'init', 'cpf_register_package_cpt' );

/**
 * Group slugs the theme ships with, and their labels.
 *
 * @return array<string,string>
 */
function cpf_package_groups() {
	return array(
		'us'           => __( 'U.S. formation', 'crosspoint' ),
		'us-ecommerce' => __( 'U.S. e-commerce', 'crosspoint' ),
		'canada'       => __( 'Canada incorporation', 'crosspoint' ),
		'bundle'       => __( 'U.S. + Canada bundle', 'crosspoint' ),
		'home'         => __( 'Homepage cards', 'crosspoint' ),
		'addon'        => __( 'Add-ons', 'crosspoint' ),
		'filingguard'  => __( 'FilingGuard plans', 'crosspoint' ),
	);
}

/**
 * Create the shipped group terms if they are missing.
 *
 * @return void
 */
function cpf_ensure_package_groups() {
	foreach ( cpf_package_groups() as $slug => $label ) {
		if ( ! term_exists( $slug, 'cpf_package_group' ) ) {
			wp_insert_term( $label, 'cpf_package_group', array( 'slug' => $slug ) );
		}
	}
}
add_action( 'admin_init', 'cpf_ensure_package_groups' );

/**
 * Package meta fields and their sanitizers.
 *
 * @return array<string,array{label:string,type:string,sanitize:string,desc?:string}>
 */
function cpf_package_meta_fields() {
	return array(
		'_cpf_key'           => array(
			'label'    => __( 'Machine key', 'crosspoint' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_key',
			'desc'     => __( 'Stable id used by the wizard, quiz and checkout: starter, growth, premium, ecom, egrowth, epremium, castarter, canonres, cagrowth, bundle, or an add-on id. Do not change once live.', 'crosspoint' ),
		),
		'_cpf_price'         => array(
			'label'    => __( 'Price', 'crosspoint' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
			'desc'     => __( 'Number only, no currency sign. e.g. 299 or 1,149.', 'crosspoint' ),
		),
		'_cpf_price_from'    => array(
			'label'    => __( 'Borrow price from', 'crosspoint' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_key',
			'desc'     => __( 'Optional machine key of another package. When set, this card renders that package price, so the two can never drift apart.', 'crosspoint' ),
		),
		'_cpf_price_prefix'  => array(
			'label'    => __( 'Price prefix', 'crosspoint' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
			'desc'     => __( 'e.g. from. Leave empty for an exact price.', 'crosspoint' ),
		),
		'_cpf_compare_price' => array(
			'label'    => __( 'Compare-at price', 'crosspoint' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
			'desc'     => __( 'Optional struck-through price, e.g. 498.', 'crosspoint' ),
		),
		'_cpf_fee_note'      => array(
			'label'    => __( 'Fee note', 'crosspoint' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
			'desc'     => __( 'e.g. + state fees.', 'crosspoint' ),
		),
		'_cpf_tagline'       => array(
			'label'    => __( 'Tagline', 'crosspoint' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		),
		'_cpf_perk'          => array(
			'label'    => __( 'Highlighted perk', 'crosspoint' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
			'desc'     => __( 'Optional single line shown in the gold perk strip.', 'crosspoint' ),
		),
		'_cpf_features'      => array(
			'label'    => __( 'Features', 'crosspoint' ),
			'type'     => 'textarea',
			'sanitize' => 'sanitize_textarea_field',
			'desc'     => __( 'One feature per line.', 'crosspoint' ),
		),
		'_cpf_badge'         => array(
			'label'    => __( 'Badge', 'crosspoint' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
			'desc'     => __( 'e.g. Recommended, Most chosen.', 'crosspoint' ),
		),
		'_cpf_corner'        => array(
			'label'    => __( 'Corner ribbon', 'crosspoint' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
			'desc'     => __( 'e.g. Save $79.', 'crosspoint' ),
		),
		'_cpf_featured'      => array(
			'label'    => __( 'Featured card', 'crosspoint' ),
			'type'     => 'checkbox',
			'sanitize' => 'rest_sanitize_boolean',
		),
		'_cpf_flag'          => array(
			'label'    => __( 'Flag', 'crosspoint' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
			'desc'     => __( 'Add-ons only. Set to "on request" for items that are never charged at checkout.', 'crosspoint' ),
		),
		'_cpf_addon_keys'    => array(
			'label'    => __( 'Allowed add-ons', 'crosspoint' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
			'desc'     => __( 'Plans only. Comma-separated add-on keys this plan may charge. Enforced server side at checkout.', 'crosspoint' ),
		),
		'_cpf_cta_label'     => array(
			'label'    => __( 'Button label', 'crosspoint' ),
			'type'     => 'text',
			'sanitize' => 'sanitize_text_field',
		),
		'_cpf_cta_url'       => array(
			'label'    => __( 'Button URL', 'crosspoint' ),
			'type'     => 'url',
			'sanitize' => 'esc_url_raw',
		),
	);
}

/**
 * Register the package meta box.
 *
 * @return void
 */
function cpf_add_package_meta_box() {
	add_meta_box(
		'cpf_package_details',
		__( 'Package details', 'crosspoint' ),
		'cpf_render_package_meta_box',
		'cpf_package',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes_cpf_package', 'cpf_add_package_meta_box' );

/**
 * Render the package meta box.
 *
 * @param WP_Post $post Current package.
 * @return void
 */
function cpf_render_package_meta_box( $post ) {
	wp_nonce_field( 'cpf_save_package', 'cpf_package_nonce' );
	?>
	<table class="form-table" role="presentation">
		<tbody>
		<?php foreach ( cpf_package_meta_fields() as $key => $field ) : ?>
			<?php $value = get_post_meta( $post->ID, $key, true ); ?>
			<tr>
				<th scope="row">
					<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
				</th>
				<td>
					<?php if ( 'textarea' === $field['type'] ) : ?>
						<textarea name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" rows="8" class="large-text code"><?php echo esc_textarea( (string) $value ); ?></textarea>
					<?php elseif ( 'checkbox' === $field['type'] ) : ?>
						<label>
							<input type="checkbox" name="<?php echo esc_attr( $key ); ?>" value="1" <?php checked( (bool) $value, true ); ?>>
							<?php esc_html_e( 'Yes', 'crosspoint' ); ?>
						</label>
					<?php else : ?>
						<input type="<?php echo esc_attr( 'url' === $field['type'] ? 'url' : 'text' ); ?>"
							name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>"
							value="<?php echo esc_attr( (string) $value ); ?>" class="regular-text">
					<?php endif; ?>

					<?php if ( ! empty( $field['desc'] ) ) : ?>
						<p class="description"><?php echo esc_html( $field['desc'] ); ?></p>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php
}

/**
 * Save package meta.
 *
 * @param int $post_id Package post ID.
 * @return void
 */
function cpf_save_package_meta( $post_id ) {
	if ( ! isset( $_POST['cpf_package_nonce'] ) ||
		! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['cpf_package_nonce'] ) ), 'cpf_save_package' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( cpf_package_meta_fields() as $key => $field ) {
		if ( 'checkbox' === $field['type'] ) {
			update_post_meta( $post_id, $key, isset( $_POST[ $key ] ) ? 1 : 0 );
			continue;
		}

		if ( ! isset( $_POST[ $key ] ) ) {
			continue;
		}

		$raw = wp_unslash( $_POST[ $key ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized on the next line by the per-field callback.

		update_post_meta( $post_id, $key, call_user_func( $field['sanitize'], $raw ) );
	}

	cpf_flush_package_cache();
}
add_action( 'save_post_cpf_package', 'cpf_save_package_meta' );

/**
 * Add price and key columns to the packages list table.
 *
 * @param array $columns Existing columns.
 * @return array
 */
function cpf_package_columns( $columns ) {
	$new = array();

	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;

		if ( 'title' === $key ) {
			$new['cpf_key']   = __( 'Key', 'crosspoint' );
			$new['cpf_price'] = __( 'Price', 'crosspoint' );
		}
	}

	return $new;
}
add_filter( 'manage_cpf_package_posts_columns', 'cpf_package_columns' );

/**
 * Render the custom package columns.
 *
 * @param string $column  Column key.
 * @param int    $post_id Package post ID.
 * @return void
 */
function cpf_package_column_content( $column, $post_id ) {
	if ( 'cpf_key' === $column ) {
		echo esc_html( (string) get_post_meta( $post_id, '_cpf_key', true ) );
	}

	if ( 'cpf_price' === $column ) {
		$label  = cpf_package_price_label( $post_id );
		$borrow = (string) get_post_meta( $post_id, '_cpf_price_from', true );

		echo esc_html( $label );

		if ( '' !== $borrow ) {
			printf( ' <em>%s</em>', esc_html( sprintf( /* translators: %s: package machine key. */ __( '(from %s)', 'crosspoint' ), $borrow ) ) );
		}
	}
}
add_action( 'manage_cpf_package_posts_custom_column', 'cpf_package_column_content', 10, 2 );

/**
 * Order the packages list table by menu order, matching front-end output.
 *
 * @param WP_Query $query Current admin query.
 * @return void
 */
function cpf_package_admin_order( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( 'cpf_package' === $query->get( 'post_type' ) && ! $query->get( 'orderby' ) ) {
		$query->set( 'orderby', 'menu_order' );
		$query->set( 'order', 'ASC' );
	}
}
add_action( 'pre_get_posts', 'cpf_package_admin_order' );
