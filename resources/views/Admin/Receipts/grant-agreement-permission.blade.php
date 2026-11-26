@include('includes.header')
<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">

        <style>
            .form-control {
                color: #fff !important;
            }

            .swal2-popup {
                background: #2c2f33 !important;
                color: #ffffff !important;
                border-radius: 10px;
                font-family: 'Segoe UI', sans-serif;
            }

            .swal2-title {
                color: #ffffff !important;
            }

            .swal2-html-container {
                color: #cccccc !important;
            }

            .swal2-icon {
                color: #00bcd4 !important;
                border-color: #00bcd4 !important;
                margin: 1.25em auto 1em auto !important;
            }

            .swal2-confirm {
                background-color: #3085d6 !important;
                color: #fff !important;
            }

            .swal2-cancel {
                background-color: #d33 !important;
                color: #fff !important;
            }

            .swal2-styled.swal2-confirm:focus,
            .swal2-styled.swal2-cancel:focus {
                box-shadow: none !important;
            }

            .select2-container .select2-selection--single {
                height: 38px !important;
                padding: 6px 12px;
                display: flex;
                align-items: center;
                border: 1px solid #ced4da;
                border-radius: 4px;
                font-size: 14px;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: normal !important;
                padding-left: 0;
                margin-left: 0;
                color: #495057;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 38px !important;
                top: 0px !important;
                right: 10px;
            }

            .dropdown-item:hover,
            .dropdown-item:focus {
                color: #16181b;
                text-decoration: none;
                background-color: #f8f9fa;
            }

            .role-search-add-group .dropdown-item:hover,
            .role-search-add-group .dropdown-item:focus {
                color: #000;
                text-decoration: none;
                background-color: #FFF;
            }

            .dropdown-item:hover p {
                color: black !important;
            }
        </style>

        <?php

use App\Models\AdminRegister;
$user = DB::table('admin_registers')->where('id', Session('LoggedAdmin'))->first();
$user_id = Session('LoggedAdmin');
$User_access_right = AdminRegister::where('id', '=', $user_id)->value('admin_category');
    
    ?>


        @include('sweetalert::alert')

        <section class="sz-panel" aria-label="Grant agreement permission">
            <header>
                <h3>Grant permission</h3>
                <span>{{ $groupPage->total() }} {{ \Illuminate\Support\Str::plural('client', $groupPage->total()) }} awaiting a decision</span>
            </header>

            <div class="table-responsive">
                <table class="table sz-stack">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th>Client name</th>
                            <th class="text-center">Total plots</th>
                            <th class="text-center">Total amount paid</th>
                            <th class="text-center">Actions</th>
                            <th class="text-center">View receipts</th>
                            <th class="text-center">Sketches</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($grouped_buyers as $multiple_user_id => $plots)
                            @php
                                $main_item = $plots->first();
                                $key = $loop->index;
                                $total_plots = $plots->count();
                                $total_paid = $plots->sum('amount_payed');
                                $group_status = $main_item->request_permission;
                            @endphp
                            <tr class="sz-group-row">
                                <td class="text-center" data-label="No.">{{ $groupPage->firstItem() + $key }}</td>
                                <td data-label="Client">
                                    <i class="mdi mdi-account-group me-2"></i>
                                    <strong>{{ $main_item->firstname }} {{ $main_item->lastname }}</strong>
                                </td>
                                <td class="text-center" data-label="Plots">
                                    <span class="sz-chip sky">{{ $total_plots }} {{ \Illuminate\Support\Str::plural('plot', $total_plots) }}</span>
                                </td>
                                <td class="text-center sz-money" data-label="Amount paid">{{ number_format($total_paid, 2) }}</td>
                                <td class="text-center" data-label="Action">
                                    @if ($group_status == 1 && ($LoggedAdminInfo->User_access_right ?? null) == 'SuperAdmin')
                                        <button class="btn btn-primary btn-sm confirm-permission-btn"
                                            data-id="{{ $multiple_user_id }}"
                                            data-name="{{ $main_item->firstname }}"
                                            data-plot-count="{{ $total_plots }}">
                                            <i class="mdi mdi-check-decagram me-1"></i> Confirm agreement for all ({{ $total_plots }})
                                        </button>
                                    @elseif ($group_status == 2)
                                        <a href="{{ url('add-agreement/' . $multiple_user_id) }}"
                                            class="btn btn-success btn-icon-text btn-sm">
                                            <i class="mdi mdi-file-document-box me-1"></i> Make agreement for all ({{ $total_plots }})
                                        </a>
                                    @endif
                                </td>
                                <td colspan="2"></td>
                            </tr>

                            @foreach ($plots as $plot_item)
                                @php
                                    $szPlotEstate = \App\Models\Estate::where('estate_name', $plot_item->estate)->first();
                                    $szPlotDocUrl = $szPlotEstate ? $szPlotEstate->documentUrl() : null;
                                @endphp
                                <tr class="sz-detail-row">
                                    <td></td>
                                    <td data-label=""><small class="fst-italic">Details</small></td>
                                    <td class="text-center" data-label="Estate">{{ $plot_item->estate }}</td>
                                    <td class="text-center" data-label="Plot"><strong>Plot no:</strong> {{ $plot_item->plot_number }}</td>
                                    <td class="text-center sz-money" data-label="Paid"><strong>Paid:</strong> {{ number_format((float) $plot_item->amount_payed) }}</td>
                                    <td class="text-center" data-label="Receipts">
                                        <a href="{{ url('/all-client-receipts/' . $plot_item->id) }}" class="btn btn-outline-info btn-sm btn-icon-text">
                                            <i class="mdi mdi-receipt btn-icon-prepend"></i> View receipts
                                        </a>
                                    </td>
                                    <td class="text-center" data-label="Sketch">
                                        @if ($szPlotDocUrl)
                                            <button type="button" class="btn btn-outline-warning btn-sm btn-icon-text open-pdf"
                                                data-bs-toggle="modal" data-bs-target="#pdfModal"
                                                data-pdf="{{ $szPlotDocUrl }}">
                                                <i class="mdi mdi-file-pdf-box btn-icon-prepend"></i> View sketch
                                            </button>
                                        @else
                                            <span class="text-muted">No sketch</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            @if ($group_status == 1 && ($LoggedAdminInfo->User_access_right ?? null) != 'SuperAdmin')
                                <tr class="sz-detail-row">
                                    <td colspan="7" class="text-center">
                                        <a href="javascript:void(0);" class="btn btn-primary btn-sm confirm-permission-btn"
                                            data-id="{{ $multiple_user_id }}"
                                            data-name="{{ $main_item->firstname }}"
                                            data-plot-count="{{ $total_plots }}">
                                            <i class="mdi mdi-check-decagram me-1"></i> Confirm permission
                                        </a>

                                        <a href="javascript:void(0);" class="btn btn-warning btn-sm confirm-revoke-btn"
                                            data-id="{{ $multiple_user_id }}"
                                            data-name="{{ $main_item->firstname }}"
                                            data-action="revoke"
                                            data-plot-count="{{ $total_plots }}">
                                            <i class="mdi mdi-shield-off-outline me-1"></i> Revoke permission
                                        </a>

                                        <a href="javascript:void(0);" class="btn btn-danger btn-sm confirm-delete-user"
                                            data-id="{{ $multiple_user_id }}"
                                            data-name="{{ $main_item->firstname }}"
                                            data-action="delete"
                                            data-plot-count="{{ $total_plots }}">
                                            <i class="mdi mdi-account-remove-outline me-1"></i> Delete user
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="sz-empty"><i class="mdi mdi-shield-check-outline"></i><b>Nothing to approve</b>No records requiring agreement permission were found.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $groupPage->links() }}</div>
        </section>

        {{-- One sketch viewer for the whole page --}}
        <div class="modal fade" id="pdfModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Estate document</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="sz-doc-viewer" id="pdfFrameBox" style="border:0;border-radius:0;margin:0">
                            <div class="sz-doc-stage">
                                <div class="sz-doc-state sz-doc-loading" id="pdfFrameLoading" hidden role="status">
                                    <span class="sz-spin" aria-hidden="true"></span>
                                    <b>Loading the sketch</b>
                                </div>
                                <iframe id="pdfFrame" class="sz-doc-frame" title="Estate sketch"
                                    onload="if (this.getAttribute('src')) { document.getElementById('pdfFrameLoading').hidden = true; document.getElementById('pdfFrameBox').classList.add('is-ready'); }"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.querySelectorAll('.open-pdf').forEach(button => {
                button.addEventListener('click', function () {
                    document.getElementById('pdfFrameBox').classList.remove('is-ready');
                    document.getElementById('pdfFrameLoading').hidden = false;
                    document.getElementById('pdfFrame').src = this.dataset.pdf + '#toolbar=0&navpanes=0&scrollbar=0';
                });
            });

            // Clear the iframe when the modal closes
            document.getElementById('pdfModal').addEventListener('hidden.bs.modal', function () {
                document.getElementById('pdfFrame').removeAttribute('src');
            });
        </script>
    </div>

    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright ©
                SozoPropertiesLimited.com
                2023</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> <a
                    href="javascript:void(0);" target="_blank">Sozo Properties</a></span>
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


<script>
    document.addEventListener('DOMContentLoaded', function () {

        const confirmButtons = document.querySelectorAll('.confirm-permission-btn');

        confirmButtons.forEach(button => {
            button.addEventListener('click', function () {

                const groupId = this.dataset.id;
                const clientName = this.dataset.name;
                const plotCount = this.dataset.plotCount;

                Swal.fire({
                    title: 'Confirm All Plots?',
                    html: `Are you sure you want to confirm agreement permission for <b>${clientName}</b> (All ${plotCount} plots)?`,
                    icon: 'warning',
                    showCancelButton: false,
                    confirmButtonText: 'Yes, confirm all',
                    cancelButtonColor: '#d33'
                }).then(result => {
                    if (result.isConfirmed) {

                        Swal.fire({
                            title: 'Processing...',
                            text: 'Please wait.',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });

                        // Using jQuery's AJAX to send the request
                        $.ajax({
                            url: `/confirm-group-permission/${groupId}`,
                            method: 'POST',
                            dataType: 'json',  // Expect JSON response
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            data: JSON.stringify({}),
                            contentType: 'application/json',  // Content type set to JSON
                            success: function (data) {
                                Swal.close();

                                if (data.success) {
                                    Swal.fire({
                                        title: 'Confirmed!',
                                        text: data.message,
                                        icon: 'success'
                                    }).then(() => (window.SozoLoader ? SozoLoader.reload() : location.reload()));
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: data.message || 'Something went wrong!',
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function (data) {
                                $('body').html(data.responseText);
                            }
                        });

                    }
                });

            });
        });
    });


    document.addEventListener('DOMContentLoaded', function () {

        const confirmButtons = document.querySelectorAll('.confirm-revoke-btn');

        confirmButtons.forEach(button => {
            button.addEventListener('click', function () {

                const groupId = this.dataset.id;
                const clientName = this.dataset.name;
                const plotCount = this.dataset.plotCount;

                Swal.fire({
                    title: 'Confirm All Plots?',
                    html: `Are you sure you want to revoke permission to generate these agreements for <b>${clientName}</b> (All ${plotCount} plots)?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, confirm all',
                    cancelButtonColor: '#d33'
                }).then(result => {
                    if (result.isConfirmed) {

                        Swal.fire({
                            title: 'Processing...',
                            text: 'Please wait.',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });

                        // Using jQuery's AJAX to send the request
                        $.ajax({
                            url: `/revoke-group-permission/${groupId}`,
                            method: 'POST',
                            dataType: 'json',  // Expect JSON response
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            data: JSON.stringify({}),
                            contentType: 'application/json',  // Content type set to JSON
                            success: function (data) {
                                Swal.close();

                                if (data.success) {
                                    Swal.fire({
                                        title: 'Confirmed!',
                                        text: data.message,
                                        icon: 'success'
                                    }).then(() => (window.SozoLoader ? SozoLoader.reload() : location.reload()));
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: data.message || 'Something went wrong!',
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function (data) {
                                $('body').html(data.responseText);
                            }
                        });

                    }
                });

            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {

        const confirmButtons = document.querySelectorAll('.confirm-delete-user');

        confirmButtons.forEach(button => {
            button.addEventListener('click', function () {

                const groupId = this.dataset.id;
                const clientName = this.dataset.name;
                const plotCount = this.dataset.plotCount;

                Swal.fire({
                    title: 'Confirm All Plots?',
                    html: `Are you sure you want to delete this client with their related agreements for <b>${clientName}</b> (All ${plotCount} plots)?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, confirm all',
                    cancelButtonColor: '#d33'
                }).then(result => {
                    if (result.isConfirmed) {

                        Swal.fire({
                            title: 'Processing...',
                            text: 'Please wait.',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });

                        // Using jQuery's AJAX to send the request
                        $.ajax({
                            url: `/delete-user-grouped-by-permission/${groupId}`,
                            method: 'POST',
                            dataType: 'json',  // Expect JSON response
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            data: JSON.stringify({}),
                            contentType: 'application/json',  // Content type set to JSON
                            success: function (data) {
                                Swal.close();

                                if (data.success) {
                                    Swal.fire({
                                        title: 'Confirmed!',
                                        text: data.message,
                                        icon: 'success'
                                    }).then(() => (window.SozoLoader ? SozoLoader.reload() : location.reload()));
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: data.message || 'Something went wrong!',
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function (data) {
                                $('body').html(data.responseText);
                            }
                        });

                    }
                });

            });
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