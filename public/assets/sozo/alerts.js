/* Sozo alerts: SweetAlert2 confirms (data-confirm), centred flash popups, and a back-to-top button. */
(function () {
  'use strict';
  var win = window, doc = document;

  function hasSwal() { return typeof win.Swal !== 'undefined'; }

  var base = {
    customClass: { container: 'sz-swal-container', popup: 'sz-swal' },
    buttonsStyling: true,
    allowOutsideClick: false,
    heightAuto: false
  };

  function fire(opts) {
    var o = {};
    Object.keys(base).forEach(function (k) { o[k] = base[k]; });
    Object.keys(opts).forEach(function (k) { o[k] = opts[k]; });
    return win.Swal.fire(o);
  }

  /* ---------- Public helpers: Sozo.alert / Sozo.confirm ---------- */
  var TITLES = { success: 'Success', error: 'Something went wrong', warning: 'Please note', info: 'Information' };

  function notify(type, message, title) {
    if (!hasSwal()) { win.alert(message); return Promise.resolve(); }
    return fire({ icon: type, title: title || TITLES[type] || 'Notice', text: String(message), confirmButtonText: 'OK' });
  }

  function ask(message, o) {
    o = o || {};
    var danger = o.danger != null ? o.danger : /\b(delete|remove|resale|revoke|cancel)\b/i.test(message);
    if (!hasSwal()) return Promise.resolve(win.confirm(message));
    return fire({
      icon: o.icon || (danger ? 'warning' : 'question'),
      title: o.title || 'Are you sure?',
      text: message,
      showCancelButton: true,
      confirmButtonText: o.yes || (danger ? 'Yes, proceed' : 'Yes, continue'),
      cancelButtonText: o.no || 'Cancel',
      reverseButtons: true,
      focusCancel: danger,
      customClass: { container: 'sz-swal-container', popup: 'sz-swal', confirmButton: danger ? 'sz-danger' : '' }
    }).then(function (r) { return !!r.isConfirmed; });
  }

  win.Sozo = win.Sozo || {};
  win.Sozo.alert = notify;
  win.Sozo.confirm = ask;

  /* ---------- Confirm on [data-confirm] (links, buttons, forms) ---------- */
  function optsOf(el) {
    return {
      title: el.getAttribute('data-confirm-title') || undefined,
      yes: el.getAttribute('data-confirm-yes') || undefined,
      no: el.getAttribute('data-confirm-no') || undefined,
      icon: el.getAttribute('data-confirm-icon') || undefined
    };
  }

  // Window capture runs before the loader's document listeners, so a cancelled confirm never shows the loader.
  win.addEventListener('click', function (e) {
    var el = e.target && e.target.closest ? e.target.closest('[data-confirm]') : null;
    if (!el || el.tagName === 'FORM') return;
    if (el._szOk) { el._szOk = false; return; }
    if (el.disabled || el.getAttribute('aria-disabled') === 'true') return;

    // Let the browser report missing required fields instead of asking first.
    var form = el.form;
    var isSubmit = form && (el.type === 'submit' || (el.tagName === 'BUTTON' && !el.getAttribute('type')));
    if (isSubmit && !el.hasAttribute('formnovalidate') && !form.noValidate && !form.checkValidity()) return;

    e.preventDefault();
    e.stopImmediatePropagation();
    ask(el.getAttribute('data-confirm'), optsOf(el)).then(function (yes) {
      if (!yes) return;
      el._szOk = true;
      el.click();
    });
  }, true);

  win.addEventListener('submit', function (e) {
    var form = e.target;
    if (!form || form.tagName !== 'FORM' || !form.hasAttribute('data-confirm')) return;
    if (form._szOk) { form._szOk = false; return; }
    e.preventDefault();
    e.stopImmediatePropagation();
    ask(form.getAttribute('data-confirm'), optsOf(form)).then(function (yes) {
      if (!yes) return;
      form._szOk = true;
      if (form.requestSubmit) form.requestSubmit(); else form.submit();
    });
  }, true);

  /* ---------- Safety net: turn any leftover inline confirm('...') into data-confirm ---------- */
  var INLINE = /^\s*(?:return\s+)?(?:window\.)?confirm\(\s*(['"])((?:\\.|(?!\1)[^\\])*)\1\s*\)\s*;?\s*$/;
  function convertInline(root) {
    (root || doc).querySelectorAll('[onclick*="confirm("], [onsubmit*="confirm("]').forEach(function (el) {
      ['onclick', 'onsubmit'].forEach(function (attr) {
        var m = INLINE.exec(el.getAttribute(attr) || '');
        if (!m) return;
        el.setAttribute('data-confirm', m[2].replace(/\\(['"])/g, '$1').trim());
        el.removeAttribute(attr);
      });
    });
  }

  /* ---------- Flash messages: centred popup, closed with OK ---------- */
  function showFlash() {
    var cfg = win.SozoFlash;
    if (!cfg || !cfg.length) return;
    var chain = Promise.resolve();
    cfg.forEach(function (f) { chain = chain.then(function () { return notify(f.type, f.message, f.title); }); });
    win.SozoFlash = [];
  }

  /* ---------- Back to top ---------- */
  var ARROW = '<svg class="sz-totop-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 19V5"/><path d="M5 12l7-7 7 7"/></svg>';
  var RING = '<svg viewBox="0 0 52 52" aria-hidden="true"><circle class="sz-totop-track" cx="26" cy="26" r="23"/><circle class="sz-totop-bar" cx="26" cy="26" r="23"/></svg>';

  function backToTop() {
    if (doc.querySelector('.sz-totop')) return;
    var btn = doc.createElement('button');
    btn.type = 'button';
    btn.className = 'sz-totop';
    btn.setAttribute('aria-label', 'Back to top');
    btn.setAttribute('title', 'Back to top');
    btn.setAttribute('data-sz-no-loader', '1');
    btn.innerHTML = RING + ARROW;
    doc.body.appendChild(btn);
    var bar = btn.querySelector('.sz-totop-bar'), LEN = 144.5, ticking = false;

    function update() {
      ticking = false;
      var y = win.pageYOffset || doc.documentElement.scrollTop || 0;
      var max = Math.max(1, doc.documentElement.scrollHeight - win.innerHeight);
      btn.classList.toggle('is-visible', y > 320);
      bar.style.strokeDashoffset = String(LEN - LEN * Math.min(1, y / max));
    }
    win.addEventListener('scroll', function () { if (!ticking) { ticking = true; win.requestAnimationFrame(update); } }, { passive: true });
    win.addEventListener('resize', update);
    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      var reduce = win.matchMedia && win.matchMedia('(prefers-reduced-motion: reduce)').matches;
      win.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' });
    });
    update();
  }

  function ready() {
    convertInline();
    showFlash();
    backToTop();
  }
  if (doc.readyState === 'loading') doc.addEventListener('DOMContentLoaded', ready); else ready();
})();
