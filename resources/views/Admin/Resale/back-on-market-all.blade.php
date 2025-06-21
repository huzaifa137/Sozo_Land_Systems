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

                        <form method="GET" action="{{ route('back-on-market-all') }}">
                            <div class="form-row align-items-center mb-4">
                                <div class="col-auto">
                                    <select name="estate" class="form-control">
                                        <option value="">-- Filter plots by Estate --</option>
                                        @foreach ($estates as $estate)
                                            <option value="{{ $estate->estate_name }}"
                                                {{ request('estate') == $estate->estate_name ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $estate->estate_name)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <br>
                                <link rel="stylesheet"
                                    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

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
