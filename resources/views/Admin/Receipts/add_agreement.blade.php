@include('includes.header')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    .btn-custom {
        background: var(--sz-brass);
        color: var(--sz-brass-ink);
        border: none;
        padding: .95rem 2rem;
        font-size: 1.05rem;
        font-weight: 600;
        border-radius: 12px;
        cursor: pointer;
        transition: background .16s ease, transform .12s ease;
        display: inline-flex;
        align-items: center;
        gap: .7rem;
    }
    .btn-custom:hover { background: #e0b862; color: var(--sz-brass-ink); }
    .btn-custom:active { transform: translateY(1px); }

    .agreement-card {
        background: var(--sz-canopy);
        border: 1px solid var(--sz-rule);
        border-radius: 20px;
        box-shadow: var(--sz-shadow);
        padding: clamp(1.6rem, 4vw, 3rem);
        text-align: center;
        max-width: 600px;
        width: 100%;
    }
    .agreement-title { color: var(--sz-chalk); font-family: var(--sz-display); margin-bottom: .8rem; font-size: 2rem; font-weight: 600; }
    .agreement-subtitle { color: var(--sz-muted); margin-bottom: 2rem; font-size: 1rem; }

    .content-wrapper-v2 {
        min-height: 70vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem clamp(1rem, 2.6vw, 2.6rem);
    }
</style>

            <div class="main-panel">
                <div class="content-wrapper-v2">

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
                                                (window.SozoLoader ? SozoLoader.go('/pending-buyers') : (window.location.href = '/pending-buyers'));
                                            } else {
                                                // Redirect if user clicks "Close"
                                                (window.SozoLoader ? SozoLoader.go('/pending-buyers') : (window.location.href = '/pending-buyers'));
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