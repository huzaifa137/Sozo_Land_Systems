@include('includes.header')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            
            <div class="row">
            
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">House Module :</h4>

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


                    <form class="form-sample" action="{{ route('send-house-data')}}" method="POST">
                      @csrf
                      <p class="card-description">Enter House Information:</p>

                      <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Estate</label>
                              <div class="col-sm-9">
                                <select name="Estate" class="form-control">
                                  @foreach ($estates as $estate)
                                  <option value={{$estate->estate_name}}>{{$estate->estate_name}}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                         
                        </div>


                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Location</label>
                            <div class="col-sm-9">
                              <input type="text" name="location" class="form-control" />
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <div class="row">

                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Width</label>
                            <div class="col-sm-9">
                                <input type="text" name="width" class="form-control" />
                            </div>
                          </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Height</label>
                              <div class="col-sm-9">
                                  <input type="text" name="height" class="form-control" />
                              </div>
                            </div>
                          </div>
                      </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">PLot Number</label>
                              <div class="col-sm-9">
                                <input type="text" name="plot_number" class="form-control" />
                              </div>
                            </div>
                          </div>


                          <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Status</label>
                              <div class="col-sm-9">
                                <select name="status" class="form-control">
                                  <option value="Not taken">Not taken</option>
                                  <option value="Taken">Taken</option>
                                </select>
                              </div>
                            </div>
                          </div>

                          
                      </div>
                      

                      <div class="row">
                        <div class="col-md-10">
                          <div class="form-group row">
                          
                            <div class="col-sm-9">
                            
                              <button type="submit" class="btn btn-primary">Add a house</button>
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

  $(document).ready(function(){
            $("#payment_method").change(function(){

            var payment_method = $(this).val();

            if(payment_method == 'paying_in_installments'){
                $('#installment_display').show();
            }
            else{
              $('#installment_display').hide();
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