@include('includes.header')
<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row ">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Finalise resale for land or plot</h4>

                        <form action="{{ route('store-resale-amount') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <p class="card-description">Enter plot or house Information to be resold:</p>

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
                                .infoData {
                                    background-color: black !important;
                                    color: white;
                                    border: 1px solid #ccc;
                                }
                                
                                /* General Layout Fix */
                                .form-group {
                                    margin-bottom: 15px;
                                }

                                .form-check {
                                    margin-bottom: 5px;
                                }

                                .form-check label {
                                    color: black;
                                    margin-left: 5px;
                                }

                                .d-none {
                                    display: none !important;
                                }

                            </style>

                            <input type="hidden" name="user_id" value="{{ $asset_info->id }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Land or Plot</label>
                                        <input type="text" readonly name="purchase_type"
                                            value="{{ $asset_info->purchase_type }}"
                                            class="form-control infoData" placeholder="Enter plot no">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Estate</label>
                                        <input type="text" readonly name="estate" id="estate"
                                            value="{{ $asset_info->estate }}" class="form-control infoData"
                                            placeholder="Enter estate name">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Plot no</label>
                                        <input type="text" readonly name="plot_no" id="plot_no"
                                            value="{{ $asset_info->plot_number }}" class="form-control infoData"
                                            placeholder="Enter plot number" required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Amount resold</label>
                                        <input type="text" name="amount_resold" id="amount_resold"
                                            class="form-control" placeholder="Enter resold back by client" required>
                                    </div>
                                </div>

                                <!-- New Input Fields -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Sozo price </label>
                                        <input type="text" name="amount_to_be_sold" class="form-control"
                                            placeholder="Amount to be sold by Sozo properties" required>
                                    </div>
                                </div>

                                <div class="col-md-3 seller-agreement-section d-none">
                                    <div class="form-group">
                                        <label>Seller agreement</label>
                                        <input type="file" name="seller_agreeement[]" id="seller_agreeement"
                                            class="form-control" placeholder="Attach seller agreement" multiple>
                                    </div>

                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Payment Method</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                id="paid_in_cash" value="1" checked>
                                            <label class="form-check-label" for="paid_in_cash">
                                                Cash Payment
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method"
                                                id="paid_later" value="0">
                                            <label class="form-check-label" for="paid_later">
                                                Later Payment
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-primary"
                                        onclick="confirm('Please confirm you want to resale this plot!')">Resale</button>
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
        $(document).ready(function() {
            function toggleSellerAgreement() {
                var paymentMethod = $("input[name='payment_method']:checked").val();

                if (paymentMethod == '1') {
                    $('.seller-agreement-section').removeClass('d-none');
                    $('#seller_agreeement').prop('required', true);
                } else {
                    $('.seller-agreement-section').addClass('d-none');
                    $('#seller_agreeement').prop('required', false);
                }
            }

            toggleSellerAgreement();

            $("input[name='payment_method']").change(function() {
                toggleSellerAgreement();
            });

            $("form").on("submit", function() {
                $("button[type='submit']").prop("disabled", true).text('Processing...');
            });
        });
    </script>

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
