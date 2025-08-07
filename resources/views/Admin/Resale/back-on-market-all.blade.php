@include('includes.header')
<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">

            </div>
        </div>



        <div class="row ">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="">All Plots back on market </h4>
      <link rel="stylesheet"
                                    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
                        
                    <form method="GET" action="{{ route('back-on-market-all') }}">
                        <div class="form-row align-items-center mb-4">
                    
                            {{-- Filter Type Selector --}}
                            <div class="col-auto">
                                <select id="filterType" name="filter_type" class="form-control">
                                    <option value="">-- Select Filter Type --</option>
                                    <option value="estate" {{ request('filter_type') == 'estate' ? 'selected' : '' }}>Filter by Estate</option>
                                    <option value="date" {{ request('filter_type') == 'date' ? 'selected' : '' }}>Filter by Date</option>
                                    <option value="name" {{ request('filter_type') == 'name' ? 'selected' : '' }}>Filter by Name</option>
                                </select>
                            </div>
                    
                            <br>
                    
                            {{-- Estate Filter --}}
                            <div class="col-auto" id="estateFilter" style="display: none;">
                                <select name="estate" class="form-control">
                                    <option value="">-- Select Estate --</option>
                                    @foreach ($estates as $estate)
                                        <option value="{{ $estate->estate_name }}"
                                            {{ request('estate') == $estate->estate_name ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $estate->estate_name)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                    
                            <br>
                    
                            {{-- Date Filter --}}
                            <div class="col-auto" id="dateFilter" style="display: none;">
                                <input type="date" name="filter_date" class="form-control" value="{{ request('filter_date') }}">
                            </div>
                    
                            <br>
                    
                            {{-- Name Filter --}}
                            <div class="col-auto position-relative" id="nameFilter" style="display: none;">
                                <input type="text" id="buyerSearch" class="form-control" placeholder="Search buyer name..." autocomplete="off">
                                <input type="hidden" name="buyer_id" id="selectedBuyerId">
                                <div id="buyerSuggestions" class="list-group position-absolute w-100" style="z-index: 999;"></div>
                            </div>
                    
                            <br>
                    
                            {{-- Submit + Reset --}}
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">
                                    Search <i class="fas fa-search"></i>
                                </button>
                                <a href="{{ route('back-on-market-all') }}" class="btn btn-secondary">
                                    <i class="fas fa-sync-alt"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

            

                @if(request('filter_type') == 'name' && request('buyer_id'))
                    @php
                        $buyer = DB::table('buyers')->where('id', request('buyer_id'))->first();
                    @endphp
                    @if($buyer)
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                document.getElementById('buyerSearch').value = "{{ $buyer->firstname }} {{ $buyer->lastname }}";
                            });
                        </script>
                    @endif
                @endif



                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th> Estate</th>
                                        <th> Plot number</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($allResales as $key => $item)
                                        <?php
                                        $userinformation = DB::table('buyers')->where('id', $item->user_id)->first();
                                        ?>
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td> {{ $item->estate }} </td>
                                            <td> {{ $item->plot_number }} </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterType = document.getElementById('filterType');
        const estateFilter = document.getElementById('estateFilter');
        const dateFilter = document.getElementById('dateFilter');
        const nameFilter = document.getElementById('nameFilter');
        const buyerSearch = document.getElementById('buyerSearch');
        const buyerSuggestions = document.getElementById('buyerSuggestions');
        const selectedBuyerId = document.getElementById('selectedBuyerId');

        function toggleFilters() {
            const value = filterType.value;
            estateFilter.style.display = 'none';
            dateFilter.style.display = 'none';
            nameFilter.style.display = 'none';

            if (value === 'estate') {
                estateFilter.style.display = 'block';
            } else if (value === 'date') {
                dateFilter.style.display = 'block';
            } else if (value === 'name') {
                nameFilter.style.display = 'block';
            }
        }

        filterType.addEventListener('change', toggleFilters);
        toggleFilters(); // restore selected filter state on page load

        // Buyer live search
        buyerSearch.addEventListener('keyup', function () {
            const query = this.value;

            if (query.length < 2) {
                buyerSuggestions.innerHTML = '';
                return;
            }

            fetch(`/search-buyers?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    buyerSuggestions.innerHTML = '';

                    if (data.length === 0) {
                        buyerSuggestions.innerHTML = '<div class="list-group-item">No results found</div>';
                        return;
                    }

                    data.forEach(buyer => {
                        const item = document.createElement('div');
                        item.classList.add('list-group-item', 'list-group-item-action');
                        item.textContent = buyer.firstname + ' ' + buyer.lastname;
                        item.dataset.id = buyer.id;

                        item.addEventListener('click', () => {
                            buyerSearch.value = item.textContent;
                            selectedBuyerId.value = buyer.id;
                            buyerSuggestions.innerHTML = '';
                        });

                        buyerSuggestions.appendChild(item);
                    });
                });
        });
    });
</script>




    </div>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->
    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright ©
                SozoPropertiesLimited.com 2023</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">E-commerce Coming soon <a
                    href="javascript:void(0);" target="_blank">Sozo Properties</a></span>
        </div>
    </footer>
    <!-- partial -->
</div>
<!-- main-panel ends -->
</div>
<!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->
<!-- plugins:js -->
<script src="/assets/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="/assets/vendors/chart.js/Chart.min.js"></script>
<script src="/assets/vendors/progressbar.js/progressbar.min.js"></script>
<script src="/assets/vendors/jvectormap/jquery-jvectormap.min.js"></script>
<script src="/assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="/assets/vendors/owl-carousel-2/owl.carousel.min.js"></script>
<script src="/assets/js/jquery.cookie.js" type="text/javascript"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="/assets/js/off-canvas.js"></script>
<script src="/assets/js/hoverable-collapse.js"></script>
<script src="/assets/js/misc.js"></script>
<script src="/assets/js/settings.js"></script>
<script src="/assets/js/todolist.js"></script>
<!-- endinject -->
<!-- Custom js for this page -->
<script src="/assets/js/dashboard.js"></script>
<!-- End custom js for this page -->
</body>

</html>
