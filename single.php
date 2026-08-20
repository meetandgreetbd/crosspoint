<?php
/**
 * A single guide article.
 *
 * Guides are native WordPress posts; /guides/ is the posts page.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="cpf-main-content">
	<div class="wrap">
		<?php
		while ( have_posts() ) {
			the_post();
			?>
			<article <?php post_class( 'cpf-entry' ); ?>>
				<h1 class="cpf-entry__title"><?php the_title(); ?></h1>

				<p class="cpf-entry__meta">
					<?php
					printf(
						/* translators: 1: publish date, 2: author name. */
						esc_html__( 'Updated %1$s · by %2$s', 'crosspoint' ),
						esc_html( get_the_modified_date() ),
						esc_html( get_the_author() )
					);
					?>
				</p>

				<?php if ( has_post_thumbnail() ) : ?>
					<?php the_post_thumbnail( 'cpf-guide-hero' ); ?>
				<?php endif; ?>

				<div class="cpf-entry__content">
					<?php the_content(); ?>
				</div>
			</article>

			<?php
			$cpf_related = new WP_Query(
				array(
					'posts_per_page'      => 3,
					'post__not_in'        => array( get_the_ID() ),
					'ignore_sticky_posts' => true,
					'no_found_rows'       => true,
				)
			);

			if ( $cpf_related->have_posts() ) :
				?>
				<section class="cpf-guides">
					<h2><?php esc_html_e( 'More guides', 'crosspoint' ); ?></h2>
					<div class="cpf-guides__grid">
						<?php
						while ( $cpf_related->have_posts() ) {
							$cpf_related->the_post();
							get_template_part( 'template-parts/guide-card' );
						}
						?>
					</div>
				</section>
				<?php
			endif;

			wp_reset_postdata();
		}
		?>
	</div>
</main>

<?php
get_footer();
