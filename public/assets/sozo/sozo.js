/* Sozo Land Systems: interface behaviour (no dependencies) */
(function () {
  'use strict';

  var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var $ = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };

  /* ---------- Seeded sketches of estate layouts ---------- */
  function rng(seed) {
    var a = (parseInt(seed, 10) || 7) * 2654435761 >>> 0;
    return function () {
      a |= 0; a = a + 0x6D2B79F5 | 0;
      var t = Math.imul(a ^ a >>> 15, 1 | a);
      t = t + Math.imul(t ^ t >>> 7, 61 | t) ^ t;
      return ((t ^ t >>> 14) >>> 0) / 4294967296;
    };
  }

  function estateSketch(seed, w, h) {
    w = w || 240; h = h || 150;
    var r = rng(seed), out = [];
    var cols = 3 + Math.floor(r() * 2), rows = 2 + Math.floor(r() * 2);
    var road = 7, padX = 12, padY = 12;
    var bw = (w - padX * 2 - road * (cols - 1)) / cols;
    var bh = (h - padY * 2 - road * (rows - 1)) / rows;
    out.push('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' + w + ' ' + h + '" role="img" aria-label="Estate layout sketch">');
    out.push('<rect width="' + w + '" height="' + h + '" fill="#10201b"/>');
    out.push('<rect x="5" y="5" width="' + (w - 10) + '" height="' + (h - 10) + '" fill="none" stroke="#d2a44e" stroke-opacity=".55" stroke-dasharray="5 4"/>');
    for (var i = 0; i < cols; i++) {
      for (var j = 0; j < rows; j++) {
        var x = padX + i * (bw + road), y = padY + j * (bh + road);
        var nx = 2 + Math.floor(r() * 3), ny = 2 + Math.floor(r() * 2);
        var cw = bw / nx, ch = bh / ny;
        for (var a = 0; a < nx; a++) {
          for (var b = 0; b < ny; b++) {
            var p = r(), fill = 'rgba(236,234,224,.08)', stroke = 'rgba(236,234,224,.28)';
            if (p > .86) { fill = 'rgba(210,164,78,.7)'; stroke = '#d2a44e'; }
            else if (p > .6) { fill = 'rgba(84,182,160,.5)'; stroke = '#54b6a0'; }
            out.push('<rect x="' + (x + a * cw + .8).toFixed(1) + '" y="' + (y + b * ch + .8).toFixed(1) + '" width="' + (cw - 1.6).toFixed(1) + '" height="' + (ch - 1.6).toFixed(1) + '" rx="1.5" fill="' + fill + '" stroke="' + stroke + '" stroke-width=".6"/>');
          }
        }
      }
    }
    var cx = w - 22, cy = 24;
    out.push('<g transform="translate(' + cx + ',' + cy + ')" fill="none" stroke="#eceae0" stroke-opacity=".7" stroke-width="1"><circle r="9"/><path d="M0 -7 L3 3 L0 1 L-3 3 Z" fill="#eceae0" fill-opacity=".8"/></g>');
    out.push('</svg>');
    return out.join('');
  }
  function svgUri(svg) { return 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(svg); }
  window.SozoSketch = { estate: estateSketch, uri: function (seed, w, h) { return svgUri(estateSketch(seed, w, h)); } };

  /* ---------- Broken photos fall back to a drawn estate sketch ---------- */
  document.addEventListener('error', function (e) {
    var el = e.target;
    if (!el || el.tagName !== 'IMG' || el.dataset.szFallback === undefined || el.dataset.szFailed) return;
    el.dataset.szFailed = '1';
    el.src = svgUri(estateSketch(el.dataset.seed || 1, 640, 400));
  }, true);

  /* ---------- Shell: drawer, groups, menus ---------- */
  var body = document.body;
  function closeNav() { body.classList.remove('sz-nav-open'); }
  document.addEventListener('click', function (e) {
    var t = e.target.closest ? e.target : null;
    if (!t) return;
    if (t.closest('[data-sz-burger]')) { body.classList.toggle('sz-nav-open'); return; }
    if (t.closest('[data-sz-collapse]')) { setCollapsed(!isCollapsed(), true); return; }
    if (t.closest('.sz-scrim')) { closeNav(); return; }

    var tog = t.closest('[data-sz-group]');
    if (tog) {
      var g = tog.parentElement, willOpen = !g.classList.contains('open');
      g.classList.toggle('open', willOpen);
      tog.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
      return;
    }

    var mbtn = t.closest('[data-sz-menu]');
    $$('.sz-menu.open').forEach(function (m) { if (!mbtn || m !== mbtn.parentElement.querySelector('.sz-menu')) m.classList.remove('open'); });
    if (mbtn) {
      var menu = mbtn.parentElement.querySelector('.sz-menu');
      var open = menu.classList.toggle('open');
      mbtn.setAttribute('aria-expanded', open ? 'true' : 'false');
      return;
    }

    if (t.closest('[data-sz-palette]')) { openPalette(); return; }
    if (t.closest('.sz-palette') && !t.closest('.sz-palette-box')) { closePalette(); }
  });

  /* ---------- Collapsible sidebar (desktop icon rail) ---------- */
  var rootEl = document.documentElement, SIDEBAR_KEY = 'sz-sidebar';
  function isCollapsed() { return rootEl.classList.contains('sz-collapsed'); }
  function syncCollapseButton() {
    var on = isCollapsed(), label = on ? 'Expand sidebar' : 'Collapse sidebar';
    $$('[data-sz-collapse]').forEach(function (b) {
      b.setAttribute('aria-pressed', on ? 'true' : 'false');
      b.setAttribute('aria-label', label);
      b.setAttribute('title', label + ' (Ctrl+B)');
      var ic = $('.mdi', b);
      if (ic) { ic.classList.toggle('mdi-menu-open', !on); ic.classList.toggle('mdi-menu', on); }
    });
  }
  function setCollapsed(on, persist) {
    rootEl.classList.toggle('sz-collapsed', on);
    syncCollapseButton();
    if (persist) { try { localStorage.setItem(SIDEBAR_KEY, on ? '1' : '0'); } catch (e) { } }
    // Let charts and anything width-aware re-measure once the layout has settled.
    setTimeout(function () { window.dispatchEvent(new Event('resize')); }, reduceMotion ? 0 : 320);
  }
  document.addEventListener('keydown', function (e) {
    if (!(e.ctrlKey || e.metaKey) || e.shiftKey || e.altKey || (e.key || '').toLowerCase() !== 'b') return;
    var el = e.target, tag = el && el.tagName;
    if (tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT' || (el && el.isContentEditable)) return;
    if (window.matchMedia && window.matchMedia('(max-width: 991.98px)').matches) return;
    e.preventDefault();
    setCollapsed(!isCollapsed(), true);
  });

  function markActive() {
    var here = location.pathname.replace(/\/+$/, '') || '/';
    var best = null, bestLen = -1;
    $$('.sz-side a.sz-link[href]').forEach(function (a) {
      var p;
      try { p = new URL(a.href, location.href).pathname.replace(/\/+$/, '') || '/'; } catch (x) { return; }
      if (p === here || (p !== '/' && here.indexOf(p + '/') === 0)) {
        if (p.length > bestLen) { best = a; bestLen = p.length; }
      }
    });
    if (best) {
      best.classList.add('is-active');
      best.setAttribute('aria-current', 'page');
      var grp = best.closest('.sz-group');
      if (grp) { grp.classList.add('open', 'has-active'); var tg = $('[data-sz-group]', grp); if (tg) tg.setAttribute('aria-expanded', 'true'); }
    }
  }

  /* ---------- Command palette (Ctrl/Cmd + K, or "/") ---------- */
  var palette, pInput, pList, pItems = [], pIndex = 0;
  function buildPalette() {
    if (palette) return;
    palette = document.createElement('div');
    palette.className = 'sz-palette';
    palette.setAttribute('role', 'dialog');
    palette.setAttribute('aria-label', 'Jump to a page');
    palette.innerHTML = '<div class="sz-palette-box"><input type="text" placeholder="Jump to a page: estates, receipts, reminders" aria-label="Search pages" autocomplete="off"><ul></ul></div>';
    document.body.appendChild(palette);
    pInput = $('input', palette); pList = $('ul', palette);
    var all = [];
    $$('.sz-side a.sz-link[href]').forEach(function (a) {
      var href = a.getAttribute('href');
      if (!href || href.charAt(0) === '#') return;
      var grp = a.closest('.sz-group'), label = (grp ? $('[data-sz-group] .sz-label', grp) : null);
      var text = (a.querySelector('.sz-label') || a).textContent.replace(/\s+/g, ' ').trim();
      all.push({ text: text, group: label ? label.textContent.trim() : '', href: a.href });
    });
    palette._all = all;
    pInput.addEventListener('input', renderPalette);
    pInput.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowDown') { pIndex = Math.min(pIndex + 1, pItems.length - 1); paintPalette(); e.preventDefault(); }
      else if (e.key === 'ArrowUp') { pIndex = Math.max(pIndex - 1, 0); paintPalette(); e.preventDefault(); }
      else if (e.key === 'Enter' && pItems[pIndex]) { location.href = pItems[pIndex].href; }
    });
  }
  function renderPalette() {
    var q = pInput.value.trim().toLowerCase();
    pItems = palette._all.filter(function (i) { return !q || (i.text + ' ' + i.group).toLowerCase().indexOf(q) > -1; }).slice(0, 12);
    pIndex = 0;
    pList.innerHTML = pItems.length ? pItems.map(function (i, n) {
      return '<li data-n="' + n + '"><a href="' + i.href + '"><span>' + esc(i.text) + '</span><small>' + esc(i.group) + '</small></a></li>';
    }).join('') : '<li class="none">Nothing matches that. Try a shorter word.</li>';
    paintPalette();
  }
  function paintPalette() { $$('li', pList).forEach(function (li, n) { li.classList.toggle('on', n === pIndex); }); }
  function esc(s) { return String(s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; }); }
  function openPalette() { buildPalette(); palette.classList.add('open'); pInput.value = ''; renderPalette(); setTimeout(function () { pInput.focus(); }, 20); }
  function closePalette() { if (palette) palette.classList.remove('open'); }
  document.addEventListener('keydown', function (e) {
    var typing = /^(INPUT|TEXTAREA|SELECT)$/.test((e.target.tagName || '')) || e.target.isContentEditable;
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') { e.preventDefault(); openPalette(); }
    else if (e.key === '/' && !typing && $('[data-sz-palette]')) { e.preventDefault(); openPalette(); }
    else if (e.key === 'Escape') { closePalette(); closeNav(); $$('.sz-menu.open').forEach(function (m) { m.classList.remove('open'); }); }
  });

  /* ---------- Tables become cards on phones when marked .sz-stack ---------- */
  function stackTables() {
    $$('table.sz-stack').forEach(function (t) {
      var heads = $$('thead th', t).map(function (th) { return th.textContent.replace(/\s+/g, ' ').trim(); });
      $$('tbody tr', t).forEach(function (tr) {
        $$('td', tr).forEach(function (td, i) { if (heads[i] !== undefined && !td.hasAttribute('data-label')) td.setAttribute('data-label', heads[i]); });
      });
    });
  }

  /* ---------- Numbers ---------- */
  function countUp() {
    $$('[data-sz-count]').forEach(function (el) {
      var target = parseFloat(el.getAttribute('data-sz-count'));
      if (isNaN(target)) return;
      var fmt = function (n) { return Math.round(n).toLocaleString('en-US'); };
      if (reduceMotion || target < 10) { el.textContent = fmt(target); return; }
      var t0 = null, dur = 900;
      function step(ts) {
        if (t0 === null) t0 = ts;
        var p = Math.min((ts - t0) / dur, 1), eased = 1 - Math.pow(1 - p, 3);
        el.textContent = fmt(target * eased);
        if (p < 1) requestAnimationFrame(step);
      }
      requestAnimationFrame(step);
    });
  }

  /* ---------- Dashboard: plot plan driven by real sales counts ---------- */
  function plotPlan() {
    $$('[data-sz-plan]').forEach(function (host) {
      var paid = parseInt(host.getAttribute('data-paid'), 10) || 0;
      var under = parseInt(host.getAttribute('data-under'), 10) || 0;
      var total = paid + under, COLS = 16, ROWS = 6, N = COLS * ROWS;
      var paidCells = total ? Math.round(N * paid / total) : 0;
      if (total && paid > 0 && paidCells === 0) paidCells = 1;
      if (total && under > 0 && paidCells === N) paidCells = N - 1;
      var W = 640, H = 250, road = 14, mx = 6, my = 6;
      var cw = (W - mx * 2 - road * 3) / COLS, ch = (H - my * 2 - road * 1) / ROWS;
      var r = rng(paid * 31 + under * 17 + 5);
      var order = []; for (var i = 0; i < N; i++) order.push(i);
      for (var k = order.length - 1; k > 0; k--) { var j = Math.floor(r() * (k + 1)); var tmp = order[k]; order[k] = order[j]; order[j] = tmp; }
      var state = new Array(N);
      for (var s = 0; s < N; s++) state[order[s]] = s < paidCells ? 'paid' : 'under';
      var svg = ['<svg viewBox="0 0 ' + W + ' ' + H + '" role="img" aria-label="Plot plan: ' + paid + ' fully paid, ' + under + ' under payment">'];
      svg.push('<rect x="1" y="1" width="' + (W - 2) + '" height="' + (H - 2) + '" rx="10" fill="none" stroke="#d2a44e" stroke-opacity=".4" stroke-dasharray="6 5"/>');
      for (var row = 0; row < ROWS; row++) {
        for (var col = 0; col < COLS; col++) {
          var idx = row * COLS + col;
          var gx = Math.floor(col / 4), gy = Math.floor(row / 3);
          var x = mx + col * cw + gx * road, y = my + row * ch + gy * road;
          var st = total ? state[idx] : 'none';
          var fill = st === 'paid' ? '#54b6a0' : st === 'under' ? '#d2a44e' : 'rgba(236,234,224,.07)';
          var op = st === 'none' ? 1 : (st === 'paid' ? .88 : .8);
          svg.push('<rect class="cell" x="' + (x + 1).toFixed(1) + '" y="' + (y + 1).toFixed(1) + '" width="' + (cw - 2).toFixed(1) + '" height="' + (ch - 2).toFixed(1) + '" rx="2.5" fill="' + fill + '" opacity="' + (reduceMotion ? op : 0) + '" data-op="' + op + '"/>');
        }
      }
      svg.push('</svg>');
      host.insertAdjacentHTML('afterbegin', svg.join(''));
      if (!reduceMotion) {
        var cells = $$('.cell', host);
        cells.forEach(function (c, n) {
          setTimeout(function () { c.setAttribute('opacity', c.getAttribute('data-op')); }, 120 + (n % COLS) * 34 + Math.floor(n / COLS) * 22);
        });
      }
    });
  }

  /* ---------- Estates page: search + cards/table switch ---------- */
  function filters() {
    $$('[data-sz-filter]').forEach(function (input) {
      var items = $$(input.getAttribute('data-sz-filter'));
      var empty = $(input.getAttribute('data-sz-empty') || '#szNoMatch');
      input.addEventListener('input', function () {
        var q = input.value.trim().toLowerCase(), shown = 0;
        items.forEach(function (el) {
          var hit = !q || (el.getAttribute('data-search') || el.textContent).toLowerCase().indexOf(q) > -1;
          el.style.display = hit ? '' : 'none';
          if (hit) shown++;
        });
        if (empty) empty.style.display = shown ? 'none' : '';
      });
    });
    $$('[data-sz-view]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var host = $(btn.getAttribute('data-sz-target'));
        if (!host) return;
        var table = btn.getAttribute('data-sz-view') === 'table';
        host.classList.toggle('sz-view-table', table);
        $$('[data-sz-view]', btn.parentElement).forEach(function (b) { b.classList.toggle('on', b === btn); b.setAttribute('aria-pressed', b === btn ? 'true' : 'false'); });
        try { localStorage.setItem('sz-estate-view', table ? 'table' : 'cards'); } catch (e) { }
      });
    });
    try {
      if (localStorage.getItem('sz-estate-view') === 'table') { var b = $('[data-sz-view="table"]'); if (b) b.click(); }
    } catch (e) { }
  }

  function sketches() {
    $$('[data-sz-sketch]').forEach(function (el) { el.innerHTML = estateSketch(el.getAttribute('data-sz-sketch'), 240, 150); });
  }

  function rescueImages() {
    // Images that failed before this script ran never reported the error, so check them now.
    $$('img[data-sz-fallback]').forEach(function (img) {
      if (img.getAttribute('src') && img.complete && img.naturalWidth === 0 && !img.dataset.szFailed) { img.dataset.szFailed = '1'; img.src = svgUri(estateSketch(img.dataset.seed || 1, 640, 400)); }
    });
  }

  function init() { syncCollapseButton(); rescueImages(); markActive(); stackTables(); countUp(); plotPlan(); filters(); sketches(); }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();
