@include('includes.header')

@php
    $szRoleHere = \App\Models\AdminRegister::where('id', session('LoggedAdmin'))->value('admin_category');
    $szPrice = function ($value) {
        $digits = preg_replace('/[^\d.]/', '', (string) $value);
        return $digits !== '' ? number_format((float) $digits) : $value;
    };
    $szStatus = function ($status) {
        switch ((string) $status) {
            case '10': return ['Sold', 'brick'];
            case '1': return ['Pending approval', 'brass'];
            default: return ['Available', 'lake'];
        }
    };
@endphp

<div class="main-panel">
    <div class="content-wrapper">

        <div class="sz-page-head">
            <div>
                <h2>Houses for sale</h2>
                <p>Filter by location, price, bedrooms, land tenure or status. Open a house to see its photos and sale details.</p>
            </div>
            @if ($szRoleHere == 'SuperAdmin' || $szRoleHere == 'Admin')
                <div class="sz-actions">
                    <a class="btn btn-primary btn-lg" href="{{ route('add-house') }}"><i class="mdi mdi-plus"></i> Add a house</a>
                </div>
            @endif
        </div>

        <form id="filterForm" class="sz-filter" aria-label="Filter houses">
            <div class="form-group">
                <label for="f-location">Location</label>
                <input type="text" id="f-location" name="location" class="form-control" placeholder="Search by location">
            </div>
            <div class="form-group">
                <label for="f-min">Minimum price</label>
                <input type="number" id="f-min" name="min_price" class="form-control" placeholder="0" min="0">
            </div>
            <div class="form-group">
                <label for="f-max">Maximum price</label>
                <input type="number" id="f-max" name="max_price" class="form-control" placeholder="Any" min="0">
            </div>
            <div class="form-group">
                <label for="f-bed">Bedrooms</label>
                <select id="f-bed" name="bedroom" class="form-control">
                    <option value="">Any</option>
                    @for ($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group">
                <label for="f-tenure">Land tenure</label>
                <select id="f-tenure" name="land_tenure" class="form-control">
                    <option value="">Any</option>
                    <option value="Customary Land Tenure">Customary</option>
                    <option value="Freehold Land Tenure">Freehold</option>
                    <option value="Mailo Land Tenure">Mailo</option>
                    <option value="Leasehold Land Tenure">Leasehold</option>
                </select>
            </div>
            <div class="form-group">
                <label for="f-status">Status</label>
                <select id="f-status" name="status" class="form-control">
                    <option value="">Any</option>
                    <option value="0">Available</option>
                    <option value="1">Pending approval</option>
                    <option value="10">Sold</option>
                </select>
            </div>
        </form>

        <div id="houseGallery" class="sz-houses lightGallery">
            @php $szShown = 0; @endphp
            @foreach ($houses as $house)
                @php
                    $images = json_decode($house->house_images, true);
                    $firstImage = isset($images[0]) ? asset('storage/' . $images[0]) : null;
                    [$szStatusLabel, $szStatusTone] = $szStatus($house->status);
                @endphp

                @if ($firstImage)
                    @php $szShown++; @endphp
                    <article class="sz-house">
                        <div class="sz-house-img">
                            <img src="{{ $images[0] }}" alt="House in {{ $house->location }}" loading="lazy"
                                data-sz-fallback data-seed="{{ $house->id }}">
                            <span class="sz-chip {{ $szStatusTone }}">{{ $szStatusLabel }}</span>
                        </div>
                        <div class="sz-house-body">
                            <div class="sz-house-price"><small>UGX</small>{{ $szPrice($house->price) }}</div>
                            <div class="sz-house-loc"><i class="mdi mdi-map-marker"></i> {{ $house->location }}</div>
                            <div class="sz-house-facts">
                                <span><i class="mdi mdi-bed-empty"></i> {{ $house->bedroom }} {{ (int) $house->bedroom === 1 ? 'bedroom' : 'bedrooms' }}</span>
                                <span><i class="mdi mdi-certificate"></i> {{ str_replace(' Land Tenure', '', $house->LandTenure) }}</span>
                            </div>
                        </div>
                        <a class="sz-open" href="{{ route('house.details', ['id' => $house->id]) }}" aria-label="Open house in {{ $house->location }}"></a>
                    </article>
                @endif
            @endforeach

            @if ($szShown === 0)
                <div class="sz-empty"><i class="mdi mdi-home-map-marker"></i><b>No houses match</b>Change or clear the filters to see more.</div>
            @endif
        </div>

        <div class="mt-4">
            <div class="pagination-container d-flex justify-content-center">
                {{ $houses->links() }}
            </div>
        </div>

    </div>
    <!-- content-wrapper ends -->
    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="d-block text-center text-sm-left d-sm-inline-block">&copy; {{ date('Y') }} Sozo Property Consultants Limited</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Sozo Land Systems</span>
        </div>
    </footer>
</div>
</div>
</div>

<script src="/assets/vendors/js/vendor.bundle.base.js"></script>
<script>
    $(document).ready(function() {
        var timer;

        function loadHouses() {
            $.ajax({
                url: "{{ route('admin.sell.house.fetch') }}",
                type: "GET",
                data: $('#filterForm').serialize(),
                beforeSend: function() {
                    $('#houseGallery').html('<div class="sz-loading">Loading houses...</div>');
                },
                success: function(data, status, xhr) {
                    var isPartial = xhr.getResponseHeader('X-Partial');
                    if (isPartial) {
                        var newHtml = $(data).find('#houseGallery').html();
                        var newPagination = $(data).find('.pagination-container').html();
                        $('#houseGallery').html(newHtml);
                        $('.pagination-container').html(newPagination);
                    } else {
                        $('#houseGallery').html(data);
                    }
                },
                error: function(xhr) {
                    $('body').html(xhr.responseText);
                }
            });
        }

        // Wait for a short pause in typing so the list does not reload on every keystroke.
        $('#filterForm input, #filterForm select').on('input change', function() {
            clearTimeout(timer);
            timer = setTimeout(loadHouses, 250);
        });

        $('#filterForm').on('submit', function(e) {
            e.preventDefault();
            loadHouses();
        });
    });
</script>
</body>

</html>
