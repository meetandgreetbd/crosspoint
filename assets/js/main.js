/**
 * CrossPoint - main.js
 * Site-wide behaviour: conversion tracking, mega menu, mobile menu, outbound
 * click tracking, scroll reveal and the homepage contact form.
 *
 * Vanilla JS only - jQuery is never a dependency of this theme.
 * Ported from the live static site; ids and analytics labels now come from
 * CrossPoint Settings through the localized cpfSite object, never hardcoded.
 */
var cpfSite = window.cpfSite || {};

/* ---------- Conversion tracking (ids from CrossPoint Settings) ---------- */
var GADS_ID = cpfSite.gadsId || '';
var LABEL_FORM = cpfSite.gadsLabelForm || '';
var LABEL_WHATSAPP = cpfSite.gadsLabelWhatsapp || '';
window.dataLayer = window.dataLayer || [];
function gtag() { dataLayer.push(arguments); }
if (GADS_ID || cpfSite.ga4Id) {
  var s = document.createElement('script');
  s.async = true;
  s.src = 'https://www.googletagmanager.com/gtag/js?id=' + (GADS_ID || cpfSite.ga4Id);
  document.head.appendChild(s);
  gtag('js', new Date());
  if (GADS_ID) { gtag('config', GADS_ID); }
  if (cpfSite.ga4Id) { gtag('config', cpfSite.ga4Id); }
}
if (cpfSite.bingUetId) {
(function(w,d,t,u,o){w[u]=w[u]||[],o.ts=(new Date).getTime();var n=d.createElement(t);n.src="https://bat.bing.net/bat.js?ti="+o.ti+("uetq"!=u?"&q="+u:""),n.async=1,n.onload=n.onreadystatechange=function(){var s=this.readyState;s&&"loaded"!==s&&"complete"!==s||(o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad"),n.onload=n.onreadystatechange=null)};var i=d.getElementsByTagName(t)[0];i.parentNode.insertBefore(n,i);})(window,document,"script","uetq",{ti:"343255087",enableAutoSpaTracking:true});
}

/* ---------- Mega menu: hover on desktop, tap on touch ---------- */
/* mega-menu: hover on desktop (CSS), tap-to-toggle on touch, click-away to close */
  (function(){
    var megas=document.querySelectorAll('.nav-mega');
    megas.forEach(function(m){
      var t=m.querySelector('.nav-mega-trigger');
      t.addEventListener('click',function(e){
        e.stopPropagation();
        var open=m.classList.contains('open');
        megas.forEach(function(x){x.classList.remove('open');x.querySelector('.nav-mega-trigger').setAttribute('aria-expanded','false');});
        if(!open){m.classList.add('open');t.setAttribute('aria-expanded','true');}
      });
    });
    document.addEventListener('click',function(){megas.forEach(function(x){x.classList.remove('open');x.querySelector('.nav-mega-trigger').setAttribute('aria-expanded','false');});});
    document.addEventListener('keydown',function(e){if(e.key==='Escape')megas.forEach(function(x){x.classList.remove('open');x.querySelector('.nav-mega-trigger').setAttribute('aria-expanded','false');});});
  })();

/* ---------- Mobile menu ---------- */
var burger = document.getElementById('burger');
var menu = document.getElementById('mobileMenu');
if (burger && menu) {
  burger.addEventListener('click', function () {
    var isOpen = menu.classList.toggle('open');
    burger.classList.toggle('open', isOpen);
    burger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    burger.setAttribute('aria-label', isOpen ? 'Close menu' : 'Open menu');
  });
  menu.querySelectorAll('a').forEach(function (a) {
    a.addEventListener('click', function () {
      menu.classList.remove('open');
      burger.classList.remove('open');
      burger.setAttribute('aria-expanded', 'false');
    });
  });
  }

/* ---------- Homepage contact form ---------- */
/* Posts to the same lead endpoint as the quiz and the wizard, with source
   "contact", so every enquiry lands in one place. */
var leadForm = document.getElementById( 'contact-form' );

if ( leadForm ) {
  leadForm.addEventListener( 'submit', function ( e ) {
    e.preventDefault();

    var btn = leadForm.querySelector( '[type="submit"]' );
    var original = btn ? btn.textContent : '';

    if ( btn ) {
      btn.disabled = true;
      btn.textContent = 'Sending\u2026';
    }

    var form = new FormData( leadForm );
    var payload = {
      full_name: form.get( 'full_name' ) || form.get( 'name' ) || '',
      email: form.get( 'email' ) || '',
      whatsapp: form.get( 'whatsapp_number' ) || form.get( 'whatsapp' ) || '',
      residence_country: form.get( 'country_of_residence' ) || '',
      business_type: form.get( 'business_type' ) || '',
      structure: form.get( 'company_interest' ) || '',
      message: form.get( 'message' ) || '',
      source: 'contact',
      source_url: location.href,
      website: form.get( 'website' ) || '',
      ts: cpfSite.ts || 0
    };

    fetch( ( cpfSite.restBase || '/wp-json/crosspoint/v1/' ) + 'lead', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': cpfSite.nonce || '' },
      body: JSON.stringify( payload )
    } )
      .then( function ( r ) {
        if ( ! r.ok ) {
          throw new Error( 'send failed' );
        }

        if ( GADS_ID && LABEL_FORM ) {
          try {
            gtag( 'event', 'conversion', { send_to: GADS_ID + '/' + LABEL_FORM, transport_type: 'beacon' } );
            window.uetq = window.uetq || [];
            window.uetq.push( 'event', 'submit_lead_form', {} );
          } catch ( _ ) {}
        }

        leadForm.innerHTML = '<div class="cpf-form-done">' +
          '<div class="cpf-form-done__tick">\u2713</div>' +
          '<h3>Message sent</h3>' +
          '<p>Thanks \u2014 we usually reply within one business day.</p></div>';
        leadForm.scrollIntoView( { behavior: 'smooth', block: 'center' } );
      } )
      .catch( function () {
        if ( btn ) {
          btn.disabled = false;
          btn.textContent = original;
        }

        window.alert( 'We could not send your message. Please try again, or message us on WhatsApp.' );
      } );
  } );
}

/* ---------- Outbound / WhatsApp click tracking ---------- */
document.addEventListener('click', function (event) {
    var link = event.target && event.target.closest ? event.target.closest('a[href*="wa.me"]') : null;
    if (!link) return;
    try {
      gtag('event', 'conversion', { send_to: GADS_ID + '/' + LABEL_WHATSAPP, transport_type: 'beacon' });
      window.uetq = window.uetq || [];
      window.uetq.push('event', 'outbound_click', {});
    } catch (_) {}
  });

/* ---------- Scroll reveal ---------- */
(function(){
  try{
    if(!('IntersectionObserver' in window)||window.matchMedia('(prefers-reduced-motion: reduce)').matches)return;
    var els=[];
    document.querySelectorAll('section:not(.pricing)').forEach(function(s){els.push(s)});
    var pr=document.querySelector('section.pricing');
    if(pr){
      var k=pr.querySelector('.kicker'),h=pr.querySelector('h2'),n=pr.querySelector('.price-note');
      if(k)els.push(k); if(h)els.push(h);
      pr.querySelectorAll('.plan').forEach(function(pl,i){pl.style.transitionDelay=(i*110)+'ms';els.push(pl)});
      if(n)els.push(n);
    }
    els.forEach(function(e){e.classList.add('rv')});
    var io=new IntersectionObserver(function(ents){
      ents.forEach(function(en){if(en.isIntersecting){en.target.classList.add('rv-in');io.unobserve(en.target)}});
    },{threshold:.1,rootMargin:'0px 0px -6% 0px'});
    els.forEach(function(e){io.observe(e)});
  }catch(e){document.querySelectorAll('.rv').forEach(function(x){x.classList.add('rv-in')})}
})();
