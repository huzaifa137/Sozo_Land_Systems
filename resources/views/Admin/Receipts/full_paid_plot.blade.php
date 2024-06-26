@include('includes.header')

            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">

                    <div class="row">

                        <div class="col-12 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="text-info">Assign a new plot to customer because plot {{ $plot_no }}
                                        in {{ $estate }} estate is already taken :</h3>

                                    @include('sweetalert::alert')

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


                                    <form class="form-sample" action="{{ route('store-agreement-new-plot') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <p class="card-description">Enter the Following Information:</p>

                                        <input type="hidden" name="user_id" value="{{ $user_id }}">


                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Amount paid</label>
                                                    <div class="col-sm-9">
                                                        <input type="number" name="amount_paid" id="amount_paid"
                                                            class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-md-2 col-form-label">Date:</label>
                                                    <div class="col-sm-10">
                                                        <input type="date" name="Date_of_payment"
                                                            id="Date_of_payment" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="plot_information_block">
                                            <p class="card-description">Enter Plot Information:</p>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Estate</label>
                                                        <div class="col-sm-9">
                                                            <select name="Estate_plot" id="Estate_plot"
                                                                class="form-control">
                                                                <option value="">--Select Estate ---</option>
                                                                @foreach ($estates as $estate)
                                                                    <option value={{ $estate->estate_name }}>
                                                                        {{ $estate->estate_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label" >Location</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="location_plot"
                                                                id="location_plot" class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Width1</label>
                                                        <div class="col-sm-9">
                                                            <input type="number" name="plot_width1" id="plot_width1"
                                                                class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Width2</label>
                                                        <div class="col-sm-9">
                                                            <input type="number" name="plot_width2" id="plot_width2"
                                                                class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Height1</label>
                                                        <div class="col-sm-9">
                                                            <input type="number" name="plot_height1"
                                                                id="plot_height1" class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Height2</label>
                                                        <div class="col-sm-9">
                                                            <input type="number" name="plot_height2"
                                                                id="plot_height2" class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Plot Number</label>
                                                        <div class="col-sm-9">
                                                        
                                                            <select id="plot_number" name="plot_number" class="form-control" required>


                                                            </select>


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                  <div class="form-group row">
                                                    <label class="col-sm-6 col-form-label">Amount in words</label>
                                                    <div class="col-sm-9">
                                                      <input type="text" name="amount_in_words" id="amount_in_words" class="form-control" required>
                                                    </div>
                                                  </div>
                                                </div>
                        
                                              </div>
                                        </div>

                                        <div class="row">

                                        </div>

                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group row">

                                                    <div class="col-sm-9">

                                                        <button type="submit" class="btn btn-primary">Sell</button>
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

            $('#Estate_plot').change(function() {

                var value = $(this).val();

                $.ajax({
                    url: '/get-second-option',
                    type: 'GET',
                    data: {
                        value: value
                    },
                    success: function(data) {

                        $('#plot_number').empty();
                        $.each(data, function(index, item) {
                            var option = $('<option></option>').text(item .plot_number).val(item.plot_number);  
                            $('#plot_number').append(option);
                        });

                    },
                    error: function(data) {
                        $('body').html(data.responseText);
                    }
                });
            });


            $("#plot_number").change(function() {

                var selectedValue = $(this).val();

                $.ajax({
                    type: 'GET',
                    url: '/get-input-option',
                    data: {
                        selectedValue: selectedValue
                    },
                    success: function(response) {
                        $('#plot_width1').val(response.width_1);
                        $('#plot_width2').val(response.width_2);
                        $('#plot_height1').val(response.height_1);
                        $('#plot_height2').val(response.height_2);
                        $('#location_plot').val(response.location);
                    },
                    error: function(data) {
                        $('body').html(data.responseText);
                    }
                });
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
