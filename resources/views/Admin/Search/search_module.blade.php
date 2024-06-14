@include('includes.header')
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">

                    <div class="row ">
                        <div class="col-12 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Search Module</h4>

                                    <form action="{{ route('search-land-db') }}" method="POST">
                                        @csrf
                                        <p class="card-description">Select the search alogrithm :</p>

                                        @if (Session::get('success'))
                                            <div class="alert alert-success">
                                                {{ Session::get('success') }}
                                            </div>
                                        @endif

                                        @if (Session::get('error'))
                                            <div class="alert alert-danger">
                                                {{ Session::get('error') }}
                                            </div>
                                        @endif


                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label class="col-sm-6 col-form-label">Choose the search
                                                        option</label>
                                                    <div class="col-sm-12">
                                                        <div class="col-sm-12">
                                                            <select name="land_plot" id="land_plot" class="form-control"
                                                                required>
                                                                <option value="">---choose---</option>
                                                                <option value="Name">Name</option>
                                                                <option value="Plot">Plot</option>
                                                                <option value="NIN">NIN</option>
                                                                <option value="date_of_birth">Date of Birth</option>
                                                                <option value="date_sold">Date Sold</option>


                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4" id="plot_estate_field" style="display: none;">
                                                <div class="form-group row">
                                                    <label class="col-sm-6 col-form-label">Estate</label>
                                                    <div class="col-sm-12">
                                                        <div class="col-sm-12">
                                                            <select name="estate" id="estate" class="form-control">
                                                                <option value="">---choose estate---</option>
                                                                @foreach ($records as $record)
                                                                    <option value="{{ $record->estate_name }}">
                                                                        {{ $record->estate_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4" id="land_estate_field" style="display: none;">
                                                <div class="form-group row">
                                                    <label class="col-sm-6 col-form-label">Land Plot</label>
                                                    <div class="col-sm-12">
                                                        <div class="col-sm-12">
                                                            <input type="text" name="land_plot" id="land_plot"
                                                                class="form-control" placeholder="Enter plot no">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-4" id="first_name" style="display: none;">
                                                <div class="form-group row">
                                                    <label class="col-sm-6 col-form-label">First name</label>
                                                    <div class="col-sm-12">
                                                        <div class="col-sm-12">
                                                            <input type="text" name="firstname" id=""
                                                            class="form-control" placeholder="Enter Firstname">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4" id="last_name" style="display: none;">
                                                <div class="form-group row">
                                                    <label class="col-sm-6 col-form-label">Last name</label>
                                                    <div class="col-sm-12">
                                                        <div class="col-sm-12">
                                                            <input type="text" name="lastname" id="lastname"
                                                            class="form-control" placeholder="Enter Lastname">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            

                                            <div class="col-md-4" id="NIN" style="display: none;">
                                                <div class="form-group row">
                                                    <label class="col-sm-6 col-form-label">NIN</label>
                                                    <div class="col-sm-12">
                                                        <div class="col-sm-12">
                                                            <input type="text" name="NIN" id="NIN"
                                                            class="form-control" placeholder="Enter NIN">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4" id="date_of_birth" style="display: none;">
                                                <div class="form-group row">
                                                    <label class="col-sm-6 col-form-label">Date of Birth</label>
                                                    <div class="col-sm-12">
                                                        <div class="col-sm-12">
                                                            <input type="date" name="date_of_birth" id="date_of_birth"
                                                            class="form-control" placeholder="Enter date of birth">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            
                                            <div class="col-md-4" id="date_sold" style="display: none;">
                                                <div class="form-group row">
                                                    <label class="col-sm-8 col-form-label">Start date</label>
                                                    <div class="col-sm-12">
                                                        <div class="col-sm-12">
                                                            <input type="date" name="date_sold" id="date_sold"
                                                            class="form-control" placeholder="Enter first date">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-4" id="end_date" style="display: none;">
                                                <div class="form-group row">
                                                    <label class="col-sm-6 col-form-label">End date</label>
                                                    <div class="col-sm-12">
                                                        <div class="col-sm-12">
                                                            <input type="date" name="end_date" id="end_date"
                                                            class="form-control" placeholder="Enter last date">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-1" id="plot_estate_field">
                                            <div class="form-group row">
                                                <button type="submit" class="btn btn-primary"
                                                    style="margin-left:1rem;">Search</button>
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
                        <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright ©
                            SozoPropertiesLimited.com 2023</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">E-commerce Coming soon
                            <a href="javascript:void(0);" target="_blank">Sozo Properties</a></span>
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
        $(document).ready(function() {

            $("#land_plot").change(function() {

                var search_name = $(this).val();

                  if (search_name == 'Name') {
                    $('#first_name').show();
                    $('#last_name').show();
                    $('#plot_estate_field').hide();
                    $('#land_estate_field').hide();
                    $('#date_of_birth').hide();
                    $('#date_sold').hide();
                    $('#NIN').hide();
                    $('#end_date').hide();

                  }
                  else if(search_name == 'Plot')
                  {
                    $('#first_name').hide();
                    $('#last_name').hide();
                    $('#plot_estate_field').show();
                    $('#land_estate_field').show();
                    $('#date_of_birth').hide();
                    $('#date_sold').hide();
                    $('#NIN').hide();
                    $('#end_date').hide();
                  }
                  else if(search_name == 'NIN')
                  {
                    $('#first_name').hide();
                    $('#last_name').hide();
                    $('#plot_estate_field').hide();
                    $('#land_estate_field').hide();
                    $('#date_of_birth').hide();
                    $('#date_sold').hide();
                    $('#NIN').show();
                    $('#end_date').hide();
                  }
                  else if(search_name == 'date_of_birth')
                  {
                    $('#first_name').hide();
                    $('#last_name').hide();
                    $('#plot_estate_field').hide();
                    $('#land_estate_field').hide();
                    $('#date_of_birth').show();
                    $('#NIN').hide();
                    $('#date_sold').hide();
                    $('#end_date').hide();
                  }
                  else if(search_name == 'date_sold')
                  {
                    $('#first_name').hide();
                    $('#last_name').hide();
                    $('#plot_estate_field').hide();
                    $('#land_estate_field').hide();
                    $('#date_of_birth').hide();
                    $('#NIN').hide();
                    $('#date_sold').show();
                    $('#end_date').show();
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
