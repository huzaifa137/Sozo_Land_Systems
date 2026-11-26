@include('includes.header')

@php
    $szRoleHere = \App\Models\AdminRegister::where('id', session('LoggedAdmin'))->value('admin_category');

    // Free photos from Unsplash. If one fails to load, the card shows a drawn layout sketch instead.
    $szPhotos = [
        'photo-1500382017468-9049fed747ef',
        'photo-1472214103451-9374bd1c798e',
        'photo-1441974231531-c6227db76b6e',
        'photo-1500530855697-b586d89ba3ee',
        'photo-1501785888041-af3ef285b470',
        'photo-1506905925346-21bda4d32df4',
    ];
    $szPrice = function ($value) {
        $digits = preg_replace('/[^\d.]/', '', (string) $value);
        return $digits !== '' ? 'UGX ' . number_format((float) $digits) : $value;
    };
@endphp

<div class="main-panel">
    <div class="content-wrapper">

        <div class="sz-page-head">
            <div>
                <h2>Estates</h2>
                <p>Every estate Sozo is selling, with its location, price and number of plots.</p>
            </div>
            @if ($szRoleHere == 'SuperAdmin')
                <div class="sz-actions">
                    <a class="btn btn-primary btn-lg" href="{{ route('add-estate') }}"><i class="mdi mdi-plus"></i> Create an estate</a>
                </div>
            @endif
        </div>

        <section class="sz-kpis" aria-label="Estate figures">
            <article class="sz-kpi">
                <header>Total estates <i class="mdi mdi-terrain"></i></header>
                <strong><span data-sz-count="{{ (int) $count_estates }}">{{ number_format((int) $count_estates) }}</span></strong>
            </article>
            @if ($szRoleHere == 'SuperAdmin')
                <article class="sz-kpi lake">
                    <header>Fully purchased plots <i class="mdi mdi-check-decagram"></i></header>
                    <strong><span data-sz-count="{{ (int) $number_plots }}">{{ number_format((int) $number_plots) }}</span></strong>
                </article>
                <article class="sz-kpi sky">
                    <header>Plots not taken <i class="mdi mdi-map-marker-outline"></i></header>
                    <strong><span data-sz-count="{{ (int) $Not_fully_paid_plots }}">{{ number_format((int) $Not_fully_paid_plots) }}</span></strong>
                </article>
            @endif
        </section>

        <div id="szEstates">
            <div class="sz-toolbar">
                <div class="sz-search">
                    <i class="mdi mdi-magnify"></i>
                    <input type="search" class="form-control" id="searchBar" placeholder="Search estates by name or location"
                        aria-label="Search estates" data-sz-filter="#szEstates [data-search]" data-sz-empty="#szNoMatch">
                </div>
                <div class="sz-seg" role="group" aria-label="Change view">
                    <button type="button" class="on" data-sz-view="cards" data-sz-target="#szEstates" aria-pressed="true"><i class="mdi mdi-view-grid"></i> Cards</button>
                    <button type="button" data-sz-view="table" data-sz-target="#szEstates" aria-pressed="false"><i class="mdi mdi-table"></i> Table</button>
                </div>
            </div>

            <div class="sz-estates">
                @foreach ($estates as $key => $item)
                    @php
                        $szKind = $item->documentKind();
                        $szUrl = $item->documentUrl();
                        $szPhoto = 'https://images.unsplash.com/' . $szPhotos[$item->id % count($szPhotos)] . '?auto=format&fit=crop&w=900&q=70';
                    @endphp
                    <article class="sz-estate" data-search="{{ strtolower($item->estate_name . ' ' . $item->location) }}">
                        @if ($szKind)
                            <div class="sz-estate-cover sz-doc">
                                @if ($szKind === 'pdf')
                                    <canvas data-sz-pdf="{{ $szUrl }}" aria-label="First page of the {{ $item->estate_name }} PDF"></canvas>
                                    <img class="sz-doc-alt" data-sz-alt="{{ $szPhoto }}" alt="Land at {{ $item->estate_name }}" data-sz-fallback data-seed="{{ $item->id }}">
                                @else
                                    <img class="doc-img" src="{{ $szUrl }}" alt="Plan of {{ $item->estate_name }}" loading="lazy" data-sz-fallback data-seed="{{ $item->id }}">
                                @endif
                                <a class="sz-doc-open" href="{{ $szUrl }}" target="_blank" rel="noopener">
                                    <i class="mdi mdi-open-in-new"></i> Open {{ $szKind === 'pdf' ? 'PDF' : 'image' }}
                                </a>
                                <span class="sz-estate-loc"><i class="mdi mdi-map-marker"></i> {{ $item->location }}</span>
                            </div>
                        @else
                            <div class="sz-estate-cover">
                                <img src="{{ $szPhoto }}" alt="Land at {{ $item->estate_name }}" loading="lazy" data-sz-fallback data-seed="{{ $item->id }}">
                                <div class="sz-estate-sketch" data-sz-sketch="{{ $item->id }}" aria-hidden="true"></div>
                                <span class="sz-estate-loc"><i class="mdi mdi-map-marker"></i> {{ $item->location }}</span>
                            </div>
                        @endif
                        <div class="sz-estate-body">
                            <h3>{{ $item->estate_name }}</h3>
                            <div class="sz-estate-meta">
                                <div><small>Estate price</small><b>{{ $szPrice($item->estate_price) }}</b></div>
                                <div style="text-align:right"><small>Plots</small><b>{{ $item->number_of_plots }}</b></div>
                            </div>
                        </div>
                        <a class="sz-open" href="{{ url('view-estate/' . $item->id) }}" aria-label="Open {{ $item->estate_name }}"></a>
                    </article>
                @endforeach
            </div>

            <div class="sz-estates-table">
                <div class="sz-panel">
                    <div class="table-responsive">
                        <table class="table sz-stack" id="estatesTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Estate name</th>
                                    <th>Estate price</th>
                                    <th>Location</th>
                                    <th>Plots</th>
                                    <th>Estate file</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($estates as $key => $item)
                                    <tr data-search="{{ strtolower($item->estate_name . ' ' . $item->location) }}">
                                        <td>{{ $key + 1 }}</td>
                                        <td><a href="{{ url('view-estate/' . $item->id) }}"><b>{{ $item->estate_name }}</b></a></td>
                                        <td class="sz-money">{{ $szPrice($item->estate_price) }}</td>
                                        <td>{{ $item->location }}</td>
                                        <td>{{ $item->number_of_plots }}</td>
                                        <td>
                                            @if ($item->documentKind())
                                                <a href="{{ $item->documentUrl() }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm">Open {{ $item->documentKind() === 'pdf' ? 'PDF' : 'image' }}</a>
                                            @else
                                                <span class="text-muted">None</span>
                                            @endif
                                        </td>
                                        <td><a href="{{ url('view-estate/' . $item->id) }}" class="btn btn-outline-success btn-sm">View estate</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="sz-empty" id="szNoMatch" style="display:none">
                <i class="mdi mdi-map-search-outline"></i><b>No estate matches that search</b>Try a shorter name or a different location.
            </div>

            @if ($estates->isEmpty())
                <div class="sz-empty">
                    <i class="mdi mdi-terrain"></i><b>No estates yet</b>
                    @if ($szRoleHere == 'SuperAdmin')
                        Create the first estate to start adding plots.
                    @else
                        Estates will appear here once a super admin creates them.
                    @endif
                </div>
            @endif
        </div>

    </div>
    <!-- content-wrapper ends -->
    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="d-block text-center text-sm-left d-sm-inline-block">&copy; {{ date('Y') }} Sozo Property Consultants Limited</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Estates without a file show a free Unsplash photo</span>
        </div>
    </footer>
</div>
<!-- main-panel ends -->
</div>
<!-- page-body-wrapper ends -->
</div>
<!-- container-scroller ends -->
<script src="/assets/vendors/js/vendor.bundle.base.js"></script>
<script src="/assets/sozo/docthumb.js" defer></script>
</body>

</html>
