/**
 * CrossPoint - chat.js
 * CrossPoint Chat widget. Rendered by template-parts/chat-widget.php and
 * enabled from CrossPoint Settings (Tracking tab).
 */
var cpChatLastFocus = null;
function scrollCpChatToBottom() {
    var box = document.getElementById('cpChatMessages');
    if (box) box.scrollTop = box.scrollHeight;
  }
function appendCpChatMsg(text, type) {
    var box = document.getElementById('cpChatMessages');
    if (!box) return;
    var msg = document.createElement('div');
    msg.className = 'cp-chat-msg ' + (type || 'bot');
    msg.textContent = text;
    box.appendChild(msg);
    scrollCpChatToBottom();
  }
function getCpChatReply(text) {
    var q = text.toLowerCase();
    if (/\b(canada|canadian|incorporat|federal|ontario|bc)\b/.test(q)) {
      return 'We help non-residents form Canadian corporations remotely, including federal and provincial options. Service fees start from USD $199, plus the government filing fee, which is charged separately at cost. For a setup path based on your country and business type, connect with a CrossPoint advisor below.';
    }
    if (/\b(llc|c-corp|c corp|corporation|delaware|wyoming|united states|u\.s\.|usa|america)\b/.test(q)) {
      return 'We support U.S. LLC and corporation formation for non-residents in any state. Service fees start from USD $299 plus state filing fees. If you need both Canada and the U.S., our U.S. + Canada bundle is USD $419, saving $79 versus separate setups. For personal guidance, a CrossPoint advisor can walk you through the best option.';
    }
    if (/\b(bank|banking|account|stripe|payment)\b/.test(q)) {
      return 'We provide business banking documentation guidance and help prepare the documents banks typically request. Account approval is decided by each bank on its own criteria. For a review of your situation, connect with a CrossPoint advisor below.';
    }
    if (/\b(price|cost|fee|how much|pricing)\b/.test(q)) {
      return 'Canada incorporation starts from USD $199 plus the government filing fee, U.S. formation from USD $299 plus state fees, and our U.S. + Canada bundle is USD $419 (saves $79 vs separate setups). See the Pricing section on this page for details, or book a free 15-minute call for a personalized quote.';
    }
    if (/\b(resident|non-resident|non resident|live|country|citizen)\b/.test(q)) {
      return 'You do not need Canadian or U.S. residence for many eligible setup paths. Eligibility depends on your country of residence and business type. Share your details with a CrossPoint advisor and we will confirm the right path for you.';
    }
    if (/\b(human|advisor|person|call|speak|talk|whatsapp)\b/.test(q)) {
      return 'Happy to connect you with a CrossPoint advisor. Use WhatsApp Advisor or Book a Free 15-Min Call below — whichever is easiest for you.';
    }
    return 'Thanks for your question. I can help with general Canada and U.S. company setup topics here. For personal guidance on your specific situation, connect with a CrossPoint advisor using the links below.';
  }
function trapCpChatFocus(event) {
    var chat = document.getElementById('cpChat');
    if (!chat || !chat.classList.contains('open') || event.key !== 'Tab') return;
    var focusable = chat.querySelectorAll('a[href], button:not([disabled]), input:not([disabled]), textarea:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])');
    if (!focusable.length) return;
    var first = focusable[0];
    var last = focusable[focusable.length - 1];
    if (event.shiftKey && document.activeElement === first) {
      event.preventDefault();
      last.focus();
    } else if (!event.shiftKey && document.activeElement === last) {
      event.preventDefault();
      first.focus();
    }
  }
function openCpChat() {
    var chat = document.getElementById('cpChat');
    var input = document.getElementById('cpChatInput');
    if (!chat) return;
    cpChatLastFocus = document.activeElement;
    chat.classList.add('open');
    chat.setAttribute('aria-hidden', 'false');
    document.addEventListener('keydown', trapCpChatFocus);
    document.body.style.overflow = 'hidden';
    var menuEl = document.getElementById('mobileMenu');
    var burgerEl = document.getElementById('burger');
    if (menuEl) menuEl.classList.remove('open');
    if (burgerEl) {
      burgerEl.classList.remove('open');
      burgerEl.setAttribute('aria-expanded', 'false');
    }
    setTimeout(function () { if (input) input.focus(); }, 120);
  }
function closeCpChat() {
    var chat = document.getElementById('cpChat');
    if (!chat) return;
    chat.classList.remove('open');
    chat.setAttribute('aria-hidden', 'true');
    document.removeEventListener('keydown', trapCpChatFocus);
    document.body.style.overflow = '';
    if (cpChatLastFocus && typeof cpChatLastFocus.focus === 'function') {
      cpChatLastFocus.focus();
    }
    cpChatLastFocus = null;
  }
function sendCpChat(event) {
    if (event) event.preventDefault();
    var input = document.getElementById('cpChatInput');
    if (!input) return false;
    var text = input.value.trim();
    if (!text) return false;
    appendCpChatMsg(text, 'user');
    input.value = '';
    input.disabled = true;
    setTimeout(function () {
      appendCpChatMsg(getCpChatReply(text), 'bot');
      input.disabled = false;
      input.focus();
    }, 450);
    return false;
  }
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      var chat = document.getElementById('cpChat');
      if (chat && chat.classList.contains('open')) closeCpChat();
    }
  });


/* ---------- Bindings ----------
   The static markup used inline onclick and onsubmit attributes; the theme
   binds the same behaviour here instead. */
( function () {
  'use strict';

  var chat = document.getElementById( 'cpChat' );

  if ( ! chat ) {
    return;
  }

  chat.addEventListener( 'click', function ( event ) {
    if ( event.target === chat ) {
      closeCpChat();
    }
  } );

  var close = chat.querySelector( '.cp-chat-close' );

  if ( close ) {
    close.addEventListener( 'click', closeCpChat );
  }

  var form = document.getElementById( 'cpChatForm' );

  if ( form ) {
    form.addEventListener( 'submit', sendCpChat );
  }

  document.addEventListener( 'click', function ( event ) {
    var trigger = event.target && event.target.closest ? event.target.closest( '.cpf-open-chat' ) : null;

    if ( trigger ) {
      event.preventDefault();
      openCpChat();
    }
  } );
}() );
