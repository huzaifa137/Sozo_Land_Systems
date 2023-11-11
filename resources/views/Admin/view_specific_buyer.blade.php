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
  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:../../partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
          <a class="sidebar-brand brand-logo" href="../../index.html"><img src="/assets/images/logo.svg" alt="logo" /></a>
          <a class="sidebar-brand brand-logo-mini" href="../../index.html"><img src="/assets/images/logo-mini.svg" alt="logo" /></a>
        </div>
        
        @include('includes.SideBar')

      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:../../partials/_navbar.html -->
        <nav class="navbar p-0 fixed-top d-flex flex-row">
          <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
            <a class="navbar-brand brand-logo-mini" href="../../index.html"><img src="/assets/images/logo-mini.svg" alt="logo" /></a>
          </div>
          <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
              <span class="mdi mdi-menu"></span>
            </button>
            
            @include('includes.TopNav')

            
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
              <span class="mdi mdi-format-line-spacing"></span>
            </button>
          </div>
        </nav>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            
            <div class="row">
                      <div class="col-lg-12 stretch-card">
                        <div class="card">
                          <div class="card-body">
                            <h4 class="card-title">{{$userdata->firstname}} {{$userdata->lastname}}  Information</h4>                            </p>
                            <div class="table-responsive">
                              <table class="table table-bordered table-contextual">
                                
                                  <tr>
                                    <th> First name </th>
                                    <th> Lastname </th>
                                    <th> Gender </th>
                                    <th> Date of Birth </th>
                                  </tr>
                                
                                <tbody>

                                  <tr class="table-info">
                                    <td> {{$userdata->firstname}} </td>
                                    <td> {{$userdata->lastname}} </td>
                                    <td> {{$userdata->gender}} </td>
                                    <td> {{$userdata->date_of_birth}} </td>
                                  </tr>
                                 
                                  <tr>
                                    <th> NIN</th>
                                    <th> Card Number </th>
                                    <th> National ID </th>
                                    <th> signature </th>
                                  </tr>

                                  <tr class="table-warning">
                                    <td> {{$userdata->NIN}} </td>
                                    <td> {{$userdata->card_number}} </td>
                                    <td> {{$userdata->national_id}} </td>
                                    <td> {{$userdata->signature}} </td>
                                  </tr>

                                  <tr>
                                    <th> Estate</th>
                                    <th> Plot Number </th>
                                    <th> Land Poster </th>
                                    <th> payment Method </th>
                                  </tr>

                                  <tr class="table-danger">
                                    <td> {{$userdata->Estate}} </td>
                                    <td> {{$userdata->plot_number}} </td>
                                    <td> {{$userdata->land_poster}} </td>
                                    <td> {{$userdata->payment_method}} </td>
                                    {{-- <td> Apr 12, 2015 </td> --}}
                                  </tr>
                                  
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
            </div>
          </div>



           <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © SozoPropertiesLimited.com 2023</span>
              <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">E-commerce Coming soon <a href="javascript:void(0);" target="_blank">Sozo Properties</a></span>
            </div>
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>

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