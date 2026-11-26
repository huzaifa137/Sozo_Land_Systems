{{-- SweetAlert2 (bundled by realrashid/sweet-alert), Sozo alert theme, flash popups and back-to-top. Include once inside <head>. --}}
@php
    $szFlash = [];
    foreach (['success' => 'success', 'error' => 'error', 'fail' => 'error', 'failed' => 'error', 'danger' => 'error', 'warning' => 'warning', 'info' => 'info'] as $szKey => $szType) {
        if (!session()->has($szKey)) {
            continue;
        }
        $szMsg = session($szKey);
        // Some controllers flash 'failed' and put the text in 'danger'.
        if ($szKey === 'failed' && session()->has('danger')) {
            $szMsg = session('danger');
        }
        if ($szKey === 'danger' && session()->has('failed')) {
            continue;
        }
        if (is_string($szMsg) && trim($szMsg) !== '') {
            $szFlash[] = ['type' => $szType, 'message' => $szMsg];
        }
    }
@endphp
<script>try{var szT=localStorage.getItem('sz-theme');if(szT&&/^[a-z0-9-]+$/.test(szT)&&szT!=='green')document.documentElement.setAttribute('data-sz-theme',szT);}catch(e){}</script>
<link rel="stylesheet" href="/assets/sozo/themes.css?v={{ @filemtime(public_path('assets/sozo/themes.css')) ?: 1 }}">
<script src="/assets/sozo/theme.js?v={{ @filemtime(public_path('assets/sozo/theme.js')) ?: 1 }}" defer></script>
<link rel="stylesheet" href="/assets/sozo/alerts.css?v={{ @filemtime(public_path('assets/sozo/alerts.css')) ?: 1 }}">
<script>window.SozoFlash = @json($szFlash);</script>
<script src="/vendor/sweetalert/sweetalert.all.js"></script>
<script src="/assets/sozo/alerts.js?v={{ @filemtime(public_path('assets/sozo/alerts.js')) ?: 1 }}"></script>
