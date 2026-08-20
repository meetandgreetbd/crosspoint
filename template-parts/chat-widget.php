<?php
/**
 * CrossPoint chat widget.
 *
 * Rendered from footer.php when the widget is enabled in CrossPoint Settings.
 * Behaviour lives in assets/js/chat.js.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

$cpf_wa_url   = cpf_whatsapp_url( __( 'Hi CrossPoint, I would like to speak with an advisor.', 'crosspoint' ) );
$cpf_calendly = (string) cpf_get_setting( 'calendly_url' );
?>

<div aria-hidden="true" aria-labelledby="cpChatTitle" class="cp-chat" id="cpChat" role="dialog" aria-modal="true">
	<div class="cp-chat-panel">
		<div class="cp-chat-header">
			<div>
				<strong id="cpChatTitle"><?php esc_html_e( 'CrossPoint Chat', 'crosspoint' ); ?></strong>
				<span><?php esc_html_e( 'Get instant answers, then connect with an advisor', 'crosspoint' ); ?></span>
			</div>
			<button aria-label="<?php esc_attr_e( 'Close chat', 'crosspoint' ); ?>" class="cp-chat-close" type="button">&times;</button>
		</div>

		<div aria-live="polite" aria-relevant="additions" class="cp-chat-messages" id="cpChatMessages">
			<div class="cp-chat-msg bot">
				<?php esc_html_e( 'Hi, I am the CrossPoint Setup Assistant. I can help with Canada and U.S. company setup questions. For personal guidance, I can connect you with a CrossPoint advisor.', 'crosspoint' ); ?>
			</div>
		</div>

		<div class="cp-chat-advisor">
			<?php if ( '' !== $cpf_wa_url ) : ?>
				<a href="<?php echo esc_url( $cpf_wa_url ); ?>" rel="noopener" target="_blank"><?php esc_html_e( 'WhatsApp Advisor', 'crosspoint' ); ?></a>
			<?php endif; ?>

			<?php if ( '' !== $cpf_calendly ) : ?>
				<a href="<?php echo esc_url( $cpf_calendly ); ?>" rel="noopener" target="_blank"><?php esc_html_e( 'Book a Free 15-Min Call', 'crosspoint' ); ?></a>
			<?php endif; ?>
		</div>

		<form class="cp-chat-form" id="cpChatForm">
			<input aria-label="<?php esc_attr_e( 'Ask a Canada or U.S. setup question', 'crosspoint' ); ?>" autocomplete="off" id="cpChatInput" placeholder="<?php esc_attr_e( 'Ask a Canada or U.S. setup question…', 'crosspoint' ); ?>" type="text">
			<button type="submit"><?php esc_html_e( 'Send', 'crosspoint' ); ?></button>
		</form>
	</div>
</div>
