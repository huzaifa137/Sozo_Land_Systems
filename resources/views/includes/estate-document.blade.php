{{--
    Estate sketch viewer (PDF or image) with its own loading and error states.

    Usage:  @include('includes.estate-document', ['estate' => $specific_estate])
            @include('includes.estate-document', ['estate' => $specific_estate, 'lazy' => true])   (inside a modal: loads when it opens)

    It always uses the estate-file route, which finds the file wherever it was uploaded,
    so it keeps working whether the site is served from /public or from the project root.
--}}
@php
    $szDocEstate = $estate ?? null;
    $szDocKind = $szDocEstate ? $szDocEstate->documentKind() : null;
    $szDocUrl = $szDocEstate ? $szDocEstate->documentUrl() : null;
    $szDocDownload = $szDocEstate ? $szDocEstate->downloadUrl() : null;
    $szDocLazy = !empty($lazy);
    $szDocFrag = '#toolbar=0&navpanes=0&scrollbar=0';
@endphp

@once
<script>
    // Small helpers the viewer markup calls; defined first so an early "load" is never missed.
    window.szDoc = window.szDoc || {
        box: function (el) { return el && el.closest ? el.closest('[data-sz-doc]') : null; },
        done: function (el) {
            var b = this.box(el); if (!b) return;
            clearTimeout(b._szT);
            b.classList.add('is-ready');
            var l = b.querySelector('.sz-doc-loading'); if (l) l.hidden = true;
        },
        fail: function (el) {
            var b = this.box(el); if (!b || b.classList.contains('is-ready')) return;
            clearTimeout(b._szT);
            var l = b.querySelector('.sz-doc-loading'); if (l) l.hidden = true;
            var f = b.querySelector('.sz-doc-failed'); if (f) f.hidden = false;
        },
        start: function (b) {
            var el = b.querySelector('.sz-doc-frame, .sz-doc-img');
            if (!el) return;
            var src = el.getAttribute('data-src');
            if (src && !el.getAttribute('src')) {
                var l = b.querySelector('.sz-doc-loading'); if (l) l.hidden = false;
                var f = b.querySelector('.sz-doc-failed'); if (f) f.hidden = true;
                el.setAttribute('src', src);
            }
            clearTimeout(b._szT);
            var self = this;
            b._szT = setTimeout(function () { self.fail(el); }, 25000);
        }
    };
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-sz-doc]').forEach(function (b) {
            var modal = b.closest('.modal');
            if (modal) {
                modal.addEventListener('shown.bs.modal', function () { window.szDoc.start(b); });
            } else {
                window.szDoc.start(b);
            }
        });
    });
</script>
@endonce

<div class="sz-doc-viewer" data-sz-doc>
    @if ($szDocKind)
        <div class="sz-doc-bar">
            <div class="sz-doc-title">
                <b>Estate sketch</b>
                <small>{{ $szDocEstate->estate_name }} &middot; {{ $szDocKind === 'pdf' ? 'PDF' : 'Image' }}</small>
            </div>
            <div class="sz-doc-actions">
                <a href="{{ $szDocUrl }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm"><i class="mdi mdi-open-in-new"></i> Open in new tab</a>
                <a href="{{ $szDocDownload }}" class="btn btn-success btn-sm"><i class="mdi mdi-download"></i> Download</a>
            </div>
        </div>

        <div class="sz-doc-stage">
            <div class="sz-doc-state sz-doc-loading" role="status">
                <span class="sz-spin" aria-hidden="true"></span>
                <b>Loading the sketch</b>
                <small>Large plans can take a few seconds.</small>
            </div>
            <div class="sz-doc-state sz-doc-failed" hidden>
                <i class="mdi mdi-file-alert-outline" aria-hidden="true"></i>
                <b>The sketch is taking too long to show here</b>
                <small>Your browser may not display PDFs inside the page. You can still open or download it.</small>
                <a href="{{ $szDocUrl }}" target="_blank" rel="noopener" class="btn btn-primary btn-sm mt-2">Open in new tab</a>
            </div>

            @if ($szDocKind === 'pdf')
                <iframe class="sz-doc-frame" title="Sketch of {{ $szDocEstate->estate_name }}"
                    @if ($szDocLazy) data-src="{{ $szDocUrl . $szDocFrag }}" @else src="{{ $szDocUrl . $szDocFrag }}" data-src="{{ $szDocUrl . $szDocFrag }}" @endif
                    onload="window.szDoc && window.szDoc.done(this)"></iframe>
            @else
                <img class="sz-doc-img" alt="Sketch of {{ $szDocEstate->estate_name }}"
                    @if ($szDocLazy) data-src="{{ $szDocUrl }}" @else src="{{ $szDocUrl }}" data-src="{{ $szDocUrl }}" @endif
                    onload="window.szDoc && window.szDoc.done(this)"
                    onerror="window.szDoc && window.szDoc.fail(this)">
            @endif
        </div>
    @else
        <div class="sz-doc-state sz-doc-empty">
            <i class="mdi mdi-file-hidden" aria-hidden="true"></i>
            <b>No sketch has been uploaded for this estate yet</b>
            <small>A super admin can add one from the estate's Edit page.</small>
        </div>
    @endif
</div>
