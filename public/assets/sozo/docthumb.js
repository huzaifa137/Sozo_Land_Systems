/* Sozo: shows the first page of an estate's PDF (or its image) as the estate card cover.
   Markup: <canvas data-sz-pdf="/estate-file/1"></canvas> inside .sz-doc, with an optional
   <img data-sz-alt="photo url"> used when the PDF cannot be rendered. */
(function () {
  'use strict';
  var BASE = '/assets/vendor/pdfjs/';
  var libPromise = null;

  function loadLib() {
    if (libPromise) return libPromise;
    libPromise = new Promise(function (resolve, reject) {
      if (window.pdfjsLib) return resolve(window.pdfjsLib);
      var s = document.createElement('script');
      s.src = BASE + 'pdf.min.js';
      s.onload = function () {
        window.pdfjsLib.GlobalWorkerOptions.workerSrc = BASE + 'pdf.worker.min.js';
        resolve(window.pdfjsLib);
      };
      s.onerror = function () { reject(new Error('pdf.js failed to load')); };
      document.head.appendChild(s);
    });
    return libPromise;
  }

  function fallback(box) {
    box.classList.remove('is-loading');
    box.classList.add('is-failed');
    var img = box.querySelector('img[data-sz-alt]');
    if (img && !img.getAttribute('src')) { img.src = img.getAttribute('data-sz-alt'); }
    var c = box.querySelector('canvas'); if (c) c.style.display = 'none';
  }

  function render(canvas) {
    var box = canvas.closest('.sz-doc') || canvas.parentElement;
    box.classList.add('is-loading');
    var timeout = setTimeout(function () { fallback(box); }, 25000);
    loadLib().then(function (lib) {
      return lib.getDocument({ url: canvas.getAttribute('data-sz-pdf') }).promise;
    }).then(function (pdf) {
      return pdf.getPage(1);
    }).then(function (page) {
      var base = page.getViewport({ scale: 1 });
      var target = Math.min(900, Math.max(520, box.clientWidth * (window.devicePixelRatio || 1)));
      var vp = page.getViewport({ scale: target / base.width });
      canvas.width = Math.floor(vp.width); canvas.height = Math.floor(vp.height);
      return page.render({ canvasContext: canvas.getContext('2d'), viewport: vp }).promise;
    }).then(function () {
      clearTimeout(timeout);
      box.classList.remove('is-loading'); box.classList.add('is-ready');
    }).catch(function () { clearTimeout(timeout); fallback(box); });
  }

  function init() {
    var canvases = Array.prototype.slice.call(document.querySelectorAll('canvas[data-sz-pdf]'));
    if (!canvases.length) return;
    if (!('IntersectionObserver' in window)) { canvases.forEach(render); return; }
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) { if (en.isIntersecting) { io.unobserve(en.target); render(en.target); } });
    }, { rootMargin: '300px 0px' });
    canvases.forEach(function (c) { io.observe(c); });
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();
