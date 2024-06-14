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
                                  <option value="House">House</option>
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

            <div class="row">
            
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Under payment Payments :</h4>

                    @include('sweetalert::alert')
{{-- 
                    @if (Session::get('success'))
										<div class="alert alert-success">
											{{Session::get('success')}}
										</div>
									@endif

                  @if (Session::get('failed'))
										<div class="alert alert-danger">
											{{Session::get('danger')}}
										</div>
									@endif --}}

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
                                                            <i class="mdi mdi-eye btn-icon-prepend"></i> Make reciept </a> </td>

                                                        <td><a href="{{'view-reciept/'.$item->id}}" class="btn btn-outline-info btn-icon-text">
                                                            <i class="mdi mdi-eye btn-icon-prepend"></i> View </a> </td>
                                                            

                                                            <td><a href="{{'attach-receipt/'.$item->id}}" class="btn btn-outline-primary btn-primary-text">
                                                            <i class="mdi mdi-eye btn-icon-prepend"></i> Upload receipt </a> </td>

                                                            <td><a href="{{'add-agreement/'.$item->id}}" class="btn btn-outline-success btn-icon-text">
                                                                <i class="mdi mdi-eye btn-icon-prepend"></i> Make agreement </a> </td>

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