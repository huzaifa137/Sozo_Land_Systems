@include('includes.header')

<style>
    .uniform-img {
        width: 100%;
        height: 100%;
        /* Ensure images occupy the full height of their container */
        object-fit: cover;
        object-position: center;
        transition: transform 0.3s ease-in-out;
    }

    /* New style to center a single image */
    .single-image-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 200px;
        /* Set a consistent height for the images */
    }

    .single-image-container img {
        width: 100%;
        height: 100%;
        /* Ensure the image fills the container without distortion */
        object-fit: cover;
    }

    .card-body strong {
        color: #0dcaf0 !important;
    }

    /* Container to ensure alignment */
    .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        align-items: center;
    }

    .info-row strong {
        flex: 1;
        text-align: left;
        font-weight: bold;
        color: #0dcaf0;
        padding-right: 10px;
    }

    .info-row span {
        flex: 2;
        text-align: left;
    }

    /* Adjust for smaller screens */
    @media (max-width: 767px) {
        .info-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .info-row strong {
            width: 100%;
            margin-bottom: 5px;
        }

        .info-row span {
            width: 100%;
        }
    }

    /* Make sure card heights are consistent */
    .card {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .card-body {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .card-body h4 {
        margin-bottom: 20px;
    }

    .styled-image {
        height: 250px;
        width: 100%;
        object-fit: contain;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">House Information</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">House Details</li>
                </ol>
            </nav>
        </div>

        <?php
        
        use App\Models\AdminRegister;
        
        $user_id = Session('LoggedAdmin');
        $User_access_right = AdminRegister::where('id', '=', $user_id)->value('admin_category');
        ?>

        <div class="row">
            <!-- House Details -->
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">House Details</h4>

                        <div class="info-row">
                            <strong class="text-primary">Price:</strong>
                            <span>{{ @$pendingHouses->price }}</span>
                        </div>
                        <div class="info-row">
                            <strong class="text-primary">Location:</strong>
                            <span>{{ @$pendingHouses->location }}</span>
                        </div>
                        <div class="info-row">
                            <strong class="text-primary">Land Tenure:</strong>
                            <span>{{ @$pendingHouses->LandTenure }}</span>
                        </div>
                        <div class="info-row">
                            <strong class="text-primary">Bedrooms:</strong>
                            <span>{{ @$pendingHouses->bedroom }}</span>
                        </div>
                        <div class="info-row">
                            <strong class="text-primary">Dimensions:</strong>
                            <span>{{ @$pendingHouses->width1 }} x {{ @$pendingHouses->height1 }} (Front),
                                {{ @$pendingHouses->width2 }} x {{ @$pendingHouses->height2 }} (Back)</span>
                        </div>
                        <div class="info-row">
                            <strong class="text-primary">Purchase Procedure:</strong>
                            <span>{{ @$pendingHouses->purchase_procedure }}</span>
                        </div>
                        <div class="info-row">
                            <strong class="text-primary">Amenities:</strong>
                            <span>{{ @$pendingHouses->amenities }}</span>
                        </div>

                        <div class="info-row">
                            <strong class="text-primary">Status:</strong>
                            <span>{{ @$pendingHouses->status == 0 ? 'Available' : 'Sold' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- House Images -->
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">House Images</h4>
                        <div class="row">
                            @php
                                $images = json_decode(@$pendingHouses->house_images, true);
                            @endphp

                            @if (count($images) == 1)
                                <!-- For a single image, span full width -->
                                <div class="col-12 single-image-container">
                                    <img src="{{ asset('storage/' . $images[0]) }}" alt="House Image"
                                        class="uniform-img mb-2">
                                </div>
                            @else
                                <!-- For multiple images -->
                                @foreach ($images as $image)
                                    <div class="col-md-4">
                                        <img src="{{ asset('storage/' . $image) }}" alt="House Image"
                                            class="uniform-img mb-2">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agreement Files -->
            @if (!empty(@$pendingHouses->agreement_files) && json_decode(@$pendingHouses->agreement_files, true))
                <div class="col-12">
                    <br>
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Agreement Files</h4>
                            <ul class="list-unstyled">
                                @foreach (json_decode(@$pendingHouses->agreement_files, true) as $file)
                                    <li>
                                        <a href="{{ asset('storage/' . $file) }}" target="_blank" class="text-info">
                                            <i class="fa fa-file-pdf"></i> {{ basename($file) }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @php
                $user = DB::table('house_buyers')
                    ->where('house_id', @$pendingHouses->id)
                    ->first();
            @endphp

            <!-- Add margin-top to create space between sections -->
            <div class="col-12" style="margin-top: 50px;">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Buyer Information</h4>

                        @if (@$user)
                            @if (@$user->selling_status == 0)
                                <div class="alert alert-danger d-flex align-items-center" role="alert"
                                    style="font-size: 16px;">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong><span style="color: black;">CEO Pending Approval</span></strong>
                                </div>
                            @endif
                        @endif

                        @if ($user)
                            <!-- Show stored buyer details nicely formatted -->
                            <div class="row">
                                <div class="col-md-6 info-row">
                                    <strong>First Name:</strong><span>{{ $user->firstname }}</span>
                                </div>
                                <div class="col-md-6 info-row">
                                    <strong>Last Name:</strong><span>{{ $user->lastname }}</span>
                                </div>
                                <div class="col-md-6 info-row">
                                    <strong>Gender:</strong><span>{{ $user->gender }}</span>
                                </div>
                                <div class="col-md-6 info-row">
                                    <strong>Date of Birth:</strong><span>{{ $user->date_of_birth }}</span>
                                </div>
                                <div class="col-md-6 info-row">
                                    <strong>NIN:</strong><span>{{ $user->NIN }}</span>
                                </div>
                                <div class="col-md-6 info-row">
                                    <strong>Card Number:</strong><span>{{ $user->card_number }}</span>
                                </div>
                                <div class="col-md-6 info-row">
                                    <strong>Phone Number:</strong><span>{{ $user->phonenumber }}</span>
                                </div>
                                <div class="col-md-6 info-row">
                                    <strong>Sold By:</strong><span>{{ $user->sold_by }}</span>
                                </div>

                                <!-- Display Images -->
                                @php
                                    $imageStyle =
                                        'height: 250px; width: 100%; object-fit: cover; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);';
                                @endphp

                                <div class="col-md-4 mt-4 text-center">
                                    <label class="fw-bold mb-2">National ID Front</label>
                                    <img src="{{ asset('storage/' . $user->national_id_front) }}" class="img-thumbnail"
                                        style="{{ $imageStyle }}">
                                </div>

                                <div class="col-md-4 mt-4 text-center">
                                    <label class="fw-bold mb-2">National ID Back</label>
                                    <img src="{{ asset('storage/' . $user->national_id_back) }}" class="img-thumbnail"
                                        style="{{ $imageStyle }}">
                                </div>

                                <div class="col-md-4 mt-4 text-center">
                                    <label class="fw-bold mb-2">Profile Image</label>
                                    <img src="{{ asset('storage/' . $user->profile_pic) }}" class="img-thumbnail"
                                        style="{{ $imageStyle }}">
                                </div>

                            </div>
                        @else
                            <!-- Show the buyer form if no buyer found -->
                            <form class="form-sample" id="myForm" action="{{ route('store-buyer-house-details') }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                <p class="card-description">Enter customer purchasing information</p>

                                <input type="hidden" name="house_id" id="house_id" value="{{ @$pendingHouses->id }}">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">First Name</label>
                                            <input type="text" name="firstname" id="firstname"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Last Name</label>
                                            <input type="text" name="lastname" id="lastname"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Gender</label>
                                            <select name="gender" id="gender" class="form-control" required>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Date of Birth</label>
                                            <input type="date" name="date_of_birth" id="date_of_birth"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Nin Number</label>
                                            <input type="text" name="NIN" id="NIN" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Card Number</label>
                                            <input type="text" name="card_number" id="card_number"
                                                class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">National ID Front</label>
                                            <input type="file" name="national_id_front" id="national_id_front"
                                                class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">National ID Back</label>
                                            <input type="file" name="national_id_back" id="national_id_back"
                                                class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Profile Image</label>
                                            <input type="file" name="profile_pic" id="profile_pic"
                                                class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label mt-2">Phone Number</label>
                                            <input type="text" name="phonenumber" id="phonenumber"
                                                class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <button type="button" id="submit_click"
                                                    class="btn btn-primary">Sell</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>

                    <!-- Make sure Font Awesome is included in your layout head -->
                    <link rel="stylesheet"
                        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="container">
                                @if ($User_access_right == 'SuperAdmin')
                                    @if (@$user)
                                        @if (@$user->selling_status == 0)
                                            <td>
                                                <button class="btn btn-primary btn-md approve-sell-btn"
                                                    data-house-id="{{ $pendingHouses->id }}">
                                                    <i class="fas fa-check-circle"></i> Approve Sale
                                                </button>
                                            </td>
                                        @else
                                            <td>
                                                <button class="btn btn-success btn-md disabled">
                                                    <i class="fas fa-check-circle"></i> Approved & Sold
                                                </button>
                                            </td>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>



                    <br>

                </div>
            </div>
        </div>
    </div>
</div>
</div>


<script src="/assets/js/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).on('click', '.approve-sell-btn', function(e) {
        e.preventDefault();
        const button = $(this);
        const houseId = button.data('house-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "Please confirm you approve this sale!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Approve',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state on the button
                button.prop('disabled', true);
                button.html('Approving... <i class="fas fa-spinner fa-spin"></i>');

                // AJAX request
                $.ajax({
                    url: '{{ route('approve-house-sell') }}',
                    method: 'POST',
                    data: {
                        house_id: houseId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Update button text
                        button.html('<i class="fe fe-check"></i> Approved');

                        // Show SweetAlert success, then reload
                        Swal.fire({
                            title: 'Success!',
                            text: 'The house sale has been approved.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(data) {
                        // Dump the full error response into the body
                        $('body').html(data.responseText);
                    }
                });
            }
        });
    });
</script>


<script src="/assets/vendors/js/vendor.bundle.base.js"></script>
<script src="/assets/vendors/select2/select2.min.js"></script>
<script src="/assets/vendors/typeahead.js/typeahead.bundle.min.js"></script>
<script src="/assets/js/off-canvas.js"></script>
<script src="/assets/js/hoverable-collapse.js"></script>
<script src="/assets/js/misc.js"></script>
<script src="/assets/js/settings.js"></script>
<script src="/assets/js/todolist.js"></script>
<script src="/assets/js/file-upload.js"></script>
<script src="/assets/js/typeahead.js"></script>
<script src="/assets/js/select2.js"></script>
</body>

</html>
