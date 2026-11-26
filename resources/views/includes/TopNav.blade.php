@php
    $szUser = $szUser ?? \Illuminate\Support\Facades\DB::table('admin_registers')->where('id', session('LoggedAdmin'))->first();
    $szRole = $szRole ?? ($szUser->admin_category ?? '');
    $szName = $szName ?? ($szUser->username ?? 'Account');
    $szSection = $szSection ?? 'Sozo Land Systems';
    $szTitle = $szTitle ?? 'Dashboard';
    $szDueToday = \App\Models\buyer::whereDate('next_installment_pay', \Carbon\Carbon::now()->format('Y/m/d'))->count();
@endphp
<button class="sz-burger" type="button" data-sz-burger aria-label="Open menu"><i class="mdi mdi-menu"></i></button>
<button class="sz-collapse" type="button" data-sz-collapse aria-label="Collapse sidebar" aria-pressed="false" title="Collapse sidebar (Ctrl+B)"><i class="mdi mdi-menu-open"></i></button>

<div class="sz-crumb">
    <small>{{ $szSection }}</small>
    <h1>{{ $szTitle }}</h1>
</div>

<div class="sz-top-actions">
    <button class="sz-find" type="button" data-sz-palette aria-label="Jump to a page">
        <i class="mdi mdi-magnify"></i><span>Jump to a page</span><kbd>Ctrl K</kbd>
    </button>

    @if ($szRole == 'SuperAdmin' || $szRole == 'Admin')
        <a class="btn btn-primary" href="{{ route('admin-buyer') }}"><i class="mdi mdi-plus"></i> Sell a plot</a>
    @endif

    @if ($szRole == 'SuperAdmin')
        <a class="sz-icon-btn" href="{{ route('payment-reminder') }}" aria-label="Today's reminders" title="Today's reminders">
            <i class="mdi mdi-bell-outline"></i>
            @if ($szDueToday > 0)<em class="sz-pill">{{ $szDueToday }}</em>@endif
        </a>
    @endif

    @php
        // To add a theme: add a row here and a matching html[data-sz-theme="id"] block in public/assets/sozo/themes.css
        $szThemes = [
            'green' => ['name' => 'Forest green', 'bg' => '#0d1a16', 'accent' => '#d2a44e'],
            'dark'  => ['name' => 'Black',        'bg' => '#08080a', 'accent' => '#e0b24f'],
            'light' => ['name' => 'Soft white',   'bg' => '#e8ebf0', 'accent' => '#a06908'],
        ];
    @endphp
    <div class="sz-menu-wrap">
        <button class="sz-icon-btn" type="button" data-sz-menu aria-haspopup="true" aria-expanded="false" aria-label="Change theme" title="Change theme">
            <i class="mdi mdi-palette-outline"></i>
        </button>
        <div class="sz-menu sz-theme-menu" role="menu" aria-label="Theme">
            <div class="sz-menu-head"><b>Appearance</b><span>Choose a theme</span></div>
            @foreach ($szThemes as $szId => $szT)
                <button type="button" class="sz-theme-opt" role="menuitemradio" aria-checked="false"
                        data-sz-theme-opt="{{ $szId }}" data-sz-allow-repeat data-bg="{{ $szT['bg'] }}">
                    <span class="sw" style="--bg: {{ $szT['bg'] }}; --ac: {{ $szT['accent'] }};"></span>
                    <span class="nm">{{ $szT['name'] }}</span>
                    <i class="mdi mdi-check ck"></i>
                </button>
            @endforeach
        </div>
    </div>

    <div class="sz-menu-wrap">
        <button class="sz-me" type="button" data-sz-menu aria-haspopup="true" aria-expanded="false">
            <span class="sz-avatar">{{ strtoupper(substr($szName, 0, 1)) }}</span>
            <span class="sz-name">{{ $szName }}</span>
            <i class="mdi mdi-chevron-down"></i>
        </button>
        <div class="sz-menu" role="menu">
            <div class="sz-menu-head"><b>{{ $szName }}</b><span>{{ $szRole }}</span></div>
            @if ($szRole == 'SuperAdmin')
                <a href="{{ url('/admin-register') }}" role="menuitem"><i class="mdi mdi-account-plus-outline"></i> Add a new admin</a>
                <a href="{{ route('user-information') }}" role="menuitem"><i class="mdi mdi-account-group-outline"></i> Manage users</a>
            @endif
            <a class="danger" href="{{ route('admin-logout') }}" role="menuitem"><i class="mdi mdi-logout"></i> Log out</a>
        </div>
    </div>
</div>
