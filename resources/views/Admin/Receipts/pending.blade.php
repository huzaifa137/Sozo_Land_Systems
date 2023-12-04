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

    <meta name="csrf-token" content="{{ csrf_token() }}"/>

  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:../../partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
          <a class="sidebar-brand brand-logo" href="{{ route('admin-dashboard')}}"><img src="/assets/images/logo.svg" alt="logo" /></a>
          <a class="sidebar-brand brand-logo-mini" href="{{ route('admin-dashboard')}}"><img src="/assets/images/logo-mini.svg" alt="logo" /></a>
        </div>
        
        @include('includes.SideBar')

      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:../../partials/_navbar.html -->
        <nav class="navbar p-0 fixed-top d-flex flex-row">
          <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
            <a class="navbar-brand brand-logo-mini" href="{{ route('admin-dashboard')}}"><img src="/assets/images/logo-mini.svg" alt="logo" /></a>
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
            
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Under payment Payments :</h4>

                    @include('sweetalert::alert')

                    @if (Session::get('success'))
										<div class="alert alert-success">
											{{Session::get('success')}}
										</div>
									@endif

                  @if (Session::get('failed'))
										<div class="alert alert-danger">
											{{Session::get('danger')}}
										</div>
									@endif

                                    <div class="row ">
                                        <div class="col-12 grid-margin">
                                          <div class="card">
                                            <div class="card-body">
                                              {{-- <h4 class="card-title">Order Status</h4> --}}
                                              <div class="table-responsive">
                                                <table class="table">
                                                  <thead>
                                                    <tr>
                                                      <th>No.</th>
                                                      <th> Client Name </th>
                                                      <th> NIN No </th>
                                                      <th> Estate </th>
                                                      <th> Plot No </th>
                                                      <th> Location </th>
                                                      <th> Amount Payed </th>
                                                      <th> Status </th>
                                                      <th colspan="2" style="text-align: center"> Reciepts </th>
                                                      <th> Make Agreement</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                    @foreach ($not_fully_paid as $key => $item)
        
                                                    <tr>
                                                        
                                                        <td>{{$key+1}}</td>
                                                      <td>
                                                        {{-- <img src="assets/images/faces/face1.jpg" alt="image" /> --}}
                                                        <span >{{$item->firstname}} {{$item->lastname}}</span>
                                                      </td>
                                                      <td> {{$item->NIN}}  </td>
                                                      <td> {{$item->estate}} </td>
                                                      <td> {{$item->plot_number}} </td>
                                                      <td> {{$item->location}} </td>
                                                      <td> {{$item->amount_payed}} </td>
                                                      <td>
                                                        <div class="badge badge-outline-warning">Under payment</div>
                                                      </td>

                                                        <td><a href="{{'add-reciept/'.$item->id}}" class="btn btn-outline-primary btn-icon-text">
                                                            <i class="mdi mdi-eye btn-icon-prepend"></i> Add reciept </a> </td>

                                                        <td><a href="{{'view-reciept/'.$item->id}}" class="btn btn-outline-info btn-icon-text">
                                                            <i class="mdi mdi-eye btn-icon-prepend"></i> View </a> </td>
                                                            

                                                            <td><a href="{{'add-agreement/'.$item->id}}" class="btn btn-outline-success btn-icon-text">
                                                                <i class="mdi mdi-eye btn-icon-prepend"></i> Upload agreement </a> </td>

                                                        </tr>
                                                    <tr>


                                                        @endforeach
                                                     
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

    <script type="text/javascript"></script>
    <script src="/assets/js/jquery.min.js"></script>

  <script>


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