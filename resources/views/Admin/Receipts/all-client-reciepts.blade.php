@include('includes.header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="main-panel">
    <div class="content-wrapper">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        <div class="row">
            <div class="col-12 ">
                <div class="card">
                    <div class="card-body">

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

                        <hr>
                        <h4 class="">{{ $userInformation->firstname }} {{ $userInformation->lastname }} Reciepts </h4>
                        <hr>

                        <div class="card">
                            <div class="row align-items-center mb-4">
                                <div class="col-md-2 d-flex justify-content-center align-items-center">
                                    <div class="profile-pic-wrapper mt-3">
                                        @if($userInformation->profile_pic)
                                            <img src="{{ asset('profile_pic/' . $userInformation->profile_pic) }}"
                                                alt="Profile Picture"
                                                class="profile-pic border border-secondary p-1 rounded-circle"
                                                style="width: 120px; height: 120px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('path/to/default/profile_pic.png') }}"
                                                alt="Default Profile Picture"
                                                class="profile-pic border border-secondary p-1 rounded-circle"
                                                style="width: 120px; height: 120px; object-fit: cover;">
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong><span style="color:#0090E7;">Name:</span></strong>
                                                {{ $userInformation->firstname }} {{ $userInformation->lastname }}</p>
                                            <p class="mb-1"><strong><span style="color:#0090E7;">Estate:</span></strong>
                                                {{ $userInformation->estate }}</p>
                                            <p class="mb-1"><strong><span style="color:#0090E7;">Plot
                                                        Number:</span></strong> {{ $userInformation->plot_number }}</p>
                                            <p class="mb-1"><strong><span style="color:#0090E7;">Gender:</span></strong>
                                                {{ $userInformation->gender }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong><span style="color:#0090E7;">Date of
                                                        Birth:</span></strong>
                                                {{ \Carbon\Carbon::parse($userInformation->date_of_birth)->format('d M Y') }}
                                            </p>
                                            <p class="mb-1"><strong><span style="color:#0090E7;">Phone
                                                        Number:</span></strong> {{ $userInformation->phonenumber }}</p>
                                            <p class="mb-1"><strong><span
                                                        style="color:#0090E7;">Location:</span></strong>
                                                {{ $userInformation->location }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="card-body">
                                @if($receipts->count())
                                    <div class="table-responsive">
                                        <table class="table table-bordered  align-middle">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Amount Paid</th>
                                                    <th>Balance</th>
                                                    <th>Date of Payment</th>
                                                    <th>Phone Number</th>
                                                    <th>Download</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($receipts as $index => $receipt)
                                                    <tr style="color:#FFF;">
                                                        <td style="width: 1px;">{{ $index + 1 }}</td>
                                                        <td>{{ $receipt->Amount }}</td>
                                                        <td>{{ $receipt->Balance }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($receipt->Date_of_payment)->format('d M Y') }}
                                                        </td>
                                                        <td>{{ $receipt->Phonenumber }}</td>
                                                        <td>
                                                            <a href="{{ asset('storage/pdf_receipts/' . $receipt->reciept) }}"
                                                                target="_blank" class="btn btn-outline-primary btn-md">
                                                                <i class="fas fa-file-pdf"></i>
                                                                <span>Download Receipt {{ $index + 1 }}</span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-center text-muted">No receipts found for this client.</p>
                                @endif
                            </div>

                            <div class="card-body">
                                @if($oldReceipts->count())
                                    <div class="table-responsive">
                                        <h3 class="text-center text-primary">Older Reciepts</h3>
                                        <table class="table table-bordered  align-middle">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Amount Paid</th>
                                                    <th>Balance</th>
                                                    <th>Phone Number</th>
                                                    <th>Download</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($oldReceipts as $index => $receipt)
                                                    <tr style="color:#FFF;">
                                                        <td style="width: 1px;">{{ $index + 1 }}</td>
                                                        <td>{{ $receipt->amount_paid }}</td>
                                                        <td>{{ $receipt->balance }}</td>
                                                        </td>
                                                        <td>{{ $userInformation->phonenumber }}</td>
                                                        <td>
                                                            <a href="{{ asset('storage/receipts/' . $receipt->file_path) }}"
                                                                target="_blank" class="btn btn-outline-primary btn-md">
                                                                <i class="fas fa-file-download"></i>
                                                                <span>Download Receipt {{ $index + 1 }}</span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-center text-muted">No receipts found for this client.</p>
                                @endif
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


@include('includes.footer')