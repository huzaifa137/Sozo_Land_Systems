@include('includes.header')
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            
            <div class="row">
            
              <div class="col-12 grid-margin">
                <div class="card">
                    <?php
                    $userInformation = DB::table('admin_registers')->where('id',Session('LoggedAdmin'))->first();
                    ?>
                    
                    @if ($userInformation->admin_category == 'SuperAdmin')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Header with Stats - Responsive -->
                @foreach ($user_information as $data)
                <div class="row mb-3 mb-md-4">
                    <div class="col-12">
                        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between flex-wrap gap-3">
                            <div>
                                <h3 class="text-primary mb-1 fs-4 fs-md-3">{{$data->firstname}} {{$data->lastname}}</h3>
                                <p class="text-muted small mb-0">Customer ID: #{{$data->id ?? 'N/A'}}</p>
                            </div>
                            <div class="d-flex flex-wrap gap-2 w-100 w-md-auto">
                                <span class="badge bg-primary p-2 p-md-3 flex-fill flex-md-grow-0">Plot: {{$data->plot_number}}</span>
                                <span class="badge bg-info p-2 p-md-3 flex-fill flex-md-grow-0">Estate: {{$data->estate}}</span>
                                @php
                                    $status = $data->next_installment_pay;
                                    $badgeClass = $status == "Fully payed" ? "bg-success" : ($status == "Resold" ? "bg-warning" : "bg-info");
                                @endphp
                                <span class="badge {{$badgeClass}} p-2 p-md-3 flex-fill flex-md-grow-0">{{$status == "Fully payed" ? $status : ($status == "Resold" ? $status : "Under payment")}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Tabs Navigation - Responsive (Scrollable on mobile) -->
                <div class="overflow-auto pb-2 mb-3" style="scrollbar-width: thin;">
                    <ul class="nav nav-tabs nav-tabs-custom flex-nowrap flex-md-wrap" id="customerTabs" role="tablist" style="min-width: max-content;">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active px-3 px-md-4" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">
                                <i class="mdi mdi-account me-1"></i> 
                                <span class="d-none d-sm-inline">Information</span>
                                <span class="d-inline d-sm-none">Info</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-3 px-md-4" id="property-tab" data-bs-toggle="tab" data-bs-target="#property" type="button" role="tab">
                                <i class="mdi mdi-home me-1"></i> 
                                <span class="d-none d-sm-inline">Property</span>
                                <span class="d-inline d-sm-none">Prop</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-3 px-md-4" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab">
                                <i class="mdi mdi-cash me-1"></i> 
                                <span class="d-none d-sm-inline">Payment</span>
                                <span class="d-inline d-sm-none">Pay</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link px-3 px-md-4" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">
                                <i class="mdi mdi-file-document me-1"></i> 
                                <span class="d-none d-sm-inline">Documents</span>
                                <span class="d-inline d-sm-none">Docs</span>
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Tab Content - Fully Responsive -->
                <div class="tab-content" id="customerTabsContent">
                    @foreach ($user_information as $data)
                    <!-- Personal Info Tab -->
                    <div class="tab-pane fade show active" id="info" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-12 col-lg-6">
                                <div class="card border h-100">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3 mb-md-4 d-flex align-items-center">
                                            <i class="mdi mdi-account-circle text-primary me-2"></i>
                                            Personal Information
                                        </h5>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-borderless mb-0">
                                                <tr>
                                                    <td class="text-muted pe-3" style="width: 40%;">Full Name</td>
                                                    <td class="fw-medium">{{$data->firstname}} {{$data->lastname}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Gender</td>
                                                    <td class="fw-medium">{{$data->gender}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Date of Birth</td>
                                                    <td class="fw-medium">{{$data->date_of_birth}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">NIN</td>
                                                    <td class="fw-medium">{{$data->NIN}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Phone Number</td>
                                                    <td class="fw-medium">
                                                        <a href="tel:{{$data->phonenumber}}" class="text-decoration-none">
                                                            {{$data->phonenumber}}
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted">Added By</td>
                                                    <td class="fw-medium">{{$data->added_by}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="card border h-100">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3 mb-md-4 d-flex align-items-center">
                                            <i class="mdi mdi-lightning-bolt text-warning me-2"></i>
                                            Quick Actions
                                        </h5>
                                        <div class="d-grid gap-2">
                                            <a href="tel:{{$data->phonenumber}}" class="btn btn-outline-primary text-start d-flex align-items-center">
                                                <i class="mdi mdi-phone me-2"></i> 
                                                <span class="flex-grow-1">Call Customer</span>
                                                <small class="text-muted">{{$data->phonenumber}}</small>
                                            </a>
                                            <button class="btn btn-outline-success text-start d-flex align-items-center" onclick="window.location.href='mailto:{{$data->email ?? ''}}'">
                                                <i class="mdi mdi-email me-2"></i> 
                                                <span class="flex-grow-1">Send Email</span>
                                            </button>
                                            <button class="btn btn-outline-info text-start d-flex align-items-center">
                                                <i class="mdi mdi-message me-2"></i> 
                                                <span class="flex-grow-1">Send SMS</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Property Info Tab -->
                    <div class="tab-pane fade" id="property" role="tabpanel">
                        <div class="card border">
                            <div class="card-body">
                                <h5 class="card-title mb-3 mb-md-4 d-flex align-items-center">
                                    <i class="mdi mdi-home-city text-primary me-2"></i>
                                    Property Information
                                </h5>
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <div class="bg-light p-3 rounded">
                                            <small class="text-muted text-uppercase fw-bold d-block mb-2">Location Details</small>
                                            <div class="mb-2">
                                                <span class="text-muted">Estate:</span>
                                                <span class="fw-medium ms-2 text-muted">{{$data->estate}}</span>
                                            </div>
                                            <div class="mb-2">
                                                <span class="text-muted">Plot Number:</span>
                                                <span class="fw-medium ms-2 text-muted">{{$data->plot_number}}</span>
                                            </div>
                                            <div>
                                                <span class="text-muted">Purchase Type:</span>
                                                <span class="fw-medium ms-2 text-muted">{{$data->purchase_type}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="bg-light p-3 rounded">
                                            <small class="text-muted text-uppercase fw-bold d-block mb-2">Dimensions</small>
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <div class="text-center p-2 bg-white rounded">
                                                        <span class="text-muted d-block small">Width 1</span>
                                                        <span class="fw-bold text-muted">{{$data->width_1}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-center p-2 bg-white rounded">
                                                        <span class="text-muted d-block small">Width 2</span>
                                                        <span class="fw-bold text-muted">{{$data->width_2}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-center p-2 bg-white rounded">
                                                        <span class="text-muted d-block small">Height 1</span>
                                                        <span class="fw-bold text-muted">{{$data->height_1}}</span>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-center p-2 bg-white rounded">
                                                        <span class="text-muted d-block small">Height 2</span>
                                                        <span class="fw-bold text-muted">{{$data->height_2}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Info Tab -->
                    <div class="tab-pane fade" id="payment" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="card border">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3 mb-md-4 d-flex align-items-center">
                                            <i class="mdi mdi-cash-multiple text-success me-2"></i>
                                            Payment Summary
                                        </h5>
                                        <div class="bg-light p-3 rounded mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                                <span class="text-muted">Amount Paid</span>
                                                <span class="text-success fw-bold fs-5">${{number_format($data->amount_payed, 2)}}</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                                <span class="text-muted">Balance</span>
                                                <span class="text-danger fw-bold fs-5">${{number_format($data->balance, 2)}}</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-muted fw-bold">Total</span>
                                                <span class="fw-bold fs-5 text-muted">${{number_format($data->amount_payed + $data->balance, 2)}}</span>
                                            </div>
                                        </div>
                                        <div class="progress" style="height: 10px;">
                                            @php
                                                $total = $data->amount_payed + $data->balance;
                                                $paidPercentage = $total > 0 ? ($data->amount_payed / $total) * 100 : 0;
                                            @endphp
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{$paidPercentage}}%" 
                                                aria-valuenow="{{$paidPercentage}}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <small class="text-muted d-block text-center mt-2">{{number_format($paidPercentage, 1)}}% Paid</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Documents Tab - Fully Responsive -->
                    <div class="tab-pane fade" id="documents" role="tabpanel">
                        <div class="row g-3">
                         
                            <!-- Uploaded Agreements -->
                            @if($data->next_installment_pay === 'Fully payed')
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3 d-flex align-items-center">
                                            <i class="mdi mdi-cloud-download text-warning me-2"></i>
                                           Download Agreement
                                            <span class="badge bg-warning ms-2">1</span>
                                        </h5>
                                        <div class="d-grid gap-2">
                                                <a href="#" 
                                                  target="_blank" 
                                                  download
                                                  class="btn btn-outline-primary btn-lg d-flex align-items-center justify-content-between p-3 shadow-sm border-2">
                                                    
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-file-earmark-arrow-down-fill me-3 fs-4"></i>
                                                        <div class="text-start">
                                                            <span class="d-block fw-bold mb-1">Download Agreement</span>
                                                            <small class="text-white">{{$data->firstname}} {{$data->lastname}}</small>
                                                        </div>
                                                    </div>

                                                    <i class="bi bi-chevron-right opacity-50"></i>
                                                </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- No Documents Message -->
                             @else  
                            <div class="col-12">
                                  <div class="text-center py-5">
                                      <i class="mdi mdi-file-document-outline text-muted" style="font-size: 48px;"></i>
                                      <h5 class="mt-3">No Documents Available</h5>
                                      <p class="text-muted">There are no documents associated with this customer.</p>
                                  </div>
                              </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add this CSS to handle responsive images and touch interactions -->
<style>
@media (max-width: 576px) {
    .nav-tabs .nav-link {
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .badge {
        font-size: 0.75rem;
    }
    
    /* Improve touch targets on mobile */
    .btn, .nav-link {
        min-height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Better scrolling for document images */
    .row.g-2 {
        margin-right: -0.25rem;
        margin-left: -0.25rem;
    }
    
    .row.g-2 > [class*="col-"] {
        padding-right: 0.25rem;
        padding-left: 0.25rem;
    }
}

/* Smooth transitions */
.tab-pane {
    transition: opacity 0.15s linear;
}

/* Document image hover effect */
.card-img-top {
    transition: transform 0.2s ease;
}

.card-img-top:hover {
    transform: scale(1.05);
}

/* Custom scrollbar for tabs on mobile */
.overflow-auto::-webkit-scrollbar {
    height: 3px;
}

.overflow-auto::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 10px;
}
</style>
                    
                    @else
                     <div class="row">
                        <div class="col-12">
                          <div class="card">
                            <div class="card-body">
                              <h4 class="card-title text-primary">Customer Information</h4>
                              @foreach ($user_information as $data)
                              <div class="row">
                                <div class="col-md-5">
                                  <div class="table-responsive">
                                    <table class="table">
                                      <tbody>
                                      
                                        <tr>
                                        
                                          <td class="text-info">Name</td>
                                          <td class="text-right font-weight-medium"> {{$data->firstname}} {{$data->lastname}}  </td>
                                        </tr>
                                        <tr>
                                         
                                          <td class="text-info">Gender</td>
                                          <td class="text-right font-weight-medium"> {{$data->gender}} </td>
                                        </tr>
                                        <tr>
                                         
                                          <td class="text-info">Date of birth</td>
                                          <td class="text-right font-weight-medium">{{$data->date_of_birth}} </td>
                                        </tr>

                                        <tr>
                                          <td class="text-info">Estate</td>
                                          <td class="text-right font-weight-medium"> {{$data->estate}} </td>
                                        </tr>

                                        <tr>
                                          <td class="text-info">Plot No.</td>
                                          <td class="text-right font-weight-medium"> {{$data->plot_number}} </td>
                                        </tr>

                                        <tr>
                                          <td class="text-info">Width 1</td>
                                          <td class="text-right font-weight-medium"> {{$data->width_1}} </td>
                                        </tr>


                                        <tr>
                                          <td class="text-info">Width 2</td>
                                          <td class="text-right font-weight-medium"> {{$data->width_2}} </td>
                                        </tr>


                                        <tr>
                                          <td class="text-info">Height 1</td>
                                          <td class="text-right font-weight-medium"> {{$data->height_1}} </td>
                                        </tr>

                                        <tr>
                                          <td class="text-info">Height 2</td>
                                          <td class="text-right font-weight-medium"> {{$data->height_2}} </td>
                                        </tr>

                                        <tr>
                                          <td class="text-info">purchasing </td>
                                          <td class="text-right font-weight-medium"> {{$data->purchase_type}} </td>
                                        </tr>

                                        <tr>
                                          <td class="text-info">Amount paid</td>
                                          <td class="text-right font-weight-medium"> {{$data->amount_payed}} </td>
                                        </tr>

                                        <tr>
                                          <td class="text-info">Balance</td>
                                          <td class="text-right font-weight-medium"> {{$data->balance}} </td>
                                        </tr>

                                        <tr> 
                                          <td class="text-info">Status</td>
                                          @if ($data->next_installment_pay == "Fully payed")
                                          <td class="text-right font-weight-medium">{{$data->next_installment_pay}} </td>
                                          @elseif ($data->next_installment_pay == "Resold")
                                          <td class="text-right font-weight-medium">{{$data->next_installment_pay}}</td>
                                          @else
                                          <td class="text-right font-weight-medium">Under payment</td>
                                          @endif
                                        </tr>

                                          @if ($userInformation->email == "sozoproperties531@gmail.com")
                                         <td class="text-info">Phone number</td>
                                         <td class="text-right font-weight-medium"> {{$data->phonenumber}} </td>
                                          @endif
                                          
                                        <tr>
                                          <td class="text-info">Added by :</td>
                                          <td class="text-right font-weight-medium"> {{$data->added_by}} </td>
                                        </tr>
                                        
                                      </tbody>
                                    </table>
                                  </div>
                                </div>

                                <div class="col-md-7">
                                <!--  <div id="audience-map" class="vector-map">-->
                                <!--    <img style="width: 100%; height:100%" src="{{'/public/public/national_id/'.$data->national_id_front}}" alt="">-->
                                <!--  </div>-->

                                <!--  <div id="audience-map" class="vector-map">-->
                                <!--    <img style="width: 100%; height:100%" src="{{'/public/public/national_id/'.$data->national_id_back}}" alt="">-->
                                <!--  </div>-->
                                <!--  @endforeach-->
                                <!--</div>-->
                              </div>

                              <div class="row">
                                <div class="col-md-5">
                                  <div class="table-responsive">
                                    <table class="table">
                                      <tbody>


                                       
                                      </tbody>
                                      
                                    </table>
                                  </div>
                                </div>


                              </div>
                            </div>
                            
                          </div>
                        </div>
                      </div>

                    @endif
                
                    
                  </div>
              </div>
            </div>
          </div>



           <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © SozoPropertiesLimited.com 2023</span>
              <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">E-commerce Coming soon <a href="javascript:void(0);" target="_blank">Sozo Properties</a></span>
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

  <script>

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