<!DOCTYPE html>
<html lang="en">

<head>
    <!--  meta tags -->
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

                                    @if(Session::get('error'))
                                        <div class="alert alert-danger">
                                            {{ Session::get('error') }}
                                        </div>
                                    @endif


                                    <form class="form-sample" id="myForm"
                                        action="{{ route('edit-user-info') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <p class="card-description">Enter Customer Buyer Information:</p>

                                        <input type="hidden" name="id" value="{{$info->id}}">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">First Name</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="firstname" id="firstname" value="{{$info->firstname}}"
                                                            class="form-control" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Last Name</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="lastname" id="lastname" value="{{$info->lastname}}"
                                                            class="form-control" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Gender</label>
                                                    <div class="col-sm-9">
                                                        <select name="gender" id="gender" class="form-control" >
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
                                                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{$info->date_of_birth}}"
                                                            class="form-control" placeholder="dd/mm/yyyy" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Nin Number</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="NIN" id="NIN" class="form-control" value="{{$info->NIN}}"
                                                            >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">CardNumber</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="card_number" id="card_number" value="{{$info->card_number}}"
                                                            class="form-control" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                            

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label mt-2">Phone number</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="phonenumber" id="phonenumber" value="{{$info->phonenumber}}"
                                                        class="form-control" >
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label mt-2">Land Poster</label>
                                                    <div class="col-sm-9">
                                                        <select name="land_poster" id="land_poster" class="form-control"
                                                            >
                                                            {{-- <option value="">--- Poster Payment---</option> --}}
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
                                                                class="form-control" >
                                                                {{-- <option value="">--- Select Payment ---</option> --}}
                                                                {{-- <option value="Full_payment">Full Payment</option> --}}
                                                                <option value="paying_in_installments">Paying in
                                                                    Installments</option>
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
                                                                class="form-control" >
                                                              <option value="{{$info->purchase_type}}">{{$info->purchase_type}}</option>

                                                            </select>
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
                                                    <button type="submit" class="btn btn-primary">Update</button>
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
