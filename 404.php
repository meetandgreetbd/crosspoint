<?php
/**
 * 404 page.
 *
 * Renders with the shared header and footer, so a mistyped URL still gives the
 * visitor the full navigation.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="cpf-main-content">
	<section class="cpf-404">
		<div class="wrap">
			<span class="kicker"><?php esc_html_e( 'Page not found', 'crosspoint' ); ?></span>
			<h1><?php esc_html_e( 'That page has moved on.', 'crosspoint' ); ?></h1>
			<p><?php esc_html_e( 'The link may be out of date. Start from the homepage, compare packages, or message an advisor and we will point you to the right place.', 'crosspoint' ); ?></p>

			<div class="hero-ctas">
				<a class="btn btn-gold" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go to the homepage', 'crosspoint' ); ?></a>
				<a class="btn btn-outline" href="<?php echo esc_url( cpf_page_url( 'pricing' ) ); ?>"><?php esc_html_e( 'See pricing', 'crosspoint' ); ?></a>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
