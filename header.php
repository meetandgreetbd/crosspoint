<?php
/**
 * The shared site header.
 *
 * One header on every page of the site: home, pricing, guides, legal pages and
 * the /start/ wizard. On the static site each page carried its own copy, which
 * is why the navigation and the logo drifted apart. There is now one copy.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

$cpf_promo    = (string) cpf_get_setting( 'promo_bar_text' );
$cpf_wa_url   = cpf_whatsapp_url();
$cpf_login    = (string) cpf_get_setting( 'stripe_login_url' );
$cpf_start_ur = cpf_page_url( 'start' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#0E1B2E">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> id="top">
<?php wp_body_open(); ?>

<a class="screen-reader-text cpf-skip-link" href="#cpf-main-content"><?php esc_html_e( 'Skip to content', 'crosspoint' ); ?></a>

<svg aria-hidden="true" focusable="false" height="0" style="position:absolute" width="0">
	<symbol id="cp-brand-icon" viewBox="0 0 20 20">
		<circle cx="5.5" cy="14.5" fill="#FFFFFF" r="2"></circle>
		<circle cx="14.5" cy="5.5" fill="#FFFFFF" r="2"></circle>
		<path d="M5.5 14.5 C5.5 10.5, 9.5 9, 10 7 C10.5 5.2, 12.5 5.5, 14.5 5.5" fill="none" stroke="#FFFFFF" stroke-linecap="round" stroke-width="1.6"></path>
		<circle cx="10" cy="7" fill="#D6A11F" r="1.85"></circle>
	</symbol>
</svg>

<?php if ( '' !== $cpf_promo ) : ?>
	<div class="cpf-promo-bar"><?php echo esc_html( $cpf_promo ); ?></div>
<?php endif; ?>

<header class="nav">
	<div class="wrap nav-in">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<span aria-hidden="true" class="brand-icon"><svg class="brand-icon-svg"><use href="#cp-brand-icon"></use></svg></span>
			<span class="brand-name"><?php bloginfo( 'name' ); ?></span>
		</a>

		<nav aria-label="<?php esc_attr_e( 'Main', 'crosspoint' ); ?>" class="nav-links">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'items_wrap'     => '%3$s',
					'depth'          => 3,
					'walker'         => new CPF_Mega_Menu_Walker(),
					'fallback_cb'    => false,
				)
			);
			?>
		</nav>

		<div class="nav-right">
			<?php do_action( 'cpf_header_before_cta' ); ?>

			<?php if ( '' !== $cpf_login ) : ?>
				<a class="nav-login" href="<?php echo esc_url( $cpf_login ); ?>" target="_blank" rel="noopener">
					<?php esc_html_e( 'Login', 'crosspoint' ); ?>
				</a>
			<?php endif; ?>

			<a class="nav-cta" href="<?php echo esc_url( $cpf_start_ur ); ?>">
				<?php esc_html_e( 'Start Your Business', 'crosspoint' ); ?>
			</a>

			<?php if ( '' !== $cpf_wa_url ) : ?>
				<a class="nav-wa" href="<?php echo esc_url( $cpf_wa_url ); ?>" target="_blank" rel="noopener">
					<i class="fa-brands fa-whatsapp" aria-hidden="true"></i> <?php esc_html_e( 'WhatsApp Advisor', 'crosspoint' ); ?>
				</a>
			<?php endif; ?>
		</div>

		<button aria-controls="mobileMenu" aria-expanded="false" aria-label="<?php esc_attr_e( 'Open menu', 'crosspoint' ); ?>" class="burger" id="burger" type="button">
			<span></span><span></span><span></span>
		</button>
	</div>

	<nav aria-label="<?php esc_attr_e( 'Mobile', 'crosspoint' ); ?>" class="mobile-menu" id="mobileMenu">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'items_wrap'     => '%3$s',
				'depth'          => 3,
				'walker'         => new CPF_Mobile_Menu_Walker(),
				'fallback_cb'    => false,
			)
		);
		?>

		<a class="mm-cta" href="<?php echo esc_url( $cpf_start_ur ); ?>"><?php esc_html_e( 'Start Your Business', 'crosspoint' ); ?></a>

		<?php if ( '' !== $cpf_login ) : ?>
			<a href="<?php echo esc_url( $cpf_login ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Login', 'crosspoint' ); ?></a>
		<?php endif; ?>

		<?php if ( '' !== $cpf_wa_url ) : ?>
			<a class="nav-wa" href="<?php echo esc_url( $cpf_wa_url ); ?>" target="_blank" rel="noopener">
				<i class="fa-brands fa-whatsapp" aria-hidden="true"></i> <?php esc_html_e( 'WhatsApp Advisor', 'crosspoint' ); ?>
			</a>
		<?php endif; ?>
	</nav>
</header>
