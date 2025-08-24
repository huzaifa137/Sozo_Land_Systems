@include('includes.header')
<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">

            </div>
        </div>


        <style>
            a {
                color: #FFF;
            }
        </style>

        <div class="row">
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h3 class="mb-0">{{ $count_estates_fully }}</h3>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box-success ">
                                    <span class="mdi mdi-arrow-top-right icon-item"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Total Number of plots Fully paid</h6>
                    </div>
                </div>
            </div>


            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">

            </div>


            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">

            </div>

            <div class="row ">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">All Plots Fully purchased in {{$estate_name}} </h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th> Client Name</th>
                                            <th> Plot Number</th>
                                            <th> Back on market</th>
                                            <th> Transfer Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($estate_data as $key => $data)

                                            <tr>

                                                <td><a
                                                        href="{{ url('view-reciept-info/' . $data->plot_number . '/' . $data->estate) }}">{{ $key + 1 }}</a>
                                                </td>
                                                <td><a
                                                        href="{{ url('view-reciept-info/' . $data->plot_number . '/' . $data->estate) }}">{{ $data->firstname }}</a>
                                                </td>
                                                <td><a
                                                        href="{{ url('view-reciept-info/' . $data->plot_number . '/' . $data->estate) }}">{{ $data->plot_number }}</a>
                                                </td>


                                                @if ($data->back_on_market_status == 0)
                                                    <td>
                                                        <a href="javascript:void();"
                                                            class="btn btn-outline-success btn-icon-text">
                                                            <i class="mdi mdi-check-circle btn-icon-prepend"></i> Normal
                                                        </a>
                                                    </td>
                                                @else
                                                    <td>
                                                        <a href="javascript:void();"
                                                            class="btn btn-outline-danger btn-icon-text">
                                                            <i class="mdi mdi-close-circle btn-icon-prepend"></i> Sold Back
                                                        </a>
                                                    </td>
                                                @endif



                                                @if ($data->shift_plot_status == 1 && $data->back_on_market_status == 1 && $data->shifted_to_status  == 0)
                                                    <td>
                                                        <a href="javascript:void();"
                                                            class="btn btn-outline-warning btn-icon-text">
                                                            <i class="mdi mdi-close-circle btn-icon-prepend"></i> Plot Relocated   </a>
                                                    </td>
                                                @elseif ($data->shift_plot_status == 0 && $data->back_on_market_status == 0 && $data->shifted_to_status == 2)
                                                    <td>
                                                        <a href="javascript:void();" class="btn btn-outline-info btn-icon-text">
                                                            <i class="mdi mdi-close-circle btn-icon-prepend"></i> Plot Reassigned
                                                        </a>
                                                    </td>
                                                @else 
                                                    <td>
                                                        -
                                                    </td>
                                                @endif


                                            </tr>

                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div>
                        <iframe
                            src="{{ asset('/public/estate_pdf/' . $estate_pdf_info) }}#toolbar=0&navpanes=0&scrollbar=0"
                            width="100%" height="900px"></iframe>
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