@include('includes.header')
<!-- partial -->

<style>
    .amenity-item {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .amenity-item input {
        flex: 1;
        margin-right: 10px;
    }

    .amenity-item .btn-danger {
        flex-shrink: 0;
    }

    #amenities-wrapper {
        margin-top: 10px;
    }
</style>

<div class="main-panel">
    <div class="content-wrapper">

        <div class="row">

            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">House Module</h4>

                        @include('sweetalert::alert')

                        @if (Session::get('success'))
                            <div class="alert alert-success">
                                {{ Session::get('success') }}
                            </div>
                        @endif

                        @if (Session::get('failed'))
                            <div class="alert alert-danger">
                                {{ Session::get('danger') }}
                            </div>
                        @endif


                        <form id="houseForm" class="form-sample" action="{{ route('send-house-data') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <p class="card-description">Enter house information</p>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Price</label>
                                        <input type="number" id="price" name="price" min="0"
                                            class="form-control" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Location</label>
                                        <input type="text" name="location" id="location" class="form-control" />
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="width1" class="col-form-label">Width 1 (sqm)</label>
                                        <input type="number" id="width1" name="width1" min="0"
                                            class="form-control" />
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="width2" class="col-form-label">Width 2 (sqm)</label>
                                        <input type="number" id="width2" name="width2" min="0"
                                            class="form-control" />
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="height1" class="col-form-label">Height 1 (sqm)</label>
                                        <input type="number" id="height1" name="height1" min="0"
                                            class="form-control" />
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="height2" class="col-form-label">Height 2 (sqm)</label>
                                        <input type="number" id="height2" name="height2" min="0"
                                            class="form-control" />
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Land Tenure</label>
                                        <select name="LandTenure" class="form-control">
                                            <option value="Customary Land Tenure"> 1. Customary Land Tenure</option>
                                            <option value="Freehold Land Tenure">2. Freehold Land Tenure</option>
                                            <option value="Mailo Land Tenure">3. Mailo Land Tenure</option>
                                            <option value="Leasehold Land Tenure">4. Leasehold Land Tenure</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-form-label">Bed Rooms</label>
                                        <input type="number" id="bedroom" name="bedroom" min="0"
                                            class="form-control" />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="col-form-label">Purchase Procedure</label>
                                        <select name="purchase_procedure" class="form-control">
                                            <option value="Cash">1. Cash</option>
                                            <option value="Bank">2. Bank</option>
                                            <option value="Initial Percentage">3. Initial Percentage</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="col-form-label">Amenities</label>

                                        <div id="amenities-wrapper">

                                        </div>

                                        <button type="button" class="btn btn-outline-info mt-2"
                                            onclick="addAmenity()">
                                            <i class="fas fa-plus-circle"></i> Add Amenity
                                        </button>

                                        <button type="button" class="btn btn-outline-primary mt-2"
                                            onclick="toggleAgreementUpload()">
                                            <i class="fas fa-paperclip"></i> Attach Agreement
                                        </button>


                                        <button type="button" class="btn btn-outline-warning mt-2"
                                            onclick="toggleHouseImagesUpload()">
                                            <i class="fas fa-paperclip"></i> Attach House Images
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="agreement-upload-section" style="display: none;">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="col-form-label">Upload Agreement(s)</label>
                                        <input type="file" name="agreements[]" class="form-control" multiple
                                            accept=".pdf, .jpg, .jpeg, .png, .doc, .docx"
                                            onchange="showSelectedFiles(this)" />
                                        <small class="form-text text-muted mt-1">
                                            You can upload multiple files (PDF, DOC, JPG, PNG).
                                        </small>

                                        <ul id="file-list" class="list-group mt-3"></ul>
                                    </div>
                                </div>
                            </div>


                            <div class="row" id="attach-house-images" style="display: none;">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="col-form-label">Upload House Images(s)</label>
                                        <input type="file" name="housePics[]" class="form-control" multiple
                                            accept=".pdf, .jpg, .jpeg, .png, .doc, .docx"
                                            onchange="showSelectedHouseFiles(this)" />
                                        <small class="form-text text-muted mt-1">
                                            You can upload multiple files (PDF, DOC, JPG, PNG).
                                        </small>

                                        <ul id="file-list-pics" class="list-group mt-3"></ul>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group row">

                                        <link rel="stylesheet"
                                            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

                                        <div class="col-sm-9">
                                            <button type="submit" class="btn btn-primary btn-md">
                                                <i class="fas fa-home"></i>
                                                Save House
                                            </button>
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
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">E-commerce Coming soon <a
                    href="javascript:void(0);" target="_blank">Sozo Properties</a></span>
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    function addAmenity() {
        const wrapper = document.getElementById('amenities-wrapper');

        const div = document.createElement('div');
        div.className = 'amenity-item';

        div.innerHTML = `
            <input type="text" name="amenities[]" class="form-control" placeholder="Enter amenity" />
            <button type="button" class="btn btn-danger" onclick="removeAmenity(this)">
                <i class="fas fa-trash-alt"></i>
            </button>
        `;

        wrapper.appendChild(div);
    }

    function removeAmenity(button) {
        button.parentElement.remove();
    }

    function toggleAgreementUpload() {
        const section = document.getElementById('agreement-upload-section');
        section.style.display = section.style.display === 'none' ? 'flex' : 'none';
    }

    function toggleHouseImagesUpload() {
        const section = document.getElementById('attach-house-images');
        section.style.display = section.style.display === 'none' ? 'flex' : 'none';
    }

    function showSelectedFiles(input) {
        const fileList = document.getElementById('file-list');
        fileList.innerHTML = '';

        Array.from(input.files).forEach(file => {
            const listItem = document.createElement('li');
            listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
            listItem.textContent = file.name;

            const fileSize = document.createElement('span');
            fileSize.className = 'badge bg-secondary rounded-pill';
            fileSize.textContent = `${(file.size / 1024).toFixed(1)} KB`;

            listItem.appendChild(fileSize);
            fileList.appendChild(listItem);
        });
    }

    function showSelectedHouseFiles(input) {
        const fileList = document.getElementById('file-list-pics');
        fileList.innerHTML = '';

        Array.from(input.files).forEach(file => {
            const listItem = document.createElement('li');
            listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
            listItem.textContent = file.name;

            const fileSize = document.createElement('span');
            fileSize.className = 'badge bg-secondary rounded-pill';
            fileSize.textContent = `${(file.size / 1024).toFixed(1)} KB`;

            listItem.appendChild(fileSize);
            fileList.appendChild(listItem);
        });
    }

    const inputIds = ['price', 'bedroom', 'width1', 'width2', 'height1', 'height2'];

    inputIds.forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('keydown', function(e) {
                if (e.key === '-' || e.key === 'e') {
                    e.preventDefault();
                }
            });
        }
    });

    document.getElementById('houseForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const requiredFields = ['price', 'bedroom', 'width1', 'width2', 'height1', 'height2', 'location'];
        let missingFields = [];
        let firstInvalid = null;

        // Clear previous validation
        requiredFields.forEach(id => {
            const field = document.getElementById(id);
            if (field) field.classList.remove('is-invalid');
        });

        // Check required fields
        requiredFields.forEach(id => {
            const field = document.getElementById(id);
            if (field && (!field.value || parseFloat(field.value) < 0)) {
                missingFields.push(id);
                field.classList.add('is-invalid');
                if (!firstInvalid) firstInvalid = field;
            }
        });

        // Validate amenities
        const amenities = document.querySelectorAll('#amenities-wrapper input[name="amenities[]"]');
        let emptyAmenities = [];

        amenities.forEach((input, index) => {
            input.classList.remove('is-invalid');
            if (!input.value.trim()) {
                emptyAmenities.push(index + 1);
                input.classList.add('is-invalid');
                if (!firstInvalid) firstInvalid = input;
            }
        });

        // Check file upload requirements
        const agreementVisible = document.getElementById('agreement-upload-section').style.display !== 'none';
        const housePicsVisible = document.getElementById('attach-house-images').style.display !== 'none';

        const agreementInput = document.querySelector('input[name="agreements[]"]');
        const housePicsInput = document.querySelector('input[name="housePics[]"]');

        let fileErrors = [];

        if (agreementInput) agreementInput.classList.remove('is-invalid');
        if (housePicsInput) housePicsInput.classList.remove('is-invalid');

        if (agreementVisible && (!agreementInput || agreementInput.files.length === 0)) {
            fileErrors.push('Please attach at least one <strong>Agreement</strong> file.');
            if (agreementInput) {
                agreementInput.classList.add('is-invalid');
                if (!firstInvalid) firstInvalid = agreementInput;
            }
        }

        // if (housePicsVisible && (!housePicsInput || housePicsInput.files.length === 0)) {
        //     fileErrors.push('Please attach at least one <strong>House Image</strong>.');
        //     if (housePicsInput) {
        //         housePicsInput.classList.add('is-invalid');
        //         if (!firstInvalid) firstInvalid = housePicsInput;
        //     }
        // }

        if (!housePicsInput || housePicsInput.files.length === 0) {
            fileErrors.push('Please attach at least one <strong>House Image</strong>.');
            if (housePicsInput) {
                housePicsInput.classList.add('is-invalid');
                if (!firstInvalid) firstInvalid = housePicsInput;
            }
        }

        // Show SweetAlert if there are issues
        if (missingFields.length > 0 || emptyAmenities.length > 0 || fileErrors.length > 0) {
            let errorMessage = '';

            if (missingFields.length > 0) {
                errorMessage += 'Please fill out the following fields:<br><strong>' + missingFields.join(', ') +
                    '</strong><br><br>';
            }

            if (emptyAmenities.length > 0) {
                errorMessage +=
                    `You have ${emptyAmenities.length} empty Amenity field(s). Fill or remove them.<br><br>`;
            }

            if (fileErrors.length > 0) {
                errorMessage += fileErrors.join('<br>') + '<br>';
            }

            Swal.fire({
                icon: 'error',
                title: 'Form Incomplete',
                html: errorMessage,
                confirmButtonText: 'Ok'
            });

            if (firstInvalid) firstInvalid.focus();
            return;
        }

        Swal.fire({
            title: 'Confirm Submission',
            text: 'Are you sure you want to submit this house listing?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Submit',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = e.target;
                const formData = new FormData(form);
                const submitBtn = form.querySelector('button[type="submit"]');

                // Disable button and show spinner
                submitBtn.disabled = true;
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = 'Saving... <i class="fas fa-spinner fa-spin"></i>';

                $.ajax({
                    url: form.action,
                    method: form.method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    // success: function(response) {
                    //     Swal.fire({
                    //         icon: 'success',
                    //         title: 'Submitted!',
                    //         text: 'Form submitted successfully.'
                    //     });
                    //     // Optionally reset form or redirect here
                    // },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Optionally reset form or reload
                            $('#houseForm')[0].reset();
                            $('#amenities-wrapper').html(
                                ''); // Clear dynamic amenities
                            $('#file-list').empty(); // Clear agreement file list
                            $('#file-list-pics')
                                .empty(); // Clear house image file list
                            $('#agreement-upload-section').hide();
                            $('#attach-house-images').hide();
                        });
                    },

                    error: function(data) {
                        $('body').html(data.responseText);
                    },
                    complete: function() {
                        // Restore the button
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                });
            }
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
