/* Sozo Land Systems: page loader and double-click protection (no dependencies)

   What it does
   1. Page changes (links, form posts) show a slim progress bar and a loader, and every
      other click is ignored until the next page arrives.
   2. A button that was just pressed ignores repeat clicks for a moment, so a double
      click never fires an action twice.
   3. AJAX / fetch requests started by a button lock that button until they finish.

   Opt-outs:   data-sz-no-loader   on a link or form  -> no page loader
               data-sz-allow-repeat on a button        -> repeat clicks are allowed
   Optional:   data-sz-loading-text="Saving..."        -> message shown in the loader
   API:        SozoLoader.show(msg) / SozoLoader.hide() / SozoLoader.progress.start() / .done()
*/
(function () {
  'use strict';
  if (window.SozoLoader) return;

  var doc = document, root = doc.documentElement;
  var COOLDOWN = 700;          // ms a pressed button ignores repeat clicks
  var AJAX_TAIL = 500;         // ms a button stays locked after its request ends (covers redirects)
  var FAILSAFE = 60000;        // ms before the loader releases itself if no page arrived
  var locked = false, overlay, msgEl, subEl, tShow, tSlow, tFail;

  var BUTTONISH = 'button, input[type="submit"], input[type="button"], input[type="image"], a.btn, .btn';
  var NO_COOLDOWN = '[data-bs-toggle], [data-bs-dismiss], [data-toggle], [data-dismiss], [data-sz-allow-repeat], ' +
    '[data-sz-burger], [data-sz-collapse], [data-sz-menu], [data-sz-group], [data-sz-palette], [data-sz-view], .page-link, .close, .btn-close';
  var DOWNLOAD_RE = /download|view-estate-pdf|\.(pdf|zip|docx?|xlsx?|csv|png|jpe?g)$|[?&]download=1/i;

  /* ---------- Top progress bar ---------- */
  var bar, barFill, barPct = 0, barTimer, barActive = 0;
  function buildBar() {
    if (bar) return;
    bar = doc.createElement('div');
    bar.className = 'sz-progress';
    bar.setAttribute('aria-hidden', 'true');
    bar.innerHTML = '<i></i>';
    doc.body.appendChild(bar);
    barFill = bar.firstChild;
  }
  function barSet(p) { barPct = p; barFill.style.width = p + '%'; }
  var progress = {
    start: function () {
      buildBar();
      barActive++;
      if (barActive > 1) return;
      clearInterval(barTimer);
      barFill.style.transition = 'none'; barSet(0);
      void barFill.offsetWidth;
      barFill.style.transition = '';
      bar.classList.add('is-on');
      barSet(14);
      barTimer = setInterval(function () {
        // Creep towards 90% and never arrive until the work is really done.
        barSet(barPct + (92 - barPct) * 0.07);
      }, 350);
    },
    done: function (force) {
      if (!bar) return;
      barActive = force ? 0 : Math.max(0, barActive - 1);
      if (barActive > 0) return;
      clearInterval(barTimer);
      barSet(100);
      setTimeout(function () { if (!barActive) bar.classList.remove('is-on'); }, 260);
    }
  };

  /* ---------- Full page loader ---------- */
  function buildOverlay() {
    if (overlay) return;
    overlay = doc.createElement('div');
    overlay.className = 'sz-loader';
    overlay.setAttribute('role', 'status');
    overlay.setAttribute('aria-live', 'polite');
    overlay.setAttribute('aria-hidden', 'true');
    overlay.innerHTML =
      '<div class="sz-loader-card">' +
        '<div class="sz-ring"><svg viewBox="0 0 50 50" aria-hidden="true"><circle class="trk" cx="25" cy="25" r="21"/><circle class="arc" cx="25" cy="25" r="21"/></svg>' +
        '<img src="/assets/sozo/favicon.png" alt="" width="30" height="30"></div>' +
        '<b class="sz-loader-msg">Loading</b><small class="sz-loader-sub"></small>' +
      '</div>';
    doc.body.appendChild(overlay);
    msgEl = overlay.querySelector('.sz-loader-msg');
    subEl = overlay.querySelector('.sz-loader-sub');
  }

  function show(message) {
    buildOverlay();
    locked = true;
    root.classList.add('sz-busy-page');
    msgEl.textContent = message || 'Loading';
    subEl.textContent = '';
    overlay.classList.add('is-on');
    overlay.setAttribute('aria-hidden', 'false');
    clearTimeout(tShow); clearTimeout(tSlow); clearTimeout(tFail);
    // The overlay already blocks clicks; the dimmed card only appears if the wait is noticeable.
    tShow = setTimeout(function () { overlay.classList.add('is-visible'); }, 160);
    tSlow = setTimeout(function () { subEl.textContent = 'This is taking longer than usual. Please keep this page open.'; }, 8000);
    tFail = setTimeout(hide, FAILSAFE);
    progress.start();
  }

  function hide() {
    locked = false;
    clearTimeout(tShow); clearTimeout(tSlow); clearTimeout(tFail);
    root.classList.remove('sz-busy-page');
    if (overlay) {
      overlay.classList.remove('is-on', 'is-visible');
      overlay.setAttribute('aria-hidden', 'true');
    }
    var navs = doc.querySelectorAll('[data-sz-navigating]');
    for (var i = 0; i < navs.length; i++) { unbusy(navs[i]); navs[i].removeAttribute('data-sz-navigating'); }
    progress.done(true);
  }

  /* ---------- Busy elements ---------- */
  function busy(el) {
    if (!el || !el.classList) return;
    el.classList.add('sz-btn-loading');
    el.setAttribute('aria-busy', 'true');
    if (el.tagName === 'INPUT') el.setAttribute('aria-disabled', 'true');
  }
  function unbusy(el) {
    if (!el || !el.classList) return;
    el.classList.remove('sz-btn-loading');
    el.removeAttribute('aria-busy');
    el.removeAttribute('aria-disabled');
  }
  function isBusy(el) { return !!(el && el.closest && el.closest('.sz-btn-loading, [aria-busy="true"]')); }
  function softLock(el, ms) { busy(el); setTimeout(function () { unbusy(el); }, ms); }

  function loadingText(el, fallback) {
    var n = el && el.closest ? el.closest('[data-sz-loading-text]') : null;
    return (n && n.getAttribute('data-sz-loading-text')) || fallback;
  }

  /* ---------- Clicks ---------- */
  var pending = null;   // the last button pressed or form submitted: AJAX started right after it is attributed to it
  function setPending(el, form) { pending = { el: el, form: form || null, t: Date.now() }; }
  function takePending() {
    var p = pending; pending = null;
    return p && Date.now() - p.t < 800 ? p : null;
  }

  function isNavigation(a, e) {
    if (!a || !a.getAttribute) return false;
    var href = a.getAttribute('href');
    if (!href || href.charAt(0) === '#' || /^\s*javascript:/i.test(href)) return false;
    if (a.hasAttribute('download') || a.hasAttribute('data-sz-no-loader')) return false;
    if (a.matches('[data-bs-toggle], [data-toggle]')) return false;
    var t = a.getAttribute('target');
    if (t && t !== '_self') return false;
    if (e && (e.ctrlKey || e.metaKey || e.shiftKey || e.altKey)) return false;
    var u;
    try { u = new URL(a.href, location.href); } catch (x) { return false; }
    if (u.protocol !== 'http:' && u.protocol !== 'https:') return false;
    if (u.origin === location.origin && u.pathname === location.pathname && u.search === location.search) return false; // same page / anchor
    return true;
  }
  function isDownload(a) {
    try { var u = new URL(a.href, location.href); return DOWNLOAD_RE.test(u.pathname + u.search); } catch (x) { return false; }
  }

  function block(e) { e.preventDefault(); e.stopImmediatePropagation(); }

  doc.addEventListener('click', function (e) {
    if (e.button && e.button !== 0) return;
    var t = e.target;
    if (!t || !t.closest) return;
    var el = t.closest('a[href], ' + BUTTONISH);
    if (!el) return;

    // 1. A page change or request is already running: ignore everything else.
    if (locked || isBusy(el)) { block(e); return; }

    var isBtn = el.matches(BUTTONISH);

    // 2. A button that was pressed a moment ago ignores the repeat click (double click).
    if (isBtn && !el.matches(NO_COOLDOWN) && !el.disabled) {
      var now = Date.now();
      if (el._szT && now - el._szT < COOLDOWN) { block(e); return; }
      el._szT = now;
      if (el.type !== 'submit' || !el.form) setPending(el, null);
    }

    // 3. Links that leave the page.
    var a = el.tagName === 'A' ? el : null;
    if (a && isNavigation(a, e)) {
      if (isDownload(a)) { softLock(a, 3000); return; }
      // Wait one tick so a confirm() or another handler can still cancel the click.
      setTimeout(function () {
        if (e.defaultPrevented) return;
        a.setAttribute('data-sz-navigating', '1');
        busy(a);
        show(loadingText(a, 'Loading'));
      }, 0);
    }
  }, true);

  /* ---------- Forms ---------- */
  function skipForm(form) {
    return !form || form.hasAttribute('data-sz-no-loader') || (form.target && form.target !== '_self');
  }
  function submitterOf(e, form) {
    return e.submitter || form.querySelector('button[type="submit"], input[type="submit"], button:not([type])');
  }

  doc.addEventListener('submit', function (e) {
    var form = e.target;
    if (!form || form.tagName !== 'FORM') return;

    // A second submit while one is running, or within a moment of the first, is dropped.
    var now = Date.now();
    if (locked || form._szBusy || (form._szT && now - form._szT < COOLDOWN)) { block(e); return; }
    form._szT = now;

    var btn = submitterOf(e, form);
    setPending(btn, form);

    if (skipForm(form)) return;
    // Wait one tick: validation plugins, confirm() or AJAX handlers may cancel the submit.
    setTimeout(function () {
      if (e.defaultPrevented) return;
      busy(btn);
      show(loadingText(btn, loadingText(form, 'Saving, please wait')));
    }, 0);
  }, true);

  // form.submit() called from scripts skips the submit event, so guard it too.
  var nativeSubmit = HTMLFormElement.prototype.submit;
  HTMLFormElement.prototype.submit = function () {
    if (locked) return;
    if (!skipForm(this)) show(loadingText(this, 'Saving, please wait'));
    return nativeSubmit.apply(this, arguments);
  };

  /* ---------- AJAX and fetch: lock the button that started the request ---------- */
  function track(p, finish) {
    progress.start();
    if (p) { busy(p.el); if (p.form) p.form._szBusy = true; }
    var ended = false;
    finish(function () {
      if (ended) return; ended = true;
      progress.done();
      if (p) setTimeout(function () { unbusy(p.el); if (p.form) p.form._szBusy = false; }, AJAX_TAIL);
    });
  }

  function hookjQuery() {
    var $ = window.jQuery;
    if (!$ || $._szHooked) return;
    $._szHooked = true;
    $(doc).on('ajaxSend', function (ev, jq, opts) {
      if (opts && opts.global === false) return;
      var p = takePending();
      track(p, function (end) { jq.always(end); });
    });
  }

  if (window.fetch) {
    var nativeFetch = window.fetch;
    window.fetch = function () {
      var p = takePending();
      var result = nativeFetch.apply(this, arguments);
      track(p, function (end) { result.then(end, end); });
      return result;
    };
  }

  /* ---------- Leaving and coming back ---------- */
  // Back / forward can restore the page from the browser cache with the loader still on.
  window.addEventListener('pageshow', function (e) { if (e.persisted) hide(); });

  function init() {
    hookjQuery();
  }
  if (doc.readyState === 'loading') doc.addEventListener('DOMContentLoaded', init); else init();
  window.addEventListener('load', hookjQuery);

  window.SozoLoader = { show: show, hide: hide, progress: progress, busy: busy, unbusy: unbusy };
})();
