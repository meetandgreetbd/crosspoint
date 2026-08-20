<?php
/**
 * Template Name: U.S. Formation
 *
 * Ported from the live page /us-formation/non-resident/. Markup is the live markup; every
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
	<span class="kicker">U.S. LLC Formation · For Non-Residents</span>
	<h1>Form your U.S. company as a non-resident — from anywhere.</h1>
	<p class="lead">We set up your U.S. LLC or C Corporation remotely — the filing, the registered agent, and guided support with your business-banking application. No U.S. travel, and no local partner needed for many eligible paths.</p>
	<div class="usm-cta-row">
	<a class="btn btn-gold" href="/start/"><i class="fa-solid fa-rocket"></i> Start your setup</a>
	<a class="btn" style="background:#25D366;color:#fff" href="<?php echo esc_url( cpf_whatsapp_url( 'Hi CrossPoint, I\'d like to form a U.S. company as a non-resident.' ) ); ?>" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i> Ask on WhatsApp</a>
	</div>
	<div class="usm-badges">
	<span class="usm-badge"><i class="fa-solid fa-circle-check"></i> All 50 states · guided state choice</span>
	<span class="usm-badge"><i class="fa-solid fa-circle-check"></i> Registered agent — 1 year included</span>
	<span class="usm-badge"><i class="fa-solid fa-circle-check"></i> Banking-application guidance</span>
	<span class="usm-badge"><i class="fa-solid fa-circle-check"></i> Remote setup — no U.S. visit</span>
	</div>
</div>
</header>

<!-- ===================== INLINE LEAD FORM + TRUST (Part 3) ===================== -->

<section class="usm-lf-sec" id="get-started">
<div class="wrap">
	<div class="usm-lf-grid">
	<div class="usm-lf-card">
		<h2 class="usm-lf-h">Get your setup recommendation</h2>
		<p class="usm-lf-sub">Tell us a little about your plan and we'll recommend the right structure, state, and package. No payment required.</p>
		<form id="usmLead" novalidate>
		<input type="text" name="cpf_hp_field" id="usm-hp" tabindex="-1" autocomplete="off" autocorrect="off" aria-hidden="true" style="display:none">
		<div class="usm-lf-row"><label>Full name
			<input type="text" id="usm-name" name="name" required autocomplete="name" placeholder="Your name"></label></div>
		<div class="usm-lf-row"><label>WhatsApp number (with country code)
			<input type="tel" id="usm-wa" name="whatsapp" required autocomplete="tel" inputmode="tel" placeholder="+92 300 1234567"></label></div>
		<div class="usm-lf-row"><label>Country of residence
			<input type="text" id="usm-country" name="country_of_residence" required autocomplete="country-name" placeholder="e.g. Pakistan"></label></div>
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
	<p>Opening a U.S. business bank account is where non-resident founders most often get stuck — and where many providers simply hand you a link and disappear. We prepare your bank-ready document pack, help match you to institutions that fit your profile, and keep working with you if the first application is declined. Approval is always the financial institution's decision — we make sure you arrive prepared, and we don't leave when it gets hard.</p>
	</div>
</div>
</section>

<!-- ===================== HOW IT WORKS ===================== -->
<section class="hiw" id="how-it-works">
<div class="wrap">
	<span class="kicker">How it works</span>
	<h2>Four steps, all handled remotely.</h2>
	<div class="usm-grid3" style="margin-top:26px">
	<div class="usm-card"><span class="usm-tag">Step 1</span><h3>Tell us your plan</h3><p>Your business activity, where your customers are, and how you want to get paid. We help you compare structures and states based on the information you provide.</p></div>
	<div class="usm-card"><span class="usm-tag">Step 2</span><h3>We file everything</h3><p>Company formation with the state, registered agent for a full year, and your digital company documents.</p></div>
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
		<div class="amt"><span class="from">from</span><?php echo esc_html( cpf_package_price_label( cpf_get_package_by_key( 'starter' )->ID, false ) ); ?> <small>USD · one-time · + state fees</small></div>
		<div class="sub">Everything you need to form and stay compliant.</div>
		<ul>
		<li>Company formation filing — any state</li>
		<li>Registered agent — 1 year included</li>
		<li>Live name availability check</li>
		<li>Digital company documents</li>
		<li>Ongoing compliance reminders</li>
		</ul>
		<a class="btn btn-gold" href="/start/">Start with Starter</a>
	</div>
	<div class="plan featured">
		<span class="tag">Most chosen</span>
		<h3>Growth</h3>
		<div class="amt"><span class="from">from</span><?php echo esc_html( cpf_package_price_label( cpf_get_package_by_key( 'growth' )->ID, false ) ); ?> <small>USD · one-time · + state fees</small></div>
		<div class="sub">Adds banking setup guidance and getting paid in USD.</div>
		<ul>
		<li>Everything in Starter</li>
		<li>Post-formation compliance guidance included</li>
		<li>Business banking-application guidance</li>
		<li>Set up to invoice &amp; get paid in USD</li>
		<li>Operating agreement template</li>
		</ul>
		<a class="btn btn-gold" href="/start/">Start with Growth</a>
	</div>
	<div class="plan">
		<h3>Premium</h3>
		<div class="amt"><span class="from">from</span><?php echo esc_html( cpf_package_price_label( cpf_get_package_by_key( 'premium' )->ID, false ) ); ?> <small>USD · one-time · + state fees</small></div>
		<div class="sub">For founders who want it fully handled.</div>
		<ul>
		<li>Everything in Growth</li>
		<li>Annual compliance planning session</li>
		<li>Dedicated account manager</li>
		<li>U.S. business mail handling</li>
		<li>Priority filing</li>
		</ul>
		<a class="btn btn-gold" href="/start/">Start with Premium</a>
	</div>
	</div>
	<p class="usm-note">All prices in USD. State/government filing fees are shown at cost and confirmed before filing — never marked up. Selling online? We also offer E-Commerce Launch (<?php echo esc_html( cpf_package_price_label( cpf_get_package_by_key( 'ecom' )->ID, false ) ); ?>), E-Commerce Growth (<?php echo esc_html( cpf_package_price_label( cpf_get_package_by_key( 'egrowth' )->ID, false ) ); ?>) and E-Commerce Premium (<?php echo esc_html( cpf_package_price_label( cpf_get_package_by_key( 'epremium' )->ID, false ) ); ?>) packages built for Amazon and Shopify sellers — <a href="<?php echo esc_url( cpf_whatsapp_url( 'Hi CrossPoint, tell me about the e-commerce packages.' ) ); ?>" target="_blank" rel="noopener" style="color:var(--gold-dark);font-weight:600">ask on WhatsApp</a>.</p>
</div>
</section>

<!-- ===================== STATES ===================== -->
<section style="background:#fff">
<div class="wrap">
	<span class="kicker">Choosing a state</span>
	<h2>Which state is right for you?</h2>
	<div class="usm-grid3" style="margin-top:26px">
	<div class="usm-card"><h3>Wyoming</h3><p>A relatively low filing fee and annual-report requirement, with strong owner privacy and no state income tax. It fits many remote businesses, but the right choice depends on how and where the business operates.</p></div>
	<div class="usm-card"><h3>New Mexico</h3><p>Usually the lowest ongoing cost, with no annual report to file. A good fit for lean, single-owner businesses.</p></div>
	<div class="usm-card"><h3>Delaware</h3><p>The standard for startups that plan to raise investment or issue shares to multiple founders.</p></div>
	</div>
	<p style="margin-top:18px">If you want the full picture before you decide, our <a href="/guides/us-llc-non-residents/">step-by-step guide to forming a U.S. LLC as a non-resident</a> walks through choosing a state, the EIN, the operating agreement, and what banks actually ask for.</p>
	<p class="usm-note">Not sure? Tell us your plan on the setup form and we'll recommend the state that fits — there's no one-size answer.</p>
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
	<span class="kicker">Questions non-resident founders ask</span>
	<h2>Good questions, straight answers.</h2>
	<div class="ca-faq" style="margin-top:22px;max-width:840px">
	<details><summary>Can I form a U.S. company if I don't live in the U.S.?</summary><p>Yes. Non-residents can own and run a U.S. LLC or C Corporation. You don't need to be a citizen, hold a visa, or visit the U.S. for many eligible setup paths. Eligibility depends on your country of residence and business activity.</p></details>
	<details><summary>Which state should I choose?</summary><p>Wyoming is a common option for some remote businesses because of its filing and annual requirements. New Mexico and Delaware may fit different cost, operational or investment needs. The appropriate state depends on how and where the business operates.</p></details>
	<details><summary>Do you guarantee a business bank account?</summary><p>No. We are not a bank and cannot guarantee approval. We prepare your application and supporting documents, explain current institution requirements, and keep supporting you — including if a first application is declined. Final approval is always the financial institution's decision.</p></details>
	<details><summary>What documents will I need?</summary><p>Usually a government ID such as a passport, your contact details, business activity information, and formation details. Some banks or platforms may request more depending on your situation.</p></details>
	<details><summary>How long does it take?</summary><p>Formation is often a few business days and varies by state. Banking and payment-platform reviews can take longer and are controlled by each provider. We never promise a fixed timeline we don't control.</p></details>
	<details><summary>Do I need to travel to the U.S.?</summary><p>No — for many eligible paths the entire setup is handled remotely, including your formation and document delivery.</p></details>
	</div>
</div>
</section>

<!-- ===================== FINAL CTA ===================== -->
<section class="final">
<div class="wrap" style="text-align:center">
	<h2 style="color:var(--navy)">Ready to start your U.S. company?</h2>
	<p style="color:var(--muted);max-width:560px;margin:12px auto 22px">Answer a few questions and we'll recommend your structure, state, and plan — then handle the rest remotely.</p>
	<div class="usm-cta-row" style="justify-content:center">
	<a class="btn btn-gold" href="/start/"><i class="fa-solid fa-rocket"></i> Start your setup</a>
	<a class="btn" style="background:#25D366;color:#fff" href="<?php echo esc_url( cpf_whatsapp_url( 'Hi CrossPoint, I\'d like to form a U.S. company as a non-resident.' ) ); ?>" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i> Ask on WhatsApp</a>
	</div>
</div>
</section>

<section style="max-width:900px;margin:48px auto;padding:28px 24px;border:1px solid #e5e7eb;border-radius:14px;background:#f9fafb;font-family:Inter,system-ui,sans-serif">
	<h2 style="margin:0 0 14px;font-size:1.25rem;color:#111827">Form a U.S. company from your country</h2>
	<ul style="margin:0 0 20px;padding-left:20px;line-height:1.9;color:#374151">
	<li><a href="/us-llc-india/" style="color:#1d4ed8">U.S. company formation from India</a></li>
	<li><a href="/us-llc-pakistan/" style="color:#1d4ed8">U.S. company formation from Pakistan</a></li>
	</ul>
	<p style="margin:0;color:#374151">Choosing where to register? <a href="/guides/best-state-non-resident-llc/" style="color:#1d4ed8">Compare the best U.S. states for a non-resident LLC</a>.</p>
</section>

<!-- ===================== FOOTER ===================== -->
</main>

<?php
get_footer();
