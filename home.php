<?php
/**
 * The posts page (/guides/).
 *
 * Set Settings -> Reading -> Posts page to the Guides page so this renders at
 * the live /guides/ URL. archive.php handles category and tag archives.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="cpf-main-content">
	<header class="hero hero-gradient">
		<div class="wrap">
			<span class="kicker"><?php esc_html_e( 'Guides', 'crosspoint' ); ?></span>
			<h1>
				<?php
				if ( is_home() ) {
					echo esc_html( get_the_title( (int) get_option( 'page_for_posts' ) ) );
				} else {
					the_archive_title();
				}
				?>
			</h1>
			<?php the_archive_description( '<p class="hero-sub">', '</p>' ); ?>
		</div>
	</header>

	<section class="cpf-guides">
		<div class="wrap">
			<?php if ( have_posts() ) : ?>
				<div class="cpf-guides__grid">
					<?php
					while ( have_posts() ) {
						the_post();
						get_template_part( 'template-parts/guide-card' );
					}
					?>
				</div>

				<?php
				the_posts_pagination(
					array(
						'class'     => 'cpf-pagination',
						'mid_size'  => 1,
						'prev_text' => esc_html__( 'Previous', 'crosspoint' ),
						'next_text' => esc_html__( 'Next', 'crosspoint' ),
					)
				);
				?>
			<?php else : ?>
				<p><?php esc_html_e( 'No guides published yet.', 'crosspoint' ); ?></p>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php
get_footer();
