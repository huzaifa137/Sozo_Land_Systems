@include('includes.header')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            
            <div class="row">
            
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Full Paid :</h4>

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


                    <style>
                        a{
                            color: white;
                        }
                    </style>
                    
                    
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
                                                      <th style="text-align: center"> Reciepts </th>
                                                      <th>Agreement</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody>
                                                    @foreach ($fully_paid as $key => $item)
        
                                                    <tr>
                                                        <td>{{$key+1}}</td>
                                                      <td>
                                                          <a href="{{ 'view-agreement/' . $item->id }}">
                                                        <span >{{$item->firstname}} {{$item->lastname}}</span>
                                                        </a>
                                                      </td>
                                                      <td><a href="{{ 'view-agreement/' . $item->id }}"> {{$item->NIN}}  </a></td>
                                                      <td><a href="{{ 'view-agreement/' . $item->id }}"> {{$item->estate}} </a></td>
                                                      <td><a href="{{ 'view-agreement/' . $item->id }}"> {{$item->plot_number}} </a> </td>
                                                      <td><a href="{{ 'view-agreement/' . $item->id }}"> {{$item->location}} </a></td>
                                                      <td><a href="{{ 'view-agreement/' . $item->id }}"> {{$item->amount_payed}} </a> </td>
                                                      <td>
                                                        <div class="badge badge-outline-warning">Full paid</div>
                                                      </td>

                                                        <td><a href="{{'view-agreement/'.$item->id}}" class="btn btn-outline-info btn-icon-text">
                                                            <i class="mdi mdi-eye btn-icon-prepend"></i> View </a> </td>
                                                        

                                                        <td><a href="{{'attach-agreement/'.$item->id}}" class="btn btn-outline-primary btn-icon-text">
                                                            <i class="mdi mdi-eye btn-icon-prepend"></i> upload</a> </td>
                                                        </tr>
                                                    <tr>


                                                        @endforeach
                                                     
                                                  </tbody>
                                                </table>

                                                
                                        <br> <br>
                                        <span>

                                            {{ $fully_paid->links() }}

                                        </span>
                                        
                                        <style>
                                            .w-5{
                                                display: none;
                                            }
                                        </style>
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