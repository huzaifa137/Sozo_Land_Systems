@include('includes.header')


<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">

        <div class="row">

            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Plot Information :</h4>

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

                        <style>
                            .black-input {
                                background-color: black !important;
                                color: white !important;
                                border: 1px solid #ccc !important;
                            }
                        </style>


                        <form class="form-sample" id="myForm" action="{{ route('store-plot-updated-information') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <p>Information on this plot stored </p>

                            <input type="hidden" name="plotId" value="{{$plotId}}">

                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="estateName" class="col-form-label">Estate Name</label>
                                        <input type="text" name="estateName" id="estateName"
                                            value="{{ $plotInformation->estate }}" class="form-control black-input"
                                            readonly>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="plotNumber" class="col-form-label">Plot number</label>
                                        <input type="text" name="plotNumber" id="plotNumber"
                                            value="{{ $plotInformation->plot_number }}" class="form-control black-input"
                                            readonly>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="plotStatus" class="col-form-label">Plot Status</label>
                                        <input type="text" name="plotStatus" id="plotStatus"
                                            value="{{ $plotInformation->status }}" class="form-control black-input"
                                            readonly>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="location" class="col-form-label">Location</label>
                                        <input type="text" name="location" id="location"
                                            value="{{ $plotInformation->location }}" class="form-control"
                                            >
                                    </div>
                                </div>

                            </div>


                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="width1" class="col-form-label">Width 1</label>
                                        <input type="text" name="width1" id="width1"
                                            value="{{ $plotInformation->width_1 }}" class="form-control"
                                            >
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="width2" class="col-form-label">Width 2</label>
                                        <input type="text" name="width2" id="width2"
                                            value="{{ $plotInformation->width_2 }}" class="form-control"
                                            >
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="height1" class="col-form-label">Height 1</label>
                                        <input type="text" name="height1" id="height1"
                                            value="{{ $plotInformation->height_1 }}" class="form-control"
                                            >
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="height2" class="col-form-label">Height 2</label>
                                        <input type="text" name="height2" id="height2"
                                            value="{{ $plotInformation->height_2 }}" class="form-control"
                                            >
                                    </div>
                                </div>

                            </div>
                            

                            
                           @if($plotInformation->exceptional_status == "Yes")
                              <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="width1" class="col-form-label">Exceptional Amount</label>
                                        <input type="text" name="exceptional_amount" id="exceptional_amount"
                                            value="{{ $plotInformation->exceptional_amount }}" class="form-control"
                                            >
                                    </div>
                                </div>
                            </div>
                           
                           @else
                           
                            <div class="row" style="display:none;">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="width1" class="col-form-label">Exceptional Amount</label>
                                        <input type="text" name="exceptional_amount" id="exceptional_amount"
                                            value="0" class="form-control"
                                            >
                                    </div>
                                </div>
                            </div>
                           
                           @endif
                            


                            <div class="row">
                                <div class="col-md-10">
                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Please confirm you want to update this informaiton !')">Update</button>
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

<script></script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="script.js"></script>
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
