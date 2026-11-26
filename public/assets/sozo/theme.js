/* Sozo theme switcher: stores the choice in localStorage and sets html[data-sz-theme]. Default theme = green (no attribute). */
(function () {
  'use strict';
  var KEY = 'sz-theme', DEFAULT = 'green', root = document.documentElement;

  function current() {
    var t = root.getAttribute('data-sz-theme');
    return t || DEFAULT;
  }

  function sync() {
    var t = current();
    document.querySelectorAll('[data-sz-theme-opt]').forEach(function (b) {
      var on = b.getAttribute('data-sz-theme-opt') === t;
      b.setAttribute('aria-checked', on ? 'true' : 'false');
      if (on) {
        var meta = document.querySelector('meta[name="theme-color"]');
        if (meta && b.getAttribute('data-bg')) meta.setAttribute('content', b.getAttribute('data-bg'));
      }
    });
  }

  function apply(id) {
    root.classList.add('sz-theme-anim');
    if (id === DEFAULT) root.removeAttribute('data-sz-theme'); else root.setAttribute('data-sz-theme', id);
    try { localStorage.setItem(KEY, id); } catch (e) {}
    sync();
    window.setTimeout(function () { root.classList.remove('sz-theme-anim'); }, 400);
    document.dispatchEvent(new CustomEvent('sz-theme-change', { detail: { theme: id } }));
  }

  document.addEventListener('click', function (e) {
    var b = e.target.closest ? e.target.closest('[data-sz-theme-opt]') : null;
    if (!b) return;
    apply(b.getAttribute('data-sz-theme-opt'));
  });

  // Keep several open tabs in step.
  window.addEventListener('storage', function (e) {
    if (e.key !== KEY) return;
    var id = e.newValue || DEFAULT;
    if (id === DEFAULT) root.removeAttribute('data-sz-theme'); else root.setAttribute('data-sz-theme', id);
    sync();
  });

  window.SozoTheme = { set: apply, get: current };
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', sync); else sync();
})();
