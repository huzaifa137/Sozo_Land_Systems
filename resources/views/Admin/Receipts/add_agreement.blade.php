@include('includes.header')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            
            <div class="row">
            
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Agreement :</h4>

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


                    <form class="form-sample" id="myForm" action="{{ route('store-agreement')}}" method="POST" enctype="multipart/form-data">
                      @csrf
                      <p class="card-description">Enter the Following Information:</p>

                      <input type="hidden" name="user_id" value="{{$user_id}}">


                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Amount paid</label>
                            <div class="col-sm-9">
                              <input type="number" name="amount_paid" id="amount_paid" class="form-control" value="{{ old('amount_paid')}}" required>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-md-2 col-form-label">Date:</label>
                            <div class="col-sm-10">
                              <input type="date" name="Date_of_payment" id="Date_of_payment" class="form-control" value="{{ old('Date_of_payment')}}" required>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group row">
                            <label class="col-sm-6 col-form-label">Amount in words</label>
                            <div class="col-sm-9">
                              <input type="text" name="amount_in_words" id="amount_in_words" class="form-control" value="{{ old('amount_in_words')}}" required>
                            </div>
                          </div>
                        </div>
                      </div>
              
                      <div class="row">
                        <div class="col-md-10">
                          <div class="form-group row">
                          
                            <div class="col-sm-9">
                            
                              {{-- <button type="submit" class="btn btn-primary">Save</button> --}}

                              <button type="submit" onclick="disableButton()"
                              class="btn btn-primary">Save</button>
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


          <script>
            function disableButton() {
          
                document.getElementById('myForm').submit();
                document.querySelector('button[type="submit"]').disabled = true;
          
            }
          </script>


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