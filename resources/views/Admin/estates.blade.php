@include('includes.header')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-12 grid-margin stretch-card">
                
              </div>
            </div>


                    <style>
                        a{
                            color: white;
                        }
                    </style>
                    
                    
                <?php  
                
                use App\Models\AdminRegister;

                $user_id = Session('LoggedAdmin');
                $User_access_right = AdminRegister::where('id', '=', $user_id)->value('admin_category');
                
                ?>

            <div class="row">
              <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0">{{$count_estates}}</h3>
                        </div>
                      </div>
               
                    </div>
                    <h6 class="text-muted font-weight-normal">Total Estates</h6>
                  </div>
                </div>
              </div>
              
             
              
              
                 @if($User_access_right == 'SuperAdmin')
                 
                  <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0">{{$number_plots}}</h3>
                        </div>
                      </div>
                   
                    </div>
                    <h6 class="text-muted font-weight-normal">Total Fully purchased plots</h6>
                  </div>
                </div>
              </div>
              
              
                            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0">{{$Not_fully_paid_plots}}</h3>
                        </div>
                      </div>
                   
                    </div>
                    <h6 class="text-muted font-weight-normal">Not taken plots</h6>
                  </div>
                </div>
              </div>
                 
                 
              <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <a href="{{ route('add-estate')}}" style="text-decoration: none;color:white;">
                    <div class="row">
                      <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                          <h4 style="text-align: center">Create a new Estate</h4>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="icon icon-box-success ">
                          <span class="mdi mdi-plus icon-item"></span>
                        </div>
                      </div>
                    </div>
                  </a>
                  </div>
                </div>
              </div>
              
            @else
            
        
            @endif
              
              

            

            </div>

            <!--<div class="row ">-->
            <!--  <div class="col-12 grid-margin">-->
            <!--    <div class="card">-->
            <!--      <div class="card-body">-->
            <!--        <h4 class="card-title">All Estates </h4>-->
            <!--        <div class="table-responsive">-->
            <!--          <table class="table">-->
            <!--            <thead>-->
            <!--              <tr>-->
            <!--                <th>No</th>-->
            <!--                <th> Estate Name</th>-->
            <!--                <th> Estate Price </th>-->
            <!--                <th> Location </th>-->
            <!--                <th> Number of plots </th>-->
            <!--                <th>View Estate</th>-->
            <!--              </tr>-->
            <!--            </thead>-->
            <!--            <tbody>-->
                        
            <!--          @foreach ($estates as $key => $item)-->
                        
            <!--            <tr>-->
            <!--                <td><a href="{{'view-estate/'.$item->id}}"> {{$key+1}} </a></td>-->
            <!--                <td><a href="{{'view-estate/'.$item->id}}"> {{$item->estate_name}} </a></td>-->
            <!--                <td><a href="{{'view-estate/'.$item->id}}"> {{$item->estate_price}} </a></td>-->
            <!--                <td><a href="{{'view-estate/'.$item->id}}"> {{$item->location}} </a></td>-->
            <!--                <td><a href="{{'view-estate/'.$item->id}}"> {{$item->number_of_plots}} </a></td>-->
            <!--                <td><a href="{{'view-estate/'.$item->id}}"><i class="btn btn-success">View Estate</i></a></td>-->
            <!--                {{-- <td><a href="{{'delete/'.$info->id}}" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this case ?')"><i class="la la-trash-o"></i></a></td> --}}-->
            <!--              </tr>-->
            <!--              @endforeach    -->
            <!--            </tbody>-->
            <!--          </table>-->
            <!--        </div>-->
            <!--      </div>-->
            <!--    </div>-->
            <!--  </div>-->
            <!--</div>-->
            
 <div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">All Estates</h4>
                <!-- Search Bar -->
                <input type="text" id="searchBar" placeholder="Search estates by name..." class="form-control mb-4">

                <div class="table-responsive">
                    <table class="table" id="estatesTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Estate Name</th>
                                <th>Estate Price</th>
                                <th>Location</th>
                                <th>Number of plots</th>
                                <th>View Estate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($estates as $key => $item)
                                <tr>
                                    <td><a href="{{ url('view-estate/'.$item->id) }}"> {{$key+1}} </a></td>
                                    <td><a href="{{ url('view-estate/'.$item->id) }}"> {{$item->estate_name}} </a></td>
                                    <td><a href="{{ url('view-estate/'.$item->id) }}"> {{$item->estate_price}} </a></td>
                                    <td><a href="{{ url('view-estate/'.$item->id) }}"> {{$item->location}} </a></td>
                                    <td><a href="{{ url('view-estate/'.$item->id) }}"> {{$item->number_of_plots}} </a></td>
                                    <td><a href="{{ url('view-estate/'.$item->id) }}" class="btn btn-success">View Estate</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('searchBar').addEventListener('keyup', function() {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll('#estatesTable tbody tr');

        rows.forEach(row => {
            const estateName = row.querySelector('td:nth-child(2) a').textContent.toLowerCase();
            if (estateName.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>


          
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