/**
 * CrossPoint - start.js
 * The /start/ guided setup wizard.
 *
 * Ported from the live wizard. Three things changed in the port:
 *   1. Prices and add-ons come from cpfConfig.packages, built from the
 *      Packages CPT. No price is written into this file.
 *   2. The four PHP endpoints under /tools/ are now REST routes under
 *      /wp-json/crosspoint/v1/, called with the WordPress REST nonce.
 *   3. The third-party emergency form channel is gone: WordPress stores every
 *      lead locally before any email is queued.
 */
var cpfConfig = window.cpfConfig || {};
var CPF_PKG = cpfConfig.packages || {};
var CPF_REST = cpfConfig.restBase || '/wp-json/crosspoint/v1/';
var CPF_SITE = window.cpfSite || {};

/**
 * Request headers for every REST call: JSON plus the WordPress nonce.
 *
 * @return {Object} Header map.
 */
function cpfHeaders() {
  return { 'Content-Type': 'application/json', 'X-WP-Nonce': cpfConfig.nonce || '' };
}

/**
 * Map the wizard's payload onto the fields the /lead endpoint accepts.
 * Anything without a column of its own is kept in the message field, so no
 * answer the visitor gave is ever dropped.
 *
 * @param {Object} p Wizard payload.
 * @return {Object} REST payload.
 */
function cpfLeadPayload(p) {
  var extra = [];
  Object.keys(p).forEach(function (k) {
    if (['name', 'email', 'phone', 'country_of_residence', 'business_type', 'company_structure',
      'jurisdiction', 'company_name', 'backup_name', 'recommended_package', 'source',
      'cpf_hp_field', '_subject'].indexOf(k) === -1 && p[k]) {
      extra.push(k + ': ' + p[k]);
    }
  });

  return {
    full_name: p.name || '',
    email: p.email || '',
    whatsapp: p.phone || '',
    residence_country: p.country_of_residence || '',
    business_type: p.business_type || '',
    structure: p.company_structure || '',
    state: p.jurisdiction || '',
    company_name: p.company_name || '',
    backup_name: p.backup_name || '',
    package: p.recommended_package || '',
    destination: p.business_destination || '',
    source: 'start-wizard',
    source_url: location.href,
    message: extra.join('\n'),
    website: p.cpf_hp_field || '',
    ts: cpfConfig.ts || 0
  };
}


(function(){try{var p=new URLSearchParams(location.search);['gclid','gbraid','wbraid','utm_source','utm_medium','utm_campaign','utm_term','utm_content'].forEach(function(k){var v=p.get(k);if(v)document.cookie='cpf_'+k+'='+encodeURIComponent(v)+';path=/;max-age='+(90*86400)+';SameSite=Lax';});}catch(e){}})();
function cpfGetTracking(){var out={};['gclid','gbraid','wbraid','utm_source','utm_medium','utm_campaign','utm_term','utm_content'].forEach(function(k){var m=document.cookie.match(new RegExp('(?:^|; )cpf_'+k+'=([^;]*)'));out[k]=m?decodeURIComponent(m[1]):'';});out.landing_page=location.pathname;out.referrer=document.referrer||'';return out;}


  /* =========================================================
     STRIPE PAYMENT LINKS — PASTE YOUR LINKS HERE (service fee only)
     The "Pay & start" button appears automatically once a link
     is present; until then only the WhatsApp/call path shows.
     ========================================================= */
  var PAYLINKS = {
    us: {
      starter: '',   // e.g. 'https://buy.stripe.com/xxxxxxxx'
      growth:  '',
      premium: '',
      ecom: '', egrowth: '', epremium: ''
    }
  };
  /* =========================================================
     STRIPE DYNAMIC CHECKOUT (plan + add-ons in one payment)
     Handled server side by POST crosspoint/v1/checkout, which reads the
     restricted key from the CPF_STRIPE_SECRET constant in wp-config.php.
     "On request" add-ons are never charged here; they are recorded in the
     session metadata and confirmed with the customer first.
     ========================================================= */
  var CHECKOUT_ENDPOINT = CPF_REST + 'checkout'; /* LIVE 2026-07-25. Set to '' to kill on-page card checkout instantly. */
  var LEAD_ID = '';        /* entry id returned by the lead endpoint; threaded into Stripe metadata */
  var VERIFY_ENDPOINT   = CPF_REST + 'verify-checkout'; /* LIVE 2026-07-25. */
  /* AI chat widget backend. The key lives in the CPF_ANTHROPIC_KEY constant;
     the endpoint answers 503 when it is unset and the bubble degrades. */
  var CHAT_ENDPOINT = CPF_REST + 'chat';

  /* ===== Tracking (mirrors homepage Landing_V31) ===== */
  var GADS_ID = CPF_SITE.gadsId || '', LABEL_FORM = CPF_SITE.gadsLabelForm || '', LABEL_WHATSAPP = CPF_SITE.gadsLabelWhatsapp || '', LABEL_PURCHASE = CPF_SITE.gadsLabelPurchase || '';/* Google Ads 'Purchase' action id 7697602738, created 2026-07-25 */
  document.addEventListener('click',function(e){var a=e.target.closest&&e.target.closest('a[href*="wa.me"]');if(a){try{gtag('event','conversion',{send_to:GADS_ID+'/'+LABEL_WHATSAPP,transport_type:'beacon'});window.uetq=window.uetq||[];window.uetq.push('event','outbound_click',{});}catch(_){}}});

  /* ===== State ===== */
  var data={business:'',entity:'',state:'',name:'',email:'',phone:'',country:'',plan:'growth',coname:'',coname2:''};
  var PRICES = CPF_PKG.prices || {};
  var PLAN_HEAD={starter:'Includes',growth:'Includes',premium:'Includes',ecom:'Includes',egrowth:'Includes',epremium:'Includes',castarter:'Includes',cagrowth:'Includes',canonres:'Includes'};
  var PLAN_FEATS={
    starter:['Company formation filing','Registered agent \u2014 1 year included','Live name availability check','Digital company documents','Business logo included','Ongoing compliance reminders (state-required)'],
    growth:['Federal tax account setup included','US business banking setup guidance','Set up to invoice & get paid in USD','Operating agreement template','Priority email support','Company formation filing','Registered agent \u2014 1 year included','Live name availability check','Digital company documents','Business logo included','Ongoing compliance reminders (state-required)'],
    premium:['Federal tax account setup included','Personal US tax setup support','Dedicated account manager','US business mail handling','Priority filing','US business banking setup guidance','Set up to invoice & get paid in USD','Operating agreement template','Priority email support','Company formation filing','Registered agent \u2014 1 year included','Live name availability check','Digital company documents','Business logo included','Ongoing compliance reminders (state-required)']
  ,ecom:['U.S. LLC formation \u2014 any state','Federal tax account setup guidance','Amazon seller documentation guidance','Shopify & payment-platform readiness','U.S. address support for seller applications','1\u20135 page business website','One dedicated setup advisor','Live name availability check','Business logo included']
  ,egrowth:['US business banking setup guidance','Set up to invoice & get paid in USD','Operating agreement template','Priority email support','U.S. LLC formation \u2014 any state','Federal tax account setup guidance','Amazon seller documentation guidance','Shopify & payment-platform readiness','U.S. address support for seller applications','1\u20135 page business website','One dedicated setup advisor','Live name availability check','Business logo included']
  ,epremium:['Personal US tax setup support','Dedicated account manager','US business mail handling','Priority filing','US business banking setup guidance','Set up to invoice & get paid in USD','Operating agreement template','Priority email support','U.S. LLC formation \u2014 any state','Federal tax account setup guidance','Amazon seller documentation guidance','Shopify & payment-platform readiness','U.S. address support for seller applications','1\u20135 page business website','One dedicated setup advisor','Live name availability check','Business logo included']
  ,castarter:['Federal incorporation and supported provincial jurisdictions','Certificate of Incorporation & Articles','NUANS name search (named companies)','Named or numbered company option','Digital document delivery','Filing status updates by email','Jurisdiction selection guidance','Next-step checklist after incorporation']
  ,cagrowth:['Everything in Starter Setup','Digital minute book & organizational resolutions','Corporate bylaws, share certificates & seal','Physical minute book binder kit','.ca domain name registration','Registered office address \u2014 12 months','Agent for service of process','First annual corporate return filing','Canadian tax account setup guidance','GST/HST setup guidance','Compliance reminders & deadline alerts','Legal templates (employment, NDA, shareholder & more)','Dedicated first-year support line']
  ,canonres:['Everything in Starter Setup','Non-resident founder onboarding call','Bank-ready document pack','Corporate resolution pack for banks','Jurisdiction fit consultation','Director residency guidance','Registered office for non-residents','Banking documentation guidance','Cross-border founder FAQ walkthrough','Dedicated advisor check-in']
  };
  /* short explanations shown when the (i) next to a feature is tapped */
  var FEAT_INFO={
    'Company formation filing':'We prepare and file your formation documents with the state.',
    'Registered agent \u2014 1 year included':'Every U.S. company must have a registered agent to receive official state mail \u2014 your first year is included.',
    'Live name availability check':'We run a preliminary search of state records to see if your name looks available, and suggest backups if not.',
    'Digital company documents':'Your formation documents delivered digitally \u2014 download, share, and store them anywhere.',
    'Business logo included':'A clean starter logo for your new company \u2014 ready for invoices, your site, and marketplaces.',
    'Ongoing compliance reminders (state-required)':'We remind you ahead of state deadlines like annual reports, so your company stays in good standing.',
    'Federal tax account setup included':'We prepare and submit your company\u2019s federal tax account application. For foreign owners this typically takes 4\u20138 weeks.',
    'US business banking setup guidance':'Guidance and document preparation for your U.S. business banking applications. Each bank makes its own approval decision.',
    'Set up to invoice & get paid in USD':'Guidance to prepare your company for invoicing clients and receiving USD payments.',
    'Operating agreement template':'A ready-to-customize operating agreement \u2014 the internal document banks and platforms often ask to see.',
    'Priority email support':'Your questions answered ahead of the standard queue.',
    'Personal US tax setup support':'Support preparing your personal US tax paperwork, reviewed with you before submission.',
    'Dedicated account manager':'One person who knows your file and handles your setup end to end.',
    'US business mail handling':'A U.S. business mailing address, with your mail received and forwarded to you.',
    'Priority filing':'Your filing is prepared and submitted ahead of standard orders.',
    'U.S. LLC formation \u2014 any state':'We form your LLC in whichever state fits your business \u2014 all 50 states supported.',
    'Federal tax account setup guidance':'Step-by-step guidance for obtaining your company\u2019s federal tax account \u2014 typically 4\u20138 weeks for foreign owners.',
    'Amazon seller documentation guidance':'Guidance on the company documents Amazon typically requests from sellers. Amazon makes its own approval decisions.',
    'Shopify & payment-platform readiness':'We prepare your company for payment-platform applications such as Shopify or Stripe. Each platform decides its own approvals.',
    'U.S. address support for seller applications':'Address options suitable for marketplace and platform applications, where available.',
    '1\u20135 page business website':'A simple 1\u20135 page website for your business \u2014 enough to look established from day one.',
    'One dedicated setup advisor':'A single advisor guides your whole setup, start to finish, on WhatsApp.'
  };
  /* ===== Add-ons (checkout stage) =====================================
     ⚠️ PLACEHOLDER PRICES — set your real ones here (only these change).
     Plan prices ($299/$399/$699) live in PRICES above and are confirmed. */
  var PRICE_NUM = CPF_PKG.priceNum || {};
  var ADDONS = CPF_PKG.addons || {};
  /* which add-ons each plan offers (items already included in a tier are omitted) */
  var ADDON_LIST = CPF_PKG.addonList || {};
  /* free 1-year offer, pre-selected on every plan */
  var freeOffer={starter:true,growth:true,premium:true,ecom:true,egrowth:true,epremium:true};
  var selAdd={starter:{},growth:{},premium:{},ecom:{},egrowth:{},epremium:{}};
  /* Paste your serverless name-check URL here to enable LIVE availability checks (deploy name-check-worker.js). Empty = capture-only. */
  var NAME_CHECK_ENDPOINT = CPF_REST + 'name-check';
  /* Instant live check runs ONLY for these states (your 3 recommended). Any other state shows the "confirm before filing" note instead. OpenSOSData covers all 50 states, so you can expand this list anytime. */
  var LIVE_STATES=['wyoming','delaware','new_mexico'];

  var BIZ=[
    {v:'ecommerce',i:'fa-cart-shopping',b:'E-commerce / Amazon',rec:'llc'},
    {v:'freelancer',i:'fa-laptop-code',b:'Freelancer / Agency',rec:'llc'},
    {v:'saas',i:'fa-rocket',b:'SaaS / Tech startup',rec:'ccorp'},
    {v:'trading',i:'fa-globe',b:'Import / Export / Trading',rec:'llc'},
    {v:'holding',i:'fa-building-columns',b:'Holding / Investments',rec:'llc'},
    {v:'other',i:'fa-circle-question',b:'Something else / not sure',rec:'llc'}
  ];

  function flow(){
    if(data.formCountry==='ca'){
      // Canada path: country -> business -> province -> name -> details (no US entity/state)
      return ['country','business','state','name','details'];
    }
    if(data.entity==='other') return ['country','business','entity','details'];
    return ['country','business','entity','state','name','details'];
  }
  var idx=0;
  var maxReached=0;
  var submitted=false;

  function recEntity(){ var f=BIZ.filter(function(o){return o.v===data.business;})[0]; return f?f.rec:'llc'; }
  function recState(){ return data.entity==='ccorp'?'delaware':'wyoming'; }

  /* ===== Labels ===== */
  function bizLabel(){var m={};BIZ.forEach(function(o){m[o.v]=o.b;});return m[data.business]||'';}
  function entityLabel(){ if(data.formCountry==='ca') return 'Corporation'; return ({llc:'LLC',ccorp:'C-Corp',other:'Not sure / other'})[data.entity]||'';}
  function stateLabel(){ if(data.formCountry==='ca') return data.state?(PROV_NAME[data.state]||'Canada'):''; return STATE_NAME[data.state]||'';}
  function planLabel(){return ({starter:'Starter',growth:'Growth',premium:'Premium'})[data.plan]||'Growth';}
  function firstName(){return (data.name||'').split(' ')[0]||'there';}

  /* ===== Builders ===== */
  function optCard(o,sel,recV){
    var rec = (recV&&recV===o.v) ? '<span class="recflag">Recommended</span>' : '';
    return '<button type="button" class="opt'+(sel?' selected':'')+'" onclick="'+o.fn+'(\''+o.v+'\')">'
      +'<span class="oic"><i class="fa-solid '+o.i+'"></i></span>'
      +'<span class="otxt"><b>'+o.b+'</b>'+(o.d?'<span>'+o.d+'</span>':'')+'</span>'
      + rec
      +'<span class="opick"><i class="fa-solid fa-check"></i></span>'
      +'</button>';
  }

  var BIZHELP={
    ecommerce:{rec:'LLC \u00b7 Wyoming',intro:'Most Amazon and e-commerce sellers choose an LLC \u2014 it\u2019s simple, protects your personal assets, and works cleanly for non-residents. We usually pair it with Wyoming for low fees and privacy.',faq:[
      ['Do I need a U.S. company to sell on Amazon?','You can start without one, but a U.S. LLC makes it easier to use U.S. payment platforms, build trust with suppliers, and keep your business and personal money separate.'],
      ['How will I get paid?','We help prepare your company so you\u2019re ready to set up business banking and platforms like Stripe, PayPal, or Shopify. Each provider makes its own approval decision.'],
      ['Which state is best for sellers?','Wyoming is a common pick \u2014 low fees, no state income tax, and strong privacy. We confirm the right fit for you before filing.'],
      ['How long does setup take?','Forming the company is usually quick \u2014 often a few business days, depending on the state. Steps like banking take longer, and we set clear expectations upfront so there are no surprises.'],
      ['Can a non-resident really own a U.S. company?','Yes. Non-residents can fully own and run a U.S. LLC \u2014 no U.S. citizenship or local partner needed for most setups.']
    ]},
    freelancer:{rec:'LLC \u00b7 Wyoming',intro:'Freelancers and agencies usually pick an LLC \u2014 a credible U.S. entity to invoice clients, with liability protection and minimal paperwork.',faq:[
      ['Why form a U.S. LLC as a freelancer?','It lets you invoice U.S. clients professionally, get paid through familiar platforms, and separate your business from your personal finances.'],
      ['Do I need to be in the U.S.?','No. You can form and run your LLC fully remotely as a non-resident \u2014 no travel and no U.S. partner needed for many setups.'],
      ['Can I change structure later?','Yes. Many start as an LLC and restructure later if their needs change. We\u2019ll guide you.'],
      ['Does an LLC protect my personal assets?','An LLC is a separate legal entity, so in most cases it helps keep your personal assets separate from business liabilities \u2014 a key reason freelancers form one.'],
      ['Do I need my own U.S. address?','No. We help you cover the U.S. address and registered-agent requirement as part of setup, so you don\u2019t need a U.S. location of your own.']
    ]},
    saas:{rec:'C-Corp \u00b7 Delaware',intro:'If you plan to raise investment, a Delaware C-Corp is the standard \u2014 investors expect it and it lets you issue stock. Bootstrapping instead? An LLC may be simpler. We\u2019ll help you choose.',faq:[
      ['Why do startups choose a C-Corp?','It lets you issue shares to investors and team members, and it\u2019s the structure most U.S. investors and accelerators expect.'],
      ['Why Delaware?','Delaware\u2019s corporate law is well-established and familiar to investors, which is why most funded startups incorporate there.'],
      ['What if I\u2019m not raising money yet?','Then an LLC may be cheaper and simpler. You can pick LLC on the next screen, or talk to us first.'],
      ['Can I convert an LLC to a C-Corp later?','Yes. Some founders start as an LLC and convert when they raise. We\u2019ll explain the trade-offs so you start in the right place.'],
      ['Do investors really expect Delaware?','Most U.S. venture investors strongly prefer a Delaware C-Corp. If you plan to raise soon, starting there avoids a costly conversion later.']
    ]},
    trading:{rec:'LLC \u00b7 Wyoming',intro:'Trading businesses usually go with an LLC \u2014 a clean U.S. entity to work with suppliers, marketplaces, and partners, with liability protection.',faq:[
      ['How does a U.S. company help with trade?','It gives you a recognised U.S. presence to contract with suppliers and partners, and to access U.S. payment and banking options.'],
      ['Do I need a special license?','Most general trading doesn\u2019t, but some regulated goods do. We\u2019ll flag anything that needs extra steps before you commit.'],
      ['How soon can I operate?','Company formation is typically fast \u2014 often a few business days. Banking and platform approvals take longer and each provider decides on its own; we guide the full sequence.'],
      ['Can I run it entirely from abroad?','Yes. You can form and operate your U.S. trading company remotely as a non-resident \u2014 no travel required for many setups.']
    ]},
    holding:{rec:'LLC \u00b7 Wyoming',intro:'For holding assets or other businesses, an LLC is the simple, flexible choice \u2014 often in Wyoming for its privacy and low ongoing costs.',faq:[
      ['Can an LLC hold other companies or assets?','Yes \u2014 a holding LLC is a common, simple structure to own assets or stakes in other businesses.'],
      ['Why Wyoming for a holding company?','Low fees, strong privacy, and no state income tax make it a popular base for holding structures.'],
      ['Can one company hold several businesses?','Yes \u2014 a holding company can own stakes in multiple businesses or assets. We\u2019ll help structure it cleanly for your goals.'],
      ['How private is ownership in Wyoming?','Wyoming offers strong owner privacy, which is why it\u2019s popular for holding structures. We\u2019ll confirm what is and isn\u2019t public before filing.']
    ]},
    other:{rec:'A quick chat',intro:'Not sure where you fit? That\u2019s completely normal. Tell us a bit about your plans and we\u2019ll recommend the right structure \u2014 including S-Corp or nonprofit questions, which depend on your residency.',faq:[
      ['What if my business isn\u2019t listed?','We work with many business types. A quick chat is the fastest way to map the right setup for you.'],
      ['Can you help with S-Corp or a nonprofit?','S-Corp eligibility depends on U.S. residency, and nonprofits follow a different process. We\u2019ll tell you honestly what fits your situation.'],
      ['How does the consultation work?','You tell us your plans over WhatsApp or a quick call, and we recommend the structure, state, and next steps. It\u2019s a no-obligation conversation.'],
      ['What do you need from me to start?','Just your name, where you\u2019re based, and a short description of your business. We\u2019ll guide you through anything else step by step.']
    ]}
  };
  function bizIcon(){var f=BIZ.filter(function(o){return o.v===data.business;})[0];return f?f.i:'fa-cart-shopping';}
  var CA_BIZHELP={
    intro:'A Canadian corporation gives you a credible, well-recognized base to trade, invoice, and build trust with partners \u2014 set up entirely remotely. We help you choose federal or the right province and prepare everything.',
    faq:[
      ['Can I incorporate in Canada from abroad?','Yes. Non-residents can own and run a Canadian corporation remotely. We set you up in a province with no Canadian-resident-director requirement (British Columbia, Ontario, or Alberta), so you can be the sole director from abroad.'],
      ['Which province is best for me?','British Columbia is the most foreign-friendly and our usual recommendation; Ontario suits Canada\u2019s largest market; Alberta is a low-cost, business-friendly option. All three let you incorporate with no resident director.'],
      ['Do I need to travel to Canada?','No. Incorporation, registered office, and documents are handled remotely for many eligible setups.'],
      ['Will you help with tax setup and banking?','Yes \u2014 we guide you through Canadian and US tax account setup and prepare the documents banks ask for. Approval is decided by each institution.']
    ]
  };
  function bizHelpHtml(){
    if(!data.business){ return '<div class="help-empty"><i class="fa-solid fa-hand-pointer"></i><p>Choose your business type to see the recommended structure and how it works.</p></div>'; }
    var isCA=(data.formCountry==='ca');
    var h=BIZHELP[data.business]||BIZHELP.ecommerce;
    var recTxt = isCA ? 'Canadian corporation' : h.rec;
    var introTxt = isCA ? CA_BIZHELP.intro : h.intro;
    var faqSrc = isCA ? CA_BIZHELP.faq : h.faq;
    var faq=faqSrc.map(function(q){return '<details><summary>'+q[0]+'</summary><p>'+q[1]+'</p></details>';}).join('');
    return '<div class="help-h"><span class="hh-ic"><i class="fa-solid '+bizIcon()+'"></i></span> '+bizLabel()+'</div>'
      +'<div class="help-rec"><span>Likely best fit</span><b>'+recTxt+'</b></div>'
      +'<p class="help-intro">'+introTxt+'</p>'
      +'<div class="help-faq">'+faq+'</div>';
  }
  var STEPMETA={
    country:{q:'Where do you want to form your company?',sub:'Choose the country \u2014 we handle both, entirely remotely.'},
    business:{q:'What business are you starting?',sub:'We\u2019ll recommend the right structure for your business \u2014 tap an option to see how it works.'},
    entity:{q:'Which entity fits your business?',sub:'Most non-residents choose an LLC. A C-Corp suits raising investment.'},
    state:{q:'Where should we form your company?',sub:'Most non-residents pick Wyoming \u2014 or tap \u201cRecommend for me.\u201d'},
    name:{q:'Name your company',sub:'We\u2019ll check availability with the state and suggest backups if needed.'},
    details:{q:'Where should we send your setup details?',sub:'We\u2019ll confirm your plan and next steps \u2014 there\u2019s no payment now.'}
  };
  var STEPLABEL={country:'Country',business:'Business',entity:'Structure',state:'State',name:'Name',details:'Details'};
  var US_STATES=[['alabama','Alabama'],['alaska','Alaska'],['arizona','Arizona'],['arkansas','Arkansas'],['california','California'],['colorado','Colorado'],['connecticut','Connecticut'],['delaware','Delaware'],['florida','Florida'],['georgia','Georgia'],['hawaii','Hawaii'],['idaho','Idaho'],['illinois','Illinois'],['indiana','Indiana'],['iowa','Iowa'],['kansas','Kansas'],['kentucky','Kentucky'],['louisiana','Louisiana'],['maine','Maine'],['maryland','Maryland'],['massachusetts','Massachusetts'],['michigan','Michigan'],['minnesota','Minnesota'],['mississippi','Mississippi'],['missouri','Missouri'],['montana','Montana'],['nebraska','Nebraska'],['nevada','Nevada'],['new_hampshire','New Hampshire'],['new_jersey','New Jersey'],['new_mexico','New Mexico'],['new_york','New York'],['north_carolina','North Carolina'],['north_dakota','North Dakota'],['ohio','Ohio'],['oklahoma','Oklahoma'],['oregon','Oregon'],['pennsylvania','Pennsylvania'],['rhode_island','Rhode Island'],['south_carolina','South Carolina'],['south_dakota','South Dakota'],['tennessee','Tennessee'],['texas','Texas'],['utah','Utah'],['vermont','Vermont'],['virginia','Virginia'],['washington','Washington'],['west_virginia','West Virginia'],['wisconsin','Wisconsin'],['wyoming','Wyoming'],['dc','Washington, D.C.']];
  var STATE_NAME={}; US_STATES.forEach(function(s){STATE_NAME[s[0]]=s[1];});
  var CA_PROVS=[['ontario','Ontario'],['bc','British Columbia'],['alberta','Alberta'],['federal','Federal (Canada-wide)']];
  var CA_PROV_DESC={ontario:'Canada\u2019s largest market \u2014 where CrossPoint is based, no resident director needed',bc:'Foreign-friendly, lower ongoing costs \u2014 no resident director needed',alberta:'Business-friendly, low-cost \u2014 no resident director needed',federal:'Nationwide name protection \u2014 resident-director service, confirmed on WhatsApp'};
  var PROV_NAME={}; CA_PROVS.forEach(function(p){PROV_NAME[p[0]]=p[1];});
  function buildBusiness(){
    var left='';
    BIZ.forEach(function(o){ left+=optCard({v:o.v,i:o.i,b:o.b,fn:'pickBiz'}, data.business===o.v, ''); });
    return '<div class="step-2pane"><div class="pane-opts">'+left+'</div>'
      +'<div class="pane-help" id="bizHelp">'+bizHelpHtml()+'</div></div>';
  }

  function cmpCard(which){
    var rec=recEntity();
    if(which==='llc'){
      var sel=(data.entity==='llc');
      return '<div class="cmp'+(sel?' sel':'')+'" onclick="pickEntity(\'llc\')">'
        +(rec==='llc'?'<span class="cbadge">Recommended for you</span>':'')
        +'<div class="chead"><div class="cic"><i class="fa-solid fa-user"></i></div><h4>LLC</h4></div>'
        +'<p class="csub">For online businesses and more flexibility.</p>'
        +'<ul>'
        +'<li><i class="fa-solid fa-check y"></i> Limited liability protection for owners</li>'
        +'<li><i class="fa-solid fa-check y"></i> Simple to run \u2014 minimal formalities</li>'
        +'<li><i class="fa-solid fa-check y"></i> Unlimited owners (U.S. &amp; international)</li>'
        +'<li><i class="fa-solid fa-check y"></i> Less paperwork, no mandatory meetings</li>'
        +'<li><i class="fa-solid fa-xmark n"></i> Cannot issue stock to investors</li>'
        +'<li><i class="fa-solid fa-xmark n"></i> Owned by members, not shareholders</li>'
        +'</ul><span class="cradio"></span></div>';
    } else {
      var sel2=(data.entity==='ccorp');
      return '<div class="cmp'+(sel2?' sel':'')+'" onclick="pickEntity(\'ccorp\')">'
        +(rec==='ccorp'?'<span class="cbadge">Recommended for you</span>':'')
        +'<div class="chead"><div class="cic"><i class="fa-solid fa-building"></i></div><h4>C-Corp</h4></div>'
        +'<p class="csub">For startups raising money from investors.</p>'
        +'<ul>'
        +'<li><i class="fa-solid fa-check y"></i> Limited liability protection for owners</li>'
        +'<li><i class="fa-solid fa-check y"></i> Raise capital by issuing stock</li>'
        +'<li><i class="fa-solid fa-check y"></i> Owned by shareholders</li>'
        +'<li><i class="fa-solid fa-xmark n"></i> More operating requirements</li>'
        +'<li><i class="fa-solid fa-xmark n"></i> More paperwork \u2014 annual meetings &amp; minutes</li>'
        +'</ul><span class="cradio"></span></div>';
    }
  }
  function buildEntity(){
    var otherSel=(data.entity==='other');
    return '<div class="cmp-grid">'+cmpCard('llc')+cmpCard('ccorp')+'</div>'
      +'<button type="button" class="cmp-other'+(otherSel?' sel':'')+'" onclick="pickEntity(\'other\')">'
      +'<span class="oic"><i class="fa-solid fa-circle-question"></i></span>'
      +'<span><b>Something else \u2014 S-Corp, nonprofit, or not sure</b><span>Talk to an advisor and we\u2019ll find the right fit for you.</span></span>'
      +'</button>';
  }

  function buildState(){
    var rec=recState();
    var opts=[
      {v:'wyoming',i:'fa-mountain-sun',b:'Wyoming',d:'Low fees, privacy, no state income tax',fn:'pickState'},
      {v:'delaware',i:'fa-landmark',b:'Delaware',d:'The standard for startups raising money',fn:'pickState'},
      {v:'new_mexico',i:'fa-shield-halved',b:'New Mexico',d:'Lowest cost, no annual report',fn:'pickState'}
    ];
    var recName=STATE_NAME[rec]||'';
    var html='<div class="opts">';
    html+='<button type="button" class="opt opt-action" onclick="recommendState()">'
      +'<span class="oic oic-action"><i class="fa-solid fa-wand-magic-sparkles"></i></span>'
      +'<span class="otxt"><b>Recommend for me</b><span>We\u2019ll pick the best-fit state \u2014 '+recName+' for your setup</span></span>'
      +'<span class="opick-go"><i class="fa-solid fa-arrow-right"></i></span></button>';
    opts.forEach(function(o){ html+=optCard(o, data.state===o.v, rec); });
    html+='</div>';
    html+='<div class="w-allstates"><label for="stateAll">Prefer a specific state?</label>'
      +'<select id="stateAll" onchange="pickStateSel(this.value)">'+stateOptions()+'</select></div>';
    return html;
  }
  function stateOptions(){
    var o='<option value="">Choose another state\u2026</option>';
    US_STATES.forEach(function(s){ o+='<option value="'+s[0]+'"'+(data.state===s[0]?' selected':'')+'>'+s[1]+'</option>'; });
    return o;
  }
  function recommendState(){ data.state=recState(); idx++; render(); }
  function pickStateSel(v){ if(!v)return; data.state=v; render(); }

  function checkNameLive(){
    var nm=(data.coname||'').trim(); if(!data.state && data.formCountry!=='ca'){ data.state=recState(); } var out=document.getElementById('nameResult'); var btn=document.getElementById('nameCheckBtn');
    if(nm.length<2){ if(out){out.className='name-result info';out.innerHTML='<i class="fa-solid fa-circle-info"></i> Enter a name first.';} return; }
    window.__ncLast=nm.toLowerCase()+'|'+data.state;
    if(btn){ btn.disabled=true; btn.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> Checking\u2026'; }
    window.__ncReq=(window.__ncReq||0)+1; var reqId=window.__ncReq; window.__ncBusy=true;
    if(out){ out.className='name-result info'; out.innerHTML='<i class="fa-solid fa-magnifying-glass"></i> Checking \u201c'+attr(nm)+'\u201d with the '+(STATE_NAME[data.state]||'state')+' registry \u2014 this is a live state search and can take up to a minute\u2026'; }
    fetch(NAME_CHECK_ENDPOINT,{method:'POST',headers:cpfHeaders(),body:JSON.stringify({company_name:nm,state:data.state,suffix:(data.entity==='ccorp'?'Inc.':'LLC')})})
      .then(function(r){return r.json();})
      .then(function(d){
        if(btn){ btn.disabled=false; btn.innerHTML='<i class="fa-solid fa-magnifying-glass"></i> Check availability'; }
        if(!out) return;
        if(reqId!==window.__ncReq){return;} window.__ncBusy=false;
        var st=STATE_NAME[data.state]||'your state';
        var shown=(d&&d.checked)?d.checked:(nm+' '+((data.entity==='ccorp')?'Inc.':'LLC'));
        if(d&&d.available===true){ out.className='name-result ok'; out.innerHTML='<i class="fa-solid fa-circle-check"></i> <b>\u201c'+attr(shown)+'\u201d is available in '+st+'</b> \u2014 live state search passed. We\u2019ll re-confirm at filing.'; }
        else if(d&&d.available===false){ out.className='name-result taken'; out.innerHTML='<i class="fa-solid fa-circle-xmark"></i> <b>\u201c'+attr(shown)+'\u201d is already registered in '+st+'.</b> Try a backup \u2014 we\u2019ll help you find one.'; }
        else { out.className='name-result info'; out.innerHTML='<i class="fa-solid fa-triangle-exclamation"></i> The state\u2019s site didn\u2019t answer our live check just now \u2014 we\u2019ll verify \u201c'+attr(shown)+'\u201d manually and confirm before filing, at no extra cost.'; }
        updateTmNote();
      })
      .catch(function(){ if(reqId!==window.__ncReq){return;} window.__ncBusy=false; if(btn){btn.disabled=false;btn.innerHTML='<i class="fa-solid fa-magnifying-glass"></i> Check availability';} if(out){out.className='name-result info';out.innerHTML='<i class="fa-solid fa-circle-info"></i> Couldn\u2019t check just now \u2014 we\u2019ll verify it for you.';} });
  }
  var TM_MARKS=['amazon','apple','google','alphabet','microsoft','meta','facebook','instagram','whatsapp','youtube','netflix','disney','tesla','spacex','openai','chatgpt','nvidia','intel','samsung','sony','nike','adidas','puma','walmart','costco','alibaba','aliexpress','tiktok','snapchat','telegram','twitter','linkedin','reddit','uber','lyft','airbnb','shopify','ebay','etsy','paypal','stripe','visa','mastercard','amex','american express','western union','moneygram','coinbase','binance','mcdonalds','starbucks','coca cola','cocacola','pepsi','red bull','redbull','gucci','prada','chanel','louis vuitton','rolex','cartier','hermes','zara','ikea','lego','ferrari','lamborghini','porsche','bmw','mercedes','toyota','honda','fedex','dhl','oracle','salesforce','adobe','canva','spotify','xbox','playstation','nintendo','marvel','pixar'];
  function tmHit(nm){
    var s=' '+String(nm||'').toLowerCase().replace(/[^a-z0-9]+/g,' ').replace(/\s+/g,' ').trim()+' ';
    for(var i=0;i<TM_MARKS.length;i++){ if(s.indexOf(' '+TM_MARKS[i]+' ')>-1){ return TM_MARKS[i].replace(/(^|\s)[a-z]/g,function(c){return c.toUpperCase();}); } }
    return null;
  }
  function updateTmNote(){
    var out=document.getElementById('nameResult')||document.getElementById('nameTips'); if(!out) return;
    var el=document.getElementById('tmNote');
    var hit=tmHit((data.coname||'').trim());
    if(!hit){ if(el) el.style.display='none'; return; }
    if(!el){ el=document.createElement('div'); el.id='tmNote'; el.className='name-result'; out.parentNode.insertBefore(el, out.nextSibling); }
    el.style.cssText='display:block;margin-top:10px;background:#FEF6E7;border:1px solid #E8C989;color:#7A5A12;';
    el.innerHTML='<i class="fa-solid fa-triangle-exclamation"></i> \u201c'+hit+'\u201d is a well-known trademark. Even if the registry shows the name as available, famous brand names can trigger trademark disputes \u2014 and banks and payment platforms often reject them. We recommend a distinctive name.';
  }
  function effState(){ if(!data.state && data.formCountry!=='ca'){ data.state=recState(); } return data.state; }
  function nameAutoCheck(){
    clearTimeout(window.__ncT);
    var nm=(data.coname||'').trim();
    var stt=effState();
    if(nm.length<3 || !NAME_CHECK_ENDPOINT || LIVE_STATES.indexOf(stt)===-1) return;
    window.__ncT=setTimeout(function(){
      var key=nm.toLowerCase()+'|'+data.state;
      var out=document.getElementById('nameResult');
      var showing=out&&(out.className.indexOf(' ok')>-1||out.className.indexOf('taken')>-1);
      if(window.__ncLast===key && showing) return;
      if(window.__ncBusy) return;
      window.__ncLast=key;
      checkNameLive();
    },900);
  }
  window.nameAutoCheck=nameAutoCheck; window.updateTmNote=updateTmNote;

  function openReview(pl){
    data.plan=pl;
    var el=document.getElementById('rvwOverlay');
    if(!el){ el=document.createElement('div'); el.id='rvwOverlay'; document.body.appendChild(el); }
    el.innerHTML=buildReview(pl);
    el.style.display='block'; el.scrollTop=0;
    document.body.style.overflow='hidden';
  }
  function closeReview(){
    var el=document.getElementById('rvwOverlay');
    if(el) el.style.display='none';
    document.body.style.overflow='';
  }
  function buildReview(pl){
    var isCa = pl.indexOf('ca')===0;
    var suffix = isCa ? 'Inc.' : (data.entity==='ccorp' ? 'Inc.' : 'LLC');
    var co = (data.coname||'').trim();
    var coLine = (co ? attr(co)+' '+suffix : 'Your new company') + (stateLabel() ? ' \u2014 ' + stateLabel() : '');
    var planPrice = PRICE_NUM[pl]||0;
    var feats = (PLAN_FEATS[pl]||[]).map(function(f){return '<li>'+f+'</li>';}).join('');
    var rows = '';
    rows += '<div class="rvw-row"><div class="rvw-rtop"><b>'+planName(pl)+(isCa?' \u2014 Canada incorporation':' \u2014 U.S. company formation')+'</b>'
          + '<span class="rvw-price">$'+fmt(planPrice)+'</span></div>'
          + '<ul class="rvw-feats">'+feats+'</ul></div>';
    if(freeOffer[pl]){
      rows += '<div class="rvw-row"><div class="rvw-rtop"><b>Domain, Email &amp; Business Website</b>'
            + '<span class="rvw-price"><span class="rvw-free">FREE</span><span class="rvw-psub">\u00b7 1st year</span></span></div>'
            + '<ul class="rvw-feats"><li>Domain name with privacy</li><li>Business email address</li><li>1\u20135 page business website</li></ul>'
            + '<small style="color:#8A93A3">Free for the first year, then $79/yr. Cancel anytime.</small></div>';
    }
    var s=selAdd[pl]||{}; var onReq=[];
    (ADDON_LIST[pl]||[]).forEach(function(id){
      var a=ADDONS[id]; if(!a||!s[id]) return;
      if(a.flag){ onReq.push(a); return; }
      rows += '<div class="rvw-row"><div class="rvw-rtop"><b>'+a.n+'</b><span class="rvw-price">+$'+fmt(a.price)+'</span></div></div>';
    });
    onReq.forEach(function(a){
      rows += '<div class="rvw-row"><div class="rvw-rtop"><b>'+a.n+' <span class="rvw-tag">on request</span></b>'
            + '<span class="rvw-price" style="color:#8A93A3">$'+fmt(a.price)+'<span class="rvw-psub">\u00b7 not charged today</span></span></div>'
            + '<small style="color:#8A93A3;display:block">Reviewed with you first \u2014 billed separately only if you proceed.</small></div>';
    });
    var payable = payableTotal(pl);
    var onreqSum = onReq.reduce(function(t,a){return t+a.price;},0);
    var feeRow = isCa
      ? '<div class="rvw-srow"><span>Government filing fee<small>Already included in your package price.</small></span><span class="rvw-free">Included</span></div>'
      : '<div class="rvw-srow"><span>State filing fee<small>Paid to the state. We confirm the exact amount with you before filing \u2014 nothing is charged for it today.</small></span><span style="color:#8A93A3">at filing</span></div>';
    return ''
      +'<div class="rvw-wrap">'
      +'<button type="button" class="rvw-back" onclick="closeReview()">\u2190 Back to your plans</button>'
      +'<div class="rvw-h1">Checkout</div>'
      +'<div class="rvw-sub">You\u2019re one step from making <b>'+coLine+'</b> official.</div>'
      +'<div class="rvw-grid">'
      +'<div class="rvw-card"><div class="rvw-step"><span class="n">1</span> Your order</div>'
      + rows
      +'<div class="rvw-sumhead">Order summary</div>'
      +'<div class="rvw-srow"><span>Subtotal due today</span><span class="rvw-price">$'+fmt(payable)+'</span></div>'
      +(onreqSum>0?'<div class="rvw-srow"><span>On-request items<small>Reviewed with you first \u2014 billed separately only if you proceed.</small></span><span style="color:#8A93A3">$'+fmt(onreqSum)+' after review</span></div>':'')
      + feeRow
      +'<div class="rvw-total"><span>Total due today</span><span>$'+fmt(payable)+' <small style="font-weight:500;color:#7A5A12">USD</small></span></div>'
      +(onreqSum>0?'<div style="text-align:right;font-size:.72rem;color:#8A93A3;margin-top:6px">Order total incl. on-request items: <b style="color:var(--navy)">$'+fmt(payable+onreqSum)+'</b> + state fees</div>':'')
      +'</div>'
      +'<div class="rvw-side">'
      +'<div class="rvw-card"><h4>Purchase with confidence</h4><ul>'
      +'<li>Secure card payment via Stripe</li>'
      +'<li>Free website, domain &amp; business email \u2014 1 year</li>'
      +'<li>Real-human support on WhatsApp, 7 days a week</li>'
      +'<li>No hidden fees \u2014 state fees confirmed before filing</li>'
      +'<li>Cancellation terms on our <a href="/refund-policy" target="_blank" rel="noopener">Refund Policy</a></li>'
      +'</ul></div>'
      +'<div class="rvw-card"><h4 style="margin:0 0 12px;color:var(--navy)">Ready to make it official?</h4>'
      +'<div class="rvw-total" style="margin-top:0"><span>Total due today</span><span>$'+fmt(payable)+'</span></div>'
      +(onreqSum>0?'<div style="font-size:.72rem;color:#8A93A3;margin:4px 0 8px;text-align:right">+ $'+fmt(onreqSum)+' after review \u00b7 order total $'+fmt(payable+onreqSum)+'</div>':'')
      +'<button type="button" class="btn btn-gold rvw-pay" onclick="startCheckout(\''+pl+'\',this)"><i class="fa-solid fa-lock"></i> Continue to secure payment \u2192</button>'
      +'<div class="rvw-note">Secure card payment by Stripe. Government and state fees are never charged on this page.</div>'
      +'</div></div></div></div>';
  }

  function buildName(){
    var isCa = data.formCountry==='ca';
    var suffix = isCa ? 'Inc.' : (data.entity==='ccorp' ? 'Inc.' : 'LLC');
    var stName = isCa ? (PROV_NAME[data.state]||'') : (STATE_NAME[data.state]||'your state');
    var filingAuth = isCa
      ? (data.state==='federal' ? 'Corporations Canada' : (stName ? 'the province of '+stName : 'your province'))
      : ('the state of '+stName);
    return '<div class="w-field"><label>Your preferred company name</label>'
      +'<div class="name-input"><input id="f-coname" type="text" autocomplete="off" placeholder="e.g. Summit Trading" value="'+attr(data.coname)+'" oninput="onNameInput();nameAutoCheck();updateTmNote()"/><span class="name-suffix">'+suffix+'</span></div>'
      +'<span class="hint">We\u2019ll add \u201c'+suffix+'\u201d for you. Pick 1\u20132 backups in case your first choice is taken.</span></div>'
      +'<div class="w-field"><label>Backup name (optional)</label><input id="f-coname2" type="text" autocomplete="off" placeholder="e.g. Summit Global" value="'+attr(data.coname2)+'" oninput="data.coname2=this.value"/></div>'
      +'<div id="nameTips" class="name-tips"></div>'
      + ((NAME_CHECK_ENDPOINT && LIVE_STATES.indexOf(data.state)>-1) ? '<button type="button" class="btn-check" id="nameCheckBtn" onclick="checkNameLive()"><i class="fa-solid fa-magnifying-glass"></i> Check availability</button><div id="nameResult" class="name-result"></div>' : '')
      +'<div class="name-note"><i class="fa-solid fa-circle-info"></i> Final availability is confirmed with '+filingAuth+' before filing. We check it for you and suggest alternatives if anything\u2019s taken.</div>';
  }
  function onNameInput(){
    var el=document.getElementById('f-coname'); if(!el) return;
    data.coname=el.value;
    renderNameTips(el.value);
    renderSummary();
  }
  function renderNameTips(v){
    var el=document.getElementById('nameTips'); if(!el) return;
    v=(v||'').trim();
    if(!v){ el.innerHTML=''; return; }
    var t='';
    if(/\b(llc|l\.?l\.?c|inc|incorporated|corp|corporation)\b/i.test(v)){
      t+='<div class="tip warn"><i class="fa-solid fa-circle-exclamation"></i> No need to add \u201cLLC\u201d or \u201cInc\u201d \u2014 we add the correct ending for you.</div>';
    }
    if(/\b(bank|banc|insurance|insur|trust|university|college|attorney|lawyer|federal|reserve|treasury|fbi|cia|nasa)\b/i.test(v)){
      t+='<div class="tip warn"><i class="fa-solid fa-triangle-exclamation"></i> Some words (like \u201cbank\u201d or \u201cinsurance\u201d) may need extra approval from the registry \u2014 we\u2019ll let you know.</div>';
    }
    if(!t){
      t='<div class="tip ok"><i class="fa-solid fa-circle-check"></i> Name format looks okay. '
        +(document.getElementById('nameCheckBtn')
            ? 'Click \u201cCheck availability\u201d to run a preliminary registry search.'
            : 'We\u2019ll confirm availability with the registry before filing.')
        +'</div>';
    }
    el.innerHTML=t;
  }
  function buildDetails(){
    return '<div class="w-field"><label>Full name <span class="lbl-req">(required)</span></label><input id="f-name" type="text" autocomplete="name" placeholder="Your name" value="'+attr(data.name)+'"/></div>'
      +'<div class="w-field"><label>Email <span class="lbl-req">(required)</span></label><input id="f-email" type="email" autocomplete="email" placeholder="you@email.com" value="'+attr(data.email)+'"/></div>'
      +'<div class="w-field"><label>WhatsApp number <span class="lbl-opt">(optional)</span></label><input id="f-phone" type="tel" autocomplete="tel" placeholder="+__ ___ ___ ____" value="'+attr(data.phone)+'"/><span class="hint">Best way for us to send your setup steps.</span></div>'
      +'<div class="w-field"><label>Country you live in <span class="lbl-opt">(optional)</span></label><select id="f-country" class="w-select" autocomplete="country-name">'+countryOptions(data.country)+'</select></div>'
      +'<input id="cpf_hp_field" name="cpf_hp_field" type="text" tabindex="-1" autocomplete="off" aria-hidden="true" style="display:none"/>'
      +'<div class="w-err" id="f-err"></div>';
  }
  function attr(s){return (s||'').replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');}

  function buildStep(key){
    if(key==='country')return buildCountry();
    if(key==='business')return buildBusiness();
    if(key==='entity')return buildEntity();
    if(key==='state')return (data.formCountry==='ca') ? buildProvince() : buildState();
    if(key==='name')return buildName();
    if(key==='details')return buildDetails();
    return '';
  }

  function buildCountry(){
    var opts=[
      {v:'us',i:'fa-flag-usa',b:'United States',d:'LLC or C-Corp \u2014 popular with founders worldwide',fn:'pickCountry'},
      {v:'ca',i:'fa-leaf',b:'Canada',d:'Federal or provincial corporation',fn:'pickCountry'}
    ];
    var html='<div class="opts">';
    opts.forEach(function(o){ html+=optCard(o, data.formCountry===o.v, ''); });
    html+='</div>';
    return html;
  }
  function pickCountry(v){ data.formCountry=v; data.state=''; if(v==='ca'){ data.entity='cacorp'; } else if(data.entity==='cacorp'){ data.entity=''; } idx++; render(); }

  var PROV_HELP={
    _default:{rec:'Ontario',intro:'For non-residents, we set you up in a province with no Canadian-resident-director requirement \u2014 so you can own and run your corporation from abroad. Ontario, British Columbia, and Alberta are all equally open on that front. We usually recommend Ontario \u2014 it\u2019s Canada\u2019s largest market and where CrossPoint is based, which keeps your registered office and our office in the same province.',faq:[
      ['Do I need a Canadian resident director?','Not in the provinces we use. British Columbia, Ontario, and Alberta have no resident-director requirement, so you can be the sole director from outside Canada.'],
      ['Which province should I pick?','All three \u2014 Ontario, British Columbia, and Alberta \u2014 let a non-resident be sole director, so none needs a Canadian partner. We usually recommend Ontario: it\u2019s Canada\u2019s largest market and where CrossPoint is based, so your registered office and our office sit in the same province. BC and Alberta are great if you prefer lower ongoing costs. We\u2019ll confirm the best fit for you.'],
      ['What about a registered office?','Every corporation needs a registered office in its province \u2014 we provide this as part of your setup, so you don\u2019t need a Canadian address of your own.'],
      ['Can I operate across Canada?','Yes. A provincial corporation can register to do business in other provinces as you grow. We\u2019ll guide you if and when you need that.'],
      ['What about Federal incorporation?','Federal gives nationwide name protection but requires a Canadian-resident director. We can provide one through our nominee service for a fee \u2014 you keep ownership and control through your shares. We confirm the fee and terms on WhatsApp before you commit.']
    ]},
    bc:{rec:'British Columbia',intro:'British Columbia has no resident-director requirement, efficient online filing, and lower ongoing costs \u2014 a strong choice if you\u2019re optimizing for cost and privacy rather than a physical Canadian base.'},
    ontario:{rec:'Ontario',intro:'Ontario is Canada\u2019s largest provincial economy, home to Toronto \u2014 and where CrossPoint is based. It removed its resident-director requirement in 2021, so you can be sole director from abroad. Because our office and your registered office would both be in Ontario, it\u2019s our usual recommendation.'},
    alberta:{rec:'Alberta',intro:'Alberta is a business-friendly, low-cost jurisdiction with no resident-director requirement \u2014 a practical base for international founders.'},
    federal:{rec:'Federal (with resident-director service)',intro:'Federal incorporation gives nationwide name protection and requires at least one Canadian-resident director. We can provide a resident director through our nominee service for a fee. You remain the owner and control the business through your shares; the nominee holds a director\u2019s legal duties and acts only on lawful instructions. Because the fee and terms depend on your setup, we confirm them with you on WhatsApp before you commit.'}
  };
  function provHelpHtml(){
    var base=PROV_HELP._default;
    var sel=data.state?PROV_HELP[data.state]:null;
    var intro=sel?sel.intro:base.intro;
    var rec=sel?sel.rec:base.rec;
    var faq=base.faq.map(function(q){return '<details><summary>'+q[0]+'</summary><p>'+q[1]+'</p></details>';}).join('');
    return '<div class="help-h"><span class="hh-ic"><i class="fa-solid fa-leaf"></i></span> Incorporating in Canada</div>'
      +'<div class="help-rec"><span>Best fit for non-residents</span><b>'+rec+'</b></div>'
      +'<p class="help-intro">'+intro+'</p>'
      +'<div class="help-faq">'+faq+'</div>';
  }
  function buildProvince(){
    var left='';
    CA_PROVS.forEach(function(p){
      left+=optCard({v:p[0],i:'fa-location-dot',b:p[1],d:CA_PROV_DESC[p[0]]||'Incorporate in this province',fn:'pickProv'}, data.state===p[0], (p[0]==='ontario'?'ontario':''));
    });
    return '<div class="step-2pane"><div class="pane-opts">'+left+'</div>'
      +'<div class="pane-help" id="provHelp">'+provHelpHtml()+'</div></div>';
  }
  function pickProv(v){
    data.state=v;
    document.getElementById('wcontent').innerHTML=buildProvince();
    var d=!stepReady('state');[document.getElementById('wnext'),document.getElementById('wnextB')].forEach(function(b){if(b)b.disabled=d;});
    renderSummary();
    updateTopProgress();
  }

  /* ===== Render / nav ===== */
  function render(){
    var f=flow(); if(idx<0)idx=0; if(idx>=f.length)idx=f.length-1;
    if(idx>maxReached)maxReached=idx;
    var key=f[idx];
    if(key==='entity' && !data.entity) data.entity=recEntity();
    if(key==='state' && !data.state && data.formCountry!=='ca') data.state=recState();
    var meta=STEPMETA[key]||{q:'',sub:''};
    var qtxt=meta.q, subtxt=meta.sub;
    if(key==='state' && data.formCountry==='ca'){ qtxt='Where in Canada should we incorporate?'; subtxt='Federal covers all of Canada \u2014 or choose a province.'; }
    if(key==='name' && data.formCountry==='ca'){ subtxt='We\u2019ll check availability with the registry and suggest backups if needed.'; }
    document.getElementById('wq').textContent=qtxt;
    document.getElementById('wsub').textContent=subtxt;
    var head=document.querySelector('.w-head'); if(head) head.style.display='flex';
    document.getElementById('wstep').style.display='block';
    document.getElementById('wsub').style.display='block';
    var wp=document.querySelector('.w-progress'); if(wp) wp.style.display='block';
    document.getElementById('wcontent').innerHTML=buildStep(key);
    if(key==='name') renderNameTips(data.coname);
    var n=idx+1, t=f.length;
    document.getElementById('wstep').textContent='Step '+n+' of '+t;
    document.getElementById('wbar').style.width=Math.round((n/t)*100)+'%';
    document.getElementById('wback').hidden=(idx===0);
    document.getElementById('wfoot').style.display='flex';
    var txt=(key==='details')?'See My Recommended Plan <i class="fa-solid fa-arrow-right"></i>':'Continue <i class="fa-solid fa-arrow-right"></i>';
    var dis=(key==='details')?false:!stepReady(key);
    [document.getElementById('wnext'),document.getElementById('wnextB')].forEach(function(b){if(b){b.style.display='inline-flex';b.innerHTML=txt;b.disabled=dis;}});
    updateTopProgress();
    try{window.scrollTo({top:0,behavior:'smooth'});}catch(_){}
  }

  function updateTopProgress(done) {
    var f=flow();
    var el=document.getElementById('progressSteps');
    if(el){
      var h='';
      for(var i=0;i<f.length;i++){
        var comp = done || i<idx;
        var st = comp ? 'completed' : (i===idx ? 'active' : '');
        var inner = comp ? '<i class="fa-solid fa-check"></i>' : String(i+1);
        var canClick = (i<=maxReached) && (done || i!==idx);
        h+='<div class="step '+st+(canClick?' st-click':'')+'"'+(canClick?' onclick="gotoStep('+i+')"':'')+'><div class="step-num">'+inner+'</div><div class="step-label">'+((f[i]==='state'&&data.formCountry==='ca')?'Province':(STEPLABEL[f[i]]||''))+'</div></div>';
      }
      el.innerHTML=h;
    }
    var bar=document.getElementById('top-progress-bar');
    if(bar) bar.style.width=(done?100:Math.round(((idx+1)/f.length)*100))+'%';
    renderSummary();
  }
  function gotoStep(n){ if(n>=0 && n<=maxReached){ idx=n; render(); } }
  function renderSummary(){
    var el=document.getElementById('wSummary'); if(!el) return;
    var c='';
    if(data.business){ c+=sumChip(bizIcon(), bizLabel()); }
    if(data.entity){ c+=sumChip(data.entity==='ccorp'?'fa-building':(data.entity==='other'?'fa-circle-question':'fa-briefcase'), entityLabel()); }
    if(data.state && data.entity!=='other'){ c+=sumChip('fa-location-dot', stateLabel()); }
    var cn=(data.coname||'').trim();
    if(cn){ var suf=(data.formCountry==='ca'||data.entity==='ccorp')?'Inc.':'LLC'; c+=sumChip('fa-file-signature', attr(cn)+' '+suf); }
    el.innerHTML = c ? ('<span class="sum-label">Your setup</span>'+c) : '';
    el.style.display = c ? 'flex' : 'none';
  }
  function sumChip(icon,text){ return '<span class="sum-chip"><i class="fa-solid '+icon+'"></i>'+text+'</span>'; }
  function stepReady(key){
    if(key==='business')return !!data.business;
    if(key==='entity')return !!data.entity;
    if(key==='state')return !!data.state;
    if(key==='name')return true;
    return true;
  }
  function pickBiz(v){
    data.business=v; data.entity=''; data.state='';
    document.getElementById('wcontent').innerHTML=buildBusiness();
    var d=!stepReady('business');[document.getElementById('wnext'),document.getElementById('wnextB')].forEach(function(b){if(b)b.disabled=d;});
    updateTopProgress();
  }
  function pickEntity(v){ data.entity=v; data.state=''; render(); }
  function pickState(v){ data.state=v; render(); }
  function wBack(){ if(idx>0){ idx--; render(); } }
  function wNext(){
    var key=flow()[idx];
    if(key==='details'){ submitSetup(); return; }
    if(!stepReady(key)) return;
    idx++; render();
  }

  /* ===== Submit ===== */
  function countryOptions(sel){
    var common=["Bahrain", "Bangladesh", "Egypt", "India", "Kuwait", "Nigeria", "Oman", "Pakistan", "Philippines", "Qatar", "Saudi Arabia", "Turkey", "United Arab Emirates", "United Kingdom"];
    var world=["Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cabo Verde", "Cambodia", "Cameroon", "Canada", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", "Congo (DRC)", "Congo (Republic)", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czechia", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini", "Ethiopia", "Fiji", "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Ivory Coast", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kosovo", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Korea", "North Macedonia", "Norway", "Oman", "Pakistan", "Palau", "Palestine", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Qatar", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Korea", "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"];
    function grp(label,arr){return '<optgroup label="'+label+'">'+arr.map(function(c){return '<option'+(c===sel?' selected':'')+'>'+c+'</option>';}).join('')+'</optgroup>';}
    return '<option value="">Select your country (optional)</option>'+grp('Most common',common)+grp('All countries (A\u2013Z)',world);
  }
  function submitSetup(){
    if(submitted){ showResult(); return; }
    var name=(document.getElementById('f-name').value||'').trim();
    var email=(document.getElementById('f-email').value||'').trim();
    var phone=(document.getElementById('f-phone').value||'').trim();
    var country=(document.getElementById('f-country').value||'').trim();
    var err=document.getElementById('f-err');
    function fail(m){err.textContent=m;err.style.display='block';}
    if(!name){return fail('Please enter your name.');}
    if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){return fail('Please enter a valid email address.');}
    if(phone && phone.replace(/[^0-9]/g,'').length<7){return fail('That WhatsApp number looks too short \u2014 or leave it blank.');}
    err.style.display='none';
    data.name=name;data.email=email;data.phone=phone;data.country=country;
    var b1=document.getElementById('wnext'),b2=document.getElementById('wnextB');
    [b1,b2].forEach(function(b){if(b){b.disabled=true;b.innerHTML='Sending\u2026';}});
    var isCA=(data.formCountry==='ca');
    var payload={
      name:name, email:email, phone:phone, country_of_residence:country,
      formCountry:isCA?'ca':'us',
      business_destination:isCA?'Canada':'United States',
      business_type:bizLabel(),
      company_name:(data.coname||'').trim(),
      backup_name:(data.coname2||'').trim(),
      company_structure:entityLabel(),
      jurisdiction:(data.entity==='other')?'':stateLabel(),
      recommended_package:(data.entity==='other')?'Advisor':(isCA?'Non-Resident Setup':'Growth'),
      form_type:isCA?'guided_setup_ca':'guided_setup_us',
      source:'start-wizard',
      cpf_hp_field:(document.getElementById('cpf_hp_field')||{}).value||'',
      _subject:(isCA?'Canada':'U.S.')+' guided setup lead \u2014 '+name
    };
    try{ Object.assign(payload, cpfGetTracking()); }catch(_){}
    try{ payload.submission_id = (window.crypto&&crypto.randomUUID)?crypto.randomUUID():('sid-'+Date.now()+'-'+Math.random().toString(16).slice(2)); }
    catch(_){ payload.submission_id='sid-'+Date.now(); }

    function cpfResetButtons(){ [b1,b2].forEach(function(b){if(b){b.disabled=false;b.innerHTML='See My Recommended Plan <i class="fa-solid fa-arrow-right"></i>';}}); }
    var MSG_BACKUP_OK   = 'Our primary system was temporarily unavailable, but your request was sent through our backup channel. Please save this message and contact us on WhatsApp if you do not hear from us.';
    var MSG_BACKUP_FAIL = 'We could not send your request. Please try again or contact us on WhatsApp.';
    var MSG_REJECTED    = 'We could not accept that submission. Please check your details and try again, or message us on WhatsApp.';
    function cpfFallbackThenReport(){ cpfResetButtons(); fail(MSG_BACKUP_FAIL); return Promise.resolve(); }

    /* Primary: durable local-first capture on Hostinger. Success + conversion gate on THIS response. */
    var _ctl,_to;
    try{ _ctl=new AbortController(); _to=setTimeout(function(){try{_ctl.abort();}catch(_){}} ,25000); }catch(_){}
    fetch(CPF_REST + 'lead',{method:'POST',headers:cpfHeaders(),body:JSON.stringify(cpfLeadPayload(payload)),signal:_ctl?_ctl.signal:undefined})
      .then(function(r){
        if(_to)clearTimeout(_to);
        var st=r.status;
        return r.json().then(function(d){return {d:d,st:st};},function(){return {d:null,st:st};});
      })
      .then(function(res){
        var d=res.d, st=res.st;

        /* 1. Stored, including a legitimate duplicate. Success. No fallback. */
        if(d&&d.ok&&d.entry){
          submitted=true;
          try{ LEAD_ID=String(d.entry||''); }catch(_){}
          if(!d.duplicate){ try{gtag('event','conversion',{send_to:GADS_ID+'/'+LABEL_FORM,transport_type:'beacon'});window.uetq=window.uetq||[];window.uetq.push('event','submit_lead_form',{});}catch(_){} }
          showResult();
          return;
        }

        /* 2. Honeypot drop. Show success to the bot, store nothing, send nothing, fire nothing. */
        if(d&&d.ok&&!d.entry){ submitted=true; showResult(); return; }

        /* 3. Deliberate server rejection: 4xx (400/403/405/415/422/429) or an explicit ok:false.
              lead.php meant to refuse this. Forwarding it to Formspree would bypass that decision. */
        if((st>=400&&st<500)||(d&&d.ok===false)){ cpfResetButtons(); fail(MSG_REJECTED); return; }

        /* 4. Genuine server-side failure: 5xx, unparseable body, or ok:true with stored:false. */
        return cpfFallbackThenReport();
      })
      .catch(function(){
        /* Network failure or the 25s abort. NOTE: a timeout does NOT prove storage failed --
           lead.php may have stored the lead and then spent time on notifications. submission_id
           is carried on both payloads so the two records can be reconciled afterwards. */
        if(_to)clearTimeout(_to);
        return cpfFallbackThenReport();
      });
  }

  /* ===== WhatsApp pre-fill ===== */
  function buildWaPlan(po){
    var pl=po||data.plan;
    var isCA=(data.formCountry==='ca');
    var opener = isCA ? (data.state==='federal'
        ? 'Hi CrossPoint, I\u2019d like to incorporate Federally in Canada and ask about the resident-director service.'
        : 'Hi CrossPoint, I\u2019d like to start my Canada company setup.')
      : 'Hi CrossPoint, I\u2019d like to start my U.S. company setup.';
    var L=[opener];
    if(data.coname) L.push('\u2022 Company name: '+data.coname+(data.coname2?' (backup: '+data.coname2+')':''));
    L.push('\u2022 Business: '+bizLabel());
    L.push('\u2022 Entity: '+entityLabel());
    L.push('\u2022 '+(isCA?'Province':'State')+': '+stateLabel());
    L.push('\u2022 Plan: '+planName(pl)+' ('+PRICES[pl]+')');
    if(freeOffer[pl]) L.push('\u2022 Included offer: Domain + email + website (free 1st year, then $79/yr)');
    var s=selAdd[pl]||{}, chosen=[];
    (ADDON_LIST[pl]||[]).forEach(function(id){ if(s[id]&&ADDONS[id]) chosen.push('   \u2013 '+ADDONS[id].n+' (+$'+ADDONS[id].price+')'); });
    if(chosen.length){ L.push('\u2022 Add-ons:'); chosen.forEach(function(c){ L.push(c); }); }
    L.push('\u2022 Estimated total: $'+fmt(planTotal(pl))+(isCA?(data.state==='federal'?' \u2014 government fee charged separately; resident-director service confirmed separately':' \u2014 government fee charged separately'):' + state fee'));
    L.push('\u2022 Country I live in: '+data.country);
    L.push('\u2022 Name: '+data.name);
    L.push('Please confirm my plan and next steps.');
    return 'https://wa.me/14374346994?text='+encodeURIComponent(L.join('\n'));
  }
  function buildWaAdvisor(){
    var L=['Hi CrossPoint, I\u2019d like help choosing the right U.S. setup.'];
    L.push('\u2022 Business: '+bizLabel());
    L.push('\u2022 Looking at: S-Corp / nonprofit / not sure');
    L.push('\u2022 Country I live in: '+data.country);
    L.push('\u2022 Name: '+data.name);
    L.push('Please advise on the best structure for me.');
    return 'https://wa.me/14374346994?text='+encodeURIComponent(L.join('\n'));
  }

  /* ===== Plan selection ===== */
  function selectPlan(p){ data.plan=p; renderResultPlans(); }
  function planName(pl){ return ({starter:'Starter',growth:'Growth',premium:'Premium',ecom:'E-Commerce Launch',egrowth:'Growth',epremium:'Premium',castarter:'Starter Setup',cagrowth:'Growth Setup',canonres:'Non-Resident Setup'})[pl]||'Growth'; }
  function planTotal(pl){ var t=PRICE_NUM[pl]||0; var s=selAdd[pl]||{}; (ADDON_LIST[pl]||[]).forEach(function(id){ if(s[id]&&ADDONS[id]) t+=ADDONS[id].price; }); return t; }
  /* what Stripe charges today: base plan + selected add-ons EXCEPT "on request" ones */
  function payableTotal(pl){ var t=PRICE_NUM[pl]||0; var s=selAdd[pl]||{}; (ADDON_LIST[pl]||[]).forEach(function(id){ var a=ADDONS[id]; if(s[id]&&a&&!a.flag) t+=a.price; }); return t; }
  function fmt(n){ return (n||0).toLocaleString('en-US'); }
  function toggleAddon(pl,id){ selAdd[pl][id]=!selAdd[pl][id]; data.plan=pl; renderResultPlans(); }
  function toggleOffer(pl){ freeOffer[pl]=!freeOffer[pl]; data.plan=pl; renderResultPlans(); }
  function offerHtml(v){
    return '<div class="pcol-offerhead">Special offer</div>'
      +'<div class="pcol-offer'+(freeOffer[v]?' on':'')+'" onclick="toggleOffer(\''+v+'\')">'
      +'<span class="offbox"><i class="fa-solid fa-check"></i></span>'
      +'<span class="offtxt"><b>Domain, Email &amp; Website</b><small>Free for 1 year, then $79/yr.<sup>*</sup></small></span>'
      +'<button type="button" class="pinfo" data-tip="'+attr('Free for the first year, then $79/yr \u2014 cancel anytime. Full terms on our Refund & Cancellation page.')+'" onmouseenter="featTip(event,this)" onmouseleave="hideTip()" onclick="featTipClick(event,this)" aria-label="Offer terms"><i class="fa-solid fa-circle-info"></i></button>'
      +'</div>';
  }
  function addonsHtml(v){
    var sel=selAdd[v]||{}, ecom=(data.business==='ecommerce'||data.business==='trading'), rows='';
    (ADDON_LIST[v]||[]).forEach(function(id){
      var a=ADDONS[id]; if(!a) return;
      var on=!!sel[id], suggest=(a.ecom&&ecom&&!on);
      rows+='<div class="addrow'+(on?' on':'')+(suggest?' suggest':'')+'" onclick="toggleAddon(\''+v+'\',\''+id+'\')">'
        +'<span class="addbox"><i class="fa-solid fa-check"></i></span>'
        +'<span class="addtxt">'+a.n+(a.flag?'<span class="addflag">'+a.flag+'</span>':'')+(suggest?'<small>Suggested for your business</small>':'')+'</span>'
        +'<span class="addpr">+$'+a.price+'</span></div>';
    });
    return '<div class="pcol-addhead">Add-ons</div><div class="pcol-addons">'+rows+'</div>';
  }
  function totalHtml(v){
    var full=planTotal(v), pay=payableTotal(v);
    var feeText=(data.formCountry==='ca')?'government fee charged separately':'+ state fees';
    var h='<div class="pcol-total"><span>Your total</span><b>$'+fmt(full)+'<small>'+feeText+'</small></b></div>';
    if(CHECKOUT_ENDPOINT && pay!==full){
      h+='<div style="font-size:.66rem;color:var(--muted);text-align:right;margin-top:4px">Due today: <b style="color:var(--navy)">$'+fmt(pay)+'</b> \u00b7 \u201con request\u201d items billed after review</div>';
    }
    return h;
  }

  function planCard(v,b,d,rec){
    var sel=(data.plan===v);
    return '<button type="button" class="opt'+(rec?' rec-opt':'')+(sel?' selected':'')+'" onclick="selectPlan(\''+v+'\')">'
      +(rec?'<span class="recbadge">Most popular</span>':'')
      +'<span class="otxt"><b>'+b+'</b><span>'+d+'</span></span>'
      +'<span class="price"><b>'+PRICES[v]+'</b><small>+ state fees</small></span>'
      +'</button>';
  }
  function renderResultPlans(){
    document.getElementById('planList').innerHTML = (data.formCountry==='ca')
      ? pcol('castarter','Starter Setup','Launch essentials',false)
        + pcol('canonres','Non-Resident Setup','Recommended',true)
        + pcol('cagrowth','Growth Setup','Most complete',false)
      : (data.business==='ecommerce')
      ? pcol('ecom','E-Commerce Launch','For sellers',false)
        + pcol('egrowth','Growth','Most popular',true)
        + pcol('epremium','Premium','Best value',false)
      : pcol('starter','Starter','Standard',false)
        + pcol('growth','Growth','Most popular',true)
        + pcol('premium','Premium','Best value',false);
    var anyPay = CHECKOUT_ENDPOINT || PAYLINKS.us.starter||PAYLINKS.us.growth||PAYLINKS.us.premium;
    document.getElementById('payNote').textContent = anyPay
      ? 'Service fees are charged securely by Stripe. Government and state fees are confirmed and collected separately before filing, and are non-refundable once filing begins.'
      : 'Pick a plan to continue on WhatsApp \u2014 we\u2019ll confirm your plan and exact fees, then send a secure payment link. No payment is taken on this page.';
  }
  function pcol(v,name,badge,featured){
    var link=PAYLINKS.us[v];
    var cta;
    var federalCA = (data.formCountry==='ca' && data.state==='federal');
    if(federalCA){
      // Federal needs the resident-director (nominee) service — price confirmed on WhatsApp, no checkout
      cta='<a class="pcol-cta" href="'+buildWaPlan(v)+'"><i class="fa-brands fa-whatsapp"></i> Confirm on WhatsApp</a>';
    } else if(CHECKOUT_ENDPOINT){
      cta='<button type="button" class="pcol-cta pay" onclick="openReview(\''+v+'\')"><i class="fa-solid fa-lock"></i> Checkout</button>';
    } else if(link){
      cta='<a class="pcol-cta pay" href="'+link+'"><i class="fa-solid fa-lock"></i> Checkout</a>';
    } else {
      cta='<a class="pcol-cta" href="'+buildWaPlan(v)+'"><i class="fa-brands fa-whatsapp"></i> Choose '+name+'</a>';
    }
    var feats='';
    PLAN_FEATS[v].forEach(function(fx){
      var tip=FEAT_INFO[fx];
      var isRoll=/^Everything in /.test(fx);
      feats+='<div class="pfeat'+(isRoll?' pfeat-roll':'')+'"><i class="fa-solid fa-check"></i><span>'+fx+'</span>'
        +(tip?'<button type="button" class="pinfo" data-tip="'+attr(tip)+'" onmouseenter="featTip(event,this)" onmouseleave="hideTip()" onclick="featTipClick(event,this)" aria-label="More about this"><i class="fa-solid fa-circle-info"></i></button>':'')
        +'</div>';
    });
    return '<div class="pcol p-'+v+(featured?' feat':'')+'">'
      +'<div class="pcol-namerow"><div class="pcol-name">'+name+'</div>'+(badge?'<span class="pcol-badge b-'+v+(featured?' hot':'')+'">'+badge+'</span>':'')+'</div>'
      +'<div class="pcol-price"><b>'+PRICES[v]+'</b><small>'+(data.formCountry==='ca'?(data.state==='federal'?'+ government fee \u00b7 + resident-director service':'+ government fee'):'+ state fees')+'</small></div>'
      +cta
      +'<div class="pcol-inc">'+PLAN_HEAD[v]+'</div>'
      +'<div class="pcol-feats">'+feats+'</div>'
      + offerHtml(v) + addonsHtml(v) + totalHtml(v)
      +'</div>';
  }

  function showResult(){
    var foot=document.getElementById('wfoot'); foot.style.display='none';
    var head=document.querySelector('.w-head'); if(head) head.style.display='none';
    document.getElementById('wstep').style.display='none';
    document.getElementById('wsub').style.display='none';
    var wp=document.querySelector('.w-progress'); if(wp) wp.style.display='none';
    updateTopProgress(true);
    var ws=document.getElementById('wSummary'); if(ws) ws.style.display='none';
    var c=document.getElementById('wcontent');
    if(data.entity==='other'){
      c.innerHTML='<div class="rok"><i class="fa-solid fa-comments"></i></div>'
        +'<h3 class="result-h">Thanks, '+attr(firstName())+'!</h3>'
        +'<p class="result-sub">S-Corp and nonprofit setups depend on your residency and goals, so the fastest path is a quick chat. We\u2019ve saved your details and we\u2019ll help you choose the right structure.</p>'
        +'<div class="pay-row">'
        +'<a class="btn btn-wa" href="'+buildWaAdvisor()+'"><i class="fa-brands fa-whatsapp"></i> Talk to an advisor on WhatsApp</a>'
        +'<a class="btn btn-outline" href="https://calendly.com/crosspointformations/intro-call">Book a free 15-min call</a>'
        +'</div>';
    } else {
      c.innerHTML='<div class="result-head">'
        +'<div class="result-head-l"><span class="rok-sm"><i class="fa-solid fa-check"></i></span>'
        +'<h3 class="result-h">Your setup is ready, '+attr(firstName())+'!</h3></div>'
        +'<div class="result-head-r"><span class="w-recap recap-inline"><i class="fa-solid fa-flag"></i>&nbsp; '+(data.formCountry==='ca'?'Canada &nbsp;&bull;&nbsp; ':'')+(data.coname?attr(data.coname)+' &nbsp;&bull;&nbsp; ':'')+bizLabel()+' &nbsp;&bull;&nbsp; '+entityLabel()+' &nbsp;&bull;&nbsp; '+stateLabel()+'</span>'+'<a class="btn btn-outline rh-call" href="https://calendly.com/crosspointformations/intro-call"><i class="fa-solid fa-calendar-check"></i> Book a Call</a></div>'
        +'</div>'
        +'<div class="plan-list" id="planList"></div>'
        +'<p class="pay-note" id="payNote"></p>'
        +'<p class="offer-foot">* Cancel anytime. Domain, Email &amp; Website are free for the first year, then renew at $79/year. Full auto-renewal and refund terms are on our <a href="/refund-policy/">Refund &amp; Cancellation</a> page.</p>';
      renderResultPlans();
    }
    try{window.scrollTo({top:0,behavior:'smooth'});}catch(_){}
  }

  /* ===== Stripe dynamic checkout ===== */
  var CHECKOUT_BUSY=false;
  var upsellShown=false;
  function startCheckout(pl,btn){
    if(pl==='starter' && data.formCountry!=='ca' && CHECKOUT_ENDPOINT && !upsellShown){ upsellShown=true; openUpsell(); return; }
    doCheckout(pl,btn);
  }
  function openUpsell(){
    var st=PRICE_NUM.starter||0, g=PRICE_NUM.growth||0, delta=g-st;
    var ov=document.createElement('div'); ov.className='up-ov'; ov.id='upOv';
    ov.innerHTML='<div class="up-card" onclick="event.stopPropagation()">'
      +'<button type="button" class="up-x" onclick="closeUpsell()" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>'
      +'<div class="up-kick">Before you check out</div>'
      +'<h3 class="up-h">Most founders pick Growth \u2014 here\u2019s why</h3>'
      +'<p class="up-p">Starter covers your company formation. Growth adds the pieces most non-residents need to actually operate \u2014 tax setup guidance, banking documentation guidance, and getting set up to invoice in USD.</p>'
      +'<div class="up-grid">'
      +'<div class="up-box"><div class="ub-t">Starter \u2014 your pick</div><div class="ub-pr">$'+fmt(st)+'<small> + state fees</small></div>'
      +'<ul><li><i class="fa-solid fa-check"></i><span>Company formation filing</span></li>'
      +'<li><i class="fa-solid fa-check"></i><span>Registered agent \u2014 1 year</span></li>'
      +'<li><i class="fa-solid fa-check"></i><span>Company documents &amp; logo</span></li></ul></div>'
      +'<div class="up-box hl"><span class="ub-badge">Most popular</span><div class="ub-t">Growth</div><div class="ub-pr">$'+fmt(g)+'<small> + state fees</small></div>'
      +'<ul><li><i class="fa-solid fa-check"></i><span>Everything in Starter</span></li>'
      +'<li><i class="fa-solid fa-check"></i><span>Tax registration guidance included</span></li>'
      +'<li><i class="fa-solid fa-check"></i><span>Banking documentation guidance</span></li>'
      +'<li><i class="fa-solid fa-check"></i><span>Invoicing &amp; getting paid in USD</span></li>'
      +'<li><i class="fa-solid fa-check"></i><span>Operating agreement + priority support</span></li></ul></div>'
      +'</div>'
      +'<div class="up-delta"><i class="fa-solid fa-tag"></i> Just $'+fmt(delta)+' more than Starter \u2014 with the full operating layer included.</div>'
      +'<div class="up-note">Any add-ons you selected carry over.</div>'
      +'<div class="up-btns">'
      +'<button type="button" class="btn btn-outline" onclick="upsellStay(this)">No thanks \u2014 continue with Starter</button>'
      +'<button type="button" class="btn btn-gold" onclick="upsellUp(this)"><i class="fa-solid fa-lock"></i> Upgrade to Growth</button>'
      +'</div></div>';
    ov.addEventListener('click',closeUpsell);
    document.body.appendChild(ov);
    document.addEventListener('keydown',upEsc);
  }
  function upEsc(e){ if(e.key==='Escape') closeUpsell(); }
  function closeUpsell(){ var o=document.getElementById('upOv'); if(o) o.remove(); document.removeEventListener('keydown',upEsc); }
  function upsellStay(btn){ doCheckout('starter',btn); }
  function upsellUp(btn){
    var src=selAdd.starter||{};
    (ADDON_LIST.growth||[]).forEach(function(id){ if(src[id]) selAdd.growth[id]=true; });
    data.plan='growth';
    doCheckout('growth',btn);
  }
  function doCheckout(pl,btn){
    if(!CHECKOUT_ENDPOINT||CHECKOUT_BUSY) return;
    CHECKOUT_BUSY=true; data.plan=pl;
    var old=btn?btn.innerHTML:'';
    if(btn){btn.disabled=true;btn.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> Opening secure checkout\u2026';}
    var s=selAdd[pl]||{}, ids=[];
    (ADDON_LIST[pl]||[]).forEach(function(id){ if(s[id]) ids.push(id); });
    try{ sessionStorage.setItem('cpx_setup', JSON.stringify({data:data,selAdd:selAdd,freeOffer:freeOffer,plan:pl,total:planTotal(pl),payable:payableTotal(pl)})); }catch(_){}
    fetch(CHECKOUT_ENDPOINT,{method:'POST',headers:cpfHeaders(),body:JSON.stringify({
      plan:pl, addons:ids, offer:!!freeOffer[pl],
      name:data.name, email:data.email, phone:data.phone, country:data.country,
      company_name:(data.coname||'').trim(), backup_name:(data.coname2||'').trim(),
      entity:entityLabel(), state:stateLabel(), business:bizLabel(),
      state_key:(data.state||''), form_country:(data.formCountry||'us'), lead_id:LEAD_ID
    })})
    .then(function(r){ return r.json(); })
    .then(function(d){
      if(d && d.url){ location.href=d.url; }
      else { throw new Error((d&&d.error)||'checkout_failed'); }
    })
    .catch(function(){
      CHECKOUT_BUSY=false;
      if(btn){btn.disabled=false;btn.innerHTML=old;}
      var n=document.getElementById('payNote');
      if(n){ n.innerHTML='<span style="color:#C2453B;font-weight:600">Couldn\u2019t open secure checkout just now.</span> Nothing was charged. Continue on WhatsApp and we\u2019ll send you a secure payment link for your '+planName(pl)+' plan:'
        +'<a class="btn btn-wa" style="display:inline-flex;margin-top:10px" href="'+buildWaPlan(pl)+'"><i class="fa-brands fa-whatsapp"></i> Continue on WhatsApp</a>'; }
    });
  }
  function restoreSnapshot(){
    try{
      var raw=sessionStorage.getItem('cpx_setup'); if(!raw) return null;
      var s=JSON.parse(raw);
      if(s&&s.data){ data=s.data; if(s.selAdd)selAdd=s.selAdd; if(s.freeOffer)freeOffer=s.freeOffer; if(s.plan)data.plan=s.plan; return s; }
    }catch(_){}
    return null;
  }
  function showPaidPending(){
    submitted=true;
    var foot=document.getElementById('wfoot'); if(foot) foot.style.display='none';
    var head=document.querySelector('.w-head'); if(head) head.style.display='none';
    var a1=document.getElementById('wstep'); if(a1) a1.style.display='none';
    var a2=document.getElementById('wsub'); if(a2) a2.style.display='none';
    var wp=document.querySelector('.w-progress'); if(wp) wp.style.display='none';
    var wsum=document.getElementById('wSummary'); if(wsum) wsum.style.display='none';
    try{ updateTopProgress(true); }catch(_){}
    var wa='https://wa.me/14374346994?text='+encodeURIComponent('Hi CrossPoint, I just completed a payment and want to confirm my setup and next steps.');
    var c=document.getElementById('wcontent');
    if(c){ c.innerHTML='<div class="rok" style="color:#C5962B"><i class="fa-solid fa-clock"></i></div>'
      +'<h3 class="result-h">Confirming your payment\u2026</h3>'
      +'<p class="result-sub">Thanks! We\u2019re confirming your payment with our processor. If you\u2019ve been charged, your setup is underway \u2014 message us on WhatsApp and we\u2019ll confirm your details and next steps right away.</p>'
      +'<div class="pay-row"><a class="btn btn-wa" href="'+wa+'"><i class="fa-brands fa-whatsapp"></i> Message us on WhatsApp</a></div>'; }
    try{ history.replaceState(null,'',location.pathname); }catch(_){}
    try{ window.scrollTo({top:0,behavior:'smooth'}); }catch(_){}
  }
  function showPaid(snap,verified,vr){
    submitted=true;
    var foot=document.getElementById('wfoot'); foot.style.display='none';
    var head=document.querySelector('.w-head'); if(head) head.style.display='none';
    document.getElementById('wstep').style.display='none';
    document.getElementById('wsub').style.display='none';
    var wp=document.querySelector('.w-progress'); if(wp) wp.style.display='none';
    updateTopProgress(true);
    var ws=document.getElementById('wSummary'); if(ws) ws.style.display='none';
    var hasSnap=!!(snap&&snap.data);
    var pl=hasSnap?snap.plan:'';
    var waMsg = hasSnap
      ? 'Hi CrossPoint, I\u2019ve just completed payment for my '+planName(pl)+' plan'+(data.coname?' \u2014 '+data.coname:'')+'. Please confirm my setup and next steps.'
      : 'Hi CrossPoint, I\u2019ve just completed my payment. Please confirm my setup and next steps.';
    var wa='https://wa.me/14374346994?text='+encodeURIComponent(waMsg);
    var onreqNote=(hasSnap&&snap.payable!==snap.total)?' Any \u201con request\u201d items you selected are reviewed first and billed separately once confirmed.':'';
    document.getElementById('wcontent').innerHTML=
      '<div class="rok"><i class="fa-solid fa-circle-check"></i></div>'
      +'<h3 class="result-h">Payment received'+(data.name?', '+attr(firstName()):'')+' \u2014 your setup has started</h3>'
      +'<p class="result-sub">Thank you! Stripe has emailed your receipt'+(data.email?' to '+attr(data.email):'')+'. Our team is starting your filing now and will message you on WhatsApp with next steps.'+onreqNote+'</p>'
      +(hasSnap&&data.coname?'<div style="text-align:center"><div class="w-recap" style="margin:0 0 16px"><i class="fa-solid fa-flag-usa"></i>&nbsp; '+attr(data.coname)+' &nbsp;&bull;&nbsp; '+planName(pl)+' plan</div></div>':'')
      +'<div class="pay-row">'
      +'<a class="btn btn-wa" href="'+wa+'"><i class="fa-brands fa-whatsapp"></i> Message us on WhatsApp</a>'
      +'<a class="btn btn-outline" href="https://calendly.com/crosspointformations/intro-call">Book your onboarding call</a>'
      +'</div>'
      +'<p class="pay-note">Government and state filing fees are confirmed with you separately before filing \u2014 exactly as shown during checkout.</p>';
    try{
      if(verified){
        var __amt=0, __cur='USD', __txn='';
        if(vr && typeof vr.amount_total==='number' && vr.amount_total>0){ __amt=vr.amount_total/100; }
        else if(hasSnap && typeof snap.payable==='number'){ __amt=snap.payable; }
        if(vr && vr.currency){ __cur=String(vr.currency).toUpperCase(); }
        if(vr && vr.lead_id){ __txn=String(vr.lead_id); }
        if(LABEL_PURCHASE){ gtag('event','conversion',{send_to:GADS_ID+'/'+LABEL_PURCHASE,value:__amt,currency:__cur,transaction_id:__txn,transport_type:'beacon'}); }
        window.uetq=window.uetq||[]; window.uetq.push('event','purchase',{revenue_value:__amt,currency:__cur});
      }
    }catch(_){}
    try{ sessionStorage.removeItem('cpx_setup'); }catch(_){}
    try{ clearProgress(); }catch(_){}
    try{ history.replaceState(null,'',location.pathname); }catch(_){}
    try{ window.scrollTo({top:0,behavior:'smooth'}); }catch(_){}
  }

  /* ===== feature info tooltip ===== */
  var tipEl=null, tipFor=null;
  function featTip(ev,btn){
    ev.stopPropagation();
    if(!tipEl){
      tipEl=document.createElement('div'); tipEl.id='featTip'; document.body.appendChild(tipEl);
      document.addEventListener('click',hideTip);
      window.addEventListener('scroll',hideTip,true);
      window.addEventListener('resize',hideTip);
    }
    tipFor=btn;
    tipEl.textContent=btn.getAttribute('data-tip')||'';
    tipEl.style.display='block';
    var r=btn.getBoundingClientRect(), tw=tipEl.offsetWidth, th=tipEl.offsetHeight;
    var left=r.right+10;                                   /* right of the icon */
    if(left+tw>window.innerWidth-8) left=r.left-tw-10;     /* flip left only if no room */
    if(left<8) left=8;
    var top=r.top+r.height/2-th/2;
    top=Math.max(8,Math.min(top,window.innerHeight-th-8));
    tipEl.style.left=left+'px'; tipEl.style.top=top+'px';
  }
  function featTipClick(ev,btn){ ev.stopPropagation(); if(tipFor!==btn) featTip(ev,btn); }
  function hideTip(){ if(tipEl) tipEl.style.display='none'; tipFor=null; }

  /* ===== AI chat widget ===== */
  var cpHist=[], cpBusy=false, cpOpened=false;
  function cpChatToggle(){
    var p=document.getElementById('cpChatPanel'), b=document.getElementById('cpChatBtn');
    var open=!p.classList.contains('open');
    p.classList.toggle('open',open); b.classList.toggle('open',open);
    if(open && !cpOpened){
      cpOpened=true;
      cpAdd('bot','Hi! I\u2019m the CrossPoint assistant. Ask me anything about forming your U.S. company \u2014 plans, states, timelines, documents.');
    }
    if(open){ var i=document.getElementById('cpInput'); if(i) setTimeout(function(){i.focus();},60); }
  }
  function cpAdd(role,text){
    var m=document.getElementById('cpMsgs'); if(!m) return null;
    var d=document.createElement('div');
    d.className='cpx-m '+(role==='user'?'user':'bot');
    d.textContent=text;
    m.appendChild(d); m.scrollTop=m.scrollHeight;
    return d;
  }
  function cpChip(t){ var i=document.getElementById('cpInput'); if(i){i.value=t;} cpSend(); }
  function cpSend(){
    if(cpBusy) return;
    var i=document.getElementById('cpInput'); var t=(i&&i.value||'').trim();
    if(!t) return;
    i.value='';
    var chips=document.getElementById('cpChips'); if(chips) chips.style.display='none';
    cpAdd('user',t);
    cpHist.push({role:'user',content:t});
    if(cpHist.length>12) cpHist=cpHist.slice(-12);
    cpBusy=true;
    var sb=document.getElementById('cpSendBtn'); if(sb) sb.disabled=true;
    var m=document.getElementById('cpMsgs');
    var ty=document.createElement('div'); ty.className='cpx-typing'; ty.innerHTML='<i></i><i></i><i></i>';
    m.appendChild(ty); m.scrollTop=m.scrollHeight;
    fetch(CHAT_ENDPOINT,{method:'POST',headers:cpfHeaders(),body:JSON.stringify({messages:cpHist})})
      .then(function(r){return r.json();})
      .then(function(d){
        ty.remove(); cpBusy=false; if(sb) sb.disabled=false;
        if(d && d.reply){
          cpHist.push({role:'assistant',content:d.reply});
          cpAdd('bot',d.reply);
        } else { throw new Error('chat failed'); }
      })
      .catch(function(){
        ty.remove(); cpBusy=false; if(sb) sb.disabled=false;
        var el=cpAdd('bot','');
        el.innerHTML='I couldn\u2019t reply just now \u2014 please message us on <a href="https://wa.me/14374346994" target="_blank" rel="noopener">WhatsApp</a> and a real person will help right away.';
      });
  }

  /* ===== save & resume progress (localStorage, no account needed) ===== */
  var CPX_PKEY='cpx_progress', CPX_TTL=14*24*60*60*1000; /* keep 14 days */
  function cpxDefaultData(){ return {business:'',entity:'',state:'',name:'',email:'',phone:'',country:'',plan:'growth',coname:'',coname2:''}; }
  function cpxDefaultFree(){ return {starter:true,growth:true,premium:true,ecom:true,egrowth:true,epremium:true}; }
  function cpxDefaultSel(){ return {starter:{},growth:{},premium:{},ecom:{},egrowth:{},epremium:{}}; }
  function saveProgress(){
    try{
      if(submitted) return;
      var fn=document.getElementById('f-name'),fe=document.getElementById('f-email'),fp=document.getElementById('f-phone'),fc=document.getElementById('f-country');
      if(fn&&fn.value)data.name=fn.value; if(fe&&fe.value)data.email=fe.value;
      if(fp&&fp.value)data.phone=fp.value; if(fc&&fc.value)data.country=fc.value;
      var meaningful=(idx>0)||data.business||data.country||data.coname||data.name||data.email;
      if(!meaningful) return;
      var dsave=JSON.parse(JSON.stringify(data)); dsave.name='';dsave.email='';dsave.phone='';dsave.country=''; /* never persist contact PII */
      localStorage.setItem(CPX_PKEY,JSON.stringify({v:1,ts:Date.now(),data:dsave,selAdd:selAdd,freeOffer:freeOffer,idx:idx,maxReached:maxReached}));
    }catch(_){}
  }
  function clearProgress(){ try{ localStorage.removeItem(CPX_PKEY); }catch(_){} }
  function loadProgress(){
    try{
      var raw=localStorage.getItem(CPX_PKEY); if(!raw) return null;
      var s=JSON.parse(raw); if(!s||!s.data) return null;
      if(s.ts&&(Date.now()-s.ts)>CPX_TTL){ clearProgress(); return null; }
      var d=s.data, meaningful=(s.idx>0)||d.business||d.country||d.coname||d.name||d.email;
      return meaningful?s:null;
    }catch(_){ return null; }
  }
  function cpxStartOver(){
    clearProgress();
    data=cpxDefaultData(); selAdd=cpxDefaultSel(); freeOffer=cpxDefaultFree();
    idx=0; maxReached=0; submitted=false;
    var nt=document.getElementById('cpxResume'); if(nt&&nt.parentNode) nt.parentNode.removeChild(nt);
    render();
  }
  function showResumeNotice(){
    try{
      var host=document.getElementById('wcontent'); if(!host||document.getElementById('cpxResume')) return;
      var bar=document.createElement('div'); bar.id='cpxResume';
      bar.setAttribute('style','display:flex;align-items:center;gap:10px;flex-wrap:wrap;justify-content:space-between;background:#EAF4FF;border:1px solid #BBD9F5;color:#0F2A47;border-radius:12px;padding:11px 14px;margin:0 0 16px;font-size:.94rem;line-height:1.35');
      bar.innerHTML='<span><i class="fa-solid fa-clock-rotate-left" style="margin-right:8px;color:#1E6FBF"></i>Welcome back \u2014 we picked up where you left off.</span><button type="button" onclick="cpxStartOver()" style="background:none;border:none;color:#1E6FBF;font-weight:600;cursor:pointer;text-decoration:underline;font-size:.94rem;padding:0">Start over</button>';
      host.parentNode.insertBefore(bar,host);
    }catch(_){}
  }

  /* init */
  (function(){
    if(CHAT_ENDPOINT){ var cb=document.getElementById('cpChatBtn'); if(cb) cb.hidden=false; }
    var q=location.search||'';
    if(q.indexOf('paid=1')>-1){
      var __sid=''; try{ __sid=(new URLSearchParams(q)).get('session_id')||''; }catch(_){}
      showPaidPending();
      if(__sid && VERIFY_ENDPOINT){
        fetch(VERIFY_ENDPOINT,{method:'POST',headers:cpfHeaders(),body:JSON.stringify({session_id:__sid})})
          .then(function(r){return r.json();})
          .then(function(v){ if(v&&v.ok&&v.paid){ showPaid(restoreSnapshot(), true, v); } })
          .catch(function(){});
      }
      return;
    }
    if(q.indexOf('canceled=1')>-1){
      var s2=restoreSnapshot();
      if(s2){
        submitted=true; showResult();
        var n=document.getElementById('payNote');
        if(n){ n.innerHTML='Checkout canceled \u2014 nothing was charged. Your setup is saved below; pay when you\u2019re ready, or continue on WhatsApp.'; }
        try{ history.replaceState(null,'',location.pathname); }catch(_){}
        return;
      }
    }
    var __prog=loadProgress();
    if(__prog){
      try{
        if(__prog.data)data=__prog.data;
        if(__prog.selAdd)selAdd=__prog.selAdd;
        if(typeof __prog.freeOffer!=='undefined')freeOffer=__prog.freeOffer;
        if(typeof __prog.idx==='number')idx=__prog.idx;
        if(typeof __prog.maxReached==='number')maxReached=Math.max(__prog.maxReached,idx);
      }catch(_){}
    }
    render();
    if(__prog) showResumeNotice();
    try{ var __wc=document.getElementById('wcontent'); if(__wc){ __wc.addEventListener('input',saveProgress); __wc.addEventListener('change',saveProgress); } }catch(_){}
  })();
  var __origRender=render;
  render=function(){ __origRender(); try{ var f=flow(); if(f[idx]==='name'){ setTimeout(function(){ updateTmNote(); nameAutoCheck(); },80); } }catch(_){} try{ saveProgress(); }catch(_){} };


/* ---------- Wire the buttons the template renders ----------
   The live markup used inline onclick attributes; the theme binds here instead. */
( function () {
  'use strict';

  var next = document.getElementById( 'wnext' );
  var nextB = document.getElementById( 'wnextB' );
  var back = document.getElementById( 'wback' );

  if ( next ) { next.addEventListener( 'click', function () { wNext(); } ); }
  if ( nextB ) { nextB.addEventListener( 'click', function () { wNext(); } ); }
  if ( back ) { back.addEventListener( 'click', function () { wBack(); } ); }
}() );
