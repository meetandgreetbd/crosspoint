<?php
/**
 * FAQ custom post type.
 *
 * The homepage FAQ list and the FAQPage schema both read from here, so an
 * answer is edited once and stays consistent with what search engines see.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the FAQ post type.
 *
 * @return void
 */
function cpf_register_faq_cpt() {
	register_post_type(
		'cpf_faq',
		array(
			'label'        => __( 'FAQs', 'crosspoint' ),
			'labels'       => array(
				'name'          => __( 'FAQs', 'crosspoint' ),
				'singular_name' => __( 'FAQ', 'crosspoint' ),
				'add_new_item'  => __( 'Add FAQ', 'crosspoint' ),
				'edit_item'     => __( 'Edit FAQ', 'crosspoint' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'show_in_menu' => 'cpf-settings',
			'show_in_rest' => false,
			'supports'     => array( 'title', 'editor', 'page-attributes' ),
			'menu_icon'    => 'dashicons-editor-help',
		)
	);
}
add_action( 'init', 'cpf_register_faq_cpt' );

/**
 * Published FAQs in menu order.
 *
 * @param int $limit Maximum number of items.
 * @return WP_Post[]
 */
function cpf_get_faqs( $limit = -1 ) {
	$cached = get_transient( 'cpf_faqs' );

	if ( is_array( $cached ) ) {
		$posts = array_values( array_filter( array_map( 'get_post', $cached ) ) );

		return $limit > 0 ? array_slice( $posts, 0, $limit ) : $posts;
	}

	$query = new WP_Query(
		array(
			'post_type'      => 'cpf_faq',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'no_found_rows'  => true,
		)
	);

	set_transient( 'cpf_faqs', wp_list_pluck( $query->posts, 'ID' ), DAY_IN_SECONDS );

	return $limit > 0 ? array_slice( $query->posts, 0, $limit ) : $query->posts;
}

/**
 * Flush the cached FAQ list.
 *
 * @return void
 */
function cpf_flush_faq_cache() {
	delete_transient( 'cpf_faqs' );
}
add_action( 'save_post_cpf_faq', 'cpf_flush_faq_cache' );
add_action( 'deleted_post', 'cpf_flush_faq_cache' );

/**
 * The FAQ list as published on the live homepage.
 *
 * @return array<int,array{q:string,a:string}>
 */
function cpf_seed_faq_data() {
	return array(
		array(
			'q' => 'Can I open a company if I do not live in Canada or the U.S.?',
			'a' => 'Yes, many Canada and U.S. company setup paths are available to non-residents. Eligibility depends on your country of residence, business activity and documentation.',
		),
		array(
			'q' => 'Which is better for me: Canada or the U.S.?',
			'a' => 'It depends on your business goal, customers, banking needs, payment platforms, and tax and compliance situation. We walk you through the comparison before you pay anything.',
		),
		array(
			'q' => 'Do you guarantee a bank account?',
			'a' => 'No. CrossPoint is not a bank and does not guarantee approval. We provide banking documentation guidance and help prepare your application, but every institution makes its own decision.',
		),
		array(
			'q' => 'What documents will I need?',
			'a' => 'Usually government ID such as a passport, contact details, business activity details, and formation details such as the company name and structure.',
		),
		array(
			'q' => 'How long does the process usually take?',
			'a' => 'Formation timing depends on the country, state, province and government processing. Banking and payment platform steps come after formation and take longer.',
		),
		array(
			'q' => 'Do I need a resident director for a Canadian corporation?',
			'a' => 'It depends on the jurisdiction. Some Canadian jurisdictions allow fully non-resident directors; others require resident directors. We confirm the rule for your chosen jurisdiction before filing.',
		),
		array(
			'q' => 'Can I use my company for Amazon, Shopify, or Stripe?',
			'a' => 'Many non-residents form a company specifically for these platforms. Each platform sets its own requirements and makes its own approval decision.',
		),
		array(
			'q' => 'Are government fees included?',
			'a' => 'Government filing fees are charged separately at cost and confirmed before payment. This applies to Canadian government filing fees, U.S. state fees and other third-party costs.',
		),
	);
}

/**
 * Create the shipped FAQs once, if none exist.
 *
 * @return int Number created.
 */
function cpf_seed_faqs() {
	if ( ! empty( cpf_get_faqs() ) ) {
		return 0;
	}

	$created = 0;
	$order   = 10;

	foreach ( cpf_seed_faq_data() as $row ) {
		$post_id = wp_insert_post(
			array(
				'post_type'    => 'cpf_faq',
				'post_status'  => 'publish',
				'post_title'   => $row['q'],
				'post_content' => $row['a'],
				'menu_order'   => $order,
			),
			true
		);

		if ( ! is_wp_error( $post_id ) ) {
			++$created;
		}

		$order += 10;
	}

	cpf_flush_faq_cache();

	return $created;
}
add_action( 'after_switch_theme', 'cpf_seed_faqs' );
