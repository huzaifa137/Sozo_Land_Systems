@include('includes.header')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            
            <div class="row">
            
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Add new Reciept :</h4>

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


                    <form class="form-sample" id="myForm" action="{{ route('store-new-receipt')}}" method="POST" enctype="multipart/form-data">
                      @csrf
                      <p class="card-description">Enter the Following Information:</p>

                      <input type="hidden" name="user_id" value="{{$user_id}}">
                      <input type="hidden" name="user_email" value="{{$LoggedAdminInfo['email']}}">
                      <input type="hidden" name="user_name" value="{{$LoggedAdminInfo['username']}}">                      
                      
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Amount paid</label>
                            <div class="col-sm-9">
                              <input type="number" name="amount_paid" id="amount_paid" class="form-control" required>
                              <span class="text-danger">@error('amount_paid'){{ $message }}@enderror</span>

                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-md-2 col-form-label">Date:</label>
                            <div class="col-sm-10">
                              <input type="date" name="Date_of_payment" id="Date_of_payment" class="form-control" required>
                              <span class="text-danger">@error('Date_of_payment'){{ $message }}@enderror</span>

                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="row">


                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Balance</label>
                            <div class="col-sm-12">
                              <input type="number" name="balance_pending" id="balance_pending" class="form-control" required>
                              <span class="text-danger">@error('balance_pending'){{ $message }}@enderror</span>

                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-md-5 col-form-label">Phonenumber</label>
                            <div class="col-sm-12">
                              <input type="number" name="phone_number"  class="form-control" required>
                              <span class="text-danger">@error('phone_number'){{ $message }}@enderror</span>

                            </div>
                          </div>
                        </div>

                      </div>


                      <div class="row">
                      
                        <div class="col-md-12">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Amount in words</label>
                            <div class="col-sm-12">
                              <input type="text" name="amount_in_words" class="form-control" required>
                              <span class="text-danger">@error('amount_in_words'){{ $message }}@enderror</span>

                            </div>
                          </div>
                        </div>

                      </div>
              
                      <div class="row">
                        <div class="col-md-10">
                          <div class="form-group row">
                          
                            <div class="col-sm-9">
                            
                              <button type="submit" onclick="disableButton()" class="btn btn-primary">Save</button>
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


          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
   
 @include('includes.footer')