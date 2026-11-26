@include('includes.header')

@php
    $szMoney = fn($n) => number_format((float) $n);
    $szFirstName = $LoggedAdminInfo->username ?? 'there';
@endphp

<div class="main-panel">
    <div class="content-wrapper">



        {{-- Hero: welcome + a plot plan drawn from the real sales counts --}}
        <section class="sz-hero" aria-label="Sales overview">
            <div>
                <span class="sz-date"><i class="mdi mdi-calendar-today"></i> {{ now()->format('l, j F Y') }}</span>
                <h2>Welcome back, {{ $szFirstName }}</h2>
                <p>Sales, instalments and plots across every estate, at a glance.</p>
                <div class="sz-actions">
                    <a class="btn btn-primary btn-lg" href="{{ route('admin-buyer') }}"><i class="mdi mdi-plus"></i> Sell a plot</a>
                    <a class="btn btn-secondary btn-lg" href="{{ route('current-sales') }}">Today's sales</a>
                </div>
            </div>

            <div class="sz-plan" data-sz-plan data-paid="{{ (int) $plots_fully_paid }}" data-under="{{ (int) $under_payment }}">
                <div class="sz-legend">
                    <span><i class="sz-dot" style="background:var(--sz-lake)"></i>Fully paid<b>{{ $szMoney($plots_fully_paid) }}</b></span>
                    <span><i class="sz-dot" style="background:var(--sz-brass)"></i>Under payment<b>{{ $szMoney($under_payment) }}</b></span>
                    @if ((int) $plots_fully_paid + (int) $under_payment === 0)
                        <span>No plots have been sold yet.</span>
                    @endif
                </div>
            </div>
        </section>

        {{-- Key figures --}}
        <section class="sz-kpis" aria-label="Key figures">
            @if ($User_access_right == 'SuperAdmin')
                <article class="sz-kpi">
                    <header>Today's sales <i class="mdi mdi-cash-100"></i></header>
                    <strong><small>UGX</small><span data-sz-count="{{ (float) $totalAmount }}">{{ $szMoney($totalAmount) }}</span></strong>
                </article>
                <article class="sz-kpi sky">
                    <header>This week <i class="mdi mdi-calendar-week"></i></header>
                    <strong><small>UGX</small><span data-sz-count="{{ (float) $totalweekSales }}">{{ $szMoney($totalweekSales) }}</span></strong>
                </article>
                <article class="sz-kpi lake">
                    <header>This month <i class="mdi mdi-calendar-month"></i></header>
                    <strong><small>UGX</small><span data-sz-count="{{ (float) $totalmonthSales }}">{{ $szMoney($totalmonthSales) }}</span></strong>
                </article>
            @endif

            <article class="sz-kpi lake">
                <header>Plots fully paid <i class="mdi mdi-check-decagram"></i></header>
                <strong><span data-sz-count="{{ (int) $plots_fully_paid }}">{{ $szMoney($plots_fully_paid) }}</span></strong>
            </article>
            <article class="sz-kpi">
                <header>Plots under payment <i class="mdi mdi-timer-sand"></i></header>
                <strong><span data-sz-count="{{ (int) $under_payment }}">{{ $szMoney($under_payment) }}</span></strong>
            </article>
            <article class="sz-kpi brick">
                <header>Income demanded <i class="mdi mdi-arrow-bottom-left"></i></header>
                <strong><small>UGX</small><span data-sz-count="{{ (float) $amount_in_debts }}">{{ $szMoney($amount_in_debts) }}</span></strong>
            </article>
        </section>

        <div class="sz-grid-2">
            {{-- Recent customers --}}
            <section class="sz-panel sz-recent" aria-label="Recent customers">
                <header>
                    <h3>Recent customers</h3>
                    <a href="{{ route('all-buyers') }}">All buyers</a>
                </header>
                @if ($recent_customers->count())
                    {{-- The 6 newest buyers as same-size cards: the photo box is a fixed shape whatever the picture is. --}}
                    <div class="sz-people">
                        @foreach ($recent_customers as $recent)
                            <a class="sz-person" href="{{ url('view-reciept/' . $recent->id) }}">
                                <span class="sz-person-photo">
                                    <img src="{{ '/public/public/national_id/' . $recent->national_id_front }}"
                                        alt="ID photo for {{ $recent->firstname }}" loading="lazy"
                                        data-sz-fallback data-seed="{{ $recent->id }}">
                                </span>
                                <span class="sz-person-text">
                                    <b>{{ $recent->firstname }}</b>
                                    <span>{{ $recent->estate }}, plot {{ $recent->plot_number }}</span>
                                </span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="sz-empty"><i class="mdi mdi-account-multiple-outline"></i><b>No customers yet</b>Sell a plot and the buyer shows up here.</div>
                @endif
            </section>

            {{-- Shortcuts --}}
            <section class="sz-panel" aria-label="Shortcuts">
                <header>
                    <h3>Shortcuts</h3>
                    <span>Jump straight to a list</span>
                </header>
                <nav class="sz-shortcuts">
                    <a class="sz-shortcut" href="{{ route('accomplished') }}">
                        <i class="mdi mdi-file-document-outline"></i>
                        <div><b>Fully purchased</b><span>Plots and houses that are paid in full</span></div>
                        <i class="mdi mdi-chevron-right"></i>
                    </a>
                    <a class="sz-shortcut" href="{{ route('current-sales') }}">
                        <i class="mdi mdi-cash-100"></i>
                        <div><b>Today's sales</b><span>Everything sold today</span></div>
                        <i class="mdi mdi-chevron-right"></i>
                    </a>
                    <a class="sz-shortcut" href="{{ route('weekly-records') }}">
                        <i class="mdi mdi-calendar-week"></i>
                        <div><b>Weekly sales</b><span>Plots and houses sold this week</span></div>
                        <i class="mdi mdi-chevron-right"></i>
                    </a>
                    <a class="sz-shortcut" href="{{ route('monthly-records') }}">
                        <i class="mdi mdi-calendar-month"></i>
                        <div><b>Monthly sales</b><span>Plots and houses sold this month</span></div>
                        <i class="mdi mdi-chevron-right"></i>
                    </a>
                    <a class="sz-shortcut" href="{{ route('payment-reminder') }}">
                        <i class="mdi mdi-bell-ring-outline"></i>
                        <div><b>Reminders</b><span>Clients whose instalments fall due today</span></div>
                        <i class="mdi mdi-chevron-right"></i>
                    </a>
                </nav>
            </section>
        </div>

        {{-- All customer sales --}}
        <section class="sz-panel" aria-label="All customer sales">
            <header>
                <h3>All customer sales</h3>
                <span>{{ $all_sales->total() }} in total</span>
            </header>
            <div class="table-responsive">
                <table class="table sz-stack">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Client</th>
                            <th>Estate</th>
                            <th>Plot</th>
                            <th>Payment mode</th>
                            <th>Amount paid</th>
                            <th>Balance</th>
                            <th>View</th>
                            @if ($User_access_right == 'SuperAdmin')
                                <th>Edit</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($all_sales as $key => $all_sale)
                            <tr>
                                <td>{{ $all_sales->firstItem() + $key }}</td>
                                <td><a href="{{ url('view-reciept/' . $all_sale->id) }}"><b>{{ $all_sale->firstname }}</b></a></td>
                                <td>{{ $all_sale->estate }}</td>
                                <td>{{ $all_sale->plot_number }}</td>
                                <td>{{ $all_sale->method_payment }}</td>
                                <td class="sz-money">{{ $szMoney($all_sale->amount_payed) }}</td>
                                <td>
                                    @if ((float) $all_sale->balance <= 0)
                                        <span class="sz-chip lake">Cleared</span>
                                    @else
                                        <span class="sz-money">{{ $szMoney($all_sale->balance) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ url('view-reciept/' . $all_sale->id) }}" class="btn btn-outline-success btn-sm btn-icon-text">
                                        <i class="mdi mdi-eye btn-icon-prepend"></i> View
                                    </a>
                                </td>
                                @if ($User_access_right == 'SuperAdmin')
                                    <td>
                                        <a href="{{ url('edit/' . $all_sale->id . '/' . $LoggedAdminInfo['id']) }}" class="btn btn-outline-warning btn-sm btn-icon-text">
                                            <i class="mdi mdi-pencil btn-icon-prepend"></i> Edit
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr><td colspan="9"><div class="sz-empty"><i class="mdi mdi-file-document-box"></i><b>No sales recorded</b>New sales will be listed here.</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $all_sales->links('pagination::bootstrap-5') }}</div>
        </section>

        {{-- Plots back on the market --}}
        <section class="sz-panel" aria-label="Plots back on the market">
            <header>
                <h3>Most recent plots back on market</h3>
                <a href="{{ route('back-on-market-all') }}">See all</a>
            </header>
            <div class="table-responsive">
                <table class="table sz-stack">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Plot</th>
                            <th>Estate</th>
                            <th>View</th>
                            <th>Plot status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($paid_not_in_cash as $key => $item)
                            @php
                                $userinformation = DB::table('buyers')->where('id', $item->user_id)->first();
                                $plot_status = DB::table('plots')
                                    ->where('estate', $item->estate)
                                    ->where('plot_number', $item->plot_number)
                                    ->first();
                            @endphp
                            <tr>
                                <td>{{ $paid_not_in_cash->firstItem() + $key }}</td>
                                <td>{{ @$userinformation->firstname . ' ' . @$userinformation->lastname }}</td>
                                <td>{{ @$userinformation->plot_number }}</td>
                                <td>{{ $item->estate }}</td>
                                <td>
                                    <a href="{{ url('view-reciept/' . $item->user_id) }}" class="btn btn-outline-info btn-sm btn-icon-text">
                                        <i class="mdi mdi-eye btn-icon-prepend"></i> View
                                    </a>
                                </td>
                                <td>
                                    @if (optional($plot_status)->status == 'Not taken')
                                        <span class="sz-chip lake">Plot available</span>
                                    @else
                                        <span class="sz-chip brick">Already resold</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6"><div class="sz-empty"><i class="mdi mdi-store"></i><b>Nothing back on the market</b>Plots that return to sale appear here.</div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $paid_not_in_cash->links('pagination::bootstrap-5') }}</div>
        </section>

    </div>
    <!-- content-wrapper ends -->
    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="d-block text-center text-sm-left d-sm-inline-block">&copy; {{ date('Y') }} Sozo Property Consultants Limited</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Sozo Land Systems</span>
        </div>
    </footer>
</div>
<!-- main-panel ends -->
</div>
<!-- page-body-wrapper ends -->
</div>
<!-- container-scroller ends -->
<script src="/assets/vendors/js/vendor.bundle.base.js"></script>
</body>

</html>
