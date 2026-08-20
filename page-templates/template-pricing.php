<?php
/**
 * Template Name: Pricing
 *
 * Ported from the live page /pricing/. Markup is the live markup; every
 * price comes from the Packages CPT and every contact detail from CrossPoint
 * Settings.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="cpf-main-content">
<header class="pr-hero" id="home">
<div class="wrap">
	<span class="kicker">Pricing</span>
	<h1>Transparent pricing. Everything shown up front.</h1>
	<p>No bait pricing, no surprise renewals. Every package below lists exactly what's included. U.S. plans are a service fee <strong>plus state fees at cost</strong>; Canada plans are a service fee <strong>plus the government filing fee</strong>, charged separately at cost.</p>
</div>
</header>

<!-- ============ U.S. FORMATION ============ -->
<section class="pricing" id="us">
<div class="wrap">
	<div class="pr-sec-head"><span class="pr-eyebrow">U.S. Company Formation</span><h2>Form a U.S. LLC or C-Corp — any state</h2></div>
	<p class="pr-note">Prices in USD, plus state/government filing fees shown at cost and confirmed before filing.</p>
	<div class="price-grid">
	<?php
	foreach ( cpf_get_packages( 'us' ) as $cpf_package ) {
		get_template_part( 'template-parts/package-card', null, array( 'package' => $cpf_package ) );
	}
	?>
	</div>
</div>
</section>

<!-- ============ E-COMMERCE ============ -->
<section class="pricing" id="ecommerce" style="background:#fff">
<div class="wrap">
	<div class="pr-sec-head"><span class="pr-eyebrow">For Online Sellers</span><h2>E-Commerce packages — Amazon &amp; Shopify</h2></div>
	<p class="pr-note">Built for sellers: U.S. company plus the documents and platform readiness marketplaces ask for. USD, plus state fees at cost.</p>
	<div class="price-grid">
	<?php
	foreach ( cpf_get_packages( 'us-ecommerce' ) as $cpf_package ) {
		get_template_part( 'template-parts/package-card', null, array( 'package' => $cpf_package ) );
	}
	?>
	</div>
</div>
</section>

<!-- ============ CANADA ============ -->
<section class="pricing" id="canada">
<div class="wrap">
	<div class="pr-sec-head"><span class="pr-eyebrow">Canada Incorporation</span><h2>Incorporate in Canada — transparent pricing</h2></div>
	<p class="pr-note">Canada prices are <strong>in USD</strong> — the government incorporation filing fee is charged separately at cost. Any applicable sales tax is shown before payment.</p>
	<div class="price-grid">
	<?php
	foreach ( cpf_get_packages( 'canada' ) as $cpf_package ) {
		get_template_part( 'template-parts/package-card', null, array( 'package' => $cpf_package ) );
	}
	?>
	</div>
	<p class="pr-note" style="margin-top:20px">Need a Canadian-resident director for a federal corporation? That's handled personally — <a href="<?php echo esc_url( cpf_whatsapp_url( 'Hi CrossPoint, I have a question about federal incorporation and resident-director service.' ) ); ?>" target="_blank" rel="noopener" style="color:var(--gold-dark);font-weight:600">message us on WhatsApp</a>.</p>
</div>
</section>

<!-- ============ RENEWALS + NOTES ============ -->
<section style="background:#fff">
<div class="wrap" style="max-width:900px">
	<div class="pr-renew">
	<h3>Yearly renewals</h3>
	<p style="color:var(--muted);font-size:.95rem">To keep a U.S. entity in good standing after year one, registered-agent and compliance renewals run <strong><?php echo esc_html( cpf_package_price_label( cpf_get_package_by_key( 'renewal' )->ID, false ) ); ?>/year</strong>, or <strong><?php echo esc_html( cpf_package_price_label( cpf_get_package_by_key( 'renewal-plus' )->ID, false ) ); ?>/year</strong> with added annual-filing support. Renewals are optional and billed separately — you're told the amount before anything renews. State annual-report fees, where applicable, are additional and shown at cost.</p>
	</div>
	<p class="pr-note" style="margin-top:22px">All prices are in U.S. dollars. Government and state fees are shown separately at cost (Canada and U.S.) — never marked up. Optional add-ons and annual services are disclosed before payment. CrossPoint Formations Inc. is a private business-formation service and is not a government agency, bank, law firm, or tax adviser; final approval of any registration or bank account rests with the relevant authority or institution.</p>
</div>
</section>

<!-- ============ FINAL CTA ============ -->
<section class="final">
<div class="wrap" style="text-align:center">
	<h2 style="color:var(--navy)">Not sure which plan fits?</h2>
	<p style="color:var(--muted);max-width:560px;margin:12px auto 22px">Answer a few questions and we'll recommend the right country, structure, and package — or just ask us directly.</p>
	<div style="display:flex;gap:12px;flex-wrap:wrap;justify-content:center">
	<a class="btn btn-gold" href="/start/"><i class="fa-solid fa-rocket"></i> Start your setup</a>
	<a class="btn" style="background:#25D366;color:#fff" href="<?php echo esc_url( cpf_whatsapp_url( 'Hi CrossPoint, I have a question about pricing.' ) ); ?>" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i> Ask on WhatsApp</a>
	</div>
</div>
</section>
</main>

<?php
get_footer();
