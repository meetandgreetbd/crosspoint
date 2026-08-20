<?php
/**
 * Template Name: Canada Incorporation
 *
 * Ported from the live page /canada-incorporation/. Markup is the live markup; every
 * price comes from the Packages CPT and every contact detail from CrossPoint
 * Settings.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="cpf-main-content">
<main id="ca-main">


<section class="ca-hero"><div class="ca-wrap"><div class="ca-hero-grid">
	<div class="ca-hero-left">
	<div class="ca-eyebrow"><span class="leaf">◆</span> Canadian Incorporation · Federal, Provincial<br><span class="ca-eyebrow-l2">For Non-Residents</span></div>
	<h1 class="ca-h1">Incorporate in Canada as a <em>Non-Resident</em> — from anywhere, or in person in Toronto.</h1>
	<p class="ca-lead">Start a Canadian corporation federally or in a supported province (ON, BC, AB, SK) — online from anywhere in the world, or face to face in downtown Toronto. <strong>Non-resident founders welcome.</strong> Transparent pricing, government fee charged separately at cost, no surprises.</p>
	<div class="ca-badges">
		<span class="ca-badge"><i class="fa-solid fa-circle-check"></i> Government fee charged separately</span>
		<span class="ca-badge"><i class="fa-solid fa-circle-check"></i> Federal or supported provinces</span>
		<span class="ca-badge"><i class="fa-solid fa-circle-check"></i> Non-residents welcome</span>
		<span class="ca-badge"><i class="fa-solid fa-circle-check"></i> Remote or in person</span>
	</div>
	</div>
	<div class="ca-hero-card">
	<h3>Find your best setup path</h3>
	<p class="ca-card-sub">Answer a few quick questions and we’ll recommend the right route and price.</p>
	<ul class="ca-card-list">
		<li>Federal or provincial guidance</li>
		<li>Named or numbered company</li>
		<li>Banking documentation help</li>
		<li>Transparent pricing, no surprises</li>
	</ul>
	<a class="btn btn-gold shimmer ca-card-cta" href="/start/">Start your incorporation <i class="fa-solid fa-arrow-right"></i></a>
	<a class="btn btn-outline ca-card-cta2" href="<?php echo esc_url( cpf_get_setting( 'calendly_url' ) ); ?>" target="_blank" rel="noopener">Book a free 15-min call</a>
	<div class="ca-card-trust"><i class="fa-solid fa-lock"></i> No credit card to get started</div>
	</div>
</div></div></section>

<section class="ca-sec"><div class="ca-wrap">
	<div class="ca-choose-head">
	<h2>Federal or provincial — we’ll help you choose</h2>
	<p class="sub">The right route depends on where you’ll operate and how you want your name protected. Here’s the plain-language difference.</p>
	<a class="btn btn-outline" href="/canada-incorporation/compare/" style="margin-top:22px"><i class="fa-solid fa-map"></i> Compare on the interactive province map</a>
	</div>
	<div class="ca-two">
	<div class="ca-choice">
		<h3>Federal incorporation <span class="tag">nationwide name</span></h3>
		<p>Incorporate under the Canada Business Corporations Act. Your name is protected across all of Canada, and you can operate in any province after registering there.</p>
		<ul>
		<li>Strongest name protection, nationwide</li>
		<li>Operate across provinces</li>
		<li>Recognized reputation for banking &amp; partners</li>
		<li>Extra-provincial registration where you operate</li>
		</ul>
	</div>
	<div class="ca-choice">
		<h3>Provincial incorporation <span class="tag">ON, BC, AB, SK &amp; more</span></h3>
		<p>Incorporate in a single province — often simpler and ideal if you’ll operate in one place. We handle Ontario, BC, Alberta, Saskatchewan and other provinces.</p>
		<ul>
		<li>Streamlined if you operate in one province</li>
		<li>Named or numbered company options</li>
		<li>Provincial name search included</li>
		<li>Straightforward path to a business bank account</li>
		</ul>
	</div>
	</div>
</div></section>

<section class="ca-sec" id="ca-pricing"><div class="ca-wrap">
	<div class="ca-choose-head">
	<h2>Simple, transparent pricing</h2>
	<p class="sub">Every price below excludes the government filing fee, which is charged separately at cost. No add-on surprises before you file.</p>
	</div>
	<div class="ca-price-grid">
<div class="ca-plan">
		<h3>Starter Setup</h3>
		<div class="desc">Federal incorporation and supported provincial jurisdictions — the essentials, done right.</div>
		<div class="price"><?php echo esc_html( cpf_package_price_label( cpf_get_package_by_key( 'castarter' )->ID, false ) ); ?><small>+ gov fee, charged separately</small></div>
		<ul>
		<li>Incorporation, supported province (ON, BC, AB, SK) or federal</li>
		<li>Certificate of Incorporation &amp; Articles</li>
		<li>NUANS name search (named companies)</li>
		<li>Named or numbered option</li>
		<li>Digital document delivery</li>
		<li>Jurisdiction selection guidance</li>
		</ul>
		<a class="btn btn-gold" href="/start/">Start Starter Setup</a>
	</div>
<div class="ca-plan feat">
		<h3>Non-Resident Setup <span class="pill">Best choice</span></h3>
		<div class="desc">Built for founders living outside Canada.</div>
		<div class="price"><?php echo esc_html( cpf_package_price_label( cpf_get_package_by_key( 'canonres' )->ID, false ) ); ?><small>+ gov fee, charged separately</small></div>
		<ul>
		<li>Everything in Starter Setup</li>
		<li>Non-resident founder onboarding call</li>
		<li>Bank-ready document pack</li>
		<li>Corporate resolutions for banks</li>
		<li>Director residency guidance</li>
		<li>Registered office for non-residents</li>
		<li>Banking documentation guidance</li>
		</ul>
		<a class="btn btn-gold" href="/start/">Start Non-Resident Setup</a>
	</div>
<div class="ca-plan">
	  
		<h3>Growth Setup</h3>
		<div class="desc">Everything to run a corporation properly from day one.</div>
		<div class="price"><?php echo esc_html( cpf_package_price_label( cpf_get_package_by_key( 'cagrowth' )->ID, false ) ); ?><small>+ gov fee, charged separately</small></div>
		<ul>
		<li>Everything in Starter Setup</li>
		<li>Digital minute book &amp; resolutions</li>
		<li>Bylaws, share certificates &amp; seal</li>
		<li>Physical minute book binder kit</li>
		<li>.ca domain registration</li>
		<li>Registered office — 12 months</li>
		<li>Agent for service &amp; first annual return</li>
		<li>Tax account &amp; GST/HST setup guidance</li>
		</ul>
		<a class="btn btn-gold" href="/start/">Start Growth Setup</a>
	</div>
	</div>
	<p class="ca-disc">Prices exclude the applicable government filing fee, which is charged separately at cost. Federal incorporation requiring a resident director is a separate service, quoted individually — <a href="<?php echo esc_url( cpf_whatsapp_url( 'Hi CrossPoint, I\'d like to ask about federal incorporation in Canada.' ) ); ?>" target="_blank" rel="noopener" style="color:var(--gold-dark);font-weight:600">message us on WhatsApp</a> and we’ll walk you through it.</p>
</div></section>

<section class="ca-sec"><div class="ca-wrap">
	<div class="ca-inperson">
	<div>
		<h2>Prefer to do it in person?</h2>
		<p>Most of our clients incorporate entirely online — but if you’re in the Greater Toronto Area and would rather sit down together, you can. We’ll walk through your setup, answer every question, and get your corporation filed.</p>
		<p class="addr"><i class="fa-solid fa-location-dot"></i> Downtown Toronto · 252 Church St, Toronto, ON</p>
	</div>
	<div class="ip-cta">
		<a class="btn btn-gold" href="<?php echo esc_url( cpf_get_setting( 'calendly_url' ) ); ?>" target="_blank" rel="noopener">Book an in-person meeting</a>
		<a class="btn" style="background:#25D366;color:#fff;border:none" href="<?php echo esc_url( cpf_whatsapp_url( 'Hi CrossPoint, I\'d like to meet in person in Toronto about incorporating.' ) ); ?>" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i> Ask on WhatsApp</a>
	</div>
	</div>
</div></section>

<section class="ca-sec"><div class="ca-wrap">
	<h2>Director residency at a glance</h2>
	<p class="ca-lead" style="max-width:820px">Canada lets non-residents own and run a corporation. Whether you need a Canadian-resident director depends on the jurisdiction you pick — here is how the common routes compare today.</p>
	<div style="overflow-x:auto;margin-top:18px">
	<table style="width:100%;border-collapse:collapse;font-size:15px;background:#fff;border:1px solid #e4e8ef;border-radius:12px;overflow:hidden">
		<thead>
		<tr style="background:#0E1B2E;color:#fff;text-align:left">
			<th style="padding:14px 16px;font-weight:700">Jurisdiction</th>
			<th style="padding:14px 16px;font-weight:700">General resident-director rule</th>
		</tr>
		</thead>
		<tbody>
		<tr style="border-top:1px solid #e4e8ef"><td style="padding:12px 16px;font-weight:600">Ontario</td><td style="padding:12px 16px">No current general resident-director quota</td></tr>
		<tr style="border-top:1px solid #e4e8ef"><td style="padding:12px 16px;font-weight:600">British Columbia</td><td style="padding:12px 16px">No current general resident-director quota</td></tr>
		<tr style="border-top:1px solid #e4e8ef"><td style="padding:12px 16px;font-weight:600">Alberta</td><td style="padding:12px 16px">No current general resident-director quota</td></tr>
		<tr style="border-top:1px solid #e4e8ef"><td style="padding:12px 16px;font-weight:600">Saskatchewan</td><td style="padding:12px 16px">No current general resident-director quota</td></tr>
		<tr style="border-top:1px solid #e4e8ef"><td style="padding:12px 16px;font-weight:600">Federal &mdash; CBCA</td><td style="padding:12px 16px">At least 25% resident-Canadian directors; at least one if fewer than four</td></tr>
		</tbody>
	</table>
	</div>
	<p class="ca-disc" style="margin-top:14px">Director residency and registered-office requirements are separate. A corporation must keep the required registered office in its jurisdiction, and extra-provincial registration may be needed where it operates. Rules can change; this reflects current general requirements and is reviewed periodically. For federal incorporation with a resident-director service, <a href="<?php echo esc_url( cpf_whatsapp_url( 'Hi CrossPoint, I\'d like to ask about federal incorporation and director residency.' ) ); ?>" target="_blank" rel="noopener" style="color:var(--gold-dark);font-weight:600">message us on WhatsApp</a>.</p>
</div></section>

<section class="ca-sec"><div class="ca-wrap">
	<h2>Questions Canadian founders ask</h2>
	<div class="ca-faq">
	<details><summary>Should I incorporate federally or provincially?</summary><p>If you want your name protected across all of Canada or plan to operate in multiple provinces, federal is usually the better fit. If you’ll operate in a single province, provincial incorporation is often simpler and just as effective. On your setup call we’ll recommend the route that fits your plans — there’s no one-size answer.</p></details>
	<details><summary>Can I incorporate in Canada if I don’t live here?</summary><p>Yes. Non-resident founders can own and run a Canadian corporation. Some provinces and the federal route have director-residency considerations, which our Non-Resident Setup is built around — including registered office and bank-ready documentation.</p></details>
	<details><summary>Is the government fee included?</summary><p>No. Government filing fees are charged separately at cost, on top of the service price shown on this page. We confirm the exact amount for your province or federal filing before you pay.</p></details>
	<details><summary>Do you help with a business bank account?</summary><p>We provide hands-on banking documentation guidance and prepare a bank-ready document pack. Final approval always rests with the bank, but we help you arrive prepared — which is where most first-time founders get stuck.</p></details>
	<details><summary>Named or numbered company — what’s the difference?</summary><p>A named company (e.g. “Maple Trading Inc.”) needs a name search and gives you a brandable identity. A numbered company (e.g. “1234567 Canada Inc.”) is faster and can be named later. We’ll set up whichever you prefer.</p></details>
	</div>
</div></section>

<section class="ca-final"><div class="ca-wrap">
	<h2>Ready to incorporate in Canada?</h2>
	<p>Answer a few quick questions and we’ll recommend the right route and price — online, or in person in Toronto.</p>
	<a class="btn btn-gold shimmer" href="/start/">Start your incorporation <i class="fa-solid fa-arrow-right"></i></a>
</div></section>
</main>
</main>

<?php
get_footer();
