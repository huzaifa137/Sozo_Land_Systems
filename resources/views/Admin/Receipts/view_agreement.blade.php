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

                      <div class="row">
                        <div class="col-12">
                          <div class="card">
                            <div class="card-body">
                              <h4 class="card-title text-primary">Customer Information</h4>
                              @foreach ($user_information as $data)
                              <div class="row">
                                <div class="col-md-5">
                                  <div class="table-responsive">
                                    <table class="table">
                                      <tbody>
                                      
                                        <tr>
                                        
                                          <td class="text-info">Name</td>
                                          <td class="text-right font-weight-medium"> {{$data->firstname}} {{$data->lastname}}  </td>
                                        </tr>
                                        <tr>
                                         
                                          <td class="text-info">Gender</td>
                                          <td class="text-right font-weight-medium"> {{$data->gender}} </td>
                                        </tr>
                                        <tr>
                                         
                                          <td class="text-info">Date of birth</td>
                                          <td class="text-right font-weight-medium">{{$data->date_of_birth}} </td>
                                        </tr>


                                        <tr>
                                          <td class="text-info">NIN</td>
                                          <td class="text-right font-weight-medium"> {{$data->NIN}} </td>
                                        </tr>

                                        {{-- <tr>
                                          <td class="text-info">Card Number</td>
                                          <td class="text-right font-weight-medium"> {{$data->card_number}} </td>
                                        </tr>

                                        <tr>
                                          <td class="text-info">Payment Method</td>
                                          <td class="text-right font-weight-medium"> {{$data->method_payment}} </td>
                                        </tr> --}}

                                        <tr>
                                          <td class="text-info">Estate</td>
                                          <td class="text-right font-weight-medium"> {{$data->estate}} </td>
                                        </tr>

                                        <tr>
                                          <td class="text-info">Plot No.</td>
                                          <td class="text-right font-weight-medium"> {{$data->plot_number}} </td>
                                        </tr>

                                        <tr>
                                          <td class="text-info">Width 1</td>
                                          <td class="text-right font-weight-medium"> {{$data->width_1}} </td>
                                        </tr>


                                        <tr>
                                          <td class="text-info">Width 2</td>
                                          <td class="text-right font-weight-medium"> {{$data->width_2}} </td>
                                        </tr>


                                        <tr>
                                          <td class="text-info">Height 1</td>
                                          <td class="text-right font-weight-medium"> {{$data->height_1}} </td>
                                        </tr>

                                        <tr>
                                          <td class="text-info">Height 2</td>
                                          <td class="text-right font-weight-medium"> {{$data->height_2}} </td>
                                        </tr>

                                        <tr>
                                          <td class="text-info">purchasing </td>
                                          <td class="text-right font-weight-medium"> {{$data->purchase_type}} </td>
                                        </tr>

                                        <tr>
                                          <td class="text-info">Amount paid</td>
                                          <td class="text-right font-weight-medium"> {{$data->amount_payed}} </td>
                                        </tr>

                                        <tr>
                                          <td class="text-info">Balance</td>
                                          <td class="text-right font-weight-medium"> {{$data->balance}} </td>
                                        </tr>

                                        <tr> 
                                          <td class="text-info">Status</td>
                                          @if ($data->next_installment_pay == "Fully payed")
                                          <td class="text-right font-weight-medium">{{$data->next_installment_pay}} </td>
                                          @elseif ($data->next_installment_pay == "Resold")
                                          <td class="text-right font-weight-medium">{{$data->next_installment_pay}}</td>
                                          @else
                                          <td class="text-right font-weight-medium">Under payment</td>
                                          @endif
                                        </tr>

                                        
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                                <div class="col-md-7">
                                  <div id="audience-map" class="vector-map">
                                    <img style="width: 100%; height:100%" src="{{'/public/national_id/'.$data->national_id_front}}" alt="">
                                  </div>

                                  <div id="audience-map" class="vector-map">
                                    <img style="width: 100%; height:100%" src="{{'/public/national_id/'.$data->national_id_back}}" alt="">
                                  </div>
                                  @endforeach
                                </div>
                              </div>

                              <h4 class="card-title text-primary mt-3">Customer payment Agreement & receipts</h4>
                              <div class="row">
                                <div class="col-md-5">
                                  <div class="table-responsive">
                                    <table class="table">
                                      <tbody>


                                       
                                      </tbody>
                                      
                                    </table>
                                  </div>
                                </div>
                                
                                <div class="col-md-9" style="padding-left: 10rem">
                                  @foreach ($user_reciepts as $user_reciept)
                                  <br> 
                                  <div id="audience-map" class="vector-map">
                                    <img style="width: 100%; height:100%" src="{{'/public/receipts/'.$user_reciept->reciept}}" alt="">
                                  </div>
                                  @endforeach

                                  @foreach ($user_agreements as $user_agreement)
                                  <br> 
                                  <div id="audience-map" class="vector-map">
                                    <img style="width: 100%; height:100%" src="{{'/public/agreements/'.$user_agreement->agreement}}" alt="">
                                  </div>
                                  @endforeach

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