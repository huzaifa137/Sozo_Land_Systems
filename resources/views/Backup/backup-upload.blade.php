@include('includes.header')
<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Add Backup Information</h4>

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

                        <form class="form-sample" id="my_form" action="{{ route('send-plot-data') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <p class="card-description">Add User, Estate and Plot Information</p>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Select Estate</label>
                                        <select class="form-control" id="estate_dropdown" name="estate" required>
                                            <option value="">Choose Estate...</option>
                                            @foreach($estates as $estate)
                                                <option value="{{ $estate->estate_name }}">{{ $estate->estate_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden fields that will show after estate selection -->
                            <div id="user_details_section" style="display: none;">

                                <!-- Container for dynamic records -->
                                <div id="records_container">
                                    <!-- Records will be added here dynamically -->
                                </div>

                                <!-- Add More Button -->
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <button type="button" id="add_record_btn" class="btn btn-success" disabled>
                                            <i class="mdi mdi-plus"></i> Add Another Record
                                        </button>
                                    </div>
                                </div>

                                <button type="button" id="submit_btn" class="btn btn-primary" disabled>Submit All
                                    Records</button>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Template for dynamic records -->
<script type="text/template" id="record_template">
    <div class="record-item card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" class="form-control firstname" name="records[__INDEX__][firstname]" placeholder="Enter first name">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" class="form-control lastname" name="records[__INDEX__][lastname]" placeholder="Enter last name">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Plot Number</label>
                        <select class="form-control plot_dropdown" name="records[__INDEX__][plot_number]" required>
                            <option value="">Choose Plot...</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-danger btn-sm remove_record" style="display: block;">
                            <i class="mdi mdi-close"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>

<script>
    $(document).ready(function () {
        let recordCount = 0;
        const estatePlots = {}; // Cache for available plots per estate

        // When estate is selected
        $('#estate_dropdown').on('change', function () {
            var estateName = $(this).val();

            if (estateName) {
                // Show the user details section
                $('#user_details_section').show();

                // Clear all existing records
                $('#records_container').empty();
                recordCount = 0;

                // Initially disable add button until first record is complete
                $('#add_record_btn').prop('disabled', true);

                // Load plots for this estate
                loadEstatePlots(estateName);

                // Add first record automatically
                addNewRecord();
            } else {
                $('#user_details_section').hide();
                $('#add_record_btn').prop('disabled', true);
            }
        });

        // Function to load available plots via AJAX
        function loadEstatePlots(estateName) {
            $.ajax({
                url: '{{ route("get-available-plots") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    estate: estateName
                },
                success: function (response) {
                    // Cache the plots for this estate
                    estatePlots[estateName] = response.plots;

                    // Update plot dropdowns for all existing records
                    $('.plot_dropdown').each(function () {
                        updatePlotDropdown($(this));
                    });
                },
                error: function (data) {
                    $('body').html(data.responseText);
                }
            });
        }

        // Function to update a specific plot dropdown
        function updatePlotDropdown(dropdown) {
            var estateName = $('#estate_dropdown').val();
            var currentValue = dropdown.val();

            dropdown.empty().append('<option value="">Choose Plot...</option>');

            if (estatePlots[estateName] && estatePlots[estateName].length > 0) {
                // Get all currently selected plot numbers
                var selectedPlots = [];
                $('.plot_dropdown').not(dropdown).each(function () {
                    var val = $(this).val();
                    if (val) selectedPlots.push(val);
                });

                // Add only unselected plots
                $.each(estatePlots[estateName], function (index, plot) {
                    if (!selectedPlots.includes(plot.plot_number) || plot.plot_number === currentValue) {
                        dropdown.append('<option value="' + plot.plot_number + '">' + plot.plot_number + '</option>');
                    }
                });
            } else {
                dropdown.append('<option value="">No available plots</option>');
            }

            // Restore the value if it exists and is still available
            if (currentValue && dropdown.find('option[value="' + currentValue + '"]').length) {
                dropdown.val(currentValue);
            }
        }

        // Function to update all plot dropdowns
        function updateAllPlotDropdowns() {
            $('.plot_dropdown').each(function () {
                updatePlotDropdown($(this));
            });
        }

        // Function to check if we can add a new record
        function canAddNewRecord() {
            // If there are no records, we can add one
            if ($('.record-item').length === 0) {
                return true;
            }

            var canAdd = true;

            // Check each existing record
            $('.record-item').each(function () {
                var firstname = $(this).find('.firstname').val();
                var lastname = $(this).find('.lastname').val();
                var plot = $(this).find('.plot_dropdown').val();

                // If any record is incomplete, we cannot add a new one
                if (!firstname || firstname.trim() === '' ||
                    !lastname || lastname.trim() === '' ||
                    !plot || plot === '') {
                    canAdd = false;
                    return false; // break the loop
                }
            });

            return canAdd;
        }

        // Function to update add button status
        function updateAddButtonStatus() {
            if (canAddNewRecord()) {
                $('#add_record_btn').prop('disabled', false);
            } else {
                $('#add_record_btn').prop('disabled', true);
            }
        }

        // Add new record
        function addNewRecord() {
            var template = $('#record_template').html();
            var newRecord = template.replace(/__INDEX__/g, recordCount);
            $('#records_container').append(newRecord);

            // Initialize the new record's plot dropdown
            var newPlotDropdown = $('#records_container .record-item:last-child .plot_dropdown');
            updatePlotDropdown(newPlotDropdown);

            // Setup event handlers for the new record
            setupRecordHandlers($('#records_container .record-item:last-child'));

            recordCount++;

            // Disable add button immediately since new record is empty
            $('#add_record_btn').prop('disabled', true);

            // Check submit button status
            checkSubmitButtonStatus();
        }

        // Setup handlers for a record
        function setupRecordHandlers(record) {
            var firstname = record.find('.firstname');
            var lastname = record.find('.lastname');
            var plotDropdown = record.find('.plot_dropdown');
            var removeBtn = record.find('.remove_record');

            // Initially disable lastname and plot dropdown
            lastname.prop('disabled', true);
            plotDropdown.prop('disabled', true);

            // Firstname input handler
            firstname.on('input', function () {
                if ($(this).val().trim() !== '') {
                    lastname.prop('disabled', false);
                } else {
                    lastname.prop('disabled', true);
                    plotDropdown.prop('disabled', true);
                    plotDropdown.val('');
                }
                checkSubmitButtonStatus();
                updateAddButtonStatus();
            });

            // Lastname input handler
            lastname.on('input', function () {
                if ($(this).val().trim() !== '') {
                    plotDropdown.prop('disabled', false);
                } else {
                    plotDropdown.prop('disabled', true);
                    plotDropdown.val('');
                }
                checkSubmitButtonStatus();
                updateAddButtonStatus();
            });

            // Plot dropdown change handler
            plotDropdown.on('change', function () {
                // Update other dropdowns to reflect the new selection
                updateAllPlotDropdowns();
                checkSubmitButtonStatus();
                updateAddButtonStatus();
            });

            // Remove button handler
            removeBtn.on('click', function () {
                record.remove();
                updateAllPlotDropdowns();
                checkSubmitButtonStatus();
                updateAddButtonStatus();
            });
        }

        // Check if submit button should be enabled
        function checkSubmitButtonStatus() {
            var anyComplete = false;

            $('.record-item').each(function () {
                var firstname = $(this).find('.firstname').val();
                var lastname = $(this).find('.lastname').val();
                var plot = $(this).find('.plot_dropdown').val();

                if (firstname && firstname.trim() !== '' &&
                    lastname && lastname.trim() !== '' &&
                    plot && plot !== '') {
                    anyComplete = true;
                }
            });

            $('#submit_btn').prop('disabled', !anyComplete);
        }

        // Add record button click with validation
        $('#add_record_btn').on('click', function () {
            // Check if we can add a new record
            if (canAddNewRecord()) {
                addNewRecord();
            } else {
                Swal.fire({
                    title: 'Cannot Add Record',
                    text: 'Please complete all fields in the existing records first before adding a new one.',
                    icon: 'warning',
                    background: '#452e6f',
                    color: '#FFF',
                    timer: 3000,
                    showConfirmButton: true
                });
            }
        });

        // Submit button click with SweetAlert confirmation
        $('#submit_btn').on('click', function (e) {
            e.preventDefault();

            // Validate all records
            var isValid = true;
            var incompleteRecords = 0;

            $('.record-item').each(function () {
                var firstname = $(this).find('.firstname').val();
                var lastname = $(this).find('.lastname').val();
                var plot = $(this).find('.plot_dropdown').val();

                if (!firstname || !lastname || !plot) {
                    incompleteRecords++;
                    isValid = false;
                }
            });

            if (!isValid) {
                Swal.fire({
                    title: 'Incomplete Records',
                    text: incompleteRecords + ' record(s) are incomplete. Please fill all fields or remove empty records.',
                    icon: 'warning',
                    background: '#452e6f',
                    color: '#FFF'
                });
                return;
            }

            Swal.fire({
                title: 'Confirm Submission',
                text: "Are you sure you want to submit these " + $('.record-item').length + " records?",
                icon: 'question',
                showCancelButton: true,
                background: '#452e6f',
                color: '#FFF',
                confirmButtonText: 'Yes, submit it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitForm();
                }
            });
        });

        // Function to submit form via AJAX
        function submitForm() {

            var submitBtn = $('#submit_btn');
            var originalText = submitBtn.html();

            // Disable button and show spinner
            submitBtn.prop('disabled', true);
            submitBtn.html('Submitting records... <i class="fas fa-spinner fa-spin"></i>');

            var formData = new FormData($('#my_form')[0]);

            $.ajax({
                url: '{{ route("send-plot-data") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,

                success: function (response) {

                    Swal.fire({
                        title: 'Success!',
                        text: response.message || 'Information submitted successfully.',
                        icon: 'success',
                        background: '#452e6f',
                        color: '#FFF'
                    }).then(() => {
                        window.location.href = "{{ url('backup-uploads') }}";
                    });
                },

                error: function (data) {

                    // restore button if error occurs
                    submitBtn.prop('disabled', false);
                    submitBtn.html(originalText);

                    $('body').html(data.responseText);
                }
            });
        }
    });
</script>

</body>

</html>