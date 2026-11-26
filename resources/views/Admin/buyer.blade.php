@include('includes.header')
<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">

        <div class="row">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Buyer Information :</h4>

                        @include('sweetalert::alert')




                        <form class="form-sample" id="myForm" action="{{ route('store-buyer-details') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <p class="card-description">Enter Customer Buyer Information:</p>

                            <input type="hidden" id="hidden_user_name" value="{{$LoggedAdminInfo['username']}}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">First Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="firstname" id="firstname" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Last Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="lastname" id="lastname" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Gender</label>
                                        <div class="col-sm-9">
                                            <select name="gender" id="gender" class="form-control" required>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Date of Birth</label>
                                        <div class="col-sm-9">
                                            <input type="date" name="date_of_birth" id="date_of_birth"
                                                class="form-control" placeholder="dd/mm/yyyy" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Nin Number</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="NIN" id="NIN" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">CardNumber</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="card_number" id="card_number" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">National ID Front</label>
                                        <div class="col-sm-9">
                                            <input type="file" name="national_id_front" id="national_id_front"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">National ID back</label>
                                        <div class="col-sm-9">
                                            <input type="file" name="national_id_back" id="national_id_back"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Profile Image</label>
                                        <div class="col-sm-9">
                                            <input type="file" name="profile_pic" id="profile_pic" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label mt-2">Phone number</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="phonenumber" id="phonenumber" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label mt-2">Land Poster</label>
                                        <div class="col-sm-9">
                                            <select name="land_poster" id="land_poster" class="form-control" required>
                                                <option value="">--- Poster Payment---</option>
                                                <option value="Paid">Paid</option>
                                                {{-- <option value="Not paid">Not paid</option> --}}
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label mt-2">Half or Full Plot</label>
                                        <div class="col-sm-9">
                                            <select name="half_or_full" id="half_or_full" class="form-control" required>
                                                <option value="">--- Select Half or Full being sold---</option>
                                                <option value="0">Full Plot</option>
                                                <option value="1">Half Plot</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <p class="card-description">Payment Method:</p>

                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Select Payment
                                                Method:</label>
                                            <div class="col-sm-9">
                                                <select name="payment_method" id="payment_method" class="form-control"
                                                    required>
                                                    <option value="">--- Select Payment ---</option>
                                                    {{-- <option value="Full_payment">Full Payment</option> --}}
                                                    <option value="paying_in_installments">Paying in
                                                        Installments</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="installment_display" style="display: none">
                                    <div class="col-md-10">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Select Installment
                                                period:</label>
                                            <div class="col-sm-9">
                                                <select name="installment_payments" id="installment_payments"
                                                    class="form-control" required>
                                                    <option value="">--Select period for Installment---
                                                    </option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                    <option value="7">7</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Purchase
                                                Category:</label>
                                            <div class="col-sm-9">
                                                <select name="purchase_type" id="purchase_type" class="form-control"
                                                    required>
                                                    <option value="">---Select Category ---</option>
                                                    <option value="plot">Buying a plot</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>
                                        <input type="radio" name="plot_mode" value="single" checked> Buying a Single
                                        Plot
                                    </label>
                                    <label class="ms-3">
                                        <input type="radio" name="plot_mode" value="multiple"> Buying Multiple Plots
                                    </label>
                                </div>


                                <div id="single_plot_section">

                                    <div id="plot_information_block" style="display: none;">
                                        <p class="card-description">Enter Plot Information:</p>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Estate</label>
                                                    <div class="col-sm-9">
                                                        <select name="Estate_plot" id="Estate_plot"
                                                            class="form-control">
                                                            <option value="">--Select Estate ---</option>
                                                            @foreach($estates as $estate)
                                                                <option value={{ $estate->estate_name }}>
                                                                    {{ $estate->estate_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Location</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="location_plot" id="location_plot"
                                                            class="form-control" />
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
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Width2</label>
                                                    <div class="col-sm-9">
                                                        <input type="number" name="plot_width2" id="plot_width2"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Height1</label>
                                                    <div class="col-sm-9">
                                                        <input type="number" name="plot_height1" id="plot_height1"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Height2</label>
                                                    <div class="col-sm-9">
                                                        <input type="number" name="plot_height2" id="plot_height2"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Plot Number</label>
                                                    <div class="col-sm-9">

                                                        <select id="plot_number" class="form-control">
                                                            <option value="">---Select Plot ---</option>


                                                        </select>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div id="house_information_block" style="display: none;">
                                        <p class="card-description">Enter House Information:</p>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Estate</label>
                                                    <div class="col-sm-9">
                                                        <select name="Estate_house" id="Estate_house"
                                                            class="form-control">
                                                            @foreach($estates as $estate)
                                                                <option value={{ $estate->estate_name }}>
                                                                    {{ $estate->estate_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Location</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="location_house" id="location_house"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Width1</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="house_width1" id="house_width1"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Width2</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="house_width2" id="house_width2"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Height1</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="house_height1" id="house_height1"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Height2</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="house_height2" id="house_height2"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Plot Number</label>
                                                    <div class="col-sm-9">
                                                        <select name="house_plot_number" id="house_plot_number"
                                                            class="form-control">
                                                            <option value="">---Select Plot ---</option>
                                                            @foreach($plots as $plot)
                                                                <option value="{{ $plot->plot_number }}">
                                                                    {{ $plot->plot_number }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div id="multiple_plot_section" style="display:none;">
                                    <style>
                                        #quick_plots { height: auto !important; overflow-y: auto; }
                                    </style>
                                    <div id="quick_plot_picker" class="border rounded p-2 mb-3">
                                        <p class="mb-2"><strong>Quick add:</strong> choose an estate, then click plot
                                            numbers (Ctrl/Cmd + click or Shift + click also work). A plot row is added
                                            automatically for each plot you select, and removed if you deselect it.</p>
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <label>Estate</label>
                                                <select id="quick_estate" class="form-control">
                                                    <option value="">-- Select Estate --</option>
                                                    @foreach($estates as $estate)
                                                        <option value="{{ $estate->estate_name }}">{{ $estate->estate_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-2" id="quick_plots_col" style="display:none;">
                                                <label>Plot Numbers</label>
                                                <div id="quick_plots_body" style="display:none;">
                                                    <select id="quick_plots" class="form-control" multiple size="8" disabled></select>
                                                    <div class="mt-1">
                                                        <button type="button" id="quick_select_all" class="btn btn-sm btn-outline-primary">Select all</button>
                                                        <button type="button" id="quick_clear" class="btn btn-sm btn-outline-secondary">Clear</button>
                                                    </div>
                                                </div>
                                                <small id="quick_plots_hint" class="d-block mt-1"></small>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="plot_items_container">
                                        <!-- dynamic plot item blocks go here -->
                                    </div>
                                    <button type="button" id="add_plot_item" class="btn btn-sm btn-primary mt-2 mb-2">+ Add
                                        Another Plot</button>
                                </div>



                                <div class="row" id="installment_money_payment" style="display: none">

                                    <p class="card-description">Payments : </p>


                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Enter amount </label>
                                            <div class="col-sm-9">
                                                <input type="number" name="entered_installment_amount"
                                                    id="entered_installment_amount" class="form-control" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Next payment</label>
                                            <div class="col-sm-8">
                                                <input type="date" name="next_installment_date"
                                                    min="<?php echo date('Y-m-d'); ?>" id="next_installment_date"
                                                    class="form-control" />
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Balance </label>
                                            <div class="col-sm-9">
                                                <input type="number" name="balance" id="balance" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="full_money_payment" style="display: none">
                                    <p class="card-description">Payments : </p>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Enter amount</label>
                                            <div class="col-sm-9">
                                                <input type="number" name="entered_amount" id="entered_amount"
                                                    class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group row">
                                            <div class="col-sm-9">
                                                <button type="button" id="submit_click"
                                                    class="btn btn-primary">Sell</button>
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
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">
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
    $(document).ready(function () {

        $('#Estate_plot').change(function () {

            var value = $(this).val();

            $.ajax({
                url: '/get-second-option',
                type: 'GET',
                data: {
                    value: value
                },
                success: function (data) {

                    $('#plot_number').empty();
                    $.each(data, function (index, item) {
                        var option = $('<option></option>').text(item
                            .plot_number).val(item.plot_number);
                        $('#plot_number').append(option);
                    });

                },
                error: function (data) {
                    $('body').html(data.responseText);
                }
            });
        });


        $("#plot_number").change(function () {

            var selectedValue = $(this).val();

            $.ajax({
                type: 'GET',
                url: '/get-input-option',
                data: { selectedValue: selectedValue },
                success: function (response) {
                    $('#plot_width1').val(response.width_1);
                    $('#plot_width2').val(response.width_2);
                    $('#plot_height1').val(response.height_1);
                    $('#plot_height2').val(response.height_2);
                    $('#location_plot').val(response.location);
                },
                error: function (data) {
                    $('body').html(data.responseText);
                }
            });
        });


        $("#payment_method").change(function () {

            var payment_method = $(this).val();

            if (payment_method == 'paying_in_installments') {
                $('#installment_display').show();
                $('#installment_money_payment').show();
                $('#full_money_payment').hide();
            } else {
                $('#installment_display').hide();
                $('#installment_money_payment').hide();
                $('#full_money_payment').show();
            }
        });

        $("#purchase_type").change(function () {

            var purchase_type = $(this).val();

            if (purchase_type == 'plot') {
                $('#plot_information_block').show();
                $('#house_information_block').hide();
            } else if (purchase_type == 'house') {
                $('#house_information_block').show();
                $('#plot_information_block').hide();
            }
        });

        $('#submit_click').click(function () {
            var hidden_user_name = $('#hidden_user_name').val();
            var firstname = $('#firstname').val();
            var profile_pic = $('#profile_pic')[0].files[0];
            var phonenumber = $('#phonenumber').val();
            var lastname = $('#lastname').val();
            var gender = $('#gender').val();
            var date_of_birth = $('#date_of_birth').val();
            var NIN = $('#NIN').val();
            var national_id_front = $("#national_id_front")[0].files[0];
            var national_id_back = $("#national_id_back")[0].files[0];
            var card_number = $('#card_number').val();
            var land_poster = $('#land_poster').val();
            var half_or_full = $('#half_or_full').val();
            var payment_method = $('#payment_method').val();
            var purchase_type = $('#purchase_type').val();
            var plot_mode = $('input[name="plot_mode"]:checked').val() || 'single'; // single by default

            if (payment_method == 'paying_in_installments') {
                var amount_payed = "0";
                var next_installment_pay = $('#next_installment_date').val();
                var balance = "0";
                var receipt_img = "0";
                var agreement = "Pending";
            } else {
                var amount_payed = "0";
                var next_installment_pay = "Fully payed";
                var balance = "0";
                var receipt_img = "0";
                var agreement = "Pending";
            }

            // ---- Validation ----
            var errors = validateForm();
            if (errors.length > 0) {
                showErrors(errors);
                return;
            }

            // ---- Confirmation before submitting ----
            Swal.fire({
                title: 'Confirm Submission',
                text: 'Are you sure you want to submit this buyer’s details?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, submit',
                cancelButtonText: 'Cancel',
                background: '#452e6f',
                color: '#FFF',
            }).then((result) => {
                if (result.isConfirmed) {

                    $('#submit_click').attr('disabled', true).html('Selling...');

                    var form_data = new FormData();

                    form_data.append('hidden_user_name', hidden_user_name);
                    form_data.append('firstname', firstname);
                    form_data.append('profile_pic', profile_pic);
                    form_data.append('phonenumber', phonenumber);
                    form_data.append('lastname', lastname);
                    form_data.append('gender', gender);
                    form_data.append('date_of_birth', date_of_birth);
                    form_data.append('NIN', NIN);
                    form_data.append('national_id_front', national_id_front);
                    form_data.append('national_id_back', national_id_back);
                    form_data.append('card_number', card_number);
                    form_data.append('land_poster', land_poster);
                    form_data.append('half_or_full', half_or_full);
                    form_data.append('payment_method', payment_method);
                    form_data.append('purchase_type', purchase_type);
                    form_data.append('amount_payed', amount_payed);
                    form_data.append('balance', balance);
                    form_data.append('receipt_img', receipt_img);
                    form_data.append('agreement', agreement);
                    form_data.append('next_installment_pay', next_installment_pay);
                    form_data.append('plot_mode', plot_mode);

                    if (plot_mode === 'single') {
                        // ---- Single plot ----
                        if (purchase_type == "plot") {
                            form_data.append('estate', $('#Estate_plot').val());
                            form_data.append('location', $('#location_plot').val());
                            form_data.append('width_1', $('#plot_width1').val());
                            form_data.append('width_2', $('#plot_width2').val());
                            form_data.append('height_1', $('#plot_height1').val());
                            form_data.append('height_2', $('#plot_height2').val());
                            form_data.append('plot_number', $('#plot_number').val());
                        } else {
                            form_data.append('estate', $('#Estate_house').val());
                            form_data.append('location', $('#location_house').val());
                            form_data.append('width_1', $('#house_width1').val());
                            form_data.append('width_2', $('#house_width2').val());
                            form_data.append('height_1', $('#house_height1').val());
                            form_data.append('height_2', $('#house_height2').val());
                            form_data.append('plot_number', $('#house_plot_number').val());
                        }
                    } else {
                        // ---- Multiple plots ----
                        let plots = [];
                        $('.plot-item').each(function () {
                            let estate = $(this).find('.estate-select').val();
                            let plot_number = $(this).find('.plot-select').val();
                            let width_1 = $(this).find('.width1').val();
                            let width_2 = $(this).find('.width2').val();
                            let height_1 = $(this).find('.height1').val();
                            let height_2 = $(this).find('.height2').val();
                            let location = $(this).find('.location').val(); // add this if present

                            plots.push({
                                estate: estate,
                                plot_number: plot_number,
                                width_1: width_1,
                                width_2: width_2,
                                height_1: height_1,
                                height_2: height_2,
                                location: location
                            });
                        });
                        form_data.append('multiple_estates', JSON.stringify(plots));

                        // send as JSON array
                    }

                    // ---- AJAX submission ----
                    $.ajax({
                        type: "POST",
                        processData: false,
                        contentType: false,
                        cache: false,
                        data: form_data,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '/store-buyer-details',
                        success: function (data) {
                            if (data.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: data.message,
                                    color: '#FFF',
                                    background: '#452e6f',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        (window.SozoLoader ? SozoLoader.go('/admin-buyer') : (window.location.href = '/admin-buyer'));
                                    }
                                });
                            }
                        },
                        error: function (data) {
                            $('body').html(data.responseText);
                        }
                    });
                }
            });


            // ---- Validation helper ----
            function validateForm() {
                var errors = [];

                if (!firstname) errors.push("First name is required.");
                if (!profile_pic) errors.push("Profile Image is required.");
                if (!phonenumber) errors.push("Phone number is required.");
                if (!lastname) errors.push("Last name is required.");
                if (!gender) errors.push("Gender is required.");
                if (!date_of_birth) errors.push("Date of birth is required.");
                if (!NIN) errors.push("NIN is required.");
                if (!national_id_front) errors.push("National ID Front is required.");
                if (!national_id_back) errors.push("National ID Back is required.");
                if (!card_number) errors.push("Card number is required.");
                if (!land_poster) errors.push("Land poster is required.");
                if (!half_or_full) errors.push("Half/Full plot choice is required.");
                if (!payment_method) errors.push("Payment method is required.");
                if (!purchase_type) errors.push("Purchase type is required.");

                if (plot_mode === 'single') {
                    let estate = $('#Estate_plot').val();
                    let plot_number = $('#plot_number').val();
                    let location = $('#location_plot').val();
                    if (!estate) errors.push("Estate is required.");
                    if (!plot_number) errors.push("Plot number is required.");
                    if (!location) errors.push("Location is required.");
                } else {
                    $('.plot-item').each(function (i) {
                        let estate = $(this).find('.estate-select').val();
                        let plot_number = $(this).find('.plot-select').val();
                        if (!estate) errors.push(`Estate is required for plot #${i + 1}.`);
                        if (!plot_number) errors.push(`Plot number is required for plot #${i + 1}.`);
                    });

                    // the same estate + plot must not appear twice
                    let seenPlots = {};
                    $('.plot-item').each(function (i) {
                        let key = $(this).find('.estate-select').val() + '||' + $(this).find('.plot-select').val();
                        if (!$(this).find('.plot-select').val()) return;
                        if (seenPlots[key] !== undefined) {
                            errors.push(`Plot #${i + 1} is a duplicate of plot #${seenPlots[key] + 1}.`);
                        } else {
                            seenPlots[key] = i;
                        }
                    });
                }

                return errors;
            }

            function showErrors(errors) {
                var errorMessage = "<ol>";
                for (var i = 0; i < errors.length; i++) {
                    errorMessage += "<li>" + errors[i] + "</li>";
                }
                errorMessage += "</ol>";

                Swal.fire({
                    icon: "error",
                    title: "Validation Error",
                    html: errorMessage,
                    color: '#FFF',
                    background: '#452e6f',
                });
            }
        });


    });

    $(document).ready(function () {
        // toggle between single/multiple
        $('input[name="plot_mode"]').change(function () {
            if ($(this).val() === 'multiple') {
                $('#single_plot_section').hide();
                $('#multiple_plot_section').show();
            } else {
                $('#single_plot_section').show();
                $('#multiple_plot_section').hide();
            }
        });

        // ---------- Plot row template ----------
        let plotRowCounter = 0;

        function plotRowHtml(index) {
            return `
        <div class="plot-item border p-2 mb-2 rounded position-relative">
            <button type="button" class="btn btn-sm btn-danger remove-plot-item"
                    style="position:absolute; top:5px; right:4px;">
                        <i class="fas fa-times"></i>
            </button>

            <div class="row mb-2">
                <div class="col-md-6">
                    <label>Estate</label>
                    <select name="plots[${index}][estate]" class="form-control estate-select">
                        <option value="">-- Select Estate --</option>
                        @foreach($estates as $estate)
                            <option value="{{ $estate->estate_name }}">{{ $estate->estate_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Plot Number</label>
                    <select name="plots[${index}][plot_number]" class="form-control plot-select">
                        <option value="">-- Select Plot --</option>
                    </select>
                </div>
            </div>

            <input type="hidden" name="plots[${index}][location]" class="location">

            <div class="row">
                <div class="col-md-3"><input type="number" name="plots[${index}][width1]" class="form-control width1" placeholder="Width1"></div>
                <div class="col-md-3"><input type="number" name="plots[${index}][width2]" class="form-control width2" placeholder="Width2"></div>
                <div class="col-md-3"><input type="number" name="plots[${index}][height1]" class="form-control height1" placeholder="Height1"></div>
                <div class="col-md-3"><input type="number" name="plots[${index}][height2]" class="form-control height2" placeholder="Height2"></div>
            </div>
        </div>`;
        }

        // ---------- "Add Another Plot" (unchanged behaviour) ----------
        $('#add_plot_item').click(function () {
            $('#plot_items_container').append(plotRowHtml(plotRowCounter++));
        });

        // ---------- Helpers ----------
        function findPlotRow(estate, plot) {
            return $('.plot-item').filter(function () {
                return $(this).find('.estate-select').val() === estate &&
                    String($(this).find('.plot-select').val()) === String(plot);
            });
        }

        function loadPlotDetails($row, estate, plot) {
            $.get('/get-input-option', { selectedValue: plot, estate: estate })
                .done(res => {
                    $row.find('.width1').val(res.width_1);
                    $row.find('.width2').val(res.width_2);
                    $row.find('.height1').val(res.height_1);
                    $row.find('.height2').val(res.height_2);
                    $row.find('.location').val(res.location);
                })
                .fail(() => Swal.fire('Error', 'Could not load plot info', 'error'));
        }

        // ---------- Quick add: pick an estate, then select many plots ----------
        const $quickEstate = $('#quick_estate');
        const $quickPlots = $('#quick_plots');
        const $quickHint = $('#quick_plots_hint');
        const quickPlotCache = {};

        // List box height: fits the plots when there are few, grows as more are selected,
        // and stops at QUICK_MAX_ROWS (it scrolls after that)
        const QUICK_MIN_ROWS = 8;
        const QUICK_EXTRA_ROWS = 4;
        const QUICK_MAX_ROWS = 15;
        function resizeQuickPlots() {
            const total = $quickPlots.find('option').length;
            if (!total) return;
            const selectedCount = ($quickPlots.val() || []).length;
            const wanted = Math.max(QUICK_MIN_ROWS, selectedCount + QUICK_EXTRA_ROWS);
            $quickPlots.attr('size', Math.max(1, Math.min(total, wanted, QUICK_MAX_ROWS)));
        }

        function resetQuickPanel() {
            $quickPlots.empty().prop('disabled', true);
            $('#quick_plots_body').hide();
            $('#quick_plots_col').hide();
            $quickHint.text('');
        }

        function addQuickRow(estate, plot, allPlots) {
            const $row = $(plotRowHtml(plotRowCounter++));
            $row.attr('data-quick', '1');
            $row.find('.estate-select').val(estate);

            const $plotSelect = $row.find('.plot-select').empty()
                .append('<option value="">-- Select Plot --</option>');
            allPlots.forEach(p => $plotSelect.append($('<option>').val(p).text(p)));
            $plotSelect.val(String(plot));

            $('#plot_items_container').append($row);
            loadPlotDetails($row, estate, plot);
        }

        $quickEstate.on('change', function () {
            const estate = $(this).val();
            resetQuickPanel();
            if (!estate) return;

            $('#quick_plots_col').show();
            $quickHint.text('Loading plots...');
            $.get('/get-second-option', { value: estate })
                .done(data => {
                    if ($quickEstate.val() !== estate) return; // estate changed meanwhile

                    const seen = {};
                    const plots = [];
                    data.forEach(item => {
                        const p = String(item.plot_number);
                        if (!seen[p]) { seen[p] = true; plots.push(p); }
                    });
                    quickPlotCache[estate] = plots;

                    $quickPlots.empty();
                    plots.forEach(p => $quickPlots.append($('<option>').val(p).text(p)));

                    // pre-select plots that already have a row
                    $quickPlots.find('option').each(function () {
                        if (findPlotRow(estate, this.value).length) this.selected = true;
                    });

                    $quickPlots.prop('disabled', plots.length === 0);
                    if (plots.length) {
                        $('#quick_plots_body').show();
                        resizeQuickPlots();
                    }
                    $quickHint.text(plots.length ? plots.length + ' plot(s) available' : 'No available plots in this estate');
                })
                .fail(() => {
                    resetQuickPanel();
                    Swal.fire('Error', 'Could not load plots', 'error');
                });
        });

        // Plain click toggles a plot (no Ctrl needed); Ctrl/Cmd/Shift keep native behaviour
        $quickPlots.on('mousedown', 'option', function (e) {
            if (e.ctrlKey || e.metaKey || e.shiftKey) return;
            e.preventDefault();
            this.selected = !this.selected;
            $quickPlots.trigger('focus').trigger('change');
        });

        $quickPlots.on('change', function () {
            const estate = $quickEstate.val();
            if (!estate) return;

            const selected = ($quickPlots.val() || []).map(String);
            resizeQuickPlots();

            // remove auto-added rows of this estate that were deselected
            $('.plot-item[data-quick="1"]').each(function () {
                const $row = $(this);
                if ($row.find('.estate-select').val() === estate &&
                    !selected.includes(String($row.find('.plot-select').val()))) {
                    $row.remove();
                }
            });

            // add a row for every newly selected plot
            selected.forEach(p => {
                if (!findPlotRow(estate, p).length) {
                    addQuickRow(estate, p, quickPlotCache[estate] || selected);
                }
            });
        });

        $('#quick_select_all').click(function () {
            if ($quickPlots.prop('disabled')) return;
            $quickPlots.find('option').prop('selected', true);
            $quickPlots.trigger('change');
        });

        $('#quick_clear').click(function () {
            if ($quickPlots.prop('disabled')) return;
            $quickPlots.find('option').prop('selected', false);
            $quickPlots.trigger('change');
        });

        // ---------- Remove a row ----------
        $(document).on('click', '.remove-plot-item', function () {
            const $row = $(this).closest('.plot-item');
            const estate = $row.find('.estate-select').val();
            const plot = String($row.find('.plot-select').val());
            $row.remove();

            // keep the quick-add list in sync
            if (estate && estate === $quickEstate.val() && !findPlotRow(estate, plot).length) {
                $quickPlots.find('option').filter(function () { return this.value === plot; })
                    .prop('selected', false);
                resizeQuickPlots();
            }
        });

        // ---------- Row events (manual rows and quick rows) ----------
        $(document).on('change', '.estate-select', function () {
            const estateSelect = $(this);
            const $row = estateSelect.closest('.plot-item');
            const plotSelect = $row.find('.plot-select');
            $row.removeAttr('data-quick'); // user edited it manually
            plotSelect.html('<option>Loading plots...</option>');
            $.get('/get-second-option', { value: estateSelect.val() })
                .done(data => {
                    plotSelect.empty().append('<option value="">-- Select Plot --</option>');
                    const seen = {};
                    data.forEach(item => {
                        const p = String(item.plot_number);
                        if (seen[p]) return;
                        seen[p] = true;
                        plotSelect.append(`<option value="${p}">${p}</option>`);
                    });
                })
                .fail(() => Swal.fire('Error', 'Could not load plots', 'error'));
        });

        $(document).on('change', '.plot-select', function () {
            const plotSelect = $(this);
            const container = plotSelect.closest('.plot-item');
            container.removeAttr('data-quick'); // user edited it manually
            loadPlotDetails(container, container.find('.estate-select').val(), plotSelect.val());
        });
    });
</script>

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