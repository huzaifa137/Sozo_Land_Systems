<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sozo Admin Dashboard</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="/assets/vendors/jvectormap/jquery-jvectormap.css">
    <link rel="stylesheet" href="/assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="/assets/vendors/owl-carousel-2/owl.carousel.min.css">
    <link rel="stylesheet" href="/assets/vendors/owl-carousel-2/owl.theme.default.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="/assets/images/favicon.png" />
</head>

<body>
    <div class="container-scroller">

        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
                <a class="sidebar-brand brand-logo" href="{{ route('admin-dashboard') }}"><img
                        src="/assets/images/logo.svg" alt="logo" /></a>
                <a class="sidebar-brand brand-logo-mini" href="{{ route('admin-dashboard') }}"><img
                        src="/assets/images/logo-mini.svg" alt="logo" /></a>
            </div>

            @include('includes.SideBar')

        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_navbar.html -->
            <nav class="navbar p-0 fixed-top d-flex flex-row">
                <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
                    <a class="navbar-brand brand-logo-mini" href="{{ route('admin-dashboard') }}"><img
                            src="/assets/images/logo-mini.svg" alt="logo" /></a>
                </div>
                <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button"
                        data-toggle="minimize">
                        <span class="mdi mdi-menu"></span>
                    </button>

                    @include('includes.TopNav')


                    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                        data-toggle="offcanvas">
                        <span class="mdi mdi-format-line-spacing"></span>
                    </button>
                </div>
            </nav>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">

                        </div>
                    </div>

                    @if (Session::get('error'))
                           <div class="alert alert-danger">
                            {{Session::get('error')}}
                           </div>
                        @endif


                        @if (Session::get('success'))
                        <div class="alert alert-success">
                         {{Session::get('success')}}
                        </div>
                     @endif

                        {{-- <input type="hidden" name="admin_category" value="{{$LoggedAdminInfo['admin_category']}}"> --}}

                    <div class="row">
                        <div class="col-sm-4 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Today's Sales</h5>
                                    <div class="row">
                                        <div class="col-8 col-sm-12 col-xl-8 my-auto">
                                            <div class="d-flex d-sm-block d-md-flex align-items-center">
                                                <h2 class="mb-0">{{ $totalAmount }}/=</h2>
                                                {{-- <p class="text-success ms-2 mb-0 font-weight-medium">+3.5%</p> --}}
                                            </div>
                                            {{-- <h6 class="text-muted font-weight-normal">11.38% Since last month</h6> --}}
                                        </div>
                                        <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                                            <i class="icon-lg mdi mdi-codepen text-primary ms-auto"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Weekly Sales</h5>
                                    <div class="row">
                                        <div class="col-8 col-sm-12 col-xl-8 my-auto">
                                            <div class="d-flex d-sm-block d-md-flex align-items-center">
                                                <h2 class="mb-0">{{ $totalweekSales }}/=</h2>
                                                {{-- <p class="text-success ms-2 mb-0 font-weight-medium">+8.3%</p> --}}
                                            </div>
                                            {{-- <h6 class="text-muted font-weight-normal"> 9.61% Since last month</h6> --}}
                                        </div>
                                        <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                                            <i class="icon-lg mdi mdi-wallet-travel text-danger ms-auto"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Monthly Sales</h5>
                                    <div class="row">
                                        <div class="col-8 col-sm-12 col-xl-8 my-auto">
                                            <div class="d-flex d-sm-block d-md-flex align-items-center">
                                                <h2 class="mb-0">{{ $totalmonthSales }}/=</h2>
                                                {{-- <p class="text-danger ms-2 mb-0 font-weight-medium">-2.1% </p> --}}
                                            </div>
                                            {{-- <h6 class="text-muted font-weight-normal">2.27% Since last month</h6> --}}
                                        </div>
                                        <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                                            <i class="icon-lg mdi mdi-monitor text-success ms-auto"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-xl-4 col-sm-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-9">
                                            <div class="d-flex align-items-center align-self-start">
                                                <h3 class="mb-0">{{ $plots_fully_paid }}</h3>
                                                {{-- <p class="text-success ms-2 mb-0 font-weight-medium">100%</p> --}}
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            {{-- <div class="icon icon-box-success ">
                          <span class="mdi mdi-arrow-top-right icon-item"></span>
                        </div> --}}
                                        </div>
                                    </div>
                                    <h6 class="text-muted font-weight-normal">Plots fully paid</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-sm-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-9">
                                            <div class="d-flex align-items-center align-self-start">
                                                <h3 class="mb-0">{{ $under_payment }}</h3>
                                                {{-- <p class="text-success ms-2 mb-0 font-weight-medium">+11%</p> --}}
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            {{-- <div class="icon icon-box-success">
                          <span class="mdi mdi-arrow-top-right icon-item"></span>
                        </div> --}}
                                        </div>
                                    </div>
                                    <h6 class="text-muted font-weight-normal">Plots under payments</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-sm-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-9">
                                            <div class="d-flex align-items-center align-self-start">
                                                <h3 class="mb-0">{{ $amount_in_debts }}/=</h3>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="icon icon-box-danger">
                                                <span class="mdi mdi-arrow-bottom-left icon-item"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <h6 class="text-muted font-weight-normal">Income demanded</h6>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xl-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Most Recent Customers</h4>
                                    <div class="owl-carousel owl-theme full-width owl-carousel-dash portfolio-carousel"
                                        id="owl-carousel-basic">
                                        @foreach ($all_sales as $all_sale)
                                            <div class="item">
                                                <img src="{{ '/public/national_id/' . $all_sale->national_id_front }}"
                                                    alt="" alt="">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-row justify-content-between">
                                        <h4 class="card-title mb-1">Quick Links</h4>
                                        <p class="text-muted mb-1">Your data status</p>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="preview-list">
                                                <div class="preview-item border-bottom">
                                                    <div class="preview-thumbnail">
                                                        <div class="preview-icon bg-primary">
                                                            <i class="mdi mdi-file-document"></i>
                                                        </div>
                                                    </div>
                                                    <div class="preview-item-content d-sm-flex flex-grow">
                                                        <a href="{{ route('accomplished') }}"
                                                            style="text-decoration: none;color:white;">
                                                            <div class="flex-grow">
                                                                <h6 class="preview-subject">Full purchased plots and
                                                                    Houses</h6>
                                                                <p class="text-muted mb-0">See details of full
                                                                    purchased plots and houses</p>
                                                            </div>
                                                        </a>

                                                    </div>
                                                </div>
                                                <div class="preview-item border-bottom">
                                                    <div class="preview-thumbnail">
                                                        <div class="preview-icon bg-success">
                                                            <i class="mdi mdi-cloud-download"></i>
                                                        </div>
                                                    </div>
                                                    <div class="preview-item-content d-sm-flex flex-grow">
                                                        <a href="{{ route('current-sales') }}"
                                                            style="text-decoration: none;color:white;">
                                                            <div class="flex-grow">
                                                                <h6 class="preview-subject">Today's Sales</h6>
                                                                <p class="text-muted mb-0">See details of full
                                                                    purchased plots and houses today</p>
                                                            </div>
                                                        </a>

                                                    </div>
                                                </div>
                                                <div class="preview-item border-bottom">
                                                    <div class="preview-thumbnail">
                                                        <div class="preview-icon bg-info">
                                                            <i class="mdi mdi-clock"></i>
                                                        </div>
                                                    </div>
                                                    <div class="preview-item-content d-sm-flex flex-grow">
                                                        <a href="{{ route('weekly-records') }}"
                                                            style="text-decoration: none;color:white;">
                                                            <div class="flex-grow">
                                                                <h6 class="preview-subject">Weekly's Sales</h6>
                                                                <p class="text-muted mb-0">See details of weekly plots
                                                                    and houses purchases</p>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="preview-item border-bottom">
                                                    <div class="preview-thumbnail">
                                                        <div class="preview-icon bg-danger">
                                                            <i class="mdi mdi-email-open"></i>
                                                        </div>
                                                    </div>
                                                    <div class="preview-item-content d-sm-flex flex-grow">
                                                        <a href="{{ route('monthly-records') }}"
                                                            style="text-decoration: none;color:white;">
                                                            <div class="flex-grow">
                                                                <h6 class="preview-subject">Monthly's Sales</h6>
                                                                <p class="text-muted mb-0">See details of monthly plots
                                                                    and houses purchase</p>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="preview-item">
                                                    <div class="preview-thumbnail">
                                                        <div class="preview-icon bg-warning">
                                                            <i class="mdi mdi-chart-pie"></i>
                                                        </div>
                                                    </div>
                                                    <div class="preview-item-content d-sm-flex flex-grow">
                                                        <a href="{{ route('payment-reminder') }}"
                                                            style="text-decoration: none;color:white;">
                                                            <div class="flex-grow">
                                                                <h6 class="preview-subject">Reminders</h6>
                                                                <p class="text-muted mb-0">See details of client
                                                                    reminders today</p>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row ">
                        <div class="col-12 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">All Customer Sales </h4>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th> Client Name </th>
                                                    <th> Estate </th>
                                                    <th> Plot no.</th>
                                                    <th> Payment Mode </th>
                                                    <th> Amount paid </th>
                                                    <th> Balance </th>
                                                    <th> View </th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($all_sales as $all_sale)
                                                    <tr>

                                                        <td>
                                                            <img style="width: 100%; height:100%"
                                                                src="{{ '/public/national_id/' . $all_sale->national_id_front }}"
                                                                alt="" id="week_img">
                                                            <span class="ps-2">{{ $all_sale->firstname }}</span>
                                                        </td>


                                                        <td> {{ $all_sale->estate }} </td>
                                                        <td> {{ $all_sale->plot_number }} </td>
                                                        <td> {{ $all_sale->method_payment }} </td>
                                                        <td> {{ $all_sale->amount_payed }} </td>
                                                        <td> {{ $all_sale->balance }} </td>

                                                        <td><a href="{{ 'view-reciept/' . $all_sale->id }}"
                                                                class="btn btn-outline-success btn-icon-text">
                                                                <i class="mdi mdi-eye btn-icon-prepend"></i> View </a>
                                                        </td>

                                                        {{-- <td><a href="{{ 'edit/' . $all_sale->id }}"
                                                                class="btn btn-outline-warning btn-icon-text">
                                                                <i class="mdi mdi-eye btn-icon-prepend"></i> Edit </a>
                                                        </td> --}}

                                                        {{-- {{}} --}}

                                                        <td><a href="{{ 'edit/'.$all_sale->id .'/'. $LoggedAdminInfo['id'] }}"
                                                            class="btn btn-outline-warning btn-icon-text">
                                                            <i class="mdi mdi-eye btn-icon-prepend"></i> Edit </a>
                                                    </td>

                                                        {{-- <td><a href="{{ 'delete/' . $all_sale->id }}"
                                                                class="btn btn-outline-danger btn-icon-text">
                                                                <i class="mdi mdi-eye btn-icon-prepend"></i> delete </a>
                                                        </td> --}}
                                                @endforeach

                                                </tr>
            
                                            </tbody>
                                            
                                        </table>

                                        <br> <br>
                                        <span>

                                            {{ $all_sales->links() }}

                                        </span>
                                        
                                        <style>
                                            .w-5{
                                                display: none;
                                            }
                                        </style>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                    </div>

                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright ©
                            SozoPropertiesLimited.com 2023</span>
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
