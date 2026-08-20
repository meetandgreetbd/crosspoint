<?php
/**
 * One package card.
 *
 * Renders a single Packages CPT entry. Every price on the site goes through
 * this partial or through cpf_package_price_label(), so /pricing/, the homepage,
 * the Canada page and the U.S. page can never disagree again.
 *
 * Expects $args['package'] to be a WP_Post of type cpf_package.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

$cpf_package = isset( $args['package'] ) ? $args['package'] : null;

if ( ! $cpf_package instanceof WP_Post ) {
	return;
}

$cpf_id       = $cpf_package->ID;
$cpf_price    = cpf_package_price( $cpf_id );
$cpf_badge    = (string) get_post_meta( $cpf_id, '_cpf_badge', true );
$cpf_corner   = (string) get_post_meta( $cpf_id, '_cpf_corner', true );
$cpf_tagline  = (string) get_post_meta( $cpf_id, '_cpf_tagline', true );
$cpf_perk     = (string) get_post_meta( $cpf_id, '_cpf_perk', true );
$cpf_features = cpf_package_features( $cpf_id );
$cpf_cta      = (string) get_post_meta( $cpf_id, '_cpf_cta_label', true );
$cpf_cta_url  = (string) get_post_meta( $cpf_id, '_cpf_cta_url', true );
$cpf_featured = (bool) get_post_meta( $cpf_id, '_cpf_featured', true );

if ( '' === $cpf_cta_url ) {
	$cpf_cta_url = cpf_whatsapp_url(
		sprintf(
			/* translators: %s: package name. */
			__( 'Hi CrossPoint, I am interested in the %s package.', 'crosspoint' ),
			get_the_title( $cpf_package )
		)
	);
}
?>

<div class="plan<?php echo $cpf_featured ? ' featured' : ''; ?>">
	<?php if ( '' !== $cpf_badge ) : ?>
		<span class="tag"><?php echo esc_html( $cpf_badge ); ?></span>
	<?php endif; ?>

	<?php if ( '' !== $cpf_corner ) : ?>
		<span class="save-corner"><?php echo esc_html( $cpf_corner ); ?></span>
	<?php endif; ?>

	<h3><?php echo esc_html( get_the_title( $cpf_package ) ); ?></h3>

	<?php if ( '' !== $cpf_price['price'] ) : ?>
		<div class="amt">
			<?php if ( '' !== $cpf_price['prefix'] ) : ?>
				<span class="from"><?php echo esc_html( $cpf_price['prefix'] ); ?></span>
			<?php endif; ?>

			<?php if ( '' !== $cpf_price['compare'] ) : ?>
				<s>$<?php echo esc_html( $cpf_price['compare'] ); ?></s>
			<?php endif; ?>

			$<?php echo esc_html( $cpf_price['price'] ); ?>

			<?php if ( '' !== $cpf_price['fee_note'] ) : ?>
				<small><?php echo esc_html( $cpf_price['fee_note'] ); ?></small>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( '' !== $cpf_tagline ) : ?>
		<div class="sub"><?php echo esc_html( $cpf_tagline ); ?></div>
	<?php endif; ?>

	<?php if ( '' !== $cpf_perk ) : ?>
		<div class="plan-perk"><span class="pp-ck">&#10003;</span> <strong><?php echo esc_html( $cpf_perk ); ?></strong></div>
	<?php endif; ?>

	<?php if ( ! empty( $cpf_features ) ) : ?>
		<ul>
			<?php foreach ( $cpf_features as $cpf_feature ) : ?>
				<li><?php echo esc_html( $cpf_feature ); ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<?php if ( '' !== $cpf_cta ) : ?>
		<a class="btn btn-gold" href="<?php echo esc_url( $cpf_cta_url ); ?>"><?php echo esc_html( $cpf_cta ); ?></a>
	<?php endif; ?>
</div>
