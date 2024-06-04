@include('includes.header')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">

            <div class="row ">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Finalise resale for land or plot</h4>

                    <form  action="{{ route('store-resale-amount')}}" method="POST" enctype="multipart/form-data">
                      @csrf
                      <p class="card-description">Enter plot or house Information to be resold:</p>

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

                  <input type="hidden" name="user_id" value="{{$asset_info->id}}">
                  
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group row">
                            <label class="col-sm-6 col-form-label">Land or Plot</label>
                            <div class="col-sm-12">
                              <div class="col-sm-12">
                                <div class="col-sm-12">
                                    <input type="text" name="purchase_type" value="{{$asset_info->purchase_type}}" class="form-control" placeholder="Enter plot no">
                                  </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group row">
                            <label class="col-sm-6 col-form-label">Estate</label>
                            <div class="col-sm-12">
                              <div class="col-sm-12">
                                <div class="col-sm-12">
                                    <input type="text" name="estate" id="estate" value="{{$asset_info->estate}}" class="form-control" placeholder="Enter plot no" >
                                  </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group row">
                            <label class="col-sm-6 col-form-label">Plot no</label>
                            <div class="col-sm-12">
                              <div class="col-sm-12">
                                <input type="text" name="plot_no" id="plot_no" value="{{$asset_info->plot_number}}" class="form-control" placeholder="Enter plot no" required>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-4" id="plot_estate_field">
                            <div class="form-group row">
                              <label class="col-sm-6 col-form-label">Amount resold</label>
                              <div class="col-sm-12">
                                <div class="col-sm-12">
                                  <div class="col-sm-12">
                                      <input type="number" name="amount_resold" id="amount_resold" class="form-control" placeholder="Enter amount resold" required>
                                    </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          
                          <div class="col-md-4" id="plot_estate_field">
                            <div class="form-group row">
                              <label class="col-sm-6 col-form-label">Resell Category</label>
                              <div class="col-sm-12">
                                <div class="col-sm-12">
                                  <div class="col-sm-12">
                                    <select name="resell_category" id="" class="form-control" required>
                                      <option value="">--- Select Category ---</option>
                                      <option value="1" class="form-control">Resold to company</option>
                                      <option value="2" class="form-control">Sell for Client</option>
                                    </select>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class="col-md-4">
                            <div class="form-group row">
                              <div class="col-sm-12">
                                <div class="col-sm-12">
                                </div>
                              </div>
                            </div>
                          </div>

                          <br>
                        <div class="col-md-1">
                          <div class="form-group row">
                        <button type="submit" class="btn btn-primary" style="margin-left:1rem;">Resale</button>
                        </div>
                      </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>

          
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

    <script type="text/javascript"></script>
    <script src="/assets/js/jquery.min.js"></script>
  
    <script>
  
      $(document).ready(function () {
        $("#land_plot").change(function () {
  
          var land_plot = $(this).val();
  
          if (land_plot == 'House') {
            $('#land_estate_field').show();
            $('#plot_estate_field').hide();
          }
          else
          {
            $('#land_estate_field').hide();
            $('#plot_estate_field').show();

          }
          
        });
      });
      </script>

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