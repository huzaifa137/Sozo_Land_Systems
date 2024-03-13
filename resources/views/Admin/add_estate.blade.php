@include('includes.header')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            
            <div class="row">
            
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Add new Estate :</h4>

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


                    <form class="form-sample" id="myForm" action="{{ route('send-estate-data')}}" method="POST" enctype="multipart/form-data">
                      @csrf
                      <p class="card-description">Enter new Estate Information:</p>

                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Estate Name :</label>
                            <div class="col-sm-9">
                              <input type="text" name="estate_name" id="estate_name" class="form-control" value="{{old('estate_name')}}" required>
                              <span class="text-danger">@error('estate_name'){{ $message }}@enderror</span>

                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Location :</label>
                            <div class="col-sm-9">
                              <input type="text" name="location" id="location" class="form-control" value="{{old('location')}}" required>
                              <span class="text-danger">@error('location'){{ $message }}@enderror</span>

                            </div>
                          </div>
                        </div>
                      </div>


                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Number of plots :</label>
                            <div class="col-sm-9">
                              <input type="number" name="number_of_plots" id="number_of_plots" class="form-control" value="{{old('number_of_plots')}}" required>
                              <span class="text-danger">@error('number_of_plots'){{ $message }}@enderror</span>

                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Estate Price :</label>
                            <div class="col-sm-9">
                              <input type="text" name="estate_price" id="estate_price" class="form-control" value="{{old('estate_price')}}" required>
                              <span class="text-danger">@error('estate_price'){{ $message }}@enderror</span>

                            </div>
                          </div>
                        </div>


                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Estate pdf :</label>
                            <div class="col-sm-9">
                              <input type="file" name="estate_pdf" id="estate_pdf" class="form-control" required>
                              <span class="text-danger">@error('estate_pdf'){{ $message }}@enderror</span>

                            </div>
                          </div>
                        </div>
                        
                      </div>
                      
                      <div class="row">
                        <div class="col-md-10">
                          <div class="form-group row">
                          
                            <div class="col-sm-9">
                            
                              <button type="submit" onclick="disableButton()" class="btn btn-primary">Submit</button>
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
						$('#btn_click').click(function(){


							var estate_name = $('#estate_name').val();
							var location = $('#location').val();
							var number_of_plots = $('#number_of_plots').val();

						
							var form_data = new FormData();

							form_data.append('estate_name', estate_name);
							form_data.append('location', location);
							form_data.append('number_of_plots', number_of_plots);

							$.ajax({
								type: "post",
								processData: false,
								contentType: false,
								cache: false,
								data		: form_data,								
								headers		:{	'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},

								url			:'/send-estate-data',
								success		:function(response){
                  Swal.fire({
                  title: response.title,
                  text: response.text,
                  icon: response.icon,
              });
								},
								error: function(data)
								{
									$('body').html(data.responseText);
								}
							});
						});
				});


        function disableButton() {
        
        document.getElementById('myForm').submit();
        document.querySelector('button[type="submit"]').disabled = true;
        
    }

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