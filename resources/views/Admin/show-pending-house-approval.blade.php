@include('includes.header')
<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">

        <style>
            a {
                color: aliceblue;
            }
        </style>

        <?php
        
        use App\Models\AdminRegister;
        
        $user_id = Session('LoggedAdmin');
        $User_access_right = AdminRegister::where('id', '=', $user_id)->value('admin_category');
        
        ?>

        <div class="row ">
            <div class="col-12 grid-margin">
                @if (count($pendingApprovals) > 0)
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Pending Houses Approval Sales </h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th> Price </th>
                                            <th> Location </th>
                                            <th> Land Tenure</th>
                                            <th> Bedrooms </th>
                                            <th> Purchase Procedure </th>
                                            @if ($User_access_right == 'SuperAdmin')
                                                <th>Action</th>
                                            @else
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pendingApprovals as $key => $all_sale)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>

                                                <td><a href="{{ 'pending-house-information/' . $all_sale->id }}">
                                                        {{ $all_sale->price }}
                                                    </a></td>
                                                <td><a href="{{ 'pending-house-information/' . $all_sale->id }}">
                                                        {{ $all_sale->location }} </a></td>
                                                <td><a href="{{ 'pending-house-information/' . $all_sale->id }}">
                                                        {{ $all_sale->LandTenure }} </a></td>
                                                <td><a href="{{ 'pending-house-information/' . $all_sale->id }}">
                                                        {{ $all_sale->bedroom }} </a></td>
                                                <td><a href="{{ 'pending-house-information/' . $all_sale->id }}">
                                                        {{ $all_sale->purchase_procedure }} </a></td>

                                                @if ($User_access_right == 'SuperAdmin')
                                                    <td><a href="{{ 'pending-house-information/' . $all_sale->id }}"
                                                            class="btn btn-outline-success btn-icon-text">
                                                            <i class="mdi mdi-eye btn-icon-prepend"></i> View </a>
                                                    </td>
                                                @else
                                                @endif
                                        @endforeach

                                        </tr>
                                    </tbody>
                                </table>

                                <br> <br>
                                <span>
                                    {{ $pendingApprovals->links() }}
                                </span>

                                <style>
                                    .w-5 {
                                        display: none;
                                    }
                                </style>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-danger d-flex align-items-center" role="alert" style="font-size: 16px;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong><span style="color: black;">No Pending Sale Approvals</span></strong>
                    </div>
                @endif

            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script src="script.js"></script>
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
