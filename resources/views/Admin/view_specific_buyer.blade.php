@include('includes.header')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            
            <div class="row">
                      <div class="col-lg-12 stretch-card">
                        <div class="card">
                          <div class="card-body">
                            <h4 class="card-title">{{$userdata->firstname}} {{$userdata->lastname}}  Information</h4>                            </p>
                            <div class="table-responsive">
                              <table class="table table-bordered table-contextual">
                                
                                  <tr>
                                    <th> First name </th>
                                    <th> Lastname </th>
                                    <th> Gender </th>
                                    <th> Date of Birth </th>
                                  </tr>
                                
                                <tbody>

                                  <tr class="table-info">
                                    <td> {{$userdata->firstname}} </td>
                                    <td> {{$userdata->lastname}} </td>
                                    <td> {{$userdata->gender}} </td>
                                    <td> {{$userdata->date_of_birth}} </td>
                                  </tr>
                                 
                                  <tr>
                                    <th> NIN</th>
                                    <th> Card Number </th>
                                    <th> National ID </th>
                                    <th> signature </th>
                                  </tr>

                                  <tr class="table-warning">
                                    <td> {{$userdata->NIN}} </td>
                                    <td> {{$userdata->card_number}} </td>
                                    <td> {{$userdata->national_id}} </td>
                                    <td> {{$userdata->signature}} </td>
                                  </tr>

                                  <tr>
                                    <th> Estate</th>
                                    <th> Plot Number </th>
                                    <th> Land Poster </th>
                                    <th> payment Method </th>
                                  </tr>

                                  <tr class="table-danger">
                                    <td> {{$userdata->Estate}} </td>
                                    <td> {{$userdata->plot_number}} </td>
                                    <td> {{$userdata->land_poster}} </td>
                                    <td> {{$userdata->payment_method}} </td>
                                    {{-- <td> Apr 12, 2015 </td> --}}
                                  </tr>
                                  
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