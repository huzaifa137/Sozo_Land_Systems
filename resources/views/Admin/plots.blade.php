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
        <a class="sidebar-brand brand-logo" href="{{ route('admin-dashboard')}}"><img src="/assets/images/logo.svg"
            alt="logo" /></a>
        <a class="sidebar-brand brand-logo-mini" href="{{ route('admin-dashboard')}}"><img
            src="/assets/images/logo-mini.svg" alt="logo" /></a>
      </div>

      @include('includes.SideBar')

    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:../../partials/_navbar.html -->
      <nav class="navbar p-0 fixed-top d-flex flex-row">
        <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
          <a class="navbar-brand brand-logo-mini" href="{{ route('admin-dashboard')}}"><img
              src="/assets/images/logo-mini.svg" alt="logo" /></a>
        </div>
        <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
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
                  <h4 class="card-title">Add a plot or House :</h4>

                  @include('sweetalert::alert')


                  @if (Session::get('success'))
                  <div class="alert alert-success">
                    {{Session::get('success')}}
                  </div>
                  @endif

                  @if (Session::get('error'))
                  <div class="alert alert-danger">
                    {{Session::get('error')}}
                  </div>
                  @endif


                  <form class="form-sample" action="{{ route('send-plot-data')}}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <p class="card-description">Enter Plot or House Information:</p>

                    <div class="row">

                      <div class="col-md-4">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Choose</label>
                          <div class="col-sm-9">
                            <select name="House_plot" id="House_plot" class="form-control" required>
                              <option value="">---Select House or Plot---</option>
                              <option value="Plot">Plot</option>
                              <option value="House">House</option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4" id="plot_estate">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Estate</label>
                          <div class="col-sm-9">
                            <select name="Estate1" id="Estate1" class="form-control" required>
                              <option value="">---Select Estate ---</option>
                              @foreach ($estates as $estate)
                              <option value="{{$estate->estate_name}}">{{$estate->estate_name}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4" id="plot_house" style="display: none" ;>
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Estate</label>
                          <div class="col-sm-9">
                            <input type="text" name="Estate2" id="Estate2" class="form-control" />
                          </div>
                        </div>
                      </div>

                      <div class="col-md-4">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Location</label>
                          <div class="col-sm-9">
                            <input type="text" name="location" id="location" class="form-control" />
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">


                      <div class="col-md-3">
                        <label class="">Width 1 (sqm)</label>
                        <div class="form-group row">
                          <div class="col-sm-9">
                            <input type="number" name="width1" id="width1" class="form-control" />
                          </div>
                        </div>
                      </div>


                      <div class="col-md-3">
                        <label class="">Width 2 (sqm)</label>
                        <div class="form-group row">
                          <div class="col-sm-9">
                            <input type="number" name="width2" id="width2" class="form-control" />
                          </div>
                        </div>
                      </div>


                      <div class="col-md-3">
                        <label class="">Height 1 (sqm)</label>
                        <div class="form-group row">
                          <div class="col-sm-9">
                            <input type="number" name="height1" id="height1" class="form-control" />
                          </div>
                        </div>
                      </div>


                      <div class="col-md-3">
                        <label class="">Height 2 (sqm)</label>
                        <div class="form-group row">
                          <div class="col-sm-9">
                            <input type="number" name="height2" id="height2" class="form-control" />
                          </div>
                        </div>
                      </div>

                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Status</label>
                          <div class="col-sm-9">
                            <select name="land_status" id="land_status" class="form-control">
                              <option value="">---Select Status ----</option>
                              <option value="Not_taken">Not taken</option>
                              <option value="Fully_taken">Fully Taken</option>
                              <option value="Under_payment">Under payment</option>

                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Plot Number</label>
                          <div class="col-sm-9">
                            <input type="text" name="plot_number" id="plot_number" class="form-control" />
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row" id="full_details" style="display: none">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">First Name</label>
                          <div class="col-sm-9">
                            <input type="text" name="firstname" id="firstname" class="form-control" required>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Last Name</label>
                          <div class="col-sm-9">
                            <input type="text" name="lastname" id="lastname" class="form-control" required>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">National ID Front</label>
                          <div class="col-sm-9">
                            <input type="file" name="national_id_front" id="national_id_front" class="form-control"
                              required>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">National ID back</label>
                          <div class="col-sm-9">
                            <input type="file" name="national_id_back" id="national_id_back" class="form-control"
                              required>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Profile pic</label>
                          <div class="col-sm-9">
                            <input type="file" name="profile_pic" id="profile_pic" class="form-control"
                              required>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label mt-2">Phone number</label>
                            <div class="col-sm-9">
                                <input type="text" name="phonenumber" id="phonenumber"
                                class="form-control" required>
                            </div>
                        </div>
                    </div>

                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Amount Paid </label>
                          <div class="col-sm-9">
                            <input type="number" name="amount_paid" id="amount_paid" class="form-control" />
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label">Date Sold</label>
                          <div class="col-sm-9">
                            <input type="date" name="date_sold" id="date_sold" class="form-control" />
                          </div>
                        </div>
                      </div>

                      <div class="col-md-9" id="land_agreement">
                        <div class="form-group row">
                          <label class="col-md-5 col-form-label">Attach Agreement</label>
                          <div class="col-sm-12">
                            <input type="file" id="agreement_added" class="form-control" multiple>
                            {{-- <input type="file" name="agreement_added" id="agreement_added" required> --}}
                          </div>
                        </div>
                      </div>

                      <div class="row" id="land_balance" style="display: none">
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Balance </label>
                            <div class="col-sm-9">
                              <input type="number" name="balance" id="balance" class="form-control" />
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6" id="next_installment_pay">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Next payment</label>
                            <div class="col-sm-9">
                              <input type="date" name="next_installment_date" id="next_installment_date"
                                class="form-control" />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-10">
                        <div class="form-group row">
                          <div class="col-sm-9">
                            <button type="button" id="add_plot" class="btn btn-primary">Add a plot</button>
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

  <script type="text/javascript"></script>
  <script src="/assets/js/jquery.min.js"></script>

  <script>

    $(document).ready(function () {
      $("#land_status").change(function () {

        var land_status = $(this).val();

        if (land_status == 'Fully_taken') {
          $('#full_details').show();
          $('#land_balance').hide();
          $('#next_installment_pay').hide();
          $('#land_agreement').show();
        }
        else if (land_status == 'Under_payment') {
          $('#full_details').show();
          $('#land_balance').show();
          $('#land_agreement').hide();
          $('#next_installment_pay').show();

        }
        else {
          $('#full_details').hide();
          $('#land_balance').hide();
          $('#land_agreement').hide();
        }
      });


      $("#House_plot").change(function () {

        var House_plot = $(this).val();

        if (House_plot == 'House') {
          $('#plot_estate').hide();
          $('#plot_house').show();
        }
        else {
          $('#plot_estate').show();
          $('#plot_house').hide();
        }
      });

      $('#add_plot').click(function () {

        var location = $('#location').val();
        var width1 = $('#width1').val();
        var width2 = $('#width2').val();
        var height1 = $('#height1').val();
        var height2 = $('#height2').val();
        var land_status = $('#land_status').val();
        var plot_number = $('#plot_number').val();
        var House_plot = $('#House_plot').val();
        var next_installment_date = $('#next_installment_date').val();
        var files = $('#agreement_added')[0].files;

        if (House_plot == 'House') {

          var Estate = $('#Estate2').val();
        }
        else {
          var Estate = $('#Estate1').val();
        }

        if (land_status == "Fully_taken") {

          var firstname = $('#firstname').val();
          var phonenumber = $('#phonenumber').val();

          var lastname = $('#lastname').val();
          var profile_pic = $('#profile_pic')[0].files[0];
          var amount_paid = $('#amount_paid').val();
          var date_sold = $('#date_sold').val();
          var balance = "0";
          var national_id_front = $("#national_id_front")[0].files[0];
          var national_id_back = $("#national_id_back")[0].files[0];
          var files = $('#agreement_added')[0].files;

          // var agreement_added = $("#agreement_added")[0].files[0];


        }
        else if (land_status == "Under_payment") {
          var firstname = $('#firstname').val();
          var profile_pic = $('#profile_pic')[0].files[0];
          var phonenumber = $('#phonenumber').val();
          var lastname = $('#lastname').val();
          var amount_paid = $('#amount_paid').val();
          var date_sold = $('#date_sold').val();
          var balance = $('#balance').val();
          var agreement_added = "Pending";
          var national_id_front = $("#national_id_front")[0].files[0];
          var national_id_back = $("#national_id_back")[0].files[0];

        }
        else {
          var firstname = "-";
          var lastname = "-";
          var amount_paid = "-";
          var date_sold = "-";
          var balance = "-";
          var agreement_added = "-";
        }

        var errors = validateForm();

        if (errors.length > 0) {
          showErrors(errors);
          return;
        }
        else 
        {
          $('#add_plot').attr('disabled', 'false');
          $('#add_plot').html('Adding plot...');

          var form_data = new FormData();

          form_data.append('Estate', Estate);
          form_data.append('location', location);
          form_data.append('width1', width1);
          form_data.append('width2', width2);
          form_data.append('height1', height1);
          form_data.append('height2', height2);
          form_data.append('land_status', land_status);
          form_data.append('firstname', firstname);
          form_data.append('lastname', lastname);
          form_data.append('phonenumber', phonenumber);
          form_data.append('profile_pic', profile_pic);
          form_data.append('amount_paid', amount_paid);
          form_data.append('date_sold', date_sold);
          form_data.append('balance', balance);
          form_data.append('plot_number', plot_number);
          form_data.append('House_plot', House_plot);
          form_data.append('next_installment_date', next_installment_date);
          // form_data.append('agreement_added', agreement_added);
          form_data.append('national_id_front', national_id_front);
          form_data.append('national_id_back', national_id_back);

          for (var i = 0; i < files.length; i++) {
                     form_data.append('files[]', files[i]);
                }

          $.ajax({
          type: "post",
          processData: false,
          contentType: false,
          cache: false,
          data: form_data,
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          url: '/send-plot-data',
          success: function (data) {
            if (data.status) {
              Swal.fire({
                icon: 'success',
                title: 'Success',
                html: data.message,
                color: '#FFF',
                background: '#452e6f',
              }).then((result) => {
                if (result.isConfirmed) {
                  window.location.href = '/plots';
                }
              });
            }
            else {
              Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: data.message,
                color: '#FFF',
                background: '#452e6f',
              }).then((result) => {
                if (result.isConfirmed) {
                  window.location.href = '/plots';
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

            if (!Estate) {
                  errors.push("Estate field is required.");
              }

              if (!location) {
                  errors.push("Location field is required.");
              }

              if (!width1) {
                  errors.push("Width1 field is required.");
              }

              if (!width2) {
                  errors.push("Width2  field is required.");
              }

              if (!height1) {
                  errors.push("height1 field is required.");
              }

               if (!height2) {
                  errors.push("height2 name is required.");
              }

              if (!land_status) {
                  errors.push("Land status field is required.");
              }

              if (!plot_number) {
                  errors.push("Plot number field is required.");
              }

              if (!House_plot) {
                  errors.push("You have to select either plot or House being stored is required.");
              }

              if (land_status == "Fully_taken") {

                if (!firstname) {
                  errors.push("firstname field is required.");
              }

              if (!lastname) {
                      errors.push("Lastname field is required.");
                  }

                  if (!profile_pic) {
                      errors.push("Profile picture field is required.");
                  }

                  if (!phonenumber) {
                      errors.push("phone number field is required.");
                  }
                  
              if (!amount_paid) {
                  errors.push("Amount paid field is required.");
              }

              if (!date_sold) {
                  errors.push("Date sold field is required.");
              }

              if (!files) {
                  errors.push("Agreement to be uploaded field is required.");
              }

              if (!balance) {
                  errors.push("Balance field is required.");
              }

              if (!national_id_front) {
                  errors.push("National Id Front field is required.");
              }

              if (!national_id_back) {
                  errors.push("National id Back field is required.");
              }
          }


          if (land_status == "Under_payment") {

            if (!firstname) {
                  errors.push("firstname field is required.");
              }

              if (!lastname) {
                      errors.push("Lastname field is required.");
                  }

                  if (!profile_pic) {
                      errors.push("Profile picture field is required.");
                  }

                  if (!phonenumber) {
                      errors.push("phone number field is required.");
                  }

              if (!amount_paid) {
                  errors.push("Amount paid field is required.");
              }

              if (!date_sold) {
                  errors.push("Date sold field is required.");
              }

              if (!balance) {
                  errors.push("Balance field is required.");
              }

              if (!national_id_front) {
                  errors.push("National Id Front field is required.");
              }

              if (!national_id_back) {
                  errors.push("National id Back field is required.");
              }

              if (!next_installment_date) {
                  errors.push("Next Installment pay date field is required.");
              }
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
                background:'#452e6f',
            });
        }

      });
    });
  </script>


  <script src="/assets/vendors/js/vendor.bundle.base.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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