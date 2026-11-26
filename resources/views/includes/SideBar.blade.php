@php
    $szUser = $szUser ?? \Illuminate\Support\Facades\DB::table('admin_registers')->where('id', session('LoggedAdmin'))->first();
    $szRole = $szRole ?? ($szUser->admin_category ?? '');
    $szName = $szName ?? ($szUser->username ?? 'Account');

    $szIsSuper = $szRole == 'SuperAdmin';
    $szIsAdmin = $szRole == 'Admin';
    $szIsStaff = $szIsSuper || $szIsAdmin;

    $szPendingHouses = \Illuminate\Support\Facades\DB::table('houses')->where('status', 1)->count();
    $szGrantPending = \Illuminate\Support\Facades\DB::table('buyers')
        ->where('next_installment_pay', '!=', 'Fully payed')
        ->where('request_permission', '=', 1)
        ->count();
    $szRemindersToday = \App\Models\buyer::whereDate('next_installment_pay', \Carbon\Carbon::now()->format('Y/m/d'))->count();
@endphp

<div class="sz-brand">
    <a href="{{ route('admin-dashboard') }}" aria-label="Sozo Property Consultants, go to dashboard">
        <img class="sz-logo" src="/assets/sozo/logo-light.png" alt="Sozo Property Consultants" width="403" height="379">
    </a>
</div>

<div class="sz-scroll">
    <ul class="sz-nav">

        @if ($szIsStaff)
            <li class="sz-heading">Overview</li>
            <li>
                <a class="sz-link" href="{{ route('admin-dashboard') }}">
                    <i class="mdi mdi-view-dashboard-outline"></i><span class="sz-label">Dashboard</span>
                </a>
            </li>
        @endif

        <li class="sz-heading">Land and houses</li>

        <li class="sz-group">
            <button class="sz-toggle" type="button" data-sz-group aria-expanded="false">
                <i class="mdi mdi-terrain"></i><span class="sz-label">Land</span><i class="mdi mdi-chevron-right sz-chev"></i>
            </button>
            <div class="sz-subwrap"><ul class="sz-sub">
                @if ($szIsStaff)
                    <li><a class="sz-link" href="{{ route('admin-buyer') }}"><span class="sz-label">Sell plot</span></a></li>
                @endif
                @if ($szIsAdmin)
                    <li><a class="sz-link" href="{{ route('admin.sell.house.fetch') }}"><span class="sz-label">Sell house</span></a></li>
                @endif
                <li><a class="sz-link" href="{{ route('estates') }}"><span class="sz-label">Estates</span></a></li>
                @if ($szIsSuper)
                    <li><a class="sz-link" href="{{ route('plots') }}"><span class="sz-label">Add plot</span></a></li>
                @endif
            </ul></div>
        </li>

        <li class="sz-group">
            <button class="sz-toggle" type="button" data-sz-group aria-expanded="false">
                <i class="mdi mdi-home-city-outline"></i><span class="sz-label">Houses</span>
                @if ($szIsSuper && $szPendingHouses > 0)<em class="sz-pill">{{ $szPendingHouses }}</em>@endif
                <i class="mdi mdi-chevron-right sz-chev"></i>
            </button>
            <div class="sz-subwrap"><ul class="sz-sub">
                @if ($szIsStaff)
                    <li><a class="sz-link" href="{{ route('add-house') }}"><span class="sz-label">Add house</span></a></li>
                    <li><a class="sz-link" href="{{ route('admin.sell.house.fetch') }}"><span class="sz-label">Sell house</span></a></li>
                @else
                    <li><a class="sz-link" href="{{ route('admin.sell.house.fetch') }}"><span class="sz-label">Houses</span></a></li>
                @endif
                @if ($szIsSuper)
                    <li>
                        <a class="sz-link" href="{{ route('approval.house.sell') }}">
                            <span class="sz-label">Pending approval</span>
                            @if ($szPendingHouses > 0)<em class="sz-pill">{{ $szPendingHouses }}</em>@endif
                        </a>
                    </li>
                @endif
            </ul></div>
        </li>

        @if ($szIsStaff)
            <li class="sz-heading">Money</li>

            <li class="sz-group">
                <button class="sz-toggle" type="button" data-sz-group aria-expanded="false">
                    <i class="mdi mdi-file-document-outline"></i><span class="sz-label">Receipts</span><i class="mdi mdi-chevron-right sz-chev"></i>
                </button>
                <div class="sz-subwrap"><ul class="sz-sub">
                    <li><a class="sz-link" href="{{ route('accomplished') }}"><span class="sz-label">Fully purchased</span></a></li>
                    <li><a class="sz-link" href="{{ route('pending-buyers') }}"><span class="sz-label">Under payments</span></a></li>
                </ul></div>
            </li>

            <li class="sz-group">
                <button class="sz-toggle" type="button" data-sz-group aria-expanded="false">
                    <i class="mdi mdi-chart-line"></i><span class="sz-label">Sales</span><i class="mdi mdi-chevron-right sz-chev"></i>
                </button>
                <div class="sz-subwrap"><ul class="sz-sub">
                    @if ($szIsSuper)
                        <li><a class="sz-link" href="{{ route('all-sales') }}"><span class="sz-label">All sales</span></a></li>
                    @endif
                    <li><a class="sz-link" href="{{ route('current-sales') }}"><span class="sz-label">Today's sales</span></a></li>
                    @if ($szIsSuper)
                        <li><a class="sz-link" href="{{ route('weekly-records') }}"><span class="sz-label">Weekly sales</span></a></li>
                        <li><a class="sz-link" href="{{ route('monthly-records') }}"><span class="sz-label">Monthly sales</span></a></li>
                    @endif
                </ul></div>
            </li>
        @endif

        @if ($szIsSuper)
            <li class="sz-group">
                <button class="sz-toggle" type="button" data-sz-group aria-expanded="false">
                    <i class="mdi mdi-cash-multiple"></i><span class="sz-label">Expenditures</span><i class="mdi mdi-chevron-right sz-chev"></i>
                </button>
                <div class="sz-subwrap"><ul class="sz-sub">
                    <li><a class="sz-link" href="{{ route('add-expenditure') }}"><span class="sz-label">Add expenditure</span></a></li>
                    <li><a class="sz-link" href="{{ route('expense-today') }}"><span class="sz-label">Today's expenditures</span></a></li>
                </ul></div>
            </li>
        @endif

        <li class="sz-heading">Clients</li>

        @if ($szIsStaff)
            <li class="sz-group">
                <button class="sz-toggle" type="button" data-sz-group aria-expanded="false">
                    <i class="mdi mdi-cached"></i><span class="sz-label">Resales</span><i class="mdi mdi-chevron-right sz-chev"></i>
                </button>
                <div class="sz-subwrap"><ul class="sz-sub">
                    <li><a class="sz-link" href="{{ route('search-land') }}"><span class="sz-label">Resale</span></a></li>
                    <li><a class="sz-link" href="{{ route('back-on-market') }}"><span class="sz-label">Back on market</span></a></li>
                    <li><a class="sz-link" href="{{ route('back-on-market-all') }}"><span class="sz-label">All back on market</span></a></li>
                </ul></div>
            </li>

            <li class="sz-group">
                <button class="sz-toggle" type="button" data-sz-group aria-expanded="false">
                    <i class="mdi mdi-swap-horizontal"></i><span class="sz-label">Transfers</span><i class="mdi mdi-chevron-right sz-chev"></i>
                </button>
                <div class="sz-subwrap"><ul class="sz-sub">
                    <li><a class="sz-link" href="{{ route('assign-empty-plot') }}"><span class="sz-label">Transfer to new plot</span></a></li>
                </ul></div>
            </li>
        @else
            <li>
                <a class="sz-link" href="{{ route('back-on-market-all') }}">
                    <i class="mdi mdi-store"></i><span class="sz-label">Back on market</span>
                </a>
            </li>
        @endif

        @if ($szIsSuper)
            <li class="sz-group">
                <button class="sz-toggle" type="button" data-sz-group aria-expanded="false">
                    <i class="mdi mdi-bell-ring-outline"></i><span class="sz-label">Reminders</span>
                    @if ($szRemindersToday > 0)<em class="sz-pill">{{ $szRemindersToday }}</em>@endif
                    <i class="mdi mdi-chevron-right sz-chev"></i>
                </button>
                <div class="sz-subwrap"><ul class="sz-sub">
                    <li><a class="sz-link" href="{{ route('set-reminder') }}"><span class="sz-label">Set reminder</span></a></li>
                    <li>
                        <a class="sz-link" href="{{ route('payment-reminder') }}">
                            <span class="sz-label">Today's reminders</span>
                            @if ($szRemindersToday > 0)<em class="sz-pill">{{ $szRemindersToday }}</em>@endif
                        </a>
                    </li>
                </ul></div>
            </li>

            <li>
                <a class="sz-link" href="{{ route('grant-agreement-permission') }}">
                    <i class="mdi mdi-key-outline"></i><span class="sz-label">Grant access</span>
                    @if ($szGrantPending > 0)<em class="sz-pill is-brass">{{ $szGrantPending }}</em>@endif
                </a>
            </li>
        @endif

        <li class="sz-heading">Tools</li>
        <li>
            <a class="sz-link" href="{{ route('search-module') }}">
                <i class="mdi mdi-magnify"></i><span class="sz-label">Search</span>
            </a>
        </li>
        @if ($szIsStaff)
            <li>
                <a class="sz-link" href="{{ route('all-posters') }}">
                    <i class="mdi mdi-image-multiple"></i><span class="sz-label">Posters</span>
                </a>
            </li>
        @endif
        @if ($szIsSuper)
            <li class="sz-group">
                <button class="sz-toggle" type="button" data-sz-group aria-expanded="false">
                    <i class="mdi mdi-shield-account-outline"></i><span class="sz-label">Administration</span><i class="mdi mdi-chevron-right sz-chev"></i>
                </button>
                <div class="sz-subwrap"><ul class="sz-sub">
                    <li><a class="sz-link" href="{{ route('user-information') }}"><span class="sz-label">View users</span></a></li>
                    <li><a class="sz-link" href="{{ route('backup.uploads') }}"><span class="sz-label">Backup</span></a></li>
                </ul></div>
            </li>
        @endif
    </ul>
</div>

<div class="sz-account">
    <span class="sz-avatar">{{ strtoupper(substr($szName, 0, 1)) }}</span>
    <div>
        <b>{{ $szName }}</b>
        <span>{{ $szRole }}</span>
    </div>
</div>
