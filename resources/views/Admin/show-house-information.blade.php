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

            <div class="row">
                <!-- House Details -->
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">House Details</h4>

                            <div class="info-row">
                                <strong class="text-primary">Price:</strong>
                                <span>{{ $house->price }}</span>
                            </div>
                            <div class="info-row">
                                <strong class="text-primary">Location:</strong>
                                <span>{{ $house->location }}</span>
                            </div>
                            <div class="info-row">
                                <strong class="text-primary">Land Tenure:</strong>
                                <span>{{ $house->LandTenure }}</span>
                            </div>
                            <div class="info-row">
                                <strong class="text-primary">Bedrooms:</strong>
                                <span>{{ $house->bedroom }}</span>
                            </div>
                            <div class="info-row">
                                <strong class="text-primary">Dimensions:</strong>
                                <span>{{ $house->width1 }} x {{ $house->height1 }} (Front),
                                    {{ $house->width2 }} x {{ $house->height2 }} (Back)</span>
                            </div>
                            <div class="info-row">
                                <strong class="text-primary">Purchase Procedure:</strong>
                                <span>{{ $house->purchase_procedure }}</span>
                            </div>
                            <div class="info-row">
                                <strong class="text-primary">Amenities:</strong>
                                <span>{{ $house->amenities }}</span>
                            </div>

                            <div class="info-row">
                                <strong class="text-primary">Status:</strong>
                                <span>{{ $house->status == 0 ? 'Available' : 'Sold' }}</span>
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
                                    $images = json_decode($house->house_images, true);
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
                @if (!empty($house->agreement_files) && json_decode($house->agreement_files, true))
                    <div class="col-12">
                        <br>
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Agreement Files</h4>
                                <ul class="list-unstyled">
                                    @foreach (json_decode($house->agreement_files, true) as $file)
                                        <li>
                                            <a href="{{ asset('storage/' . $file) }}" target="_blank"
                                                class="text-info">
                                                <i class="fa fa-file-pdf"></i> {{ basename($file) }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Add margin-top to create space between sections -->
                <div class="col-12" style="margin-top: 50px;"> <!-- Adjust the margin as needed -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Buyer Information</h4>

                            <form class="form-sample" id="myForm" action="{{ route('store-buyer-details') }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                <p class="card-description">Enter customer purchasing information</p>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">First Name</label>
                                            <input type="text" name="firstname" id="firstname" class="form-control"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Last Name</label>
                                            <input type="text" name="lastname" id="lastname" class="form-control"
                                                required>
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
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
    </div>

    <script src="/assets/js/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
