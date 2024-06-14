@include('includes.header')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">

            <div class="row ">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Search Land or plot</h4>

                    <form  action="{{ route('search-plot-land-db')}}" method="POST">
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


                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group row">
                            <label class="col-sm-6 col-form-label">Land or Plot</label>
                            <div class="col-sm-12">
                              <div class="col-sm-12">
                                <select name="land_plot" id="land_plot" class="form-control" required>
                                  <option value="">---choose---</option>
                                  <option value="Plot">Plot</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-4" id="plot_estate_field">
                          <div class="form-group row">
                            <label class="col-sm-6 col-form-label">Estate</label>
                            <div class="col-sm-12">
                              <div class="col-sm-12">
                                <select name="estate" id="estate" class="form-control">
                                  <option value="">---choose estate---</option>
                                  @foreach ($records as $record)
                                  <option value="{{$record->estate_name}}">{{$record->estate_name}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-4" id="land_estate_field" style="display: none">
                          <div class="form-group row">
                            <label class="col-sm-6 col-form-label">Land Estate</label>
                            <div class="col-sm-12">
                              <div class="col-sm-12">
                                <input type="text" name="land_estate" id="land_estate" class="form-control" placeholder="Enter plot no" >
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group row">
                            <label class="col-sm-6 col-form-label">Plot no</label>
                            <div class="col-sm-12">
                              <div class="col-sm-12">
                                <input type="number" name="plot_no" id="plot_no" class="form-control" placeholder="Enter plot no" required>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-1">
                          <div class="form-group row">
                            {{-- <input type="submit" value="Submit"> --}}
                        <button type="submit" class="btn btn-primary" style="margin-left:1rem;">Search</button>
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