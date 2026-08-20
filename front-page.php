<?php
/**
 * The homepage.
 *
 * Ported from the live homepage. The quiz markup lives in
 * template-parts/quiz.php and its behaviour in assets/js/quiz.js; the package
 * cards come from the Packages CPT.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="cpf-main-content">
<!-- ============ HERO ============ -->
<header class="hero hero-gradient" id="home">
<div class="wrap">
<span class="eyebrow">Canada &amp; U.S. Company Setup for Non-Residents</span>
<div class="hero-grid">
<div class="hero-copy">
<h1>Open a Company in Canada or the U.S. as a <em>Non-Resident</em></h1>
<p class="hero-sub">Start remotely from anywhere — formation, documentation and next-step guidance.</p>
<p class="hero-trust-line">No travel or local partner for many eligible setups — clear guidance before you pay.</p>
<ul class="hero-checklist"><li><span class="cp-check">✓</span> U.S. LLC, U.S. corporation, or Canadian corporation setup</li><li><span class="cp-check">✓</span> Filing coordination and formation documents</li><li><span class="cp-check">✓</span> Banking and compliance guidance based on your case</li></ul>
<div class="hero-ctas">
<button class="btn btn-gold shimmer" onclick="window.location.href='/start/'" type="button">Start Your Business →</button>
<a class="btn btn-outline" href="<?php echo esc_url( cpf_get_setting( 'calendly_url' ) ); ?>">Book a Free 15-Min Call</a>
</div>
<p class="hero-microcopy"></p>
</div>
<!-- RIGHT: Quiz + Dashboard split -->
<div class="hero-split" id="hero-quiz-anchor">
<?php get_template_part( 'template-parts/quiz' ); ?>
<div aria-hidden="true" class="hero-dashboard-panel">
<div class="hero-ledger">
<div class="hero-ledger-flags"><svg aria-hidden="true" class="hero-ledger-flag" viewbox="0 0 640 480" xmlns="http://www.w3.org/2000/svg"><path d="M150.1 0h339.7v480H150z" fill="#fff"></path><path d="M-19.7 0h169.8v480H-19.7zm509.5 0h169.8v480H489.9z" fill="#d52b1e"></path><path d="M320 150l-14 40h-40l32 26-12 40 34-24 34 24-12-40 32-26h-40z" fill="#d52b1e"></path></svg><svg aria-hidden="true" class="hero-ledger-flag" viewbox="0 0 19 10" xmlns="http://www.w3.org/2000/svg"><rect fill="#b22234" height="10" width="19"></rect><path d="M0 0.8H19M0 2.4H19M0 4H19M0 5.6H19M0 7.2H19M0 8.8H19" stroke="#fff" stroke-width="0.8"></path><rect fill="#3c3b6e" height="5.6" width="7.6"></rect></svg></div>
<div class="hero-ledger-region">U.S. &middot; Canada</div>
<div class="hero-ledger-sub">Your setup</div>
<p class="hero-ledger-lead">Everything prepared, filed and documented.</p>
<div class="hero-ledger-rule"></div>
<ul class="hero-ledger-list">
<li>U.S. or Canadian company<i class="fa-solid fa-check"></i></li>
<li>Banking setup guidance<i class="fa-solid fa-check"></i></li>
<li>Formation documents<i class="fa-solid fa-check"></i></li>
<li>U.S. + Canada support<i class="fa-solid fa-check"></i></li>
</ul>
<div class="hero-ledger-foot"><i class="fa-solid fa-shield-halved"></i>CrossPoint Formations Inc. &mdash; Toronto, Canada</div>
</div>
</div>
</div>
</div>
</div>
</header>
<!-- ============ TRUST BANNER ============ -->
<section aria-label="Why CrossPoint" class="trust-banner" id="trust">
<div class="tb-wrap">
<div class="tb-item"><div class="tb-big">U.S. + Canada</div><div class="tb-lab">Business setup options<br/>in both countries</div></div>
<div class="tb-div"></div>
<div class="tb-item"><div class="tb-big">Multiple States &amp; Provinces</div><div class="tb-lab">Choose an available jurisdiction<br/>that fits your business</div></div>
<div class="tb-div"></div>
<div class="tb-item"><div class="tb-big">Non-Resident Friendly</div><div class="tb-lab">Built for founders abroad<br/>— remote setup for eligible paths</div></div>
</div></section>
<!-- ============ USA VS CANADA ============ -->
<section class="compare-cp" id="usa-vs-canada">
<div class="wrap">
<div class="section-head">
<span class="kicker">USA vs Canada</span>
<h2>Should a non-resident form a company in the U.S. or Canada?</h2>
<p>Both are open to non-residents, and the right choice depends on where your customers are, how you bank, and what you sell. Here is the honest comparison founders ask us to walk through.</p>
</div>
<p class="tbl-swipe">Swipe to compare &#8594;</p>
<div class="comparison-box">
<table class="comparison-table">
<thead><tr><th>Factor</th><th><span class="cmp2-country"><svg aria-hidden="true" class="cmp2-flag" viewbox="0 0 19 10" xmlns="http://www.w3.org/2000/svg"><rect fill="#b22234" height="10" width="19"></rect><path d="M0 0.8H19M0 2.4H19M0 4H19M0 5.6H19M0 7.2H19M0 8.8H19" stroke="#fff" stroke-width="0.8"></path><rect fill="#3c3b6e" height="5.6" width="7.6"></rect></svg> United States</span></th><th><span class="cmp2-country"><svg aria-hidden="true" class="cmp2-flag" viewbox="0 0 640 480" xmlns="http://www.w3.org/2000/svg"><path d="M150.1 0h339.7v480H150z" fill="#fff"></path><path d="M-19.7 0h169.8v480H-19.7zm509.5 0h169.8v480H489.9z" fill="#d52b1e"></path><path d="M201 232l-13.3 4.4 61.4 54c4.7 13.7-1.6 17.8-5.6 25l66.6-8.4-1.6 67 13.9-.3-3.1-66.6 66.7 8c-4.1-8.7-7.8-13.3-4-27.2l61.3-51-10.7-4c-8.8-6.8 3.8-32.6 5.6-48.9 0 0-35.7 12.3-38 5.8l-9.2-17.5-32.6 35.8c-3.5.9-5-.5-5.9-3.5l15-74.8-23.8 13.4q-3.2 1.3-5.2-2.2l-23-46-23.6 47.8q-2.8 2.5-5 .7L264 130.8l13.7 74.1c-1.1 3-3.7 3.8-6.7 2.2l-31.2-35.3c-4 6.5-6.8 17.1-12.2 19.5s-23.5-4.5-35.6-7c4.2 14.8 17 39.6 9 47.7" fill="#d52b1e"></path></svg> Canada</span></th></tr></thead>
<tbody>
<tr><td>Common structures</td><td>LLC or C Corporation</td><td>Federal or provincial corporation</td></tr>
<tr><td>Government fees</td><td>Vary by state, billed separately</td><td>Charged separately at cost, confirmed before payment</td></tr>
<tr><td>Banking</td><td>Depends on provider and applicant country</td><td>Depends on institution and applicant profile</td></tr>
<tr><td>Annual compliance</td><td>State and federal obligations</td><td>Federal or provincial obligations</td></tr>
<tr><td>Often suits</td><td>U.S. customers, e-commerce, SaaS, raising investment</td><td>Canadian market, CAD operations, Canadian clients</td></tr>
<tr><td>Local presence</td><td>Registered agent required (included year 1)</td><td>Registered office required (included year 1)</td></tr>
</tbody>
</table>
</div>
<p style="margin-top:22px;color:#4F5C6D;line-height:1.65;max-width:70ch">Still weighing your options? Learn how to <a href="/guides/us-llc-non-residents/">form a U.S. LLC as a non-resident</a>, explore the <a href="/canada-incorporation/">Canada incorporation path</a>, or take the setup quiz.</p>
</div>
</section>
<!-- ============ WHY CROSSPOINT ============ -->
<section class="why-cp" id="why-crosspoint">
<div class="wrap">
<div class="section-head">
<span class="kicker">Why CrossPoint</span>
<h2>You have the vision. We have the process.</h2>
<p>CrossPoint is built for founders outside Canada and the U.S. who want a clear, guided setup path instead of confusing forms and risky promises.</p>
</div>
<div class="cp-card-grid">
<div class="cp-card">
<div class="cp-ico">◎</div>
<h3>Built for non-residents</h3>
<p>We guide founders abroad through Canada and U.S. setup paths based on country, activity, and documentation.</p>
</div>
<div class="cp-card">
<div class="cp-ico">◫</div>
<h3>Banking help that continues after a &ldquo;no&rdquo;</h3>
<p>Matched to your country, prepared properly — and if an application is declined, we review the feedback and help you evaluate another eligible option in your plan.</p>
</div>
<div class="cp-card">
<div class="cp-ico">◈</div>
<h3>One team across both countries</h3>
<p>Compare Canadian and U.S. formation options with one team, one point of contact, and one clear setup path.</p>
</div>
</div>
</div>
</section>


<!-- ============ WHAT YOU GET ============ -->
<section class="tools-cp" id="what-you-get">
<div class="wrap">
<div class="section-head">
<span class="kicker">Support from launch to growth</span>
<h2>U.S. &amp; Canada company formation services for non-residents.</h2>
<p>From formation to documents and next-step guidance, CrossPoint gives non-resident founders a clean path to start remotely.</p>
</div>
<div class="cp-card-grid">
<div class="cp-card">
<div class="cp-ico">▦</div>
<h3>Starting your business</h3>
<ul class="tool-list">
<li><span class="tool-dot">✓</span><span><b>Company formation</b>Canada or U.S. company setup support.</span></li>
<li><span class="tool-dot">✓</span><span><b>Banking documentation guidance</b>A bank-ready document pack and support through your business-banking application.</span></li>
<li><span class="tool-dot">✓</span><span><b>Corporate documents</b>Articles, resolutions, minute book, and formation documents.</span></li>
</ul>
</div>
<div class="cp-card">
<div class="cp-ico">◫</div>
<h3>Banking &amp; payments</h3>
<ul class="tool-list">
<li><span class="tool-dot">✓</span><span><b>Banking setup guidance</b>Guidance on banking requirements and documentation for non-residents.</span></li>
<li><span class="tool-dot">✓</span><span><b>Payment platform readiness</b>Guidance for preparing your company for Stripe, PayPal, or Shopify applications.</span></li>
<li><span class="tool-dot">✓</span><span><b>USD &amp; CAD setup guidance</b>Support with next steps for receiving payments through eligible providers.</span></li>
</ul>
</div>
<div class="cp-card">
<div class="cp-ico">◇</div>
<h3>Staying compliant</h3>
<ul class="tool-list">
<li><span class="tool-dot">✓</span><span><b>Annual filings</b>Ongoing reminders and filing support options.</span></li>
<li><span class="tool-dot">✓</span><span><b>Updates &amp; changes</b>Address, directors, and share-structure changes.</span></li>
<li><span class="tool-dot">✓</span><span><b>Ongoing guidance</b>Annual plans and advisor support.</span></li>
</ul>
</div>
</div>
</div>
</section>


<!-- ============ HOW IT WORKS ============ -->
<section class="hiw" id="how-it-works">
<div class="wrap">
<div class="hiw-head">
<span class="kicker">How it works</span>
<h2>How to form a U.S. or Canadian company remotely — three steps.</h2>
<p class="hiw-lede">Many eligible setups can be completed remotely — no travel, no local partner, no in-person visit. Start online and get guided at every step.</p>
</div>
<div class="hiw-grid">
<div class="hiw-card">
<div class="hiw-top"><div class="hiw-ico"><svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" viewbox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-8.5 8.5 8.5 8.5 0 0 1-3.8-.9L3 21l1.9-5.7a8.5 8.5 0 0 1-.9-3.8A8.38 8.38 0 0 1 12.5 3 8.38 8.38 0 0 1 21 11.5z"></path><circle cx="9" cy="11.5" fill="currentColor" r=".7" stroke="none"></circle><circle cx="12.5" cy="11.5" fill="currentColor" r=".7" stroke="none"></circle><circle cx="16" cy="11.5" fill="currentColor" r=".7" stroke="none"></circle></svg></div><span class="hiw-step">Step 01</span></div>
<h3>Tell us about your business</h3>
<p>Answer a few quick questions or message us on WhatsApp. We confirm your country, business type, and best setup path.</p>
</div>
<div class="hiw-card">
<div class="hiw-top"><div class="hiw-ico"><svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" viewbox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><path d="M14 2v6h6"></path><line x1="8" x2="16" y1="13" y2="13"></line><line x1="8" x2="13" y1="17" y2="17"></line></svg></div><span class="hiw-step">Step 02</span></div>
<h3>We prepare your filing</h3>
<p>Our team prepares your formation details and coordinates your U.S. or Canadian company setup, keeping you updated at each stage.</p>
</div>
<div class="hiw-card">
<div class="hiw-top"><div class="hiw-ico"><svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" viewbox="0 0 24 24"><circle cx="12" cy="12" r="9"></circle><path d="M8.4 12.4l2.5 2.5 4.7-5.2"></path></svg></div><span class="hiw-step">Step 03</span></div>
<h3>Receive your documents</h3>
<p>Get your company documents with clear next-step guidance, including banking documentation and setup requirements.</p>
</div>
</div>
<div class="hiw-cta-row">
<button class="hiw-cta" onclick="window.location.href='/start/'" type="button">Start Your Business <i class="fa-solid fa-arrow-right"></i></button>
<a class="hiw-wa" href="<?php echo esc_url( cpf_whatsapp_url() ); ?>" onclick="if(window.fireWhatsAppConversion)fireWhatsAppConversion()"><svg aria-hidden="true" fill="currentColor" viewbox="0 0 24 24"><path d="M17.5 14.4c-.3-.15-1.7-.84-2-.94s-.46-.15-.65.15-.74.94-.9 1.13-.33.22-.62.07a8.2 8.2 0 0 1-2.4-1.48 9 9 0 0 1-1.67-2.07c-.17-.3 0-.46.13-.6s.3-.34.44-.51a2 2 0 0 0 .3-.5.55.55 0 0 0 0-.52c-.07-.15-.65-1.57-.9-2.15s-.48-.5-.65-.5h-.56a1.07 1.07 0 0 0-.77.36 3.25 3.25 0 0 0-1 2.42 5.64 5.64 0 0 0 1.18 3 12.9 12.9 0 0 0 4.94 4.36c.69.3 1.23.48 1.65.61a4 4 0 0 0 1.82.11 3 3 0 0 0 1.95-1.37 2.4 2.4 0 0 0 .17-1.37c-.07-.12-.27-.19-.56-.34zM12 2a10 10 0 0 0-8.6 15.06L2 22l5.06-1.33A10 10 0 1 0 12 2zm0 18.2a8.2 8.2 0 0 1-4.18-1.14l-.3-.18-3 .79.8-2.93-.2-.3A8.2 8.2 0 1 1 12 20.2z"></path></svg> Ask a question on WhatsApp</a>
</div>
</div>
</section>

<!-- ============ PRICING ============ -->

<!-- ============ BANKING ============ -->
<section class="bank-sec" id="banking">
<div class="wrap">
<div class="section-head">
<span class="kicker">Banking support</span>
<h2>Banking support that continues after the first decline.</h2>
</div>
<div class="bank-grid">
<div class="bank-copy">
<p class="lead">Opening a U.S. business account as a non-resident is where many founders get stuck &mdash; not at formation. We guide you through the application process and help identify banking options suited to your country and business instead of sending everyone to the same place. If an application is declined, we don&rsquo;t disappear: we review the available feedback and help you evaluate another eligible option included in your service plan.</p>
<p class="bk-promise">Approval is always the financial institution&rsquo;s decision &mdash; we prepare your documents, guide the application, and stay with you through it. We don&rsquo;t make promises a bank hasn&rsquo;t made.</p>
<p class="bk-note">CrossPoint is not a bank, law firm, or tax advisor, and does not guarantee account approval. Eligibility, processing time, and final decisions are determined by each financial institution.</p>
</div>
<div class="bank-panel">
<h3>How we support your banking application</h3>
<p class="bk-sub">Four things we do on every application.</p>
<ul class="bank-steps">
<li><span class="bank-num">1</span><span><b>Match by country &amp; business</b><span class="d">We identify options that may accept applicants from your country, subject to each provider&rsquo;s current eligibility requirements.</span></span></li>
<li><span class="bank-num">2</span><span><b>Prepare the document pack</b><span class="d">Organized the way institutions expect, before you apply.</span></span></li>
<li><span class="bank-num">3</span><span><b>Guide the application</b><span class="d">We walk the process with you, step by step.</span></span></li>
<li><span class="bank-num">4</span><span><b>Support after a decline</b><span class="d">We review the available feedback and guide you toward another eligible option where appropriate.</span></span></li>
</ul>
</div>
</div>
</div>
</section>
<section class="pricing" id="products">
<div class="wrap">
<span class="kicker">Transparent pricing</span>
<h2>U.S. and Canada company formation packages and pricing.</h2>
<div class="price-grid">
	<?php
	foreach ( cpf_get_packages( 'home' ) as $cpf_package ) {
		get_template_part( 'template-parts/package-card', null, array( 'package' => $cpf_package ) );
	}
	?>
	</div>
<p class="price-note">Registered agent (U.S.) and registered office (Canada) are included for the first year in every package; renewals are billed annually and shown before purchase. Free basic website (1–5 pages), domain, hosting, and business email are included for the first year only. Domain availability, premium domains, custom design, ecommerce store setup, paid themes, plugins, advanced SEO, and renewals are not included unless quoted separately. All prices are in USD; any applicable taxes are shown at checkout.</p>
</div>
</section>


<!-- ============ E-COMMERCE LAUNCH (special package, below pricing) ============ -->
<section class="ecom-sec" id="ecommerce">
<div class="wrap">
	<div class="ecom-card">
	<span class="ecom-badge">&#9733; Special package for online sellers</span>
	<div class="ecom-grid">
		<div class="ecom-left">
		<h2>E-Commerce Launch Package</h2>
		<div class="ecom-price"><span class="from">from</span><?php echo esc_html( cpf_package_price_label( cpf_get_package_by_key( 'ecom' )->ID, false ) ); ?> <small>USD · one-time · + government/state fees</small></div>
		<p class="ecom-sub">For Amazon, Shopify, and online sellers who need company setup, documentation, and launch support.</p>
		<div class="plan-perk" style="margin:14px 0 22px"><span class="pp-ck">&#10003;</span> <strong>Free basic website (1&ndash;5 pages), domain &amp; email &mdash; 1 year</strong></div>
		<a class="btn btn-gold" href="<?php echo esc_url( cpf_whatsapp_url( 'Hi CrossPoint, I\'m interested in the E-Commerce Launch package.' ) ); ?>">Start E-Commerce Package &#8594;</a>
		</div>
		<div class="ecom-right">
		<ul class="ecom-feats">
			<li><span class="ec-ck">&#10003;</span> U.S. LLC or Canadian corporation setup</li>
			<li><span class="ec-ck">&#10003;</span> Amazon seller documentation guidance</li>
			<li><span class="ec-ck">&#10003;</span> Shopify and payment-platform readiness guidance</li>
			<li><span class="ec-ck">&#10003;</span> U.S. and Canada address support options for seller applications</li>
			<li><span class="ec-ck">&#10003;</span> 1&ndash;5 page business website setup</li>
			<li><span class="ec-ck">&#10003;</span> One dedicated setup advisor</li>
		</ul>
		</div>
	</div>
	<p class="ecom-disc">Amazon, Shopify, payment processors, banks, and address providers make their own approval decisions. CrossPoint provides setup guidance, documentation support, and address options where suitable.</p>
	</div>
</div>
</section>

<!-- ============ COMPARISON ============ -->
<section class="compare-cp" id="compare">
<div class="wrap">
<div class="comparison-box">
<div class="section-head" style="margin-bottom:28px">
<span class="kicker">Compare your options</span>
<h2>Why founders choose CrossPoint.</h2>
<p>A coordinated setup path built for non-residents &mdash; compared honestly against traditional support and doing it yourself.</p>
</div>
<p class="tbl-swipe">Swipe to compare &#8594;</p>
<div class="cmpx-wrap">
<table class="cmpx" aria-label="CrossPoint comparison">
<thead><tr><th>What you get</th><th class="fcol">CrossPoint<br><span class="cmpx-badge">Recommended</span></th><th>Traditional support</th><th>Do it yourself</th></tr></thead>
<tbody>
<tr><td>Built for non-residents</td><td class="fcol"><span class="cell"><span class="cmpx-ck">&#10003;</span>Specialist support</span></td><td>Varies by provider</td><td>Independent research</td></tr>
<tr><td>Formation and documents</td><td class="fcol"><span class="cell"><span class="cmpx-ck">&#10003;</span>Coordinated for you</span></td><td>Depends on engagement</td><td>Complete forms yourself</td></tr>
<tr><td>Banking documentation guidance</td><td class="fcol"><span class="cell"><span class="cmpx-ck">&#10003;</span>Multiple eligible options</span></td><td>Varies by provider</td><td>No guided support</td></tr>
<tr><td>Canada and U.S. support</td><td class="fcol"><span class="cell"><span class="cmpx-ck">&#10003;</span>One coordinated team</span></td><td>Often separate providers</td><td>Separate systems</td></tr>
<tr><td>Registered agent or office</td><td class="fcol"><span class="cell"><span class="cmpx-ck">&#10003;</span>Year 1 included</span></td><td>Often billed separately</td><td>Arrange independently</td></tr>
<tr><td>Ongoing compliance</td><td class="fcol"><span class="cell"><span class="cmpx-ck">&#10003;</span>Annual support plans</span></td><td>Depends on engagement</td><td>Track deadlines yourself</td></tr>
<tr><td>Starting price</td><td class="fcol"><span class="cmpx-price">From USD <?php echo esc_html( cpf_package_price_label( cpf_get_package_by_key( 'castarter' )->ID, false ) ); ?> <small>package dependent, + gov fee</small></span></td><td>Varies by provider</td><td>Government and required third-party costs</td></tr>
</tbody>
</table>
</div>
<p class="cmp-foot">A coordinated formation process at a transparent package price, without hourly billing for routine administrative setup.</p>
<div class="cmp-cta"><a class="btn btn-gold" href="#products">See packages &#8594;</a></div>
</div>
</div>
</section>


<!-- ============ PAIN ============ -->

<!-- ============ WHAT YOU GET ============ -->

<!-- ============ BANKS ============ -->

<!-- ============ HOW IT WORKS ============ -->

<!-- ============ FAQ ============ -->
<!-- ============ FILINGGUARD ============ -->
<section class="fg-sec" id="filingguard-teaser">
<div class="wrap">
	<div class="fg-wrap">
	<div class="fg-copy">
		<span class="fg-kicker"><i class="fa-solid fa-shield-halved"></i> New — FilingGuard <span class="fg-soon">Early access</span></span>
		<h2>Formed your company? Stay ahead of every important filing deadline.</h2>
		<p class="fg-lede">Every U.S. and Canadian company has ongoing filing and compliance obligations — annual reports and federal filings. Missing certain deadlines can lead to penalties, loss of good standing, or administrative dissolution. <strong>FilingGuard</strong> tracks important deadlines for every entity and reminds you in advance — so you can stay organized and act before each due date.</p>
		<ul class="fg-points">
		<li><i class="fa-solid fa-circle-check"></i> Every deadline for every entity, in one calm dashboard</li>
		<li><i class="fa-solid fa-circle-check"></i> Email &amp; SMS reminders well before each due date</li>
		<li><i class="fa-solid fa-circle-check"></i> Federal filing watch — the deadline owners forget</li>
		</ul>
		<div class="fg-cta-row">
		<a class="fg-cta" href="/filingguard/">Get early access <i class="fa-solid fa-arrow-right"></i></a>
		<span class="fg-note">Launching soon · Join the waitlist — no card required</span>
		</div>
	</div>
	<div class="fg-card" aria-hidden="true">
		<div class="fg-card-head"><span class="fg-dot"></span> Upcoming deadlines</div>
		<div class="fg-row"><div><b>Wyoming annual report</b><span>Meridian Trade LLC</span></div><span class="fg-badge fg-warn">in 40 days</span></div>
		<div class="fg-row"><div><b>Federal annual filing</b><span>Foreign-owned LLC</span></div><span class="fg-badge fg-urgent">in 68 days</span></div>
		<div class="fg-row"><div><b>Ontario annual return</b><span>Northline Inc.</span></div><span class="fg-badge fg-calm">in 5 months</span></div>
		<div class="fg-card-foot"><i class="fa-solid fa-bell"></i> We remind you before every one.</div>
	</div>
	</div>
</div>
</section>


<section class="faq" id="resources" style="background:#fff">
<div class="wrap">
<span class="kicker">Questions</span>
<h2>Non-resident company formation FAQs.</h2>
<p style="color:var(--muted);max-width:70ch;margin-top:14px">Answers to common questions about non-resident company formation, banking documentation, director requirements, and government fees.</p>
<div class="faq-list" style="margin-top:26px">
	<?php
	foreach ( cpf_get_faqs() as $cpf_faq ) {
		get_template_part( 'template-parts/faq-item', null, array( 'faq' => $cpf_faq ) );
	}
	?>
	</div></div>
</section>
<!-- ============ CONTACT ============ -->
<section class="contact" id="contact">
<div class="wrap">
<div class="contact-head">
<span class="kicker">Contact Us</span>
<h2>Contact CrossPoint</h2>
<p>Get help with your Canada or U.S. company setup. Choose a contact option or send a request.</p>
</div>
<div class="contact-split">
<div class="contact-options">
<h3 class="contact-col-title">Choose a contact option</h3>
<div class="contact-cards">
<button class="contact-card" onclick="openCpChat()" type="button">
<span aria-hidden="true" class="contact-card-icon chat"><i class="fa-solid fa-comments"></i></span>
<span>
<strong>CrossPoint Chat</strong>
<span>Get instant answers, then connect with an advisor</span>
</span>
</button>
<a class="contact-card" href="<?php echo esc_url( cpf_whatsapp_url( 'Hi CrossPoint, I\'d like to speak with an advisor.' ) ); ?>" rel="noopener" target="_blank">
<span aria-hidden="true" class="contact-card-icon wa"><i class="fa-brands fa-whatsapp"></i></span>
<span>
<strong>WhatsApp Advisor</strong>
<span>Message us for a quick reply</span>
</span>
</a>
<a class="contact-card" href="<?php echo esc_url( cpf_get_setting( 'calendly_url' ) ); ?>">
<span aria-hidden="true" class="contact-card-icon cal"><i class="fa-solid fa-calendar-check"></i></span>
<span>
<strong>Book a Free 15-Minute Call</strong>
<span>Discuss your setup before you start</span>
</span>
</a>
<a class="contact-card" href="<?php echo esc_attr( cpf_mailto_url() ); ?>">
<span aria-hidden="true" class="contact-card-icon mail"><i class="fa-solid fa-envelope"></i></span>
<span>
<strong>Email Support</strong>
<span>hello@crosspointformations.com</span>
</span>
</a>
</div>
</div>
<div class="contact-form-col">
<h3 class="contact-col-title">Send Your Setup Request</h3>
<div class="contact-form-wrap">
<form class="form" id="contact-form" method="post">
<input aria-hidden="true" autocomplete="off" class="cpf-hp" name="website" tabindex="-1" type="text"/>
<div>
<label for="f-name">Full Name</label>
<input autocomplete="name" id="f-name" name="name" required="" type="text"/>
</div>
<div>
<label for="f-email">Email</label>
<input autocomplete="email" id="f-email" name="email" required="" type="email"/>
</div>
<div>
<label for="f-whatsapp">WhatsApp Number</label>
<input autocomplete="tel" id="f-whatsapp" name="whatsapp_number" placeholder="Include country code" required="" type="tel"/>
</div>
<div>
<label for="f-country">Country</label>
<select id="f-country" name="country_of_residence" required="">
<option value="">Select your country…</option>
</select>
</div>
<div>
<label for="f-open">I want to open</label>
<select id="f-open" name="company_interest" required="">
<option value="">Select an option…</option>
<option>Canadian corporation</option>
<option>U.S. LLC</option>
<option>U.S. corporation</option>
<option>Canada + U.S. company setup</option>
<option>Not sure yet</option>
</select>
</div>
<div>
<label for="f-business">Business type</label>
<select id="f-business" name="business_type" required="">
<option value="">Select business type…</option>
<option>Online business</option>
<option>Consulting / agency</option>
<option>E-commerce</option>
<option>Import / export</option>
<option>Real estate</option>
<option>Other</option>
</select>
</div>
<div class="form-full">
<label for="f-msg">Message</label>
<textarea id="f-msg" name="message" placeholder="Tell us about your business or any questions"></textarea>
</div>
<button class="btn btn-gold form-full" style="border:none;cursor:pointer;font:inherit;font-weight:700" type="submit">Send My Request</button>
</form>
</div>
</div>
</div>
</div>
</section>
<!-- ============ FINAL CTA ============ -->
<section class="final">
<div class="wrap">
<h2>Ready to choose your Canada or U.S. setup path?</h2>
<p>Start with the quick setup check or book a short call with a CrossPoint advisor.</p>
<div class="row">
<a class="btn btn-gold" href="#hero-quiz-anchor">Find Your Best Setup Path</a>
<a class="btn btn-outline" href="<?php echo esc_url( cpf_get_setting( 'calendly_url' ) ); ?>">Book a Free 15-Min Call</a>
<a class="btn btn-wa" href="<?php echo esc_url( cpf_whatsapp_url( 'Hi CrossPoint, I\'d like to open a company as a non-resident.' ) ); ?>"><i class="fa-brands fa-whatsapp"></i> Chat on WhatsApp</a>
<a class="btn btn-outline" href="<?php echo esc_attr( cpf_mailto_url() ); ?>"><i class="fa-solid fa-envelope"></i> Email us</a>
</div>
</div>
</section>
<!-- ============ FOOTER ============ -->
</main>

<?php
get_footer();
