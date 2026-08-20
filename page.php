<?php
/**
 * Generic page template.
 *
 * Used by the legal pages (/privacy-policy/, /terms-of-services/,
 * /refund-policy/) and any page without a template of its own. The shared
 * header and footer apply here too, which is what the static legal pages were
 * missing.
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

				<div class="cpf-entry__content">
					<?php the_content(); ?>
				</div>
			</article>
			<?php
		}
		?>
	</div>
</main>

<?php
get_footer();
