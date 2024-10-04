@include('includes.header')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-12 grid-margin stretch-card">
                
              </div>
            </div>


            <div class="row">
              <div class="col-xl-4 col-sm-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0">{{$totalAmount}}/=</h3>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="icon icon-box-success ">
                          <span class="mdi mdi-arrow-top-right icon-item"></span>
                        </div>
                      </div>
                    </div>
                    <h6 class="text-muted font-weight-normal">Total Income generated</h6>
                  </div>
                </div>
              </div>

              <div class="col-xl-4 col-sm-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0">{{$plots_sold}}</h3>
                          {{-- <p class="text-success ms-2 mb-0 font-weight-medium">+11%</p> --}}
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="icon icon-box-success">
                          <span class="mdi mdi-arrow-top-right icon-item"></span>
                        </div>
                      </div>
                    </div>
                    <h6 class="text-muted font-weight-normal">Plots fully Sold</h6>
                  </div>
                </div>
              </div>

              <div class="col-xl-4 col-sm-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0">{{$under_payment_plots}}</h3>
                          {{-- <p class="text-danger ms-2 mb-0 font-weight-medium">-2.4%</p> --}}
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="icon icon-box-danger">
                          <span class="mdi mdi-arrow-bottom-left icon-item"></span>
                        </div>
                      </div>
                    </div>
                    <h6 class="text-muted font-weight-normal">Plots under payment</h6>
                  </div>
                </div>
              </div>
            </div>

            <div class="row ">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Sozo Properties Customer Sales </h4>
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr>
                              <th>No</th>
                            <th> Client Name </th>
                            <th> Estate </th>
                            <th> Plot no.</th>
                            <th> Payment Mode </th>
                            <th> Amount paid </th>
                            <th> Balance </th>
                            <th> View-record </th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_sales as $key => $all_sale)
                          <tr>
                            
                            
                            <td>{{$key+1}}</td>
                            <td>
                                <img style="width: 100%; height:100%" src="{{'/public/public/national_id/'.$all_sale->national_id_front}}" alt="" id="week_img">
                              <span class="ps-2">{{$all_sale->firstname}}</span>
                            </td>

                            
                            <td> {{$all_sale->estate}}  </td>
                            <td> {{$all_sale->plot_number}} </td>
                            <td> {{$all_sale->method_payment}} </td>
                            <td> {{$all_sale->amount_payed}} </td>
                            <td> {{$all_sale->balance}} </td>
                                
                                <td><a href="{{'view-reciept/'.$all_sale->id}}" class="btn btn-outline-success btn-icon-text">
                                    <i class="mdi mdi-eye btn-icon-prepend"></i> View </a> </td>                           
                            @endforeach
                            
                          </tr>
                          

                        </tbody>
                      </table>
                
                    </div>
                    
                     </br> </br>
                     {{ $all_sales->links() }}
                  </div>
                </div>
              </div>
            </div>
 <style>
                                            .w-5{
                                                display: none;
                                            }
                                        </style>
          
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
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