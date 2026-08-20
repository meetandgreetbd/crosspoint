<?php
/**
 * Fallback template.
 *
 * WordPress falls back here only when no more specific template matched. Every
 * page of the live site has its own template; this keeps the hierarchy honest.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="cpf-main-content" class="cpf-main">
	<div class="wrap">
		<?php
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				?>
				<article <?php post_class( 'cpf-entry' ); ?>>
					<h1 class="cpf-entry__title"><?php the_title(); ?></h1>
					<div class="cpf-entry__content">
						<?php the_content(); ?>
					</div>
				</article>
				<?php
			}

			the_posts_pagination(
				array(
					'mid_size'  => 1,
					'prev_text' => esc_html__( 'Previous', 'crosspoint' ),
					'next_text' => esc_html__( 'Next', 'crosspoint' ),
				)
			);
		} else {
			?>
			<p class="cpf-entry__empty"><?php esc_html_e( 'Nothing found.', 'crosspoint' ); ?></p>
			<?php
		}
		?>
	</div>
</main>

<?php
get_footer();
