<?php
/**
 * SEO layer: schema output and robots rules.
 *
 * Titles, descriptions and OG tags stay with Rank Math, per page, exactly as
 * they are on the live site. This file only adds what a plugin cannot know:
 * the Organization graph, the FAQPage built from the FAQ CPT, the Service
 * graph built from the Packages CPT, and the noindex rule for /start/.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

/**
 * Print the JSON-LD graph in the document head.
 *
 * @return void
 */
function cpf_print_schema() {
	$graph = array( cpf_schema_organization(), cpf_schema_website() );

	if ( is_front_page() ) {
		$faq = cpf_schema_faq();

		if ( $faq ) {
			$graph[] = $faq;
		}

		$service = cpf_schema_service();

		if ( $service ) {
			$graph[] = $service;
		}
	}

	if ( is_page_template( array( 'page-templates/template-pricing.php', 'page-templates/template-us-formation.php', 'page-templates/template-canada.php' ) ) ) {
		$service = cpf_schema_service();

		if ( $service ) {
			$graph[] = $service;
		}
	}

	if ( is_singular( 'post' ) ) {
		$graph[] = cpf_schema_article();
	}

	$graph = apply_filters( 'cpf_schema_graph', $graph );

	if ( empty( $graph ) ) {
		return;
	}

	$json = wp_json_encode(
		array(
			'@context' => 'https://schema.org',
			'@graph'   => array_values( $graph ),
		),
		JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
	);

	if ( false === $json ) {
		return;
	}

	echo '<script type="application/ld+json">' . $json . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_json_encode output, printed inside a JSON-LD script tag.
}
add_action( 'wp_head', 'cpf_print_schema', 20 );

/**
 * Organization node.
 *
 * @return array
 */
function cpf_schema_organization() {
	$node = array(
		'@type' => 'Organization',
		'@id'   => home_url( '/#org' ),
		'name'  => get_bloginfo( 'name' ),
		'url'   => home_url( '/' ),
		'email' => sanitize_email( (string) cpf_get_setting( 'contact_email' ) ),
	);

	$logo = get_theme_file_uri( 'assets/img/logo.png' );

	if ( file_exists( get_theme_file_path( 'assets/img/logo.png' ) ) ) {
		$node['logo'] = $logo;
	}

	$number = preg_replace( '/[^0-9+]/', '', (string) cpf_get_setting( 'whatsapp_number' ) );

	if ( '' !== $number ) {
		$node['telephone'] = '+' . ltrim( $number, '+' );
	}

	$node['address'] = array(
		'@type'           => 'PostalAddress',
		'addressLocality' => 'Toronto',
		'addressRegion'   => 'ON',
		'addressCountry'  => 'CA',
	);

	return $node;
}

/**
 * WebSite node.
 *
 * @return array
 */
function cpf_schema_website() {
	return array(
		'@type'      => 'WebSite',
		'@id'        => home_url( '/#website' ),
		'url'        => home_url( '/' ),
		'name'       => get_bloginfo( 'name' ),
		'publisher'  => array( '@id' => home_url( '/#org' ) ),
		'inLanguage' => get_bloginfo( 'language' ),
	);
}

/**
 * FAQPage node, built from the FAQ CPT.
 *
 * @return array|null
 */
function cpf_schema_faq() {
	$faqs = cpf_get_faqs();

	if ( empty( $faqs ) ) {
		return null;
	}

	$entities = array();

	foreach ( $faqs as $faq ) {
		$entities[] = array(
			'@type'          => 'Question',
			'name'           => wp_strip_all_tags( get_the_title( $faq ) ),
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => wp_strip_all_tags( $faq->post_content ),
			),
		);
	}

	return array(
		'@type'      => 'FAQPage',
		'mainEntity' => $entities,
	);
}

/**
 * Service node with an AggregateOffer built from the Packages CPT.
 *
 * The low and high price come from the same records the pages render, so the
 * structured data can never advertise a price the site does not charge.
 *
 * @return array|null
 */
function cpf_schema_service() {
	$amounts = array();

	foreach ( array( 'us', 'us-ecommerce', 'canada', 'bundle' ) as $group ) {
		foreach ( cpf_get_packages( $group ) as $package ) {
			$groups = wp_get_object_terms( $package->ID, 'cpf_package_group', array( 'fields' => 'slugs' ) );

			// Add-ons are not products in their own right; they must not drag the
			// advertised price range down.
			if ( is_array( $groups ) && in_array( 'addon', $groups, true ) ) {
				continue;
			}

			$price = cpf_package_price( $package->ID );

			if ( $price['amount'] > 0 ) {
				$amounts[] = $price['amount'];
			}
		}
	}

	if ( empty( $amounts ) ) {
		return null;
	}

	return array(
		'@type'       => 'Service',
		'name'        => __( 'Non-resident company formation (U.S. & Canada)', 'crosspoint' ),
		'serviceType' => __( 'Business formation, registered agent, and banking documentation guidance for non-residents', 'crosspoint' ),
		'provider'    => array( '@id' => home_url( '/#org' ) ),
		'areaServed'  => array(
			array(
				'@type' => 'Country',
				'name'  => 'United States',
			),
			array(
				'@type' => 'Country',
				'name'  => 'Canada',
			),
		),
		'offers'      => array(
			'@type'         => 'AggregateOffer',
			'lowPrice'      => (string) min( $amounts ),
			'highPrice'     => (string) max( $amounts ),
			'priceCurrency' => 'USD',
			'offerCount'    => (string) count( $amounts ),
		),
	);
}

/**
 * Article node for a guide.
 *
 * @return array
 */
function cpf_schema_article() {
	return array(
		'@type'            => 'Article',
		'headline'         => wp_strip_all_tags( get_the_title() ),
		'datePublished'    => get_the_date( 'c' ),
		'dateModified'     => get_the_modified_date( 'c' ),
		'author'           => array(
			'@type' => 'Person',
			'name'  => get_the_author(),
		),
		'publisher'        => array( '@id' => home_url( '/#org' ) ),
		'mainEntityOfPage' => get_permalink(),
	);
}

// Keep the wizard out of the index, exactly as the live page is.
add_filter( 'rank_math/frontend/robots', 'cpf_robots_noindex_array' );
add_filter( 'wp_robots', 'cpf_robots_wp' );

/**
 * Rank Math robots array for the wizard.
 *
 * @param array $robots Rank Math robots settings.
 * @return array
 */
function cpf_robots_noindex_array( $robots ) {
	if ( is_page_template( 'page-templates/template-start.php' ) ) {
		$robots['index']  = 'noindex';
		$robots['follow'] = 'follow';
	}

	return $robots;
}

/**
 * Core robots directives for the wizard, for installs without Rank Math.
 *
 * @param array $robots Robots directives.
 * @return array
 */
function cpf_robots_wp( $robots ) {
	if ( is_page_template( 'page-templates/template-start.php' ) ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}

	return $robots;
}

/**
 * Canonical hreflang pair carried over from the live pages.
 *
 * @return void
 */
function cpf_print_hreflang() {
	if ( ! is_front_page() ) {
		return;
	}

	$alternates = apply_filters(
		'cpf_hreflang_alternates',
		array(
			'en'        => home_url( '/' ),
			'x-default' => home_url( '/' ),
		)
	);

	foreach ( $alternates as $lang => $url ) {
		printf(
			'<link rel="alternate" hreflang="%1$s" href="%2$s">' . "\n",
			esc_attr( $lang ),
			esc_url( $url )
		);
	}
}
add_action( 'wp_head', 'cpf_print_hreflang', 5 );
