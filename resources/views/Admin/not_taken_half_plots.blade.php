@include('includes.header')
<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">

            </div>
        </div>

        <div class="row">
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h3 class="mb-0">{{ $not_taken_half_plots_count }}</h3>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Total Half plots Not taken</h6>
                    </div>
                </div>
            </div>

            <div class="row ">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Half plots Not taken {{ $estate_name }} estate </h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th> Estate Name</th>
                                            <th> Plot Number</th>
                                            <th> Width1 </th>
                                            <th> Width2 </th>
                                            <th> height1 </th>
                                            <th> height2 </th>
                                            <th> Location</th>
                                            <th>Plot Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($not_taken_half_plots as $key => $data)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td> {{ $data->estate }} </td>
                                                <td> {{ $data->plot_number }} </td>
                                                <td> {{ $data->width_1 }} </td>
                                                <td> {{ $data->width_2 }} </td>
                                                <td> {{ $data->height_1 }} </td>
                                                <td> {{ $data->height_2 }} </td>
                                                <td> {{ $data->location }} </td>
                                                <td> {{ $data->status }}</td>

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
                    SozoPropertiesLimited.com 2024</span>
                <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">E-commerce Coming soon
                    <a href="javascript:void(0);" target="_blank">Sozo Properties</a></span>
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
