<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sozo Admin Dashboard</title>
    <link rel="stylesheet" href="/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="/assets/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="/assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="shortcut icon" href="/img/favicon.jpg" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}" />

</head>

<style>
    /* Custom Button Styles (Maintained & Slightly Enhanced) */
    .btn-custom {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        padding: 1rem 2.5rem;
        font-size: 1.25rem;
        font-weight: 600;
        border-radius: 50px;
        box-shadow: 0 8px 15px rgba(102, 126, 234, 0.3);
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        text-transform: uppercase;
        /* Added subtle enhancement */
        letter-spacing: 1px;
    }

    .btn-custom:hover {
        background: linear-gradient(135deg, #764ba2, #667eea);
        box-shadow: 0 15px 20px rgba(118, 75, 162, 0.5);
        transform: translateY(-3px);
    }

    .btn-custom:active {
        transform: translateY(-1px);
        box-shadow: 0 5px 10px rgba(102, 126, 234, 0.2);
    }

    /* SweetAlert Styles (Maintained) */
    .swal2-popup {
        background: #2c2f33 !important;
        color: #ffffff !important;
        border-radius: 10px;
        font-family: 'Segoe UI', sans-serif;
    }

    /* New Card/Panel Styles for Main Content */
    .agreement-card {
        background-color: #191c24;
        /* Should match the dashboard background color */
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        padding: 3rem;
        text-align: center;
        max-width: 600px;
        width: 90%;
        border: 1px solid rgba(255, 255, 255, 0.05);
        /* Subtle border for definition */
    }

    .agreement-title {
        color: #667eea;
        /* Color matching the button gradient */
        margin-bottom: 1rem;
        font-size: 2rem;
        font-weight: 700;
    }

    .agreement-subtitle {
        color: #b8b8b8;
        margin-bottom: 2.5rem;
        font-size: 1rem;
    }

    /* Override the initial height style for a cleaner card layout */
    .content-wrapper-v2 {
        min-height: 80vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding-top: 2rem;
        /* Ensure space from the navbar */
        padding-bottom: 2rem;
    }

    /* NEW CSS ADDED: Border for Sidebar Separation */
    .sidebar {
        border-right: 1px solid rgba(255, 255, 255, 0.08);
        /* Subtle, dark-theme appropriate border */
    }
</style>

<body>
    <div class="container-scroller">
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
                <a class="sidebar-brand brand-logo" href="{{ route('admin-dashboard')}}"><img
                        src="/assets/images/logo.svg" alt="logo" /></a>
                <a class="sidebar-brand brand-logo-mini" href="{{ route('admin-dashboard')}}"><img
                        src="/assets/images/logo-mini.svg" alt="logo" /></a>
            </div>

            @include('includes.SideBar')

        </nav>

        <div class="container-fluid page-body-wrapper">
            <nav class="navbar p-0 fixed-top d-flex flex-row">
                <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
                    <a class="navbar-brand brand-logo-mini" href="{{route('admin-dashboard')}}"><img
                            src="/assets/images/logo-mini.svg" alt="logo" /></a>
                </div>
                <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button"
                        data-toggle="minimize">
                        <span class="mdi mdi-menu"></span>
                    </button>

                    <ul class="navbar-nav navbar-nav-right">
                        <?php

use App\Models\AdminRegister;
// These use statements are already in your original code, but kept here for clarity in the block
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Session;

$current_user_id = Session('LoggedAdmin');
$User_access_right = AdminRegister::where('id', '=', $current_user_id)->value('admin_category');
?>

                        @if ($User_access_right == 'SuperAdmin')
                            <li class="nav-item dropdown d-none d-lg-block">
                                <a class="nav-link btn btn-success create-new-button" id="createbuttonDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">+ Manage Admins</a>
                                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                                    aria-labelledby="createbuttonDropdown">
                                    <h6 class="p-3 mb-0" style="text-align: center">Sozo Properties</h6>
                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item preview-item" href="{{ url('/admin-register') }}">
                                        <div class="preview-thumbnail">
                                            <div class="preview-icon bg-dark rounded-circle">
                                                <i class="mdi mdi-plus text-primary"></i>
                                            </div>
                                        </div>
                                        <div class="preview-item-content">
                                            <p class="preview-subject ellipsis mb-1"> Add new Admin</p>
                                        </div>
                                    </a>
                                    <div class="dropdown-divider"></div>
                            </li>
                        @endif

                        <?php

$user = DB::table('admin_registers')->where('id', Session('LoggedAdmin'))->first();
?>

                        <li class="nav-item dropdown">
                            <a class="nav-link" id="profileDropdown" href="#" data-bs-toggle="dropdown">
                                <div class="navbar-profile">
                                    <img class="img-xs rounded-circle" src="/assets/images/faces/face15.jpg" alt="">
                                    <p class="mb-0 d-none d-sm-block navbar-profile-name">{{ $user->username }}</p>
                                    <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                                aria-labelledby="profileDropdown">
                                <h6 class="p-3 mb-0">Profile</h6>
                                <div class="dropdown-divider"></div>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item preview-item" href="{{ route('admin-logout') }}">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon bg-dark rounded-circle">
                                            <i class="mdi mdi-logout text-danger"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content">
                                        <p class="preview-subject mb-1">Log out</p>
                                    </div>
                                </a>
                            </div>
                        </li>
                    </ul>


                    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                        data-toggle="offcanvas">
                        <span class="mdi mdi-format-line-spacing"></span>
                    </button>
                </div>
            </nav>
            <div class="main-panel">
                <div class="content-wrapper-v2">

                    <script>
                        @if(session('error'))
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: '{{ session('error') }}',
                                confirmButtonText: 'OK'
                            });
                        @endif
                    </script>

                    <div class="agreement-card">
                        <h2 class="agreement-title">Agreement Generation</h2>
                        <p class="agreement-subtitle">Click the button below to instantly generate and finalize the
                            contract for the
                            buyer.</p>

                        <form id="agreementForm" action="{{ route('store-agreement') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user_id }}">

                            <button type="button" id="generateBtn" class="btn-custom">
                                <i class="fas fa-file-contract me-2"></i> Generate Agreement
                            </button>
                        </form>
                    </div>
                </div>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                <script>
                    document.getElementById('generateBtn').addEventListener('click', function () {
                        Swal.fire({
                            title: 'Confirm Agreement',
                            text: 'Are you sure you want to generate this agreement?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, generate it!',
                            cancelButtonText: 'Cancel',
                            reverseButtons: true,
                            focusCancel: true,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const btn = this;
                                btn.disabled = true;
                                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Generating...';

                                // AJAX form submission
                                $.ajax({
                                    url: $('#agreementForm').attr('action'),
                                    type: $('#agreementForm').attr('method'),
                                    data: $('#agreementForm').serialize(),
                                    success: function (response) {
                                        Swal.fire({
                                            title: 'Success!',
                                            text: response.message,
                                            icon: 'success',
                                            showCancelButton: true,
                                            confirmButtonText: 'Download Agreement',
                                            cancelButtonText: 'Close'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                // Open the PDF in a new tab
                                                window.open(response.download_link, '_blank');
                                                // Optional: redirect after download
                                                window.location.href = '/pending-buyers';
                                            } else {
                                                // Redirect if user clicks "Close"
                                                window.location.href = '/pending-buyers';
                                            }

                                            // Re-enable button
                                            $('#generateBtn').prop('disabled', false).html('<i class="fas fa-file-contract me-2"></i> Generate Agreement');
                                        });
                                    },

                                    //                         error: function (xhr) {
                                    //                             let errorMsg = xhr.responseJSON?.message || 'Something went wrong!';
                                    //                             Swal.fire('Error', errorMsg, 'error');
                                    //                             $('#generateBtn').prop('disabled', false).html('<i class="fas fa-file-contract me-2"></i> Generate Agreement');
                                    //                         }

                                    error: function (data) {
                                        $('body').html(data.responseText);
                                    }

                                });
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>

    <script type="text/javascript"></script>
    <script src="/assets/js/jquery.min.js"></script>

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