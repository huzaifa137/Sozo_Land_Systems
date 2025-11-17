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

      thead th {
        background-color: #343a40;
        /* Dark grey */
        border-bottom: 2px solid #495057;
        padding: 12px 15px;
      }

      tbody td {
        padding: 12px 15px;
      }

      .table-hover tbody tr:hover {
        background-color: #495057;
        color: #fff !important;
      }

      .table-hover tbody tr:hover td {
        color: #fff !important;
      }
    </style>

    <?php

use App\Models\AdminRegister;
$user = DB::table('admin_registers')->where('id', Session('LoggedAdmin'))->first();
$user_id = Session('LoggedAdmin');
$User_access_right = AdminRegister::where('id', '=', $user_id)->value('admin_category');
    
    ?>


    <div class="row">

      <div class="col-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <h4>Grant Permission</h4>

            @include('sweetalert::alert')

          <div class="row">
              <div class="col-12 grid-margin">
                  <div class="card">
                      <div class="card-body">
                          <div class="table-responsive">
                              <table class="table table-hover">
                                  <thead>
                                    <tr>
                                        <th class="text-center">No.</th>
                                        <th class="text-center">Client Name</th>
                                        <th class="text-center">Total Plots</th>
                                        <th class="text-center">Total Amount Paid</th>
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

                                              // dd($main_item);
                                          @endphp
                                          
                                          <tr class="table-primary" style="border-top: 2px solid #007bff;">
                                              <td class="text-center">{{ $key + 1 }}</td>
                                              <td class="text-center">
                                                  <i class="mdi mdi-account-group me-2 text-primary"></i>
                                                  <strong>{{ $main_item->firstname }} {{ $main_item->lastname }}</strong>
                                              </td>
                                              <td class="text-center">
                                                  <span class="badge bg-info text-dark">{{ $total_plots }} Plots</span>
                                              </td>
                                              <td class="text-center">
                                                  <strong>{{ number_format($total_paid, 2) }}</strong>
                                              </td>
                                              <td class="text-center">
                                                  @if ($group_status == 1 && ($LoggedAdminInfo->User_access_right ?? null) == 'SuperAdmin')
                                                      <button class="btn btn-primary btn-sm confirm-permission-btn" data-id="{{ $multiple_user_id }}"
                                                          data-name="{{ $main_item->firstname }}" data-plot-count="{{ $total_plots }}">
                                                          <i class="mdi mdi-check-decagram me-1"></i> Confirm Agreement for All ({{ $total_plots }})
                                                      </button>
                                                  @elseif ($group_status == 2)
                                                      <a href="{{ url('add-agreement/' . $multiple_user_id) }}" 
                                                          class="btn btn-success btn-icon-text btn-sm">
                                                          <i class="mdi mdi-file-document-box me-1"></i> Make Agreement for All ({{ $total_plots }})
                                                      </a>
                                                  @else
                                                  @endif
                                              </td>
                                                 <td></td>
                                          </tr>
                                          
                                         @foreach ($plots as $plot_item)
                                            <tr class="table-light" style="color: #000 !important;">
                                                <td style="color: #000 !important;"></td>

                                                <td style="color: #000 !important;">
                                                    <small class="text-muted fst-italic" style="color: #000 !important;">Details:</small>
                                                </td>

                                                <td style="color: #000 !important;">{{ $plot_item->estate }}</td>

                                                <td style="color: #000 !important;">
                                                    <strong style="color: #000 !important;">Plot No:</strong> {{ $plot_item->plot_number }}
                                                </td>

                                                <td style="color: #000 !important;">
                                                    <strong style="color: #000 !important;">Paid:</strong> {{ $plot_item->amount_payed }}
                                                </td>

                                                <td class="text-center" style="color: #000 !important;">
                                                    <a href="{{ url('/all-client-receipts/' . $plot_item->id) }}"
                                                      class="btn btn-outline-info btn-xs"
                                                      style="color: #000 !important; border-color: #0d6efd;">
                                                        <i class="mdi mdi-receipt" style="color: #000 !important;"></i>
                                                        View Receipts
                                                    </a>
                                                </td>
                                            </tr>
                                          @endforeach

                                            @if ($group_status == 1 && ($LoggedAdminInfo->User_access_right ?? null) != 'SuperAdmin')
                                            <tr style="
                                                background-color:#f8f9fa;
                                                color:#000;
                                                --bs-table-hover-color:#000 !important;
                                                --bs-table-hover-bg:#e9ecef !important;
                                            "
                                                onmouseover="this.style.backgroundColor='#e9ecef';"
                                                onmouseout="this.style.backgroundColor='#f8f9fa';">

                                                <td colspan="6" class="text-center">
                                                    <a href="javascript:void(0);" 
                                                      class="btn btn-primary btn-sm confirm-permission-btn"
                                                      data-id="{{ $multiple_user_id }}"
                                                      data-name="{{ $main_item->firstname }}"
                                                      data-plot-count="{{ $total_plots }}">
                                                        <i class="mdi mdi-check-decagram me-1"></i> 
                                                        Confirm Permission
                                                    </a>
                                                </td>
                                            </tr>
                                            @endif

                                      @empty
                                          <tr>
                                              <td colspan="5" class="text-center text-warning py-4">
                                                  <i class="mdi mdi-alert-circle me-2"></i> No records requiring agreement permission found.
                                              </td>
                                          </tr>
                                      @endforelse
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
              </div>
          </div>

          </div>
        </div>
      </div>
    </div>
  </div>



  <footer class="footer">
    <div class="d-sm-flex justify-content-center justify-content-sm-between">
      <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © SozoPropertiesLimited.com
        2023</span>
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

                    fetch(`/confirm-group-permission/${groupId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({})
                    })
                    .then(async (res) => {

                        const contentType = res.headers.get("content-type");

                        if (contentType && contentType.includes("text/html")) {
                            // Laravel dd() output (HTML)
                            const html = await res.text();
                            document.body.innerHTML = html;
                            return;
                        }

                        // If JSON
                        return res.json();
                    })
                    .then(data => {
                        if (!data) return;

                        Swal.close();

                        if (data.success) {
                            Swal.fire({
                                title: 'Confirmed!',
                                text: data.message,
                                icon: 'success'
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.message || 'Something went wrong!',
                                icon: 'error'
                            });
                        }
                    })
                    .catch(err => {
                        Swal.close();
                        Swal.fire({
                            title: 'Server Error',
                            text: err.message || 'Something went wrong!',
                            icon: 'error'
                        });
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