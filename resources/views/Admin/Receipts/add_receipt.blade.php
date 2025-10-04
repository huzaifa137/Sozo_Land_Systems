@include('includes.header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
</style>

<div class="main-panel">
  <div class="content-wrapper">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <div class="row">
      <div class="col-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <h4 class="">Creat new reciept </h4>

            @include('sweetalert::alert')

            @if (Session::get('success'))
              <div class="alert alert-success">
                {{Session::get('success')}}
              </div>
            @endif

            @if (Session::get('failed'))
              <div class="alert alert-danger">
                {{Session::get('danger')}}
              </div>
            @endif

            @if(session('download_link'))
              <div id="receipt-alert" class="alert alert-success mt-3 d-flex justify-content-between align-items-center">
                <span>Receipt saved successfully.</span>
                <a href="{{ session('download_link') }}" target="_blank" class="btn btn-sm btn-primary">
                  <i class="bi bi-download"></i> Download Receipt
                </a>
              </div>

              <script>
                document.addEventListener('DOMContentLoaded', function () {
                  Swal.fire({
                    icon: 'success',
                    title: 'Receipt Saved!',
                    text: 'You can download the receipt below.',
                    timer: 4000,
                    showConfirmButton: false,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end'
                  });
                });
              </script>
            @endif

            <form class="form-sample" id="myForm" action="{{ route('store-new-receipt')}}" method="POST"
              enctype="multipart/form-data">
              @csrf

              <style>
                .profile-pic-wrapper {
                  width: 100px;
                  height: 100px;
                  border-radius: 50%;
                  overflow: hidden;
                  display: flex;
                  justify-content: center;
                  align-items: center;
                  border: 1px solid #FFF;
                  padding: 4px;
                  box-sizing: border-box;
                }

                .profile-pic {
                  width: 100%;
                  height: 100%;
                  object-fit: cover;
                  border-radius: 0;
                  display: block;
                }

                input[readonly] {
                  background-color: transparent !important;
                }
              </style>

              <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                <h4 class="mb-0">Add New Receipt</h4>

                <a href="{{ url('/all-client-receipts/' . $user_id) }}" class="btn btn-light btn-md text-primary">
                  <i class="fas fa-file-invoice-dollar me-2 text-primary"></i>
                  View Clients Receipts
                </a>
              </div>

              <div class="card-body">
                <div class="row align-items-center mb-4">
                  <div class="col-md-2 d-flex justify-content-center align-items-center">
                    <div class="profile-pic-wrapper">
                      @if($userInformation->profile_pic)
                        <img src="{{ asset('profile_pic/' . $userInformation->profile_pic) }}" alt="Profile Picture"
                          class="profile-pic border border-secondary p-1">
                      @else
                        <img src="{{ asset('path/to/default/profile_pic.png') }}" alt="Default Profile Picture"
                          class="profile-pic border border-secondary p-1">
                      @endif
                    </div>
                  </div>
                  <div class="col-md-10">
                    <h5 class="mb-0"><strong><span style="color:#0090E7;">Name:</span></strong>
                      {{ $userInformation->firstname }}
                      {{ $userInformation->lastname }}
                    </h5>
                    <p class="mb-0"><strong><span style="color:#0090E7;">Estate:</span></strong>
                      {{ $userInformation->estate }}</p>
                    <p class="mb-0"><strong><span style="color:#0090E7;">Plot Number:</span></strong>
                      {{ $userInformation->plot_number }}</p>
                  </div>
                </div>

                <hr class="my-4">

                <h6 class="card-title">Payment Information</h6>

                <input type="hidden" name="user_id" value="{{$user_id}}">
                <input type="hidden" name="user_email" value="{{$LoggedAdminInfo['email']}}">
                <input type="hidden" name="user_name" value="{{$LoggedAdminInfo['username']}}">
                <input type="hidden" name="phone_number" value="{{$userInformation['phonenumber']}}">

                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="amount_paid">Amount Paid</label>
                      <input type="text" name="amount_paid" id="amount_paid" class="form-control"
                        placeholder="Enter amount paid" required>
                      @error('amount_paid')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="Date_of_payment">Date of Payment</label>
                      <input type="date" name="Date_of_payment" id="Date_of_payment" class="form-control" readonly
                        required>
                      @error('Date_of_payment')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="balance_pending">Balance</label>
                      <input type="text" name="balance_pending" id="balance_pending" class="form-control"
                        placeholder="Enter balance" required>
                      @error('balance_pending')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="form-group">
                      <label for="amount_in_words">Amount in Words</label>
                      <input type="text" name="amount_in_words" id="amount_in_words" class="form-control" readonly
                        required>
                      @error('amount_in_words')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                  </div>
                </div>

                <button type="button" id="submitBtn" onclick="confirmSubmission()" class="btn btn-primary">
                  <i class="mdi mdi-content-save btn-icon-prepend"></i> <span id="submitBtnText">Save</span>
                </button>

            </form>
          </div>

          <script>
            const amountInput = document.getElementById('amount_paid');
            const amountInWords = document.getElementById('amount_in_words');

            amountInput.addEventListener('input', function (e) {
              const rawValue = this.value.replace(/[^0-9]/g, '');

              const formattedValue = Number(rawValue).toLocaleString();
              this.value = formattedValue;

              if (rawValue !== '') {
                amountInWords.value = convertNumberToWords(Number(rawValue));
              } else {
                amountInWords.value = '';
              }
            });

            function convertNumberToWords(num) {
              const a = [
                '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six',
                'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve',
                'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen',
                'Eighteen', 'Nineteen'
              ];
              const b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

              const numberToWords = (n) => {
                if (n < 20) return a[n];
                if (n < 100) return b[Math.floor(n / 10)] + (n % 10 ? ' ' + a[n % 10] : '');
                if (n < 1000) return a[Math.floor(n / 100)] + ' Hundred' + (n % 100 ? ' and ' + numberToWords(n % 100) : '');
                if (n < 1000000) return numberToWords(Math.floor(n / 1000)) + ' Thousand' + (n % 1000 ? ' ' + numberToWords(n % 1000) : '');
                if (n < 1000000000) return numberToWords(Math.floor(n / 1000000)) + ' Million' + (n % 1000000 ? ' ' + numberToWords(n % 1000000) : '');
                return numberToWords(Math.floor(n / 1000000000)) + ' Billion' + (n % 1000000000 ? ' ' + numberToWords(n % 1000000000) : '');
              };

              return numberToWords(num) + ' Ugandan Shilling Only.';
            }

            document.addEventListener('DOMContentLoaded', function () {
              const balanceInput = document.getElementById('balance_pending');

              balanceInput.addEventListener('input', function (e) {
                let value = e.target.value.replace(/,/g, '');
                if (!isNaN(value) && value !== '') {
                  e.target.value = Number(value).toLocaleString('en-US');
                }
              });
            });

            document.addEventListener('DOMContentLoaded', function () {
              const dateInput = document.getElementById('Date_of_payment');
              const today = new Date().toISOString().split('T')[0];
              dateInput.value = today;
            });

            function confirmSubmission() {
              const form = document.getElementById('myForm');
              const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
              const submitBtn = document.getElementById('submitBtn');
              const submitBtnText = document.getElementById('submitBtnText');
              let isValid = true;

              inputs.forEach(input => {
                input.classList.remove('is-invalid');
              });

              inputs.forEach(input => {
                if (!input.value.trim()) {
                  input.classList.add('is-invalid');
                  isValid = false;
                }
              });

              if (!isValid) {
                const firstInvalid = form.querySelector('.is-invalid');
                if (firstInvalid) {
                  firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }

                Swal.fire({
                  icon: 'error',
                  title: 'Missing Fields',
                  text: 'Please fill in all required fields before submitting.',
                });

                return;
              }

              Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to save this receipt?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!',
                cancelButtonText: 'Cancel'
              }).then((result) => {
                if (result.isConfirmed) {
                  submitBtn.disabled = true;
                  submitBtn.innerHTML = `
          Saving...<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
        `;

                  form.submit();
                }
              });
            }

          </script>

        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@include('includes.footer')