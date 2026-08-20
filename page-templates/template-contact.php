<?php
/**
 * Template Name: Contact
 *
 * Ported from the live page /contact-us/. Markup is the live markup; every
 * price comes from the Packages CPT and every contact detail from CrossPoint
 * Settings.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="cpf-main-content">
<section class="hero">
<div class="wrap">
<span class="kicker">Contact us</span>
<h1>Talk to CrossPoint</h1>
<p>Real people and honest answers about your Canada or U.S. company setup. WhatsApp is the fastest way to reach us — we usually reply within hours.</p>
<div class="opts">
<a class="opt wa" href="<?php echo esc_url( cpf_whatsapp_url( 'Hi CrossPoint, I\'d like to open a company as a non-resident.' ) ); ?>" target="_blank" rel="noopener">
<span class="ic">✆</span>
<h3>WhatsApp advisor</h3>
<p>Message us at +1 (437) 434-6994. Fastest replies — ask anything about your setup, pricing, or documents.</p>
<span class="go">Open WhatsApp →</span>
</a>
<a class="opt call" href="<?php echo esc_url( cpf_get_setting( 'calendly_url' ) ); ?>" target="_blank" rel="noopener">
<span class="ic">◔</span>
<h3>Free 15-minute call</h3>
<p>Book a short call to discuss your country, business type, and the best setup path before you pay anything.</p>
<span class="go">Book a time →</span>
</a>
<a class="opt mail" href="<?php echo esc_attr( cpf_mailto_url() ); ?>">
<span class="ic">✉</span>
<h3>Email support</h3>
<p>hello@crosspointformations.com — best for documents and detailed questions. We reply within one business day.</p>
<span class="go">Send an email →</span>
</a>
</div>
<div class="assure"><span>No credit card required</span><span>Reply within one business day</span><span>Your information stays private</span></div>
</div>
</section>

<section class="formsec">
<div class="wrap">
<div class="fcard">
<h2>Send your setup request</h2>
<p class="sub">Tell us where you're based and what you're building — an advisor will reply by email or WhatsApp.</p>
<form id="cform" novalidate>
<div class="grid2">
<div><label for="cf-name">Full name *</label><input id="cf-name" name="name" required autocomplete="name"/></div>
<div><label for="cf-email">Email *</label><input id="cf-email" name="email" type="email" required autocomplete="email"/></div>
</div>
<div class="grid2">
<div><label for="cf-wa">WhatsApp number with country code *</label><input id="cf-wa" name="whatsapp" required inputmode="tel" placeholder="+92 300 1234567"/></div>
<div><label for="cf-country">Country</label><input id="cf-country" name="country" autocomplete="country-name"/></div>
</div>
<label for="cf-open">I want to open</label>
<select id="cf-open" name="interested_in">
<option value="">Select an option…</option>
<option>U.S. LLC</option>
<option>U.S. corporation</option>
<option>Canadian corporation</option>
<option>Canada + U.S. company setup</option>
<option>Not sure yet</option>
</select>
<label for="cf-msg">Message</label>
<textarea id="cf-msg" name="message" placeholder="Tell us about your business…"></textarea>
<input name="_gotcha" style="display:none" tabindex="-1" autocomplete="off"/>
<p class="micro">We use this only to contact you about your setup request.</p>
<button class="btn" id="cf-btn" type="submit">Send My Request</button>
<p class="fmsg" id="cf-msgout"></p>
</form>
</div>
</div>
</section>
</main>

<?php
get_footer();
