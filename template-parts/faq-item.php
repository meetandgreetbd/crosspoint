<?php
/**
 * One FAQ item.
 *
 * Expects $args['faq'] to be a WP_Post of type cpf_faq.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

$cpf_faq = isset( $args['faq'] ) ? $args['faq'] : null;

if ( ! $cpf_faq instanceof WP_Post ) {
	return;
}
?>

<details>
	<summary><?php echo esc_html( get_the_title( $cpf_faq ) ); ?></summary>
	<?php echo wp_kses_post( wpautop( $cpf_faq->post_content ) ); ?>
</details>
