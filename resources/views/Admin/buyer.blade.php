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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <style>
        /* Custom CSS to change the title color */
        .swal-title {
            color: 'red' !important;
            /* Change the color as per your preference */
        }

    </style>


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
                                    <h4 class="card-title">Buyer Information :</h4>

                                    @include('sweetalert::alert')

                                    @if(Session::get('success'))
                                        <div class="alert alert-success">
                                            {{ Session::get('success') }}
                                        </div>
                                    @endif

                                    @if(Session::get('failed'))
                                        <div class="alert alert-danger">
                                            {{ Session::get('danger') }}
                                        </div>
                                    @endif


                                    <form class="form-sample" id="myForm"
                                        action="{{ route('store-buyer-details') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <p class="card-description">Enter Customer Buyer Information:</p>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">First Name</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="firstname" id="firstname"
                                                            class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Last Name</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="lastname" id="lastname"
                                                            class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Gender</label>
                                                    <div class="col-sm-9">
                                                        <select name="gender" id="gender" class="form-control" required>
                                                            <option value="Male">Male</option>
                                                            <option value="Female">Female</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Date of Birth</label>
                                                    <div class="col-sm-9">
                                                        <input type="date" name="date_of_birth" id="date_of_birth"
                                                            class="form-control" placeholder="dd/mm/yyyy" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Nin Number</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="NIN" id="NIN" class="form-control"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">CardNumber</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="card_number" id="card_number"
                                                            class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">National ID Front</label>
                                                    <div class="col-sm-9">
                                                        <input type="file" name="national_id_front"
                                                            id="national_id_front" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">National ID back</label>
                                                    <div class="col-sm-9">
                                                        <input type="file" name="national_id_back" id="national_id_back"
                                                            class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label mt-2">Land Poster</label>
                                                    <div class="col-sm-9">
                                                        <select name="land_poster" id="land_poster" class="form-control"
                                                            required>
                                                            <option value="">--- Poster Payment---</option>
                                                            <option value="Paid">Paid</option>
                                                            {{-- <option value="Not paid">Not paid</option> --}}
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>


                                            <p class="card-description">Payment Method:</p>

                                            <div class="row">
                                                <div class="col-md-10">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Select Payment
                                                            Method:</label>
                                                        <div class="col-sm-9">
                                                            <select name="payment_method" id="payment_method"
                                                                class="form-control" required>
                                                                <option value="">--- Select Payment ---</option>
                                                                {{-- <option value="Full_payment">Full Payment</option> --}}
                                                                <option value="paying_in_installments">Paying in
                                                                    Installments</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row" id="installment_display" style="display: none">
                                                <div class="col-md-10">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Select Installment
                                                            period:</label>
                                                        <div class="col-sm-9">
                                                            <select name="installment_payments"
                                                                id="installment_payments" class="form-control" required>
                                                                <option value="">--Select period for Installment---
                                                                </option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                                <option value="5">5</option>
                                                                <option value="6">6</option>
                                                                <option value="7">7</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-md-10">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Purchase
                                                            Category:</label>
                                                        <div class="col-sm-9">
                                                            <select name="purchase_type" id="purchase_type"
                                                                class="form-control" required>
                                                                <option value="">---Select Category ---</option>
                                                                <option value="plot">Buying a plot</option>
                                                                <option value="house">Buying a house</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="plot_information_block" style="display: none;">
                                                <p class="card-description">Enter Plot Information:</p>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label">Estate</label>
                                                            <div class="col-sm-9">
                                                                <select name="Estate_plot" id="Estate_plot"
                                                                    class="form-control">
                                                                    <option value="">--Select Estate ---</option>
                                                                    @foreach($estates as $estate)
                                                                        <option value={{ $estate->estate_name }}>
                                                                            {{ $estate->estate_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                    </div>


                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label">Location</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" name="location_plot"
                                                                    id="location_plot" class="form-control" />
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
                                                                    class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label">Width2</label>
                                                            <div class="col-sm-9">
                                                                <input type="number" name="plot_width2" id="plot_width2"
                                                                    class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label">Height1</label>
                                                            <div class="col-sm-9">
                                                                <input type="number" name="plot_height1"
                                                                    id="plot_height1" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label">Height2</label>
                                                            <div class="col-sm-9">
                                                                <input type="number" name="plot_height2"
                                                                    id="plot_height2" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label">Plot Number</label>
                                                            <div class="col-sm-9">

                                                                <select id="plot_number" class="form-control">
                                                                    <option value="">---Select Plot ---</option>


                                                                </select>


                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="house_information_block" style="display: none;">
                                                <p class="card-description">Enter House Information:</p>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label">Estate</label>
                                                            <div class="col-sm-9">
                                                                <select name="Estate_house" id="Estate_house"
                                                                    class="form-control">
                                                                    @foreach($estates as $estate)
                                                                        <option value={{ $estate->estate_name }}>
                                                                            {{ $estate->estate_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                    </div>


                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label">Location</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" name="location_house"
                                                                    id="location_house" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label">Width1</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" name="house_width1" id="house_width1"
                                                                    class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label">Width2</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" name="house_width2" id="house_width2"
                                                                    class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label">Height1</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" name="house_height1"
                                                                    id="house_height1" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label">Height2</label>
                                                            <div class="col-sm-9">
                                                                <input type="text" name="house_height2"
                                                                    id="house_height2" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group row">
                                                            <label class="col-sm-3 col-form-label">Plot Number</label>
                                                            <div class="col-sm-9">
                                                                <select name="house_plot_number" id="house_plot_number"
                                                                    class="form-control">
                                                                    <option value="">---Select Plot ---</option>
                                                                    @foreach($plots as $plot)
                                                                        <option value="{{ $plot->plot_number }}">
                                                                            {{ $plot->plot_number }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row" id="installment_money_payment" style="display: none">

                                                <p class="card-description">Payments : </p>


                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Enter amount </label>
                                                        <div class="col-sm-9">
                                                            <input type="number" name="entered_installment_amount"
                                                                id="entered_installment_amount" class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-4 col-form-label">Next payment</label>
                                                        <div class="col-sm-8">
                                                            <input type="date" name="next_installment_date"
                                                                id="next_installment_date" class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Balance </label>
                                                        <div class="col-sm-9">
                                                            <input type="number" name="balance" id="balance"
                                                                class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row" id="full_money_payment" style="display: none">
                                                <p class="card-description">Payments : </p>
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Enter amount</label>
                                                        <div class="col-sm-9">
                                                            <input type="number" name="entered_amount"
                                                                id="entered_amount" class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-10">
                                                    <div class="form-group row">
                                                        <div class="col-sm-9">
                                                            <button type="button" id="submit_click"
                                                                class="btn btn-primary">Sell</button>
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
        $(document).ready(function () {

            $('#Estate_plot').change(function () {

                var value = $(this).val();

                $.ajax({
                    url: '/get-second-option',
                    type: 'GET',
                    data: {
                        value: value
                    },
                    success: function (data) {

                        $('#plot_number').empty();
                        $.each(data, function (index, item) {
                            var option = $('<option></option>').text(item
                                .plot_number).val(item.plot_number);
                            $('#plot_number').append(option);
                        });

                    },
                    error: function (data) {
                        $('body').html(data.responseText);
                    }
                });
            });


            $("#plot_number").change(function () {

                var selectedValue = $(this).val();

                $.ajax({
                type: 'GET',
                url: '/get-input-option',
                data: { selectedValue: selectedValue },
                success: function(response) {
                    $('#plot_width1').val(response.width_1);
                    $('#plot_width2').val(response.width_2);
                    $('#plot_height1').val(response.height_1);
                    $('#plot_height2').val(response.height_2);
                    $('#location_plot').val(response.location);                    
                },
                error: function (data) {
                            $('body').html(data.responseText);
                        }
            });    
        });
           

            $("#payment_method").change(function () {

                var payment_method = $(this).val();

                if (payment_method == 'paying_in_installments') {
                    $('#installment_display').show();
                    $('#installment_money_payment').show();
                    $('#full_money_payment').hide();
                } else {
                    $('#installment_display').hide();
                    $('#installment_money_payment').hide();
                    $('#full_money_payment').show();
                }
            });

            $("#purchase_type").change(function () {

                var purchase_type = $(this).val();

                if (purchase_type == 'plot') {
                    $('#plot_information_block').show();
                    $('#house_information_block').hide();
                } else if (purchase_type == 'house') {
                    $('#house_information_block').show();
                    $('#plot_information_block').hide();
                }
            });

            $('#submit_click').click(function () {

                var firstname = $('#firstname').val();
                var lastname = $('#lastname').val();
                var gender = $('#gender').val();
                var date_of_birth = $('#date_of_birth').val();
                var NIN = $('#NIN').val();
                var national_id_front = $("#national_id_front")[0].files[0];
                var national_id_back = $("#national_id_back")[0].files[0];
                var card_number = $('#card_number').val();
                var land_poster = $('#land_poster').val();
                var payment_method = $('#payment_method').val();
                var purchase_type = $('#purchase_type').val();

                if (purchase_type == "plot") {

                    var estate = $('#Estate_plot').val();
                    var location = $('#location_plot').val();
                    var width_1 = $('#plot_width1').val();
                    var width_2 = $('#plot_width2').val();
                    var height_1 = $('#plot_height1').val();
                    var height_2 = $('#plot_height2').val();
                    var plot_number = $('#plot_number').val();
                } else {
                    var estate = $('#Estate_house').val();
                    var location = $('#location_house').val();
                    var width_1 = $('#house_width1').val();
                    var width_2 = $('#house_width2').val();
                    var height_1 = $('#house_height1').val();
                    var height_2 = $('#house_height2').val();
                    var plot_number = $('#house_plot_number').val();
                }

                if (payment_method == 'paying_in_installments') {

                    var amount_payed = "0";
                    var next_installment_pay = $('#next_installment_date').val();
                    var balance = "0";
                    var receipt_img = "0";
                    var agreement = "Pending";
                } else {
                    var amount_payed = "0";
                    var next_installment_pay = "Fully payed";
                    var balance = "0";
                    var receipt_img = "0";
                    var agreement = "Pending";
                }

                var errors = validateForm();

                if (errors.length > 0) {
                    showErrors(errors);
                    return;
                } else {

                    $('#submit_click').attr('disabled', 'false');
                    $('#submit_click').html('Selling...');

                    var form_data = new FormData();

                    form_data.append('firstname', firstname);
                    form_data.append('lastname', lastname);
                    form_data.append('gender', gender);
                    form_data.append('date_of_birth', date_of_birth);
                    form_data.append('NIN', NIN);
                    form_data.append('national_id_front', national_id_front);
                    form_data.append('national_id_back', national_id_back);
                    form_data.append('card_number', card_number);
                    form_data.append('land_poster', land_poster);
                    form_data.append('payment_method', payment_method);
                    form_data.append('purchase_type', purchase_type);
                    form_data.append('estate', estate);
                    form_data.append('location', location);
                    form_data.append('width_1', width_1);
                    form_data.append('width_2', width_2);
                    form_data.append('height_1', height_1);
                    form_data.append('height_2', height_2);
                    form_data.append('plot_number', plot_number);
                    form_data.append('amount_payed', amount_payed);
                    form_data.append('balance', balance);
                    form_data.append('receipt_img', receipt_img);
                    form_data.append('agreement', agreement);
                    form_data.append('next_installment_pay', next_installment_pay);


                    $.ajax({
                        type: "post",
                        processData: false,
                        contentType: false,
                        cache: false,
                        data: form_data,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '/store-buyer-details',
                        success: function (data) {
                            if (data.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: data.message,
                                    color: '#FFF',
                                    background: '#452e6f',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = '/admin-buyer';
                                    }
                                });
                            }
                        },
                        error: function (data) {
                            $('body').html(data.responseText);
                        }
                    });
                }

                function validateForm() {

                    var errors = [];

                    if (!firstname) {
                        errors.push("First name is required.");
                    }

                    if (!lastname) {
                        errors.push("last name is required.");
                    }

                    if (!gender) {
                        errors.push("Gender is required.");
                    }

                    if (!date_of_birth) {
                        errors.push("Date of birth name is required.");
                    }

                    if (!NIN) {
                        errors.push("NIN number is required.");
                    }

                    if (!national_id_front) {
                        errors.push("National ID Front is required.");
                    }

                    if (!national_id_back) {
                        errors.push("National ID back is required.");
                    }

                    if (!card_number) {
                        errors.push("Card number is required.");
                    }

                    if (!land_poster) {
                        errors.push("Land poster is required.");
                    }

                    if (!payment_method) {
                        errors.push("Payment method is required.");
                    }

                    if (!purchase_type) {
                        errors.push("Purchase is required.");
                    }

                    if (!estate) {
                        errors.push("Estate is required.");
                    }

                    if (!location) {
                        errors.push("Location is required.");
                    }

                    if (!plot_number) {
                        errors.push("Plot number is required.");
                    }

                    if (!amount_payed) {
                        errors.push("Amount paid is required.");
                    }

                    if (!next_installment_pay) {
                        errors.push("Next Installment pay is required.");
                    }

                    if (!balance) {
                        errors.push("Balance is required.");
                    }

                    if (!receipt_img) {
                        errors.push("Receipt is required.");
                    }

                    if (!agreement) {
                        errors.push("Agreement is required.");
                    }

                    return errors;
                }

                function showErrors(errors) {

                    var errorMessage = "<ol>";
                    for (var i = 0; i < errors.length; i++) {
                        errorMessage += "<li>" + errors[i] + "</li>";
                    }
                    errorMessage += "</ol>";

                    Swal.fire({
                        icon: "error",
                        title: "Validation Error",
                        html: errorMessage,
                        color: '#FFF',
                        background: '#452e6f',
                    });
                }

            });
        });

    </script>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="script.js"></script>
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
