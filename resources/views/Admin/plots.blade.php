@include('includes.header')
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">

                    <div class="row">

                        <div class="col-12 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Add a plot or House :</h4>

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


                                    <form class="form-sample" id="my_form" action="{{ route('send-plot-data') }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <p class="card-description">Enter Plot or House Information:</p>

                                        <div class="row">

                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Choose</label>
                                                    <div class="col-sm-9">
                                                        <select name="House_plot" id="House_plot" class="form-control"
                                                            required>
                                                            <option value="">---Select House or Plot---</option>
                                                            <option value="Plot">Plot</option>
                                                            {{-- <option value="House">House</option> --}}
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4" id="plot_estate">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Estate</label>
                                                    <div class="col-sm-9">
                                                        <select name="Estate1" id="Estate1" class="form-control"
                                                            required>
                                                            <option value="">---Select Estate ---</option>
                                                            @foreach ($estates as $estate)
                                                                <option value="{{ $estate->estate_name }}">
                                                                    {{ $estate->estate_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <input type="hidden" id="hidden_user_naeme"
                                                value="{{ $LoggedAdminInfo['username'] }}">

                                            <div class="col-md-4" id="plot_house" style="display: none" ;>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Estate</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="Estate2" id="Estate2"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Location</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="location" id="location"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">


                                            <div class="col-md-3">
                                                <label class="">Width 1 (sqm)</label>
                                                <div class="form-group row">
                                                    <div class="col-sm-9">
                                                        <input type="number" name="width1" id="width1"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-3">
                                                <label class="">Width 2 (sqm)</label>
                                                <div class="form-group row">
                                                    <div class="col-sm-9">
                                                        <input type="number" name="width2" id="width2"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-3">
                                                <label class="">Height 1 (sqm)</label>
                                                <div class="form-group row">
                                                    <div class="col-sm-9">
                                                        <input type="number" name="height1" id="height1"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-3">
                                                <label class="">Height 2 (sqm)</label>
                                                <div class="form-group row">
                                                    <div class="col-sm-9">
                                                        <input type="number" name="height2" id="height2"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Status</label>
                                                    <div class="col-sm-9">
                                                        <select name="land_status" id="land_status"
                                                            class="form-control">
                                                            <option value="">---Select Status ----</option>
                                                            <option value="Not_taken">Not taken</option>
                                                            <option value="Fully_taken">Fully Taken</option>
                                                            <option value="Under_payment">Under payment</option>

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Plot Number</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="plot_number" id="plot_number"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Exceptional</label>
                                                    <div class="col-sm-9">
                                                        <select name="exceptional_status" id="exceptional_status"
                                                            class="form-control">
                                                            <option value="">---Select ----</option>
                                                            <option value="No">No</option>
                                                            <option value="Yes">Yes</option>

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" id="full_details" style="display: none">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">First Name</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="firstname" id="firstname"
                                                            class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Last Name</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="lastname" id="lastname"
                                                            class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">National ID Front</label>
                                                    <div class="col-sm-9">
                                                        <input type="file" name="national_id_front"
                                                            id="national_id_front" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">National ID back</label>
                                                    <div class="col-sm-9">
                                                        <input type="file" name="national_id_back"
                                                            id="national_id_back" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Profile pic</label>
                                                    <div class="col-sm-9">
                                                        <input type="file" name="profile_pic" id="profile_pic"
                                                            class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label mt-2">Phone number</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="phonenumber" id="phonenumber"
                                                            class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Amount Paid </label>
                                                    <div class="col-sm-9">
                                                        <input type="number" name="amount_paid" id="amount_paid"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Date Sold</label>
                                                    <div class="col-sm-9">
                                                        <input type="date" name="date_sold" id="date_sold"
                                                            class="form-control" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-9" id="land_agreement">
                                                <div class="form-group row">
                                                    <label class="col-md-5 col-form-label">Attach Agreement</label>
                                                    <div class="col-sm-12">
                                                        <input type="file" id="agreement_added"
                                                            class="form-control" multiple>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row" id="land_balance" style="display: none">
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Balance </label>
                                                        <div class="col-sm-9">
                                                            <input type="number" name="balance" id="balance"
                                                                class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6" id="next_installment_pay">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Next payment</label>
                                                        <div class="col-sm-9">
                                                            <input type="date" name="next_installment_date" min="<?php echo date('Y-m-d'); ?>"
                                                                id="next_installment_date" class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" id="exceptional_amount_div" style="display: none;">
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="form-label">Enter exceptional amount for this
                                                        plot</label>
                                                    <div class="col-sm-9">
                                                        <input type="number" min="0"
                                                            name="exceptional_amount" id="exceptional_amount"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group row">
                                                    <div class="col-sm-9">
                                                        <button type="button" id="add_plot"
                                                            class="btn btn-primary">Add a plot</button>
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

            var hidden_user_naeme = $('#hidden_user_naeme').val();

            $("#land_status").change(function() {

                var land_status = $(this).val();

                if (land_status == 'Fully_taken') {
                    $('#full_details').show();
                    $('#land_balance').hide();
                    $('#next_installment_pay').hide();
                    $('#land_agreement').show();
                } else if (land_status == 'Under_payment') {
                    $('#full_details').show();
                    $('#land_balance').show();
                    $('#land_agreement').hide();
                    $('#next_installment_pay').show();

                } else {
                    $('#full_details').hide();
                    $('#land_balance').hide();
                    $('#land_agreement').hide();
                }
            });


            $(document).ready(function() {

                $("#exceptional_status").change(function() {

                    var exceptional_status = $(this).val();

                    if (exceptional_status == 'Yes') {

                        $('#exceptional_amount_div').show();
                    } else {
                        $('#exceptional_amount_div').hide();
                    }
                });
            });

            $("#House_plot").change(function() {

                var House_plot = $(this).val();

                if (House_plot == 'House') {
                    $('#plot_estate').hide();
                    $('#plot_house').show();
                } else {
                    $('#plot_estate').show();
                    $('#plot_house').hide();
                }
            });

            $('#add_plot').click(function() {

                var location = $('#location').val();
                var width1 = $('#width1').val();
                var width2 = $('#width2').val();
                var height1 = $('#height1').val();
                var height2 = $('#height2').val();
                var land_status = $('#land_status').val();
                var plot_number = $('#plot_number').val();
                var House_plot = $('#House_plot').val();
                var exceptional_status = $('#exceptional_status').val();
                var exceptional_amount = $('#exceptional_amount').val();
                var next_installment_date = $('#next_installment_date').val();
                var files = $('#agreement_added')[0].files;

                if (House_plot == 'House') {

                    var Estate = $('#Estate2').val();
                } else {
                    var Estate = $('#Estate1').val();
                }

                if (land_status == "Fully_taken") {

                    var firstname = $('#firstname').val();
                    var phonenumber = $('#phonenumber').val();

                    var lastname = $('#lastname').val();
                    var profile_pic = $('#profile_pic')[0].files[0];
                    var amount_paid = $('#amount_paid').val();
                    var date_sold = $('#date_sold').val();
                    var balance = "0";
                    var national_id_front = $("#national_id_front")[0].files[0];
                    var national_id_back = $("#national_id_back")[0].files[0];
                    var files = $('#agreement_added')[0].files;


                } else if (land_status == "Under_payment") {
                    var firstname = $('#firstname').val();
                    var profile_pic = $('#profile_pic')[0].files[0];
                    var phonenumber = $('#phonenumber').val();
                    var lastname = $('#lastname').val();
                    var amount_paid = $('#amount_paid').val();
                    var date_sold = $('#date_sold').val();
                    var balance = $('#balance').val();
                    var agreement_added = "Pending";
                    var national_id_front = $("#national_id_front")[0].files[0];
                    var national_id_back = $("#national_id_back")[0].files[0];

                } else {
                    var firstname = "-";
                    var lastname = "-";
                    var amount_paid = "-";
                    var date_sold = "-";
                    var balance = "-";
                    var agreement_added = "-";
                }

                var errors = validateForm();

                if (errors.length > 0) {
                    showErrors(errors);
                    return;
                } else {
                    $('#add_plot').attr('disabled', 'false');
                    $('#add_plot').html('Adding plot...');

                    var form_data = new FormData();

                    form_data.append('Estate', Estate);
                    form_data.append('location', location);
                    form_data.append('width1', width1);
                    form_data.append('width2', width2);
                    form_data.append('height1', height1);
                    form_data.append('height2', height2);
                    form_data.append('land_status', land_status);
                    form_data.append('firstname', firstname);
                    form_data.append('lastname', lastname);
                    form_data.append('phonenumber', phonenumber);
                    form_data.append('profile_pic', profile_pic);
                    form_data.append('amount_paid', amount_paid);
                    form_data.append('date_sold', date_sold);
                    form_data.append('balance', balance);
                    form_data.append('plot_number', plot_number);
                    form_data.append('House_plot', House_plot);
                    form_data.append('next_installment_date', next_installment_date);
                    form_data.append('national_id_front', national_id_front);
                    form_data.append('national_id_back', national_id_back);
                    form_data.append('hidden_user_naeme', hidden_user_naeme);
                    form_data.append('exceptional_status',exceptional_status);
                    form_data.append('exceptional_amount',exceptional_amount);

                    for (var i = 0; i < files.length; i++) {
                        form_data.append('files[]', files[i]);
                    }

                    $.ajax({
                        type: "post",
                        processData: false,
                        contentType: false,
                        cache: false,
                        data: form_data,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '/send-plot-data',
                        success: function(data) {
                            if (data.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    html: data.message,
                                    color: '#FFF',
                                    background: '#452e6f',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = '/plots';
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Validation Error',
                                    html: data.message,
                                    color: '#FFF',
                                    background: '#452e6f',
                                });
                            }
                        },
                        complete: function() {
                            $('#my_form button').prop('disabled', false);
                            $('#my_form button').html('Submit');
                        },
                        error: function(data) {
                            $('body').html(data.responseText);
                        }
                    });
                }

                function validateForm() {

                    var errors = [];

                    if (!Estate) {
                        errors.push("Estate field is required.");
                    }

                    if (!location) {
                        errors.push("Location field is required.");
                    }

                    if (!width1) {
                        errors.push("Width1 field is required.");
                    }

                    if (!width2) {
                        errors.push("Width2  field is required.");
                    }

                    if (!height1) {
                        errors.push("height1 field is required.");
                    }

                    if (!height2) {
                        errors.push("height2 name is required.");
                    }

                    if (!land_status) {
                        errors.push("Land status field is required.");
                    }

                    if (!plot_number) {
                        errors.push("Plot number field is required.");
                    }

                    if (!House_plot) {
                        errors.push(
                            "You have to select either plot or House being stored is required."
                        );
                    }

                    if (!exceptional_status) {
                        errors.push(
                            "Exceptional option is not selected."
                        );
                    }

                    if (exceptional_status == 'Yes') {
                        if (!exceptional_amount) {
                            errors.push("Exceptional amount is required");
                        }
                    }


                    if (land_status == "Fully_taken") {

                        if (!firstname) {
                            errors.push("firstname field is required.");
                        }

                        if (!lastname) {
                            errors.push("Lastname field is required.");
                        }

                        if (!profile_pic) {
                            errors.push("Profile picture field is required.");
                        }

                        if (!phonenumber) {
                            errors.push("phone number field is required.");
                        }

                        if (!amount_paid) {
                            errors.push("Amount paid field is required.");
                        }

                        if (!date_sold) {
                            errors.push("Date sold field is required.");
                        }

                        if (!balance) {
                            errors.push("Balance field is required.");
                        }

                        if (!national_id_front) {
                            errors.push("National Id Front field is required.");
                        }

                        if (!national_id_back) {
                            errors.push("National id Back field is required.");
                        }
                    }


                    if (land_status == "Under_payment") {

                        if (!firstname) {
                            errors.push("firstname field is required.");
                        }

                        if (!lastname) {
                            errors.push("Lastname field is required.");
                        }

                        if (!profile_pic) {
                            errors.push("Profile picture field is required.");
                        }

                        if (!phonenumber) {
                            errors.push("phone number field is required.");
                        }

                        if (!amount_paid) {
                            errors.push("Amount paid field is required.");
                        }

                        if (!date_sold) {
                            errors.push("Date sold field is required.");
                        }

                        if (!balance) {
                            errors.push("Balance field is required.");
                        }

                        if (!national_id_front) {
                            errors.push("National Id Front field is required.");
                        }

                        if (!national_id_back) {
                            errors.push("National id Back field is required.");
                        }

                        if (!next_installment_date) {
                            errors.push("Next Installment pay date field is required.");
                        }
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
    </script>


    <script src="/assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
