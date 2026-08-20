<?php
/**
 * One guide card for the /guides/ archive and the related-guides strip.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;
?>

<article <?php post_class( 'cpf-guide-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php the_post_thumbnail( 'cpf-guide-card' ); ?>
		</a>
	<?php endif; ?>

	<div class="cpf-guide-card__body">
		<h3 class="cpf-guide-card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<p class="cpf-guide-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>

		<a class="cpf-guide-card__more" href="<?php the_permalink(); ?>">
			<?php esc_html_e( 'Read the guide', 'crosspoint' ); ?> &rarr;
		</a>
	</div>
</article>
