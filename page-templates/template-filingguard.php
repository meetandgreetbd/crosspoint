<?php
/**
 * Template Name: FilingGuard
 *
 * Ported from the live page /filingguard/. Markup is the live markup; every
 * price comes from the Packages CPT and every contact detail from CrossPoint
 * Settings.
 *
 * @package CrossPoint
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="cpf-main-content">
<!-- HERO -->
<section class="hero">
	<div class="wrap hero-grid">
	<div>
		<div class="pill-badge"><span class="tag">US + CANADA</span> Built for non-resident owners</div>
		<h1>Don't lose your company to a <span class="u">missed deadline.</span></h1>
		<p class="lede">FilingGuard tracks the key compliance deadlines for your business entities — including federal filings that can carry a $25,000 IRS penalty for certain foreign-owned U.S. entities — and reminds you well before each one. FilingGuard tracks deadlines and sends reminders — it does not prepare or submit filings.</p>
		<div class="hero-cta">
		<a href="#start" class="btn btn-gold btn-lg">Start tracking free<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
		<a href="#how" class="btn btn-ghost btn-lg">See how it works</a>
		</div>
		<div class="hero-trust">
		<span class="d"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7L9 18l-5-5"/></svg>No card to start</span>
		<span class="d"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7L9 18l-5-5"/></svg>Set up in 3 minutes</span>
		<span class="d"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7L9 18l-5-5"/></svg>Cancel anytime</span>
		</div>
	</div>

	<div class="stage">
		<div class="app-card">
		<div class="app-top"><div class="dots"><i></i><i></i><i></i></div><div class="ttl">Compliance health</div></div>
		<div class="app-body">
			<div class="status-hero">
			<div class="eb">Next deadline</div>
			<div class="cd mono">17 <span>days</span></div>
			<div class="cw"><b>Federal Form 5472</b> · Meridian Trade LLC · due Apr 15</div>
			</div>
			<div class="mini-warn">
			<div class="b"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 9v4M12 17h.01M10.3 3.9L1.8 18a2 2 0 001.7 3h17a2 2 0 001.7-3L14.7 3.9a2 2 0 00-3.4 0z"/></svg></div>
			<div class="t"><b>$25,000 IRS penalty</b> can apply if this federal filing is missed — even with zero income. We're watching it.</div>
			</div>
			<div class="mini-rows">
			<div class="mrow"><span class="dot" style="background:var(--amber)"></span><div><div class="nm">Federal Form 5472</div><div class="jz">Wyoming · non-resident LLC</div></div><span class="dt">Apr 15</span></div>
			<div class="mrow"><span class="dot" style="background:var(--teal)"></span><div><div class="nm">Annual return</div><div class="jz">Canada · federal corp</div></div><span class="dt">Nov 30</span></div>
			<div class="mrow"><span class="dot" style="background:var(--red)"></span><div><div class="nm">Franchise tax</div><div class="jz">Delaware · LLC</div></div><span class="dt" style="color:var(--red)">overdue</span></div>
			</div>
		</div>
		</div>
	</div>
	</div>
</section>

<!-- THE $25K TERROR -->
<section class="band paper" id="problem">
	<div class="wrap terror">
	<div>
		<div class="terror-num">$25,000<small>POTENTIAL IRS PENALTY · FORM 5472 · CERTAIN FOREIGN-OWNED U.S. LLCs · EVEN WITH NO INCOME</small></div>
	</div>
	<div>
		<div class="eyebrow">The filing nobody warns you about</div>
		<h2>Many owners never hear about Form 5472 until it's too late.</h2>
		<p>Certain foreign-owned U.S. entities may need to file Form 5472 when they have reportable transactions with a related party. The filing may apply even when the business has no taxable income. Failure to submit a required form can trigger a $25,000 penalty.</p>
		<div class="note">
		<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg>
		<div>FilingGuard flags the 5472 the moment you add a non-resident LLC — and every other deadline your entity owes, across the US and Canada.</div>
		</div>
	</div>
	</div>
</section>

<!-- FEATURES -->
<section class="band" id="features">
	<div class="wrap">
	<div class="center" style="max-width:640px">
		<div class="eyebrow">What you get</div>
		<h2 class="h-sec">One calm dashboard for everything you owe.</h2>
		<p class="sub-sec">Add your entities once. FilingGuard works out every recurring obligation and keeps watch so you don't have to.</p>
	</div>
	<div class="feat-grid">
		<div class="feat">
		<div class="ic ic-red"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 9v4M12 17h.01M10.3 3.9L1.8 18a2 2 0 001.7 3h17a2 2 0 001.7-3L14.7 3.9a2 2 0 00-3.4 0z"/></svg></div>
		<h3>The $25K watch</h3>
		<p>Own a non-resident US LLC? We create the Form 5472 obligation automatically and warn you long before the deadline.</p>
		</div>
		<div class="feat">
		<div class="ic ic-gold"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M3 9h18M8 2v4M16 2v4"/></svg></div>
		<h3>Every deadline, calculated</h3>
		<p>State annual reports, franchise tax, registered-agent renewals, Canadian annual returns — worked out from your formation date.</p>
		</div>
		<div class="feat">
		<div class="ic ic-teal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 8a6 6 0 1112 0c0 7 3 9 3 9H3s3-2 3-9M10.3 21a1.9 1.9 0 003.4 0"/></svg></div>
		<h3>Reminders that escalate</h3>
		<p>Email and text at 90, 60, 30, 7, and 1 days out — getting louder as the date approaches. You choose the timing.</p>
		</div>
		<div class="feat">
		<div class="ic ic-navy"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg></div>
		<h3>Traffic-light status</h3>
		<p>Green means you're safe, amber means it's coming, red means act now. Your whole portfolio at a glance.</p>
		</div>
		<div class="feat">
		<div class="ic ic-amber"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 8v5M12 16h.01"/></svg></div>
		<h3>Rule-change alerts</h3>
		<p>When a fee or filing rule changes in a jurisdiction you're in, you hear about it — not after the fact.</p>
		</div>
		<div class="feat">
		<div class="ic ic-navy"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg></div>
		<h3>Document locker</h3>
		<p>Keep formation docs, EIN letters, and filed-proof uploads in one place, attached to the right entity.</p>
		</div>
	</div>
	</div>
</section>

<!-- HOW IT WORKS -->
<section class="band paper" id="how">
	<div class="wrap">
	<div class="center" style="max-width:600px">
		<div class="eyebrow">Three minutes to set up</div>
		<h2 class="h-sec">How FilingGuard works.</h2>
	</div>
	<div class="steps">
		<div class="step">
		<div class="n">1</div>
		<h3>Add your entity</h3>
		<p>Name, jurisdiction, entity type, and formation date. That's all we need to get started.</p>
		</div>
		<div class="step">
		<div class="n">2</div>
		<h3>See every deadline</h3>
		<p>FilingGuard instantly builds your obligation schedule — including the federal filings most tools miss.</p>
		</div>
		<div class="step">
		<div class="n">3</div>
		<h3>Relax — we'll remind you</h3>
		<p>Your dashboard goes green. From then on, we nudge you by email and text well before anything is due.</p>
		</div>
	</div>
	</div>
</section>

<!-- PRICING -->
<section class="band" id="pricing">
	<div class="wrap">
	<div class="center" style="max-width:600px">
		<div class="eyebrow">Simple pricing</div>
		<h2 class="h-sec">Less than one missed penalty.</h2>
		<div style="text-align:center;margin:8px 0 0"><span style="display:inline-block;background:#FEF6E7;border:1px solid #E8C989;color:#7A5A12;font-weight:700;font-size:.85rem;padding:5px 14px;border-radius:999px">Early access — launching soon</span></div>
		<p class="sub-sec">A single missed Form 5472 filing can trigger a $25,000 IRS penalty. A year of FilingGuard costs less than dinner out.</p>
	</div>
	<div class="price-grid">
		<div class="plan">
		<div class="pn">Solo</div>
		<div class="pp"><span class="amt"><?php echo esc_html( cpf_package_price_label( cpf_get_package_by_key( 'fg-basic' )->ID, false ) ); ?></span><span class="per">/ month</span></div>
		<div class="pd">For one company, kept perfectly on track.</div>
		<ul>
			<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7L9 18l-5-5"/></svg>1 entity</li>
			<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7L9 18l-5-5"/></svg>All deadline tracking</li>
			<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7L9 18l-5-5"/></svg>Email + SMS reminders</li>
			<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7L9 18l-5-5"/></svg>Form 5472 watch</li>
		</ul>
		<a href="#start" class="btn btn-ghost">Join the waitlist</a>
		</div>
		<div class="plan feature">
		<div class="pn">Owner</div>
		<div class="pp"><span class="amt"><?php echo esc_html( cpf_package_price_label( cpf_get_package_by_key( 'fg-pro' )->ID, false ) ); ?></span><span class="per">/ month</span></div>
		<div class="pd">For founders running a few entities at once.</div>
		<ul>
			<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7L9 18l-5-5"/></svg>Up to 5 entities</li>
			<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7L9 18l-5-5"/></svg>Everything in Solo</li>
			<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7L9 18l-5-5"/></svg>Rule-change alerts</li>
			<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7L9 18l-5-5"/></svg>Document locker</li>
		</ul>
		<a href="#start" class="btn btn-gold">Join the waitlist</a>
		</div>
		<div class="plan">
		<div class="pn">Pro</div>
		<div class="pp"><span class="amt"><?php echo esc_html( cpf_package_price_label( cpf_get_package_by_key( 'fg-scale' )->ID, false ) ); ?></span><span class="per">/ month</span></div>
		<div class="pd">For accountants and agents managing clients.</div>
		<ul>
			<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7L9 18l-5-5"/></svg>Up to 25 entities</li>
			<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7L9 18l-5-5"/></svg>Team seats + client view</li>
			<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7L9 18l-5-5"/></svg>Everything in Owner</li>
			<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7L9 18l-5-5"/></svg>Priority support</li>
		</ul>
		<a href="#start" class="btn btn-ghost">Join the waitlist</a>
		</div>
	</div>
	<p class="price-foot">Prefer us to handle the filing itself? Add <b>“File it for me”</b> on any plan, priced per filing.</p>
	</div>
</section>

<!-- FAQ -->
<section class="band paper" id="faq">
	<div class="wrap">
	<div class="center" style="max-width:600px">
		<div class="eyebrow">Good to know</div>
		<h2 class="h-sec">Questions, answered.</h2>
	</div>
	<div class="faq">
		<details class="qa" open>
		<summary>What is Form 5472 and why does it matter?<span class="pm"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg></span></summary>
		<div class="a">It's a US federal filing that applies to certain foreign-owned single-member LLCs with reportable related-party transactions — and it can apply even in years with no income or activity. The penalty for missing a required filing starts at $25,000. Many owners never learn it applies to them — which is exactly why FilingGuard flags it automatically.</div>
		</details>
		<details class="qa">
		<summary>Which jurisdictions do you cover?<span class="pm"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg></span></summary>
		<div class="a">US states and Canadian federal and provincial entities, with a particular focus on non-resident owners who juggle obligations across borders. Add an entity and you'll see exactly what it owes.</div>
		</details>
		<details class="qa">
		<summary>Is FilingGuard a law firm or accountant?<span class="pm"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg></span></summary>
		<div class="a">No. FilingGuard is a private deadline-tracking service — not a government agency, law firm, or tax advisor, and it doesn't provide legal or tax advice. We help you see and stay ahead of your obligations. For interpretation, we'll point you to a qualified professional.</div>
		</details>
		<details class="qa">
		<summary>How do the reminders reach me?<span class="pm"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg></span></summary>
		<div class="a">Email and text message, at the lead times you choose — up to 90, 60, 30, 7, and 1 day before each deadline. They escalate in urgency as the date gets closer.</div>
		</details>
		<details class="qa">
		<summary>Can you file the paperwork for me?<span class="pm"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg></span></summary>
		<div class="a">Yes — add "File it for me" on any plan and our team handles the filing for a per-filing fee. Otherwise, FilingGuard keeps you informed and you file yourself.</div>
		</details>
	</div>
	</div>
</section>

<!-- CTA + LEAD -->
<section class="band" id="start">
	<div class="wrap">
	<div class="cta-band">
		<div class="cta-grid">
		<div>
			<h2>Get early access to FilingGuard.</h2>
			<p>FilingGuard is launching soon. Join the early-access list and we’ll email you the moment it opens — plus lock in founding-member pricing. No card required.</p>
		</div>
		<div class="lead">
			<label>Company name</label>
			<input id="fg-company" type="text" placeholder="e.g. Meridian Trade LLC">
			<div class="row">
			<div style="flex:1">
				<label>Jurisdiction</label>
				<select id="fg-juris"><option>Wyoming (US)</option><option>Delaware (US)</option><option>Other US state</option><option>Canada — federal</option><option>Canada — province</option></select>
			</div>
			<div style="flex:1">
				<label>Entity type</label>
				<select id="fg-entity"><option>Non-resident LLC</option><option>US LLC</option><option>Corporation</option></select>
			</div>
			</div>
			<label>Email for reminders</label>
			<input id="fg-email" type="email" placeholder="you@company.com">
			<button id="fg-submit" class="btn btn-gold">Get early access<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></button>
			<div class="fine">Private deadline-tracking service — not a government agency. Not legal or tax advice.</div>
		</div>
		</div>
	</div>
	</div>
</section>

<?php // The page's own footer is gone: the shared site footer applies here, like everywhere else. ?>

</main>

<?php
get_footer();
