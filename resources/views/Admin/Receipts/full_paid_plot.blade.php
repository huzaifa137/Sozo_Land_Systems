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
    <link rel="stylesheet" href="/assets/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="/assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="/assets/images/favicon.png" />

    <meta name="csrf-token" content="{{ csrf_token() }}" />

</head>

<body>
    <div class="container-scroller">
        <!-- partial:../../partials/_sidebar.html -->
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
            <!-- partial:../../partials/_navbar.html -->
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

                        <div class="col-12 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="text-info">Assign a new plot to customer because plot {{ $plot_no }}
                                        in {{ $estate }} estate is already taken :</h3>

                                    @include('sweetalert::alert')

                                    @if (Session::get('success'))
                                        <div class="alert alert-success">
                                            {{ Session::get('success') }}
                                        </div>
                                    @endif

                                    @if (Session::get('error'))
                                        <div class="alert alert-danger">
                                            {{ Session::get('error') }}
                                        </div>
                                    @endif


                                    <form class="form-sample" action="{{ route('store-agreement-new-plot') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <p class="card-description">Enter the Following Information:</p>

                                        <input type="hidden" name="user_id" value="{{ $user_id }}">


                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Amount paid</label>
                                                    <div class="col-sm-9">
                                                        <input type="number" name="amount_paid" id="amount_paid"
                                                            class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-md-2 col-form-label">Date:</label>
                                                    <div class="col-sm-10">
                                                        <input type="date" name="Date_of_payment"
                                                            id="Date_of_payment" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="plot_information_block">
                                            <p class="card-description">Enter Plot Information:</p>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Estate</label>
                                                        <div class="col-sm-9">
                                                            <select name="Estate_plot" id="Estate_plot"
                                                                class="form-control">
                                                                <option value="">--Select Estate ---</option>
                                                                @foreach ($estates as $estate)
                                                                    <option value={{ $estate->estate_name }}>
                                                                        {{ $estate->estate_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label" >Location</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="location_plot"
                                                                id="location_plot" class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Width1</label>
                                                        <div class="col-sm-9">
                                                            <input type="number" name="plot_width1" id="plot_width1"
                                                                class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Width2</label>
                                                        <div class="col-sm-9">
                                                            <input type="number" name="plot_width2" id="plot_width2"
                                                                class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Height1</label>
                                                        <div class="col-sm-9">
                                                            <input type="number" name="plot_height1"
                                                                id="plot_height1" class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Height2</label>
                                                        <div class="col-sm-9">
                                                            <input type="number" name="plot_height2"
                                                                id="plot_height2" class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Plot Number</label>
                                                        <div class="col-sm-9">
                                                        
                                                            <select id="plot_number" name="plot_number" class="form-control" required>


                                                            </select>


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">

                                        </div>

                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group row">

                                                    <div class="col-sm-9">

                                                        <button type="submit" class="btn btn-primary">Sell</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



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

    <script type="text/javascript"></script>
    <script src="/assets/js/jquery.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#Estate_plot').change(function() {

                var value = $(this).val();

                $.ajax({
                    url: '/get-second-option',
                    type: 'GET',
                    data: {
                        value: value
                    },
                    success: function(data) {

                        $('#plot_number').empty();
                        $.each(data, function(index, item) {
                            var option = $('<option></option>').text(item .plot_number).val(item.plot_number);  
                            $('#plot_number').append(option);
                        });

                    },
                    error: function(data) {
                        $('body').html(data.responseText);
                    }
                });
            });


            $("#plot_number").change(function() {

                var selectedValue = $(this).val();

                $.ajax({
                    type: 'GET',
                    url: '/get-input-option',
                    data: {
                        selectedValue: selectedValue
                    },
                    success: function(response) {
                        $('#plot_width1').val(response.width_1);
                        $('#plot_width2').val(response.width_2);
                        $('#plot_height1').val(response.height_1);
                        $('#plot_height2').val(response.height_2);
                        $('#location_plot').val(response.location);
                    },
                    error: function(data) {
                        $('body').html(data.responseText);
                    }
                });
            });


        });
    </script>


    <script src="/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="/assets/vendors/select2/select2.min.js"></script>
    <script src="/assets/vendors/typeahead.js/typeahead.bundle.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="/assets/js/off-canvas.js"></script>
    <script src="/assets/js/hoverable-collapse.js"></script>
    <script src="/assets/js/misc.js"></script>
    <script src="/assets/js/settings.js"></script>
    <script src="/assets/js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="/assets/js/file-upload.js"></script>
    <script src="/assets/js/typeahead.js"></script>
    <script src="/assets/js/select2.js"></script>
    <!-- End custom js for this page -->
</body>

</html>
