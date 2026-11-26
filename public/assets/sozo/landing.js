(function () {
  'use strict';
  document.documentElement.classList.remove('no-js');
  var nav = document.querySelector('.nav');
  function onScroll() { nav.classList.toggle('solid', window.scrollY > 40); }
  onScroll(); window.addEventListener('scroll', onScroll, { passive: true });

  var burger = document.querySelector('.burger');
  function setMenu(open) {
    document.body.classList.toggle('menu-open', open);
    nav.classList.toggle('menu-open', open);
    burger.setAttribute('aria-expanded', open ? 'true' : 'false');
  }
  burger.addEventListener('click', function () { setMenu(!document.body.classList.contains('menu-open')); });
  document.querySelectorAll('.links a').forEach(function (a) { a.addEventListener('click', function () { setMenu(false); }); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') setMenu(false); });

  var items = document.querySelectorAll('.rv');
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) { if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); } });
    }, { threshold: .12, rootMargin: '0px 0px -40px 0px' });
    items.forEach(function (el, i) { el.style.transitionDelay = (i % 4) * 70 + 'ms'; io.observe(el); });
  } else { items.forEach(function (el) { el.classList.add('in'); }); }

  // Estates without a photo, or whose photo fails, get a drawn layout sketch.
  function sketchFor(el, seed, w, h) { if (window.SozoSketch) el.innerHTML = window.SozoSketch.estate(seed, w, h); }
  document.querySelectorAll('[data-sz-sketch]').forEach(function (el) { sketchFor(el, el.getAttribute('data-sz-sketch'), 240, 150); });
  document.querySelectorAll('[data-sz-hero-plan]').forEach(function (el) { sketchFor(el, 11, 320, 200); });
})();
