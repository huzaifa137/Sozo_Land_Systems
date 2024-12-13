@include('includes.header')
<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row ">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4>Provide user selling agreement</h4>
                        
                            <form action="{{ route('attach-seller-agreement') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                            
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
                            
                                <input type="hidden" name="user_id" value="{{ $userId }}">
                            
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="file" name="seller_agreeement[]" id="seller_agreeement" class="form-control" multiple required>
                                        </div>
                                    </div>
                            
                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-primary"
                                            onclick="confirm('Please confirm you want to upload this seller agreement!')">Upload Agreement</button>
                                    </div>
                                </div>
                            </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/js/jquery.min.js"></script>
    <script>

    </script>

    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->
    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright ©
                SozoPropertiesLimited.com 2023</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">E-commerce Coming soon <a
                    href="javascript:void(0);" target="_blank">Sozo Properties</a></span>
        </div>
    </footer>
    <!-- partial -->
</div>
