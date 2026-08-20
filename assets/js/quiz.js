/**
 * CrossPoint - quiz.js
 * Homepage "Find Your Best Setup Path" quiz (3 steps).
 *
 * Every price shown here comes from cpfConfig.packages, which the theme builds
 * from the Packages CPT: the quiz can never quote a price /pricing/ does not.
 * Submissions go to POST /wp-json/crosspoint/v1/lead with source "home-quiz".
 */
var cpfConfig = window.cpfConfig || {};
var CPF_PKG = cpfConfig.packages || {};
var CPF_SITE = window.cpfSite || {};

/**
 * One package from the CPT by machine key.
 *
 * @param {string} key Package key, e.g. starter.
 * @return {Object} Package data, or an empty object.
 */
function cpfPackage( key ) {
  return ( CPF_PKG.byKey && CPF_PKG.byKey[ key ] ) || {};
}

/**
 * A WhatsApp link with a prefilled message, using the configured number.
 *
 * @param {string} text Message body.
 * @return {string} URL.
 */
function cpfWa( text ) {
  return ( CPF_SITE.waBase || 'https://wa.me/' ) + '?text=' + encodeURIComponent( text );
}
var heroQuizStep = 1;
var heroQuizTotal = 3;
var heroQuizData = { country: '', destination: '', business_goal: '', name: '', email: '', phone: '' }
;
function getHeroQuizFlow() {
    var flow = ['country', 'destination'];
    if (heroQuizData.destination === 'help_decide') flow.push('goal');
    flow.push('contact');
    return flow;
  }
function getHeroQuizStepId() {
    var flow = getHeroQuizFlow();
    return flow[heroQuizStep - 1] || flow[0];
  }
function focusHeroQuiz() {
    var anchor = document.getElementById('hero-quiz-anchor') || document.getElementById('hero-quiz');
    if (anchor) {
      anchor.scrollIntoView({ behavior: 'smooth', block: 'center' });
      var panel = anchor.querySelector ? anchor.querySelector('.hero-quiz-panel') || anchor : anchor;
      panel.style.transition = 'outline 0.2s ease';
      panel.style.outline = '3px solid var(--accent)';
      panel.style.outlineOffset = '3px';
      setTimeout(function () { panel.style.outline = ''; panel.style.outlineOffset = ''; }, 2200);
    }
    var menu = document.getElementById('mobileMenu');
    var burger = document.getElementById('burger');
    if (menu) menu.classList.remove('open');
    if (burger) { burger.classList.remove('open'); burger.setAttribute('aria-expanded', 'false'); }
  }
function heroQuizPick(field, value, el) {
    heroQuizData[field] = value;
    if (field === 'destination' && value !== 'help_decide') {
      heroQuizData.business_goal = '';
      var goalStep = document.querySelector('.hero-quiz-step[data-step-id="goal"]');
      if (goalStep) {
        goalStep.querySelectorAll('.hero-quiz-option').forEach(function (o) {
          o.classList.remove('selected');
          var radio = o.querySelector('input[type="radio"]');
          if (radio) radio.checked = false;
        });
      }
    }
    var step = el.closest('.hero-quiz-step');
    if (step) {
      step.querySelectorAll('.hero-quiz-option').forEach(function (o) { o.classList.remove('selected'); });
      el.classList.add('selected');
      var radio = el.querySelector('input[type="radio"]');
      if (radio) radio.checked = true;
    }
    updateHeroQuizNextState();
  }
function updateHeroQuizNextState() {
    var nextBtn = document.getElementById('hero-quiz-next');
    var backBtn = document.getElementById('hero-quiz-back');
    if (!nextBtn) return;
    var stepId = getHeroQuizStepId();
    if (backBtn) backBtn.classList.toggle('hidden', heroQuizStep === 1);
    var canContinue = false;
    if (stepId === 'country') canContinue = !!heroQuizData.country;
    else if (stepId === 'destination') canContinue = !!heroQuizData.destination;
    else if (stepId === 'goal') canContinue = !!heroQuizData.business_goal;
    else if (stepId === 'contact') {
      nextBtn.textContent = 'Get my path';
      nextBtn.disabled = false;
      return;
    }
    nextBtn.textContent = 'Continue';
    nextBtn.disabled = !canContinue;
  }
function heroQuizNext() {
    var stepId = getHeroQuizStepId();
    if (stepId === 'contact') { submitHeroQuiz(); return; }
    if (stepId === 'country' && !heroQuizData.country) return;
    if (stepId === 'destination' && !heroQuizData.destination) return;
    if (stepId === 'goal' && !heroQuizData.business_goal) return;
    if (heroQuizStep < getHeroQuizFlow().length) {
      heroQuizStep++;
      showHeroQuizStep();
    }
  }
function heroQuizBack() {
    if (heroQuizStep > 1) {
      heroQuizStep--;
      showHeroQuizStep();
    }
  }
function updateHeroQuizLayout() {
    var panel = document.getElementById('hero-quiz');
    var split = document.getElementById('hero-quiz-anchor');
    var started = heroQuizStep > 1;
    if (panel) panel.classList.toggle('quiz-started', started);
    if (split) split.classList.toggle('quiz-started', started);
    var reassure = document.getElementById('hero-quiz-reassure');
    if (reassure) reassure.style.display = started ? 'none' : 'flex';
  }
function showHeroQuizStep() {
    var stepId = getHeroQuizStepId();
    document.querySelectorAll('.hero-quiz-step').forEach(function (s) {
      s.classList.toggle('active', s.dataset.stepId === stepId);
    });
    heroQuizTotal = getHeroQuizFlow().length;
    var thanks = document.getElementById('hero-quiz-thanks');
    if (thanks) thanks.classList.remove('show');
    document.getElementById('hero-quiz-actions').style.display = 'flex';
    updateHeroQuizLayout();
    updateHeroQuizProgress();
    updateHeroQuizNextState();
    updateHeroDashboard();
  }
function updateHeroDashboard() {
    var widths = [[25,15,10],[50,35,25],[67,50,35],[85,70,55],[100,90,80]];
    var idx = Math.max(heroQuizStep - 1, 0);
    var w = widths[idx];
    var d1 = document.getElementById('hq-dash-1');
    var d2 = document.getElementById('hq-dash-2');
    var d3 = document.getElementById('hq-dash-3');
    if (d1) d1.style.width = w[0] + '%';
    if (d2) d2.style.width = w[1] + '%';
    if (d3) d3.style.width = w[2] + '%';
  }
function updateHeroQuizProgress() {
    var pct = Math.round((heroQuizStep / heroQuizTotal) * 100);
    var label = document.getElementById('hero-quiz-step-label');
    var pctEl = document.getElementById('hero-quiz-pct');
    var bar = document.getElementById('hero-quiz-bar');
    if (label) label.textContent = 'Step ' + heroQuizStep + ' of ' + heroQuizTotal;
    if (pctEl) pctEl.textContent = pct + '%';
    if (bar) bar.style.width = pct + '%';
  }
function destinationLabel(val) {
    if (val === 'usa') return 'U.S.';
    if (val === 'canada') return 'Canada';
    if (val === 'both') return 'Both U.S. and Canada';
    if (val === 'help_decide') return 'Help me decide';
    return val;
  }
function businessGoalLabel(val) {
    if (val === 'online_business') return 'Online business / freelancing';
    if (val === 'ecommerce') return 'E-commerce / Amazon / Shopify';
    if (val === 'consulting') return 'Consulting or agency';
    if (val === 'international_trading') return 'International trading';
    if (val === 'not_sure') return 'I am not sure yet';
    return val;
  }
/* Countries routed to a manual eligibility review — edit this list only. */
  var manualReviewCountries = ['Iran', 'Syria', 'North Korea', 'Cuba', 'Russia', 'Belarus'];
function getEffectiveDestination() {
    if (manualReviewCountries.indexOf(heroQuizData.country) !== -1) return 'review';
    if (heroQuizData.business_goal === 'ecommerce') return 'ecommerce';
    if (heroQuizData.destination !== 'help_decide') return heroQuizData.destination;
    if (heroQuizData.business_goal === 'international_trading') return 'both';
    if (heroQuizData.business_goal === 'not_sure') return 'advisor';
    return 'usa';
  }
var CALENDLY_URL = CPF_SITE.calendlyUrl || '';


  /**
   * The package recommended for a quiz outcome.
   *
   * Names, prices and detail lines come from the Packages CPT; only the routing
   * logic lives here.
   *
   * @param {string} dest Quiz destination.
   * @return {Object} Recommendation.
   */
  function getRecommendedPackage(dest) {
    if (dest === 'review') {
      return {
        name: 'Manual eligibility review',
        price: 'Advisor-led',
        detail: 'Service availability for your country needs a quick manual review. Message us on WhatsApp and we will confirm your options.',
        cta: 'Request Eligibility Review on WhatsApp',
        checkout: cpfWa('Hi CrossPoint, I completed the setup quiz and I need an eligibility review for my country.'),
        whatsapp: cpfWa('Hi CrossPoint, I completed the setup quiz and I need an eligibility review for my country.')
      };
    }
    if (dest === 'advisor') {
      return {
        name: 'Personalized setup review',
        price: 'Advisor-led',
        detail: 'We will recommend the right Canada or U.S. path for your goal.',
        checkout: CALENDLY_URL,
        whatsapp: cpfWa('Hi CrossPoint, I completed the setup quiz and I need help choosing the right package.')
      };
    }

    var map = { ecommerce: 'ecom', canada: 'home-canada', both: 'bundle' };
    var pkg = cpfPackage(map[dest] || 'home-us');

    return {
      name: pkg.name || '',
      price: pkg.price || '',
      detail: [pkg.detail, pkg.feeNote].filter(Boolean).join(' \u00b7 '),
      checkout: pkg.ctaUrl || '',
      whatsapp: cpfWa('Hi CrossPoint, I completed the setup quiz and I want help with ' + (pkg.name || 'my recommended package') + '.')
    };
  }
function updateQuizResultPackage() {
    var dest = getEffectiveDestination();
    var pkg = getRecommendedPackage(dest);
    var tagEl = document.getElementById('hero-quiz-result-tag');
    var nameEl = document.getElementById('hero-quiz-result-name');
    var priceEl = document.getElementById('hero-quiz-result-price');
    var detailEl = document.getElementById('hero-quiz-result-detail');
    var checkoutEl = document.getElementById('hero-quiz-checkout');
    if (tagEl) {
      if (dest === 'review') {
        tagEl.textContent = 'Eligibility review required';
      } else if (heroQuizData.destination === 'help_decide') {
        tagEl.textContent = 'Suggested for you';
      } else {
        tagEl.textContent = 'Recommended';
      }
    }
    if (nameEl) nameEl.textContent = pkg.name;
    if (priceEl) priceEl.textContent = pkg.price;
    if (detailEl) detailEl.textContent = pkg.detail;
    if (checkoutEl) {
      checkoutEl.href = pkg.checkout;
      checkoutEl.textContent = pkg.cta || (pkg.checkout === CALENDLY_URL ? 'Book a Free 15-Min Call' : 'Start This Package');
    }
    var whatsappEl = document.getElementById('hero-quiz-whatsapp');
    if (whatsappEl) {
      whatsappEl.href = pkg.whatsapp || cpfWa('Hi CrossPoint, I completed the setup quiz and I want help with my recommended package.');
      whatsappEl.style.display = dest === 'review' ? 'none' : 'inline-flex';
    }
    var bookCallEl = document.getElementById('hero-quiz-book-call');
    if (bookCallEl) {
      bookCallEl.style.display = dest === 'review' ? 'none' : 'inline-flex';
    }
  }
function submitHeroQuiz() {
    var name = (document.getElementById('hero-quiz-name').value || '').trim();
    var email = (document.getElementById('hero-quiz-email').value || '').trim();
    var phone = (document.getElementById('hero-quiz-phone').value || '').trim();
    if (!name || !phone) {
      alert('Please enter your name and WhatsApp number.');
      return;
    }
    /* The lead endpoint requires an email address (build spec section 11.1), so
       the quiz asks for one too - on the static site it was optional and those
       submissions arrived with no way to reply by email. */
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      alert('Please enter a valid email address.');
      return;
    }

    heroQuizData.name = name;
    heroQuizData.email = email;
    heroQuizData.phone = phone;

    var nextBtn = document.getElementById('hero-quiz-next');
    nextBtn.disabled = true;
    nextBtn.textContent = 'Sending…';


    fetch(( cpfConfig.restBase || '/wp-json/crosspoint/v1/' ) + 'lead', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': cpfConfig.nonce || '' },
      body: JSON.stringify({
        full_name: name,
        email: email,
        whatsapp: phone,
        residence_country: heroQuizData.country,
        destination: destinationLabel(heroQuizData.destination),
        business_goal: heroQuizData.business_goal ? businessGoalLabel(heroQuizData.business_goal) : '',
        package: getRecommendedPackage(getEffectiveDestination()).name,
        source: 'home-quiz',
        source_url: location.href,
        website: (document.getElementById('hero-quiz-gotcha') || {}).value || '',
        ts: cpfConfig.ts || 0
      })
    }).then(function (res) {
      if (res.ok) {
        /* Conversion deliberately NOT fired here. This request is intercepted by
           /tools/cpf-lead.js, which re-points it at lead.php and fires the conversion
           itself, gated on !duplicate. Firing again here double-counted every new lead
           and counted 1 for duplicates that should count 0.
           One conversion owner per form: the shim. Owner decision 2026-07-29. */
        showHeroQuizThankYou();
      } else {
        throw new Error('submit failed');
      }
    }).catch(function () {
      alert('Something went wrong. Please try again or contact us on WhatsApp.');
      nextBtn.disabled = false;
      nextBtn.textContent = 'Get my path';
    });
  }
function showHeroQuizThankYou() {
    document.querySelectorAll('.hero-quiz-step').forEach(function (s) { s.classList.remove('active'); });
    document.getElementById('hero-quiz-actions').style.display = 'none';
    updateHeroQuizLayout();
    updateQuizResultPackage();
    document.getElementById('hero-quiz-thanks').classList.add('show');
    var hd = document.getElementById('hero-quiz-heading');
    if (hd) hd.textContent = 'Your Setup Path is Ready';
    var sh = document.getElementById('hero-quiz-subhead');
    if (sh) sh.style.display = 'none';
    var tk = document.getElementById('hero-quiz-headtick');
    if (tk) tk.classList.add('show');
    var bar = document.getElementById('hero-quiz-bar');
    if (bar) bar.style.width = '100%';
    var pctEl = document.getElementById('hero-quiz-pct');
    if (pctEl) pctEl.textContent = '100%';
    var label = document.getElementById('hero-quiz-step-label');
    if (label) label.textContent = 'Based on your answers';
  }
function resetHeroQuiz() {
    heroQuizStep = 1;
    heroQuizData = { country: '', destination: '', business_goal: '', name: '', email: '', phone: '' };
    document.getElementById('hero-quiz-country').value = '';
    document.getElementById('hero-quiz-name').value = '';
    document.getElementById('hero-quiz-email').value = '';
    document.getElementById('hero-quiz-phone').value = '';
    var gotcha = document.getElementById('hero-quiz-gotcha');
    if (gotcha) gotcha.value = '';
    document.querySelectorAll('.hero-quiz-option').forEach(function (o) {
      o.classList.remove('selected');
      var radio = o.querySelector('input[type="radio"]');
      if (radio) radio.checked = false;
    });
    document.getElementById('hero-quiz-thanks').classList.remove('show');
    var hd = document.getElementById('hero-quiz-heading');
    if (hd) hd.textContent = 'Find Your Best Setup Path';
    var sh = document.getElementById('hero-quiz-subhead');
    if (sh) sh.style.display = '';
    var tk = document.getElementById('hero-quiz-headtick');
    if (tk) tk.classList.remove('show');
    showHeroQuizStep();
  }

var __hqc = document.getElementById('hero-quiz-country');
if (__hqc) __hqc.addEventListener('change', function () {
    heroQuizData.country = this.value;
    updateHeroQuizNextState();
  });
var _hc=document.getElementById('hero-quiz-country'), _fc=document.getElementById('f-country');
if(_hc&&_fc&&_fc.options.length<=1){_fc.innerHTML=_hc.innerHTML;}

updateHeroQuizLayout();
updateHeroQuizNextState();
updateHeroDashboard();


/* ---------- Mega-menu promo link opens the quiz ---------- */
document.addEventListener( 'click', function ( event ) {
  var link = event.target && event.target.closest ? event.target.closest( 'a[href*="#hero-quiz-anchor"]' ) : null;

  if ( link ) {
    event.preventDefault();
    focusHeroQuiz();
  }
} );
