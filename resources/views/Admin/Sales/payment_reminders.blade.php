@include('includes.header')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-12 grid-margin stretch-card">
                
              </div>
            </div>

            <div class="row">
              <div class="col-xl-6 col-sm-6 grid-margin stretch-card">
                <a href="{{ route('set-reminder') }}" style="text-decoration: none; width: 100%;">
                  <div class="card h-100">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-9">
                          <div class="d-flex align-items-center align-self-start">
                            <h3 class="mb-0"></h3>
                          </div>
                        </div>
                        <div class="col-3">
                          <div class="icon icon-box-success">
                            <span class="mdi mdi-alarm icon-item"></span>
                          </div>
                        </div>
                      </div>
                      <h6 class="text-white font-weight-normal">Set Reminders</h6>
                    </div>
                  </div>
                </a>
              </div>

            <div class="col-xl-6 col-sm-6 grid-margin stretch-card">
              <a href="{{ route('payment-reminder') }}" style="text-decoration: none; width: 100%;">
                <div class="card h-100">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0">{{ $records_count }}</h3>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="icon icon-box-success">
                          <span class="mdi mdi-alarm icon-item"></span>
                        </div>
                      </div>
                    </div>
                    <h6 class="text-white font-weight-normal">Today's Scheduled Client Reminders</h6>
                  </div>
                </div>
              </a>
            </div>
          </div>


            <div class="row ">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="">Todays Reminders</h4>
                   
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
                  

                    @if ($records->isEmpty())
                      <div class="col-sm-12 col-md-12">
                          <div class="alert alert-warning" role="alert">
                              No Reminders for clients found today
                          </div>
                      </div>
                    @else
                    <div class="table-responsive">
                          <table class="table">
                            <thead>
                              <tr>
                                <th> Client Name </th>
                                <th> Estate </th>
                                <th> Plot no.</th>
                                <th> Purchasing</th>
                                <th> Amount paid </th>
                                <th> Balance </th>
                                <th> View-record </th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($records as $all_sale)
                              <tr>
                                
                                <td>
                                    <a href="{{'view-reciept/'.$all_sale->id}}">
                                    <img style="width: 100%; height:100%" src="{{'/public/public/national_id/'.$all_sale->national_id_front}}" alt="" id="week_img">
                                  <span class="ps-2">{{$all_sale->firstname}}</span>
                                  </a>
                                </td>
                                
                                <td><a href="{{'view-reciept/'.$all_sale->id}}"> {{$all_sale->estate}}  </a></td>
                                <td><a href="{{'view-reciept/'.$all_sale->id}}"> {{$all_sale->plot_number}} </a></td>
                                <td><a href="{{'view-reciept/'.$all_sale->id}}"> {{$all_sale->purchase_type}} </a></td>
                                <td><a href="{{'view-reciept/'.$all_sale->id}}"> {{$all_sale->amount_payed}} </a></td>
                                <td><a href="{{'view-reciept/'.$all_sale->id}}"> {{$all_sale->balance}} </a></td>
                                    
                                    <td><a href="{{'view-reciept/'.$all_sale->id}}" class="btn btn-outline-success btn-icon-text">
                                        <i class="mdi mdi-eye btn-icon-prepend"></i> View </a> </td>      
                                        
                                        <td><a href="{{'update-payment-reminder/'.$all_sale->id}}" class="btn btn-outline-primary btn-icon-text">
                                          <i class="mdi mdi-eye btn-icon-prepend"></i> update Status </a> </td> 
                                @endforeach
                                
                              </tr>
                              
                            
                            </tbody>
                          </table>
                        </div>
                    @endif

                  </div>
                </div>
              </div>
            </div>

            <div class="row ">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="">Upcoming Reminders</h4>

                  @if ($upcomingreminders->isEmpty())

                @else
                <div class="table-responsive">
                      <table class="table">
                        <thead>
                        <tr>
                          <th> Client Name </th>
                          <th> Estate </th>
                          <th> Plot no.</th>
                          <th> Purchasing</th>
                          <th> Amount paid </th>
                          <th> Balance </th>
                          <th> Days Remaining </th> 
                          <th> View-record </th>
                          <th> Action </th>
                        </tr>
                      </thead>

                        <tbody>
                            @foreach ($upcomingreminders as $all_sale)
                          <tr>
                            
                            <td>
                                <a href="{{'view-reciept/'.$all_sale->id}}">
                                <img style="width: 100%; height:100%" src="{{'/public/public/national_id/'.$all_sale->national_id_front}}" alt="" id="week_img">
                              <span class="ps-2">{{$all_sale->firstname}}</span>
                              </a>
                            </td>
                            
                            <td><a href="{{'view-reciept/'.$all_sale->id}}"> {{$all_sale->estate}}  </a></td>
                            <td><a href="{{'view-reciept/'.$all_sale->id}}"> {{$all_sale->plot_number}} </a></td>
                            <td><a href="{{'view-reciept/'.$all_sale->id}}"> {{$all_sale->purchase_type}} </a></td>
                            <td><a href="{{'view-reciept/'.$all_sale->id}}"> {{$all_sale->amount_payed}} </a></td>
                            <td><a href="{{'view-reciept/'.$all_sale->id}}"> {{$all_sale->balance}} </a></td>
                              
                            <td>
                              @php
                                $dueDate = \Carbon\Carbon::parse($all_sale->next_installment_pay);
                                $daysRemaining = \Carbon\Carbon::now()->diffInDays($dueDate, false);
                              @endphp
                              <span class="text-success">
                                @if ($daysRemaining > 0)
                                  {{ $daysRemaining }} days left
                                @elseif ($daysRemaining == 0)
                                  Due today
                                @else
                                  Overdue by {{ abs($daysRemaining) }} days
                                @endif
                              </span>
                            </td>

                                <td><a href="{{'view-reciept/'.$all_sale->id}}" class="btn btn-outline-success btn-icon-text">
                                    <i class="mdi mdi-eye btn-icon-prepend"></i> View </a> </td>      
                                    
                                    <td><a href="{{'update-payment-reminder/'.$all_sale->id}}" class="btn btn-outline-primary btn-icon-text">
                                      <i class="mdi mdi-eye btn-icon-prepend"></i> update Status </a> </td> 
                            @endforeach
                            
                          </tr>
                          
                         
                        </tbody>
                      </table>
                    </div>
                 @endif

                    
                  </div>
                </div>
              </div>
            </div>

            <div class="row ">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="">Expired Reminders</h4>
                   
                  @if ($expiredreminders->isEmpty())

                @else
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th> Client Name </th>
                      <th> Estate </th>
                      <th> Plot no.</th>
                      <th> Purchasing</th>
                      <th> Amount paid </th>
                      <th> Balance </th>
                      <th> Days Expired </th> <!-- New Column -->
                      <th> View-record </th>
                      <th> Action </th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($expiredreminders as $all_sale)
                      <tr>
                        <td>
                          <a href="{{ 'view-reciept/' . $all_sale->id }}">
                            <img style="width: 100%; height:100%" src="{{ '/public/public/national_id/' . $all_sale->national_id_front }}" alt="" id="week_img">
                            <span class="ps-2">{{ $all_sale->firstname }}</span>
                          </a>
                        </td>

                        <td><a href="{{ 'view-reciept/' . $all_sale->id }}">{{ $all_sale->estate }}</a></td>
                        <td><a href="{{ 'view-reciept/' . $all_sale->id }}">{{ $all_sale->plot_number }}</a></td>
                        <td><a href="{{ 'view-reciept/' . $all_sale->id }}">{{ $all_sale->purchase_type }}</a></td>
                        <td><a href="{{ 'view-reciept/' . $all_sale->id }}">{{ $all_sale->amount_payed }}</a></td>
                        <td><a href="{{ 'view-reciept/' . $all_sale->id }}">{{ $all_sale->balance }}</a></td>

                        <!-- New column: Days Expired -->
                        <td>
                          @php
                            $dueDate = \Carbon\Carbon::parse($all_sale->next_installment_pay);
                            $daysExpired = $dueDate->diffInDays(\Carbon\Carbon::now());
                          @endphp

                          <span class="text-danger">{{ $daysExpired }} days ago</span>
                        </td>

                        <td>
                          <a href="{{ 'view-reciept/' . $all_sale->id }}" class="btn btn-outline-success btn-icon-text">
                            <i class="mdi mdi-eye btn-icon-prepend"></i> View
                          </a>
                        </td>

                        <td>
                          <a href="{{ 'update-payment-reminder/' . $all_sale->id }}" class="btn btn-outline-primary btn-icon-text">
                            <i class="mdi mdi-eye btn-icon-prepend"></i> update Status
                          </a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>

                 @endif

                    
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