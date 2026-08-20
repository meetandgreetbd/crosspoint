<?php
/**
 * The shared site footer.
 *
 * One footer on every page. The brand blurb, address card, support line and the
 * legal disclaimer all come from CrossPoint Settings, so the four different
 * disclaimers the static site had cannot come back.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

$cpf_wa_url     = cpf_whatsapp_url();
$cpf_mail_url   = cpf_mailto_url();
$cpf_email      = (string) cpf_get_setting( 'contact_email' );
$cpf_contact_ur = cpf_page_url( 'contact-us' );
?>

<footer>
	<div class="wrap">
		<div class="foot-grid">
			<div>
				<h4><?php esc_html_e( 'Navigate', 'crosspoint' ); ?></h4>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer_nav',
						'container'      => false,
						'menu_class'     => 'foot-links',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
				?>
			</div>

			<div class="foot-brandcol">
				<div class="foot-brand">
					<span aria-hidden="true" class="brand-icon"><svg class="brand-icon-svg"><use href="#cp-brand-icon"></use></svg></span>
					<span class="brand-name"><?php bloginfo( 'name' ); ?></span>
				</div>

				<div aria-hidden="true" class="foot-div"><span></span></div>

				<p class="foot-about"><?php echo wp_kses_post( cpf_get_setting( 'brand_blurb' ) ); ?></p>

				<p class="foot-chip"><?php echo wp_kses_post( cpf_get_setting( 'address_card' ) ); ?></p>

				<p class="foot-support"><?php echo esc_html( cpf_get_setting( 'support_line' ) ); ?></p>
			</div>

			<div>
				<h4><?php esc_html_e( 'Get in touch', 'crosspoint' ); ?></h4>
				<ul class="foot-contact-list">
					<?php if ( '' !== $cpf_wa_url ) : ?>
						<li>
							<a href="<?php echo esc_url( $cpf_wa_url ); ?>" target="_blank" rel="noopener">
								<i class="fa-brands fa-whatsapp" aria-hidden="true"></i> <?php esc_html_e( 'WhatsApp advisor', 'crosspoint' ); ?>
							</a>
						</li>
					<?php endif; ?>

					<?php if ( '' !== $cpf_mail_url ) : ?>
						<li>
							<a href="<?php echo esc_url( $cpf_mail_url ); ?>">
								<i class="fa-solid fa-envelope" aria-hidden="true"></i> <?php echo esc_html( $cpf_email ); ?>
							</a>
						</li>
					<?php endif; ?>

					<?php if ( cpf_chat_enabled() ) : ?>
						<li>
							<a href="#cpChat" class="cpf-open-chat">
								<i class="fa-solid fa-comments" aria-hidden="true"></i> <?php esc_html_e( 'Live chat', 'crosspoint' ); ?>
							</a>
						</li>
					<?php endif; ?>

					<li>
						<a href="<?php echo esc_url( $cpf_contact_ur ); ?>">
							<i class="fa-solid fa-file-lines" aria-hidden="true"></i> <?php esc_html_e( 'Contact form', 'crosspoint' ); ?>
						</a>
					</li>
				</ul>
			</div>
		</div>

		<div class="foot-legal">
			<div class="foot-legal-links">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer_legal',
						'container'      => false,
						'items_wrap'     => '%3$s',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
				?>
			</div>
			<div class="foot-copy">
				<?php
				echo '&copy; ';
				printf(
					/* translators: 1: current year, 2: site name. */
					esc_html__( '%1$s %2$s. All rights reserved.', 'crosspoint' ),
					esc_html( gmdate( 'Y' ) ),
					esc_html( get_bloginfo( 'name' ) )
				);
				?>
			</div>
		</div>

		<?php do_action( 'cpf_footer_before_disclaimer' ); ?>

		<p class="disc"><?php echo wp_kses_post( cpf_get_setting( 'footer_disclaimer' ) ); ?></p>
	</div>
</footer>

<?php
if ( cpf_chat_enabled() ) {
	get_template_part( 'template-parts/chat-widget' );
}
?>

<?php if ( '' !== $cpf_wa_url ) : ?>
	<a aria-label="<?php esc_attr_e( 'Chat with us on WhatsApp', 'crosspoint' ); ?>" class="wa-float" href="<?php echo esc_url( $cpf_wa_url ); ?>" target="_blank" rel="noopener">
		<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12.04 2c-5.46 0-9.9 4.44-9.9 9.9 0 1.75.46 3.45 1.32 4.95L2 22l5.3-1.39a9.87 9.87 0 0 0 4.74 1.21h.01c5.46 0 9.9-4.44 9.9-9.9 0-2.65-1.03-5.13-2.9-7-1.87-1.87-4.36-2.9-7.01-2.92zm0 18.13h-.01a8.2 8.2 0 0 1-4.18-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.2 8.2 0 0 1-1.26-4.38c0-4.54 3.7-8.23 8.24-8.23 2.2 0 4.27.86 5.82 2.42a8.18 8.18 0 0 1 2.41 5.83c0 4.54-3.7 8.22-8.23 8.22zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.13-.17.25-.64.8-.78.97-.14.17-.29.19-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.17-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.43h-.48c-.17 0-.43.06-.66.31-.22.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.17 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.15-1.18-.06-.1-.23-.16-.48-.29z"></path></svg>
	</a>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
