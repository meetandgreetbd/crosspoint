<?php
/**
 * Template Name: U.S. LLC for India
 *
 * Ported from the live page /us-llc-india/. Markup is the live markup; every
 * price comes from the Packages CPT and every contact detail from CrossPoint
 * Settings.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="cpf-main-content">
<!-- ===================== HERO ===================== -->
<header class="usm-hero" id="home">
<div class="wrap">
	<span class="kicker">U.S. LLC Formation · For Founders in India</span>
	<h1>Form a U.S. LLC from India — built for global payments and USD banking.</h1>
	<p class="lead">Set up your U.S. LLC remotely from India, receive your formation documents and registered-agent service, and get guided support preparing applications for eligible U.S. banking and payment platforms. Approval always depends on each provider's eligibility and verification rules.</p>
	<div class="usm-cta-row">
	<a class="btn btn-gold" href="#get-started"><i class="fa-solid fa-rocket"></i> Get my setup recommendation</a>
	<a class="btn" style="background:#25D366;color:#fff" href="<?php echo esc_url( cpf_whatsapp_url( 'Hi CrossPoint, I\'m in India and I\'d like to form a U.S. LLC.' ) ); ?>" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i> Ask on WhatsApp</a>
	</div>
	<div class="usm-badges">
	<span class="usm-badge"><i class="fa-solid fa-circle-check"></i> Guidance for Stripe and PayPal applications</span>
	<span class="usm-badge"><i class="fa-solid fa-circle-check"></i> USD business-banking application support</span>
	<span class="usm-badge"><i class="fa-solid fa-circle-check"></i> Registered agent — 1 year included</span>
	<span class="usm-badge"><i class="fa-solid fa-circle-check"></i> Remote company formation from India</span>
	</div>
</div>
</header>

<!-- ===================== INLINE LEAD FORM + TRUST (Part 3) ===================== -->

<section class="usm-lf-sec" id="get-started">
<div class="wrap">
	<div class="usm-lf-grid">
	<div class="usm-lf-card">
		<h2 class="usm-lf-h">Get your setup recommendation</h2>
		<p class="usm-lf-sub">Tell us what you're building from India and we'll recommend the right structure, state, and package — plus guidance preparing eligible Stripe, payment-platform and USD banking applications. No payment required.</p>
		<form id="usmLead" novalidate>
		<input type="text" name="cpf_hp_field" id="usm-hp" tabindex="-1" autocomplete="off" autocorrect="off" aria-hidden="true" style="display:none">
		<div class="usm-lf-row"><label>Full name
			<input type="text" id="usm-name" name="name" required autocomplete="name" placeholder="Your name"></label></div>
		<div class="usm-lf-row"><label>WhatsApp number (with country code)
			<input type="tel" id="usm-wa" name="whatsapp" required autocomplete="tel" inputmode="tel" placeholder="+91 98765 43210"></label></div>
		<div class="usm-lf-row"><label>Country of residence
			<input type="text" id="usm-country" name="country_of_residence" required autocomplete="country-name" value="India" placeholder="e.g. India"></label></div>
		<div class="usm-lf-row"><label>What are you setting up?
			<select id="usm-interest" name="business_type" required>
			<option value="">Select&hellip;</option>
			<option value="US LLC">U.S. LLC</option>
			<option value="US C Corporation">U.S. C Corporation</option>
			<option value="Not sure yet">Not sure yet</option>
			</select></label></div>
		<div class="usm-lf-row"><label>Email <span class="usm-opt" style="display:none">(optional)</span>
			<input type="email" id="usm-email" name="email" required autocomplete="email" placeholder="you@email.com"></label></div>
		<button type="submit" class="btn btn-gold usm-lf-btn" id="usm-submit"><i class="fa-solid fa-paper-plane"></i> Get My Setup Recommendation</button>
		<p class="usm-lf-msg" id="usm-msg" aria-live="polite"></p>
		<p class="usm-lf-micro">No payment required. We normally reply by email or WhatsApp within one business day. Please don't submit passports, identity documents, or banking records through this form.</p>
		</form>
		<div class="usm-lf-done" id="usm-done" hidden>
		<h3><i class="fa-solid fa-circle-check" style="color:var(--emerald)"></i> Request received</h3>
		<p>Thanks &mdash; a CrossPoint specialist will reach out. We'll reply to your email shortly. You can also message us on WhatsApp:</p>
		<a class="btn" style="background:#25D366;color:#fff" id="usm-done-wa" href="<?php echo esc_url( cpf_whatsapp_url() ); ?>" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i> Continue on WhatsApp</a>
		</div>
	</div>
	<aside class="usm-trust">
		<div class="usm-trust-item"><strong>CrossPoint Formations Inc.</strong><span>A private company based in Toronto, Canada. We are not a government agency.</span></div>
		<div class="usm-trust-item"><strong>Transparent pricing</strong><span>Service prices are shown in USD. Government and state filing fees are shown at cost and confirmed before filing.</span></div>
		<div class="usm-trust-item"><strong>Banking guidance, not a guarantee</strong><span>We help prepare your application and documents. Approval always remains the financial institution's decision.</span></div>
		<div class="usm-trust-item"><strong>Real support</strong><span>Talk to a real advisor by WhatsApp or email &mdash; before and after you order.</span></div>
		<div class="usm-trust-note">No payment required to request a setup recommendation.</div>
	</aside>
	</div>
</div>
</section>



<!-- ===================== WHY / MOAT ===================== -->
<section>
<div class="wrap">
	<div class="usm-moat">
	<span class="usm-tag" style="color:var(--gold)">Where we're different</span>
	<h2>Most services stop at the paperwork. We stay through the banking.</h2>
	<p>For founders in India, the hard part isn't the LLC — it's preparing strong applications for eligible U.S. banking and payment platforms afterward, and that's exactly where many providers hand you a link and disappear. We prepare your bank-ready document pack, help you evaluate banking and payment providers based on their current eligibility, business-activity and documentation requirements, and keep working with you if a first application is declined. Applications and approvals remain subject to each provider's review — we make sure you arrive prepared, and we don't leave when it gets hard.</p>
	</div>
</div>
</section>

<!-- ===================== HOW IT WORKS ===================== -->
<section class="hiw" id="how-it-works">
<div class="wrap">
	<span class="kicker">How it works</span>
	<h2>Four steps, with company formation handled remotely.</h2>
	<div class="usm-grid3" style="margin-top:26px">
	<div class="usm-card"><span class="usm-tag">Step 1</span><h3>Tell us your plan</h3><p>Your business activity, where your customers are, and how you want to get paid. We help you compare structures and states based on the information you provide.</p></div>
	<div class="usm-card"><span class="usm-tag">Step 2</span><h3>We coordinate your formation filing</h3><p>Company formation with the state, registered agent for a full year, and your digital company documents.</p></div>
	<div class="usm-card"><span class="usm-tag">Step 3</span><h3>We prepare your banking</h3><p>A bank-ready document pack, guidance on current institution requirements, and support through your application.</p></div>
	</div>
	<div class="usm-grid3" style="margin-top:18px">
	<div class="usm-card"><span class="usm-tag">Step 4</span><h3>You're set up — and supported</h3><p>You receive your documents and next-step checklist. If banking or payment platforms take longer, we stay with you.</p></div>
	<div class="usm-card" style="background:var(--paper)"><span class="usm-tag">Optional add-ons</span><h3>Grow when ready</h3><p>Optional support includes U.S. address and mail handling, post-formation compliance guidance, document organization and annual compliance reminders.</p></div>
	<div class="usm-card" style="background:var(--paper)"><span class="usm-tag">Ongoing</span><h3>Annual compliance</h3><p>Annual report and registered-agent renewals are available as separate services so you stay in good standing.</p></div>
	</div>
</div>
</section>

<!-- ===================== PRICING ===================== -->
<section class="pricing" id="pricing">
<div class="wrap">
	<span class="kicker">Transparent pricing</span>
	<h2>Clear USD pricing. State fees shown at cost.</h2>
	<div class="price-grid" style="margin-top:26px">
	<div class="plan">
		<h3>Starter</h3>
		<div class="amt"><span class="from">from</span><?php echo esc_html( cpf_package_price_label( cpf_get_package_by_key( 'starter' )->ID, false ) ); ?> <span class="amt-inr" data-usd="299"></span><small>USD · one-time · + state fees</small></div>
		<div class="sub">Everything you need to form and stay compliant.</div>
		<ul>
		<li>Company formation filing — any state</li>
		<li>Registered agent — 1 year included</li>
		<li>Live name availability check</li>
		<li>Digital company documents</li>
		<li>Ongoing compliance reminders</li>
		</ul>
		<a class="btn btn-gold" href="#get-started">Start with Starter</a>
	</div>
	<div class="plan featured">
		<span class="tag">Most chosen</span>
		<h3>Growth</h3>
		<div class="amt"><span class="from">from</span><?php echo esc_html( cpf_package_price_label( cpf_get_package_by_key( 'growth' )->ID, false ) ); ?> <span class="amt-inr" data-usd="399"></span><small>USD · one-time · + state fees</small></div>
		<div class="sub">Adds banking setup guidance and getting paid in USD.</div>
		<ul>
		<li>Everything in Starter</li>
		<li>Post-formation compliance guidance included</li>
		<li>Business banking-application guidance</li>
		<li>Set up to invoice &amp; get paid in USD</li>
		<li>Operating agreement template</li>
		</ul>
		<a class="btn btn-gold" href="#get-started">Start with Growth</a>
	</div>
	<div class="plan">
		<h3>Premium</h3>
		<div class="amt"><span class="from">from</span><?php echo esc_html( cpf_package_price_label( cpf_get_package_by_key( 'premium' )->ID, false ) ); ?> <span class="amt-inr" data-usd="699"></span><small>USD · one-time · + state fees</small></div>
		<div class="sub">For founders who want it fully handled.</div>
		<ul>
		<li>Everything in Growth</li>
		<li>Annual compliance planning session</li>
		<li>Dedicated account manager</li>
		<li>U.S. business mail handling</li>
		<li>Priority filing</li>
		</ul>
		<a class="btn btn-gold" href="#get-started">Start with Premium</a>
	</div>
	</div>
	<p class="usm-note">All prices are billed in USD; the ₹ figures are an approximate reference only, and your card or payment provider determines the actual conversion rate. State filing fees are shown at cost and confirmed before filing — never marked up. Selling online? We also offer E-Commerce Launch (<?php echo esc_html( cpf_package_price_label( cpf_get_package_by_key( 'ecom' )->ID, false ) ); ?>), E-Commerce Growth (<?php echo esc_html( cpf_package_price_label( cpf_get_package_by_key( 'egrowth' )->ID, false ) ); ?>) and E-Commerce Premium (<?php echo esc_html( cpf_package_price_label( cpf_get_package_by_key( 'epremium' )->ID, false ) ); ?>) packages built for Amazon and Shopify sellers — <a href="<?php echo esc_url( cpf_whatsapp_url( 'Hi CrossPoint, tell me about the e-commerce packages.' ) ); ?>" target="_blank" rel="noopener" style="color:var(--gold-dark);font-weight:600">ask on WhatsApp</a>.</p>
</div>
</section>

<!-- ===================== STATES ===================== -->
<section style="background:#fff">
<div class="wrap">
	<span class="kicker">Built for founders in India</span>
	<h2>Who forms a U.S. LLC from India — and why</h2>
	<div class="usm-grid3" style="margin-top:26px">
	<div class="usm-card"><span class="usm-tag">Freelancers &amp; agencies</span><h3>Get paid by U.S. clients in USD</h3><p>Invoice American and global clients from a U.S. entity, apply for eligible card-payment platforms such as Stripe, and present as the professional operation you are — instead of routing client payments to a personal account.</p></div>
	<div class="usm-card"><span class="usm-tag">SaaS &amp; digital products</span><h3>Apply for Stripe &amp; bill worldwide</h3><p>A U.S. entity can provide Indian founders with a route to apply for eligible Stripe and PayPal accounts and bill international customers in USD. Each platform applies its own eligibility, verification and approval requirements.</p></div>
	<div class="usm-card"><span class="usm-tag">E-commerce &amp; Amazon</span><h3>Sell on U.S. marketplaces</h3><p>Run Amazon US, Shopify and other stores under a U.S. company with a U.S. bank account, cleaner payment-gateway relationships, and USD settlement.</p></div>
	</div>
	<p class="usm-note"><strong>Which state?</strong> Wyoming and New Mexico are commonly considered by non-resident founders for their fee and maintenance structures; the right state depends on your activity, customers, investors and ongoing requirements — see our <a href="/guides/best-state-non-resident-llc/">best-state guide</a> or <a href="/guides/us-llc-non-residents/">non-resident LLC guide</a>. Tell us your plan on the form and we'll recommend the state that fits, or read the <a href="/us-formation/non-resident/">full non-resident U.S. LLC overview</a>.</p>
</div>
</section>

<!-- ===================== COMPARE (neutral) ===================== -->
<section class="compare-cp" id="compare">
<div class="wrap">
	<span class="kicker">Before you choose a provider</span>
	<h2>Compare on what actually matters.</h2>
	<p style="max-width:760px;color:var(--muted);margin-top:8px">Formation services price differently, and a low headline fee can hide higher renewals. Before choosing anyone — including us — compare the full picture and verify current terms directly with each provider.</p>
	<div class="usm-grid3" style="margin-top:22px">
	<div class="usm-card"><h3>Initial vs. ongoing</h3><p>Look past the first-year price. Compare registered-agent renewal, compliance services, and mail handling year over year.</p></div>
	<div class="usm-card"><h3>What's included</h3><p>Check which documents, filings, and support are in the price versus billed separately later.</p></div>
	<div class="usm-card"><h3>Support that lasts</h3><p>Ask what happens after formation — especially with banking. That's where most first-time founders get stuck.</p></div>
	</div>
</div>
</section>

<!-- ===================== FAQ ===================== -->
<section class="faq" id="faq" style="background:var(--paper)">
<div class="wrap">
	<span class="kicker">Questions founders in India ask</span>
	<h2>Good questions, straight answers.</h2>
	<div class="ca-faq" style="margin-top:22px;max-width:840px">
	<details><summary>Can I open a U.S. LLC from India as an Indian resident?</summary><p>Yes. Indian residents can legally own and run a U.S. LLC. You don't need U.S. citizenship, a visa, or a U.S. co-founder. Company formation can generally be completed remotely from India, though banks and payment platforms may require their own verification steps. You do remain subject to Indian law on owning a foreign company — see the tax and FEMA questions below.</p></details>
	<details><summary>Will a U.S. LLC get me Stripe and PayPal?</summary><p>A U.S. LLC may provide eligible Indian founders with a route to apply for Stripe, PayPal and other international payment platforms. We help you get formation and banking in place so you can apply; each provider applies its own eligibility, verification and approval requirements.</p></details>
	<details><summary>Can I get a U.S. business bank account from India?</summary><p>U.S. banking options may be available remotely depending on your company, business activity, operating footprint and documentation. We help you evaluate providers and prepare your application. Each provider makes its own eligibility and approval decision.</p></details>
	<details><summary>Do I have to pay tax in India on a U.S. LLC?</summary><p>Indian residents may have Indian tax and foreign-asset reporting obligations when they own a U.S. company or receive foreign-source income. How it is treated depends on your residency, ownership, management, entity classification and business facts. CrossPoint handles U.S. formation and guidance and does not provide Indian tax advice — please confirm your exact position with a qualified Indian chartered accountant (CA) before operating or remitting funds.</p></details>
	<details><summary>What about FEMA and RBI rules?</summary><p>Indian residents making an overseas investment may have reporting, bank-routing and ongoing compliance obligations under India's Overseas Investment framework. What applies depends on your ownership, control, funding method and the nature of the business. Before remitting funds, confirm the process with your authorised-dealer (AD) bank and a qualified Indian CA or FEMA adviser. We flag this honestly rather than pretend it doesn't exist.</p></details>
	<details><summary>What documents do I need and how long does it take?</summary><p>Usually your passport, contact details, and business activity information. Formation is often a few business days and varies by state; bank and payment-platform reviews take longer and are controlled by each provider. We never promise a timeline we don't control.</p></details>
	</div>
</div>
</section>

<!-- ===================== FINAL CTA ===================== -->
<section class="final">
<div class="wrap" style="text-align:center">
	<h2 style="color:var(--navy)">Ready to form your U.S. LLC from India?</h2>
	<p style="color:var(--muted);max-width:560px;margin:12px auto 22px">Tell us what you're building and we'll recommend your structure, state, and plan — then handle the U.S. setup remotely while you stay in India.</p>
	<div class="usm-cta-row" style="justify-content:center">
	<a class="btn btn-gold" href="#get-started"><i class="fa-solid fa-rocket"></i> Get my setup recommendation</a>
	<a class="btn" style="background:#25D366;color:#fff" href="<?php echo esc_url( cpf_whatsapp_url( 'Hi CrossPoint, I\'m in India and I\'d like to form a U.S. LLC.' ) ); ?>" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i> Ask on WhatsApp</a>
	</div>
</div>
</section>

<!-- ===================== FOOTER ===================== -->
</main>

<?php
get_footer();
