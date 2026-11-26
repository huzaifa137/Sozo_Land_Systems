@include('includes.header')
<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">

            </div>
        </div>

        <?php 

        $whereConditions = ['estate' => $specific_estate->estate_name];

$not_fully_paid = DB::table('plots')->where($whereConditions)
    ->where('status', '!=', 'Fully payed')
    ->get();

$fully_paid_half = DB::table('plots')->where($whereConditions)
    ->where('status', '=', 'Fully payed')
    ->where('half_or_full', '=', '1')
    ->get();


$fully_paid_and_taken_plots_count = DB::table('plots')
    ->where($whereConditions)
    ->where('status', '=', 'Fully payed')
    ->count();

$unmatchedPlots = [];

// Implementation of the confirm agreement payment agreement :

foreach ($not_fully_paid as $plot) {
    $buyer = DB::table('buyers')
        ->where('plot_number', $plot->plot_number)
        ->where('estate', $plot->estate)
        ->where('next_installment_pay', '!=', 'Fully payed')
        ->where('request_permission', 1)
        ->first();

    if (!$buyer) {
        $unmatchedPlots[] = $plot;
    }
}

$unmatchedPlotsCollection = collect($unmatchedPlots);

$countAvailablePlots = $unmatchedPlotsCollection->merge($fully_paid_half);


        ?>


        <div class="row">
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <a href="{{ url('total-plots-in-estate/' . $estate_id) }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-9">
                                    <div class="d-flex align-items-center align-self-start">
                                        <h3 class="mb-0">{{ $specific_estate->number_of_plots }}</h3>
                                    </div>
                                </div>
                                <div class="col-3">

                                </div>
                            </div>
                            <h6 class="text-muted font-weight-normal mt-3">Plots in {{ $estate_name }} Estate</h6>
                        </div>
                </a>
            </div>
        </div>



        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
            <div class="card">
                <a href="{{ url('total-fully-paid-plots-in-estate/' . $estate_id) }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <!--@if ($connectedPlotsProcessedFullyPaid != 0)-->
                                    <!--    <h3 class="mb-0"> {{ $connectedPlotsProcessedFullyPaidJoined }}</h3>-->
                                    <!--@else-->
                                    <!--    <h3 class="mb-0">{{ $fully_paid_and_taken_plots_count }}</h3>-->
                                    <!--@endif-->
                                    <h3 class="mb-0">{{ $fully_paid_and_taken_plots_count }}</h3>
                                </div>
                            </div>
                            <div class="col-3">

                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal mt-3">Fully paid plots in
                            {{ $specific_estate->estate_name }}
                        </h6>
                    </div>
                </a>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
            <div class="card">
                <a href="{{ url('total-not-taken-plots-in-estate/' . $estate_id) }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">

                                    <!--@if ($connectedPlotsProcessedNotFullyPaid != 0)-->
                                    <!--    <h3 class="mb-0"> {{ $connectedPlotsProcessedNotFullyPaidjoined }}</h3>-->
                                    <!--@else-->
                                    <!--    {{-- {{ $count_estates_not_fully }} --}}-->
                                    <!--    <h3 class="mb-0">{{ count($countAvailablePlots) }}</h3>-->
                                    <!--@endif-->

                                    <h3 class="mb-0">{{ count($all_plot_estates_not_fully) }}</h3>
                                </div>
                            </div>
                            <div class="col-3">
                                {{-- <div class="icon icon-box-success">
                                    <span class="mdi mdi-arrow-top-right icon-item"></span>
                                </div> --}}
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal mt-3">Not taken plots in
                            {{ $specific_estate->estate_name }}
                        </h6>
                    </div>
            </div>
            </a>
        </div>


        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
            <div class="card">
                <a href="{{ url('total-half-plots-in-estate/' . $estate_id) }}">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h3 class="mb-0">{{ $total_half_plots }}</h3>
                                </div>
                            </div>
                            <div class="col-3">
                                {{-- <div class="icon icon-box-success">
                                    <span class="mdi mdi-arrow-top-right icon-item"></span>
                                </div> --}}
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal mt-3">Half plots in
                            {{ $specific_estate->estate_name }}
                        </h6>
                    </div>
            </div>
            </a>
        </div>
    </div>





    <div class="row ">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">

                    <?php
$userInfo = DB::table('admin_registers')->where('id', Session('LoggedAdmin'))->value('admin_category');
                    ?>

                    <h4 class="card-title">Estate Information</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th> Estate Name</th>
                                    <th> Estate Price </th>
                                    <th> Location </th>
                                    <th> Number of plots </th>
                                    <th> Download Sketch</th>
                                    <th> View Sketch</th>
                                    @if ($userInfo == 'SuperAdmin')
                                        <th> Action </th>
                                    @endif

                                </tr>
                            </thead>
                            <tbody>

                                <link rel="stylesheet"
                                    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

                                <div class="mt-3">

                                </div>

                                <tr>
                                    <td> {{ $specific_estate->estate_name }} </td>
                                    <td> {{ $specific_estate->estate_price }} </td>
                                    <td> {{ $specific_estate->location }} </td>
                                    <td> {{ $specific_estate->number_of_plots }} </td>
                                    <td>
                                        @if ($specific_estate->documentKind())
                                            <a href="{{ $specific_estate->downloadUrl() }}" class="btn btn-success">
                                                <i class="mdi mdi-download"></i> Download Sketch
                                            </a>
                                        @else
                                            <span class="text-muted">No sketch</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($specific_estate->documentKind())
                                            <a href="#estate-sketch" class="btn btn-primary">
                                                <i class="mdi mdi-eye-outline"></i> View sketch
                                            </a>
                                        @else
                                            <span class="text-muted">No sketch</span>
                                        @endif
                                    </td>

                                    @if ($userInfo == 'SuperAdmin')
                                        <td><a class="text-white btn btn-primary"
                                                href="{{ url('edit-estate-information/' . $specific_estate->id) }}"
                                                data-confirm="Please confirm you want to edit this plot Information">
                                                Edit </a></td>
                                    @endif

                                </tr>

                            </tbody>
                        </table>
                      
                    </div>
                </div>
            </div>
        </div>







        <div id="estate-sketch">
            @include('includes.estate-document', ['estate' => $specific_estate])
        </div>

    </div>
</div>

<footer class="footer">
    <div class="d-sm-flex justify-content-center justify-content-sm-between">
        <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright ©
            SozoPropertiesLimited.com 2023</span>
        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">
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