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
            <h4 class="card-title">Under payment Payments :</h4>

            @include('sweetalert::alert')

            @if (Session::get('success'))
              <div class="alert alert-success">
                {{Session::get('success')}}
              </div>
            @endif

            @if (Session::get('error'))
              <div class="alert alert-danger">
                {{Session::get('error')}}
              </div>
            @endif

            @if(session('download_agreement_link'))
              <div id="receipt-alert" class="alert alert-success mt-3 d-flex justify-content-between align-items-center">
                <span>Agreement saved successfully.</span>
                <a href="{{ session('download_agreement_link') }}" target="_blank" class="btn btn-sm btn-primary">
                  <i class="bi bi-download"></i> Download Agreement
                </a>
              </div>

              <script>
                document.addEventListener('DOMContentLoaded', function () {
                  Swal.fire({
                    icon: 'success',
                    title: 'Agreement Generated!',
                    text: 'You can download the agreement below.',
                    timer: 9000,
                    showConfirmButton: false,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end'
                  });
                });
              </script>
            @endif

            <div class="row ">

              @if (Session::get('success'))
                <div class="alert alert-success">
                  {{Session::get('success')}}
                </div>
              @endif

              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr>
                            <th>No.</th>
                            <th> Client Name </th>
                            <th> Estate </th>
                            <th> Plot No </th>
                            <th> Amount Payed </th>
                            @if ($User_access_right == 'Admin')
                              <th style="text-align: center"> Reciepts </th>
                            @endif
                            @if ($User_access_right == 'SuperAdmin')
                              <th style="text-align: center"> View Reciepts </th>
                            @endif
                            <th> Agreement</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($not_fully_paid as $key => $item)

                                                    <?php 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                $estatePrice = DB::table('estates')
                              ->where('estate_name', $item->estate)
                              ->value('estate_price');

                            $cleanPrice = str_replace(',', '', $estatePrice);

                            // check for exceptional plots and exceptional amounts

                            $exceptionalPlot = DB::table('plots')
                              ->where('estate', $item->estate)
                              ->where('plot_number', $item->plot_number)
                              ->first();

                            if ($exceptionalPlot->exceptional_status == 'Yes') {
                              $exceptionalPrice = $exceptionalPlot->exceptional_amount;
                            } elseif ($exceptionalPlot->exceptional_status == 'No') {
                              $amount_payed = $item->amount_payed;
                            }
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ?>

                                                    <tr>
                                                      <td>{{$key + 1}}</td>
                                                      <td>
                                                        <span>{{$item->firstname}} {{$item->lastname}}</span>
                                                      </td>
                                                      <td> {{$item->estate}} </td>
                                                      <td> {{$item->plot_number}} </td>
                                                      <td> {{$item->amount_payed}} </td>

                                                      <!--@if ($User_access_right == 'Admin')-->


                                                      <!--                         @endif-->

                                                      <td>
                                                        <a href="{{ 'add-reciept/' . $item->id }}" class="btn btn-outline-info btn-icon-text">
                                                          <i class="mdi mdi-file-plus btn-icon-prepend"></i>
                                                          Make Receipt
                                                        </a>

                                                        <!-- Button to open modal -->
                                                        <a href="javascript:void(0);"
                                                          class="btn btn-outline-success btn-icon-text open-receipt-modal"
                                                          data-id="{{ $item->id }}" data-toggle="modal" data-target="#uploadReceiptModal">
                                                          <i class="mdi mdi-camera btn-icon-prepend"></i>
                                                          Upload Receipts
                                                        </a>
                                                      </td>

                                                      @if ($User_access_right == 'SuperAdmin' || $User_access_right == 'Admin')
                                                        <td>
                                                          <a href="{{ url('/all-client-receipts/' . $item->id) }}" class="btn btn-outline-primary">
                                                            <i class="mdi mdi-receipt me-2 text-primary"></i>
                                                            Client Receipts
                                                          </a>
                                                        </td>
                                                      @endif

                                                      <td>
                                                        @if($item->request_permission == 0)
                                                          <button class="btn btn-warning btn-sm request-permission-btn" data-id="{{ $item->id }}"
                                                            data-exceptional-status="{{ $exceptionalPlot->exceptional_status }}"
                                                            data-exceptional-price="{{ $exceptionalPlot->exceptional_amount }}"
                                                            data-clean-price="{{ $cleanPrice }}" data-amount-payed="{{ $item->amount_payed }}"
                                                            data-firstname="{{ $item->firstname }}" data-plot="{{ $item->plot_number }}">
                                                            <i class="mdi mdi-lock-question me-1"></i> Request Permission
                                                          </button>

                                                        @elseif($item->request_permission == 1)
                                                          @if ($User_access_right == 'SuperAdmin')
                                                            <button class="btn btn-primary   btn-sm confirm-permission-btn" data-id="{{ $item->id }}"
                                                              data-name="{{ $item->firstname }}" data-plot="{{ $item->plot_number }}">
                                                              <i class="mdi mdi-check-decagram me-1"></i> Confirm Permission
                                                            </button>
                                                          @else
                                                            <a href="javascript:void();" class="btn btn-primary btn-sm">
                                                              <i class="mdi mdi-clock-outline me-1"></i> Pending Confirmation
                                                            </a>
                                                          @endif
                                                        @elseif($item->request_permission == 2)
                                                          <a href="{{ url('add-agreement/' . $item->id) }}"
                                                            class="btn btn-outline-success btn-icon-text">
                                                            <i class="mdi mdi-eye btn-icon-prepend"></i> Make Agreement
                                                          </a>
                                                        @endif
                                                      </td>
                                                    </tr>

                          @endforeach

                        </tbody>
                      </table>

                      <!-- Upload Receipt Modal -->
                      <div class="modal fade" id="uploadReceiptModal" tabindex="-1" role="dialog"
                        aria-labelledby="uploadReceiptModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <form id="receiptForm" method="POST" action="{{ route('receipt.upload') }}"
                            enctype="multipart/form-data" novalidate>
                            @csrf
                            <input type="hidden" name="item_id" id="modal_item_id">
                            <div class="modal-content" style="background-color: #343a40; color: #fff;">

                              <div class="modal-header">
                                <h5 class="modal-title" id="uploadReceiptModalLabel">Upload Receipt</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>

                              <div class="modal-body">
                                <!-- Receipt File Upload -->
                                <div class="form-group">
                                  <label for="receipt">Attach Receipt</label>
                                  <input type="file" class="form-control" id="receipt" name="receipt"
                                    accept="image/*,application/pdf" required>
                                  <div class="invalid-feedback">Please attach a receipt.</div>

                                  <!-- Preview Image -->
                                  <img id="receiptPreview" src="#" alt="Preview" class="img-fluid mt-2"
                                    style="display: none; max-height: 200px;" />
                                </div>

                                <!-- Amount Paid -->
                                <div class="form-group">
                                  <label for="amount_paid">Amount Paid</label>
                                  <input type="text" class="form-control" id="amount_paid" name="amount_paid" required>
                                  <div class="invalid-feedback">Amount Paid is required.</div>
                                </div>

                                <!-- Balance -->
                                <div class="form-group">
                                  <label for="balance">Balance</label>
                                  <input type="text" class="form-control" id="balance" name="balance" required>
                                  <div class="invalid-feedback">Balance is required.</div>
                                </div>

                              </div>

                              <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">
                                  <i class="mdi mdi-content-save"></i> Save
                                </button>
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

<script type="text/javascript"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.request-permission-btn');

    buttons.forEach(button => {
      button.addEventListener('click', function () {
        const exceptionalStatus = this.dataset.exceptionalStatus;
        const exceptionalPrice = parseFloat(this.dataset.exceptionalPrice.replace(/,/g, '') || 0);
        const cleanPrice  = parseFloat(this.dataset.cleanPrice.replace(/,/g, '') || 0);
        const EstatePrice = cleanPrice; // ✅ re-assign value
        const amountPayed = parseFloat(this.dataset.amountPayed.replace(/,/g, '') || 0);
        const clientName = this.dataset.firstname;
        const plotNumber = this.dataset.plot;
        const itemId = this.dataset.id; // Add this in HTML (explained below)
        let isValid = false;
        let errorMessage = '';
        let alertType = '';

        if (exceptionalStatus === 'Yes') {
          if ( amountPayed < exceptionalPrice) {
            errorMessage = `This is an exceptional plot (${plotNumber}) for ${clientName}. Required amount is Ugx${exceptionalPrice.toLocaleString()}, which is less than the amount paid: Ugx${amountPayed.toLocaleString()}.`;
            alertType = 'error';
          }else {
            isValid = true;
          }
        } else {
          if (amountPayed < EstatePrice) {
            errorMessage = `Amount paid (Ugx${amountPayed.toLocaleString()}) is not enough for estate price (Ugx${cleanPrice.toLocaleString()}).`;
            alertType = 'warning';
          } else {
            isValid = true;
          }
        }

        if (!isValid) {
          Swal.fire({
            icon: alertType,
            title: alertType === 'error' ? 'Exceptional Plot' : 'Payment Not Enough',
            html: errorMessage
          });
          return;
        }

        Swal.fire({
          title: 'Are you sure?',
          text: "Do you want to request permission to generate agreement?",
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, request it!'
        }).then((result) => {
          if (result.isConfirmed) {
            fetch(`/update-request-permission/${itemId}`, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              },
              body: JSON.stringify({ request_permission: 1 })
            })
              .then(async (response) => {
                const contentType = response.headers.get('Content-Type');
                let data;

                if (contentType && contentType.includes('application/json')) {
                  data = await response.json();
                } else {
                  const text = await response.text();
                  throw new Error(text);
                }

                if (response.ok) {
                  if (data.success) {
                    Swal.fire({
                      icon: 'success',
                      title: 'Permission Requested',
                      text: 'Request has been submitted successfully.'
                    }).then(() => {
                      location.reload();
                    });
                  } else {
                    // Backend returned success: false with an error message
                    Swal.fire({
                      icon: 'error',
                      title: 'Error',
                      text: data.message || 'Failed to update request. Please try again.'
                    });
                  }
                } else {
                  // Response is not OK (like 500 or 400)
                  throw new Error(data.message || 'Server returned an error response.');
                }
              })
              .catch((error) => {
                // Show actual error message (like HTML or Laravel exception)
                Swal.fire({
                  icon: 'error',
                  title: 'Server Error',
                  html: `<pre style="text-align: left;">${escapeHtml(error.message)}</pre>`
                });

                console.error('Fetch error:', error);
              });
          }
        });

      });
    });
  });



document.addEventListener('DOMContentLoaded', function () {
  const confirmButtons = document.querySelectorAll('.confirm-permission-btn');

  confirmButtons.forEach(button => {
    button.addEventListener('click', function () {
      const itemId = this.dataset.id;
      const clientName = this.dataset.name;
      const plotNumber = this.dataset.plot;

      Swal.fire({
        title: 'Confirm Permission?',
        html: `Are you sure you want to confirm permission for <b>${clientName}</b> (Plot ${plotNumber})?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, confirm it'
      }).then((result) => {
        if (result.isConfirmed) {

          // Step 1: Show "Generating Agreement..." loading alert
          Swal.fire({
            title: 'Generating Agreement...',
            text: 'Please wait while we generate the agreement.',
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });

          // Step 2: Send AJAX request
          $.ajax({
            url: `/confirm-request-permission/${itemId}`,
            type: 'POST',
            data: JSON.stringify({ request_permission: 2 }),
            contentType: 'application/json',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            success: function (response) {
              Swal.close();

              if (response.status === 'success') {
                Swal.fire({
                  title: 'Agreement Generated!',
                  text: response.message,
                  icon: 'success',
                  confirmButtonText: 'OK'
                }).then(() => {
                  // Redirect when user clicks OK
                  window.location.href = '/accomplished';
                });
              } else {
                Swal.fire({
                  title: 'Error!',
                  text: response.message || 'Something went wrong!',
                  icon: 'error'
                });
              }
            },
            error: function (xhr) {
              Swal.close();

              let msg = 'An unexpected error occurred.';
              if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
              }

              Swal.fire({
                title: 'Error!',
                text: msg,
                icon: 'error'
              });
            }
          });
        }
      });
    });
  });
});




</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('receiptForm');
    const receiptInput = document.getElementById('receipt');
    const receiptPreview = document.getElementById('receiptPreview');
    const itemIdInput = document.getElementById('modal_item_id');
    const amountPaidInput = document.getElementById('amount_paid');
    const balanceInput = document.getElementById('balance');

    // 🔁 Format number with commas (on input)
    function formatNumberWithCommas(input) {
      const value = input.value.replace(/,/g, '');
      if (!isNaN(value) && value !== '') {
        input.value = parseFloat(value).toLocaleString();
      }
    }

    amountPaidInput.addEventListener('input', () => formatNumberWithCommas(amountPaidInput));
    balanceInput.addEventListener('input', () => formatNumberWithCommas(balanceInput));

    // 🖼️ Show receipt preview (image only)
    receiptInput.addEventListener('change', function () {
      const file = this.files[0];
      if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function (e) {
          receiptPreview.src = e.target.result;
          receiptPreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
      } else {
        receiptPreview.style.display = 'none';
        receiptPreview.src = '';
      }
    });

    // 🧠 Update item_id in modal when button is clicked
    document.querySelectorAll('.open-receipt-modal').forEach(button => {
      button.addEventListener('click', function () {
        const itemId = this.getAttribute('data-id');
        itemIdInput.value = itemId;
        form.reset();
        receiptPreview.style.display = 'none';
      });
    });

    // 🧪 Form submit with validation and SweetAlert confirmation
    form.addEventListener('submit', function (e) {
      e.preventDefault();

      // Remove previous validation
      form.classList.remove('was-validated');
      let hasError = false;

      // List of required field IDs
      const requiredFields = ['receipt', 'amount_paid', 'balance'];

      requiredFields.forEach(function (fieldId) {
        const field = document.getElementById(fieldId);
        const rawValue = field.value.replace(/,/g, '').trim();
        if (!rawValue) {
          field.classList.add('is-invalid');
          hasError = true;
        } else {
          field.classList.remove('is-invalid');
        }
      });

      if (hasError) {
        form.classList.add('was-validated');
        return;
      }

      // ✅ Confirm submission
      Swal.fire({
        title: 'Confirm Submission',
        text: "Are you sure all details are correct?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Submit'
      }).then((result) => {
        if (result.isConfirmed) {
          // Remove commas before submitting the form
          amountPaidInput.value = amountPaidInput.value.replace(/,/g, '');
          balanceInput.value = balanceInput.value.replace(/,/g, '');
          form.submit();
        }
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