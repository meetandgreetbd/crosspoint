<?php
/**
 * Template Name: Start Wizard
 *
 * The /start/ guided setup. Markup, start.css and start.js are ported from the
 * live wizard; the slim standalone header it used to carry is replaced by the
 * shared site header, per the build decision. Swapping back to a slim header
 * later is a one-line change here.
 *
 * The page is noindex, follow - matching the live page - see inc/seo.php.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="cpf-main-content">
	<h1 class="screen-reader-text"><?php esc_html_e( 'Start Your U.S. Company Setup', 'crosspoint' ); ?></h1>

	<section class="setup">
		<div class="top-progress">
			<div class="progress-steps" id="progressSteps"></div>
			<div class="progress-bar-container">
				<div class="progress-bar-fill" id="top-progress-bar"></div>
			</div>
		</div>

		<div class="wizard">
			<span class="w-step" id="wstep"><?php esc_html_e( 'Step 1 of 4', 'crosspoint' ); ?></span>

			<div class="w-head">
				<h2 class="w-q" id="wq"><?php esc_html_e( 'What business are you starting?', 'crosspoint' ); ?></h2>
				<div class="w-head-r">
					<button class="btn btn-gold wz-next" id="wnext" type="button">
						<?php esc_html_e( 'Continue', 'crosspoint' ); ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
					</button>
				</div>
			</div>

			<p class="w-sub" id="wsub"></p>

			<div class="w-progress"><div class="w-bar" id="wbar"></div></div>

			<div id="wSummary" class="w-summary"></div>
			<div id="wcontent"></div>

			<div class="wz-foot" id="wfoot">
				<button class="wz-back" id="wback" type="button" hidden>
					<i class="fa-solid fa-arrow-left" aria-hidden="true"></i> <?php esc_html_e( 'Back', 'crosspoint' ); ?>
				</button>
				<button class="btn btn-gold wz-next" id="wnextB" type="button">
					<?php esc_html_e( 'Continue', 'crosspoint' ); ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
				</button>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
