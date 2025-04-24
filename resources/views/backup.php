<script type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<button type="button" class="btn btn-lg btn-primary btn-block px-4" onclick="confirmSubmission(this)">
    <i class="fe fe-check"></i> Send
</button>


<script type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script>

<script>
    function confirmSubmission(button) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to proceed with the submission?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                button.disabled = true;
                button.innerHTML = '<i class="fe fe-loader"></i> Sending...'; // Optionally change button text
                // Submit the form
                document.querySelector("form").submit(); // Adjust to target your form
            }
        });
    }
</script>



// SESSEIONS

@if (Session::get('success'))
<div class="alert alert-success">
    {{ Session::get('success') }}
</div>
@endif

@if (Session::get('fail'))
<div class="alert alert-danger">
    {{ Session::get('fail') }}
</div>
@endif


error: function(data) {
$('body').html(data.responseText);
}


<!-- Confirm on Form submission -->

<button class="btn btn-primary"><i class="fa fa-fw fa-save"></i> Save</button>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    @if (session('success'))
        Swal.fire({
            title: 'Success!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    @endif
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('myForm').addEventListener('submit', function (event) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, submit it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
</script>


<!-- DELETE BUTTONS SWAL IMPLEMENTATION -->


<a href="{{ url('delete-record/' . $item->md_id) }}" class="btn btn-sm btn-danger delete-record-btn">
    Delete
</a>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    @if (session('success'))
        Swal.fire({
            title: 'Success!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    @endif
</script>

<script>

document.addEventListener('DOMContentLoaded', function() {
       document.querySelectorAll('.delete-record-btn').forEach(function(btn) {
           btn.addEventListener('click', function(event) {
               event.preventDefault();

               Swal.fire({
                   title: 'Are you sure?',
                   text: 'Please confirm before you delete this record!',
                   icon: 'warning',
                   showCancelButton: true,
                   confirmButtonColor: '#3085d6',
                   cancelButtonColor: '#d33',
                   confirmButtonText: 'Yes, delete it!'
               }).then((result) => {
                   if (result.isConfirmed) {
                       window.location.href = this.href;
                   }
               });
           });
       });
   });

</script>


<!-- EDIT BUTTON INFORMATION -->


<a href="{{ url('master-data/edit-record/' . $item->md_id) }}" class="btn btn-sm btn-primary edit-record-btn"
    data-url="{{ url('master-data/edit-record/' . $item->md_id) }}">
    Edit
</a>

<script>
    // Select all edit buttons

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.edit-record-btn').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default anchor behavior

                let url = this.getAttribute('data-url'); // Get URL from data attribute

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to edit this record!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, edit it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url; // Redirect to URL if confirmed
                    }
                });
            });
        });
    });


document.getElementById('register-user-btn').addEventListener('click', function(event) {
            event.preventDefault();

            var button = this;

            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to register a new user?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, register!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {

                    button.disabled = true;
                    button.innerHTML = 'Creating user account...<i class="fas fa-spinner fa-spin"></i>';
                    document.querySelector("form").submit();
                }
            });
        });

</script>


@if ($procurement->isEmpty())
<div class="col-sm-12 col-md-12">
                <div class="alert alert-warning" role="alert">
                    No prequalification periods found
                </div>
            </div>
                @else
                 @endif


                 @if (empty($time_data))

                 @else
                 @endif

                 <!-- selecting master data individual meaning  -->

                 <p>{{ Controller::rgf('master_datas', $user_profile_data->year, 'md_id', 'md_name') }}

                 <!-- selecting master data drop down meaning  -->
                 <p>{{ Controller::rgf('master_datas', $user_profile_data->year, 'md_id', 'md_name') }}

                 <td><?php
                     echo Controller::DropMasterData(config('constants.options.PROCUREMENT_CATEGORY'), $procurement_record->category_of_procurement, 'category_of_procurement', 2);
                     ?></td>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<br>
<script type="text/javascript"></script>

<script>
        function handleSubmit(form) {
            const uploadButton = document.getElementById('uploadButton');
            const loader = document.getElementById('loader');

            uploadButton.disabled = true;
            loader.style.display = 'inline-block';

            uploadButton.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Uploading...';

            return true;
        }
    </script>


.border {
                        border: 1px solid #ddd;
                        border-radius: 8px;
                        padding: 20px;
                        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.25);
                        background-color: #ffffff;
                    }

<!-- php artisan schedule:work -->

echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">';



<?php

    $ActiveUser = $procurement_officer = config('constants.options.HeadOfProcurementUserId');
    $userRoleId = DB::table('admins')->where('id', Session('LoggedAdmin'))->value('user_id');

?>


<div class="alert alert-warning" role="alert">
                    Head of Procurement required to process this section
                </div>

 closing Modal bootstrap Information

<!-- NEW BOOTSTRAP IMPLEMENTATION CLOSING MODAL -->

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>


$finanical_year  = Controller::rgf('master_datas', $year, 'md_id', 'md_code');
        $Record_Approval = procurement_approval::first();

        if ($Record_Approval) {
            $procurement = DB::table('procurements')->join('master_datas', 'procurements.currency', '=', 'master_datas.md_id')->select('procurements.*', 'master_datas.created_at as master_created_at', 'master_datas.md_name')->where('procurements.year', $year)->where('procurements.is_item_planned', 0)->paginate(30);

            $data = ['LoggedUserAdmin' => Admin::where('id', '=', session('LoggedAdmin'))->first()];

            return view('procurement-plan.ProcurementRecords', $data, compact(['procurement', 'year']))->with('finanical_year', $finanical_year);
        } else {
            $procurement = DB::table('procurements')->join('master_datas', 'procurements.currency', '=', 'master_datas.md_id')->select('procurements.*', 'master_datas.created_at as master_created_at', 'master_datas.md_name')->where('procurements.year', $year)->where('procurements.is_item_planned', 0)->paginate(30);

            $data = ['LoggedUserAdmin' => Admin::where('id', '=', session('LoggedAdmin'))->first()];

            return view('procurement-plan.ProcurementRecords', $data, compact(['procurement', 'year']))->with('finanical_year', $finanical_year);
        }



<!-- MODAL CLOSING -->

<!-- Replace data-dismiss="modal" with data-bs-dismiss="modal" throughout the code. -->

<!-- CKEDITOR BEING IMPELEMENTED IN THE SYSTEM -->

<section>
                                    <div class="tab-pane">
                                        <div class="col-md-12">
                                            <div class="editor-container">
                                                <textarea name="content" id="t_description"></textarea>
                                            </div>

                                            <script>
                                                ClassicEditor
                                                    .create(document.querySelector('#t_description'))
                                                    .then(editor => {
                                                        window.t_description = editor;
                                                    })
                                                    .catch(error => {
                                                        console.error('There was a problem initializing CKEditor:', error);
                                                    });
                                            </script>
                                        </div>
                                    </div>
                                </section>


                                <script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script>

           $linkToProceed = DB::table('master_datas')
            ->where('md_master_code_id', config('constants.options.APP_CONFIG'))
            ->where('md_code', operator: 'Supplier Portal')
            ->value('md_name');

'pendingRequests' => $linkToProceed .'/support-team/pending-issues',



$user_comments = DB::table('procurement_comments')->where('ppc_year', $year)->where('ppc_division', $division)->get();

$currencyIds = DB::table('procurements')->where('year', $year)->where('requisition_division', $division)->distinct()->pluck('currency');

if ($currencyIds->count() === 1) {
    $currencyIds = [$currencyIds->first()];
}

$currencies = collect($currencyIds)
    ->map(function ($currencyId) {
        return Controller::MasterRecord(config('constants.options.CURRENCY_METHOD'), $currencyId);
    })
    ->filter()
    ->toArray();

    $finalCurrency = count($currencies) === 1 ? $currencies[0] : $currencies;


    <br>Currency:
    <b>{{ is_array($finalCurrency) ? implode(', ', $finalCurrency) : $finalCurrency }}</b>



    $procurement_officer = config('constants.options.PROCUREMENT_OFFICER');


    <tr class="bg-dark text-white">
                        <td colspan="8"><strong>Contract Reference: {{ $draftRecord->fc_contract_ref }} | Contract
                                Title: {{ $draftRecord->fc_contract_title }} | Contract Subject:
                                {{ $draftRecord->fc_contract_subject }} | Start Date: {{ $draftRecord->fc_start_date }} |
                                End Date: {{ $draftRecord->fc_end_date }}</strong></td>

                                <!-- LABELS PILL-->

@keyframes pop {
    0% {
        transform: scale(1);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 0 15px rgba(255, 0, 0, 0.5);
    }

    50% {
        transform: scale(1.1);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 0 25px rgba(255, 0, 0, 0.8);
    }

    100% {
        transform: scale(1);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 0 15px rgba(255, 0, 0, 0.5);
    }
}

$('#student_username').removeClass('is-invalid').removeClass('is-valid');


                                    <!-- BADGE  -->

                                <span class="badge badge-danger position-absolute rounded-circle px-3 py-2"
                                    style="top: 10px; right: 10px; font-size: 16px; width: 40px; height: 40px; display: flex; justify-content: center; align-items: center;
                                       box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); animation: pop 1.2s infinite;">
                                    121
                                </span>


                                WORKFLOW REQUIRED COLUMNS
                        ----------------------------------------
                                aman_counter  =====> 1 // intials
                                aman_added_by
                                aman_status   =====> 1 // intials
                                aman_date_added
                                aman_type
                                aman_reference

                                WORKFLOW STATUSES AND VALUES
                        -----------------------------------------

                                aman_counter  =====> 1 //   {1,1 for initiating approva}
                                aman_status   =====> 1 //

                                aman_status   =====> 1 //   {1,2... approving the counter increases accordingly in approvals}
                                aman_counter  =====> 1,2,3.... //

                                aman_status   =====> 10 //   {10,2... status  value is 10 for fully approved approvals}
                                aman_counter  =====> 1,2,3.... //

                                WORKFLOW BLADES AND CONTROLLERS CODE REQUIRED
                        -----------------------------------------
                        1.Contrller containing Approval View for new Approvers and Removing (MasterApprovalOrder)

                        public function aaTestApprovals()
                        {

                            $data = ['LoggedUserAdmin' => Admin::where('id', '=', session('LoggedAdmin'))->first()];

                            return view('master-approval-order.aa-test-approvals', $data);
                        }

                        2.Contrller containing Logic to approve / delete / request more information (MaAideMemoireApprovalTest)
                        3.Table which contains all recent approvals (master_approvals)
                        4.Table which contains all approvals orders (master_approval_orders)


                        WORKFLOW BLADE TO CHECK OF SOMEONE IF AMONGST THE APPROVERS CODE TO BE USED
                        -----------------------------------------
                        ($list->taf_counter == 2 && in_array(Helper::user_id(), $approvers))
                        {

                        }

                            WORKFLOW APPROVAL CODE TO BE USED
                        -----------------------------------------

                    <div class="row">
                        <div class="col-md-12">
                            <?php

                                $masterApproval = new MaAideMemoireApprovalTest();

                                $accomodation = DB::table('aide_memoire_accomodation_nos')->where('id', 10033)->first();
                                $aman_type    = 'HZA';
                                // $aman_type = 'AIDE MEMOIRE';

                                $masterApproval->display([
                                    'table'         => 'aide_memoire_accomodation_nos',
                                    'status'        => 'aman_status',
                                    'counter'       => @$accomodation->aman_counter,
                                    'counter_field' => 'aman_counter',
                                    'id_field'      => 'id',

                                    'where'         => @$aman_type,
                                    'id'            => @$accomodation->id,
                                    'notify_user'   => @$accomodation->am_added_by,
                                    'reference'     => @$accomodation->aman_ref,
                                    'extra'         => [
                                        'labels' => [
                                            '1' => 'Prepared By',
                                            '2' => 'Tested By',
                                            '3' => 'Verified By',
                                        ],
                                    ],
                                ]);

                                echo '<div style="clear:both"></div>';

                            ?>
                        </div>
                    </div>

       -------------------------------------------------------------------------------------------------------------

              Full page code
              -------------------------------------------------------------------------------------------------------------

              <div class="w-40 bg-style min-h-100vh page-style">
