@include('includes.header')
<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">

            </div>
        </div>


        <?php  
                
                use App\Models\AdminRegister;

$user_id = Session('LoggedAdmin');
$userInfo = AdminRegister::where('id', '=', $user_id)->value('admin_category');
                                    
              ?>

        <div class="row ">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Available plots in {{$estate_name}} estate </h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Estate</th>
                                        <th> Plot No</th>
                                        <th> Plot Price</th>
                                        <th> Exceptional Amount</th>
                                        <th style="text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($estate_data as $key => $data)

                                        <tr>
                                            <td>{{$key + 1}}</td>
                                            <td> {{ $data->estate }} </td>
                                            <td> {{ $data->plot_number }} </td>


                                            @if($data->exceptional_amount > 0)
                                                <td>0</td>
                                                <td> {{ $data->exceptional_amount}}</td>
                                            @else()
                                                <td>{{ $estate_price}}</td>
                                                <td> {{ $data->exceptional_amount}}</td>
                                            @endif

                                            <td>
                                                <a href="javascript:void(0);" class="btn btn-success transfer-client-btn"
                                                    data-id="{{ $data->id }}" data-estate="{{ $data->estate }}"
                                                    data-plot="{{ $data->plot_number }}">
                                                    <i class="mdi mdi-map-marker-check"></i> Transfer Client
                                                </a>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- jQuery (required for AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.transfer-client-btn').click(function (e) {
                e.preventDefault();

                const clientId = $(this).data('id');
                const estate = $(this).data('estate');
                const plotNumber = $(this).data('plot');

                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to transfer the client for Plot ${plotNumber} in ${estate} estate.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, transfer',
                    background: '#800080',
                    color: '#fff',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/transfer-client',
                            type: 'POST',
                            data: {
                                id: clientId,
                                estate: estate,
                                plot_number: plotNumber,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Client has been transferred.',
                                    icon: 'success',
                                    background: '#800080',
                                    color: '#fff',
                                    confirmButtonColor: '#28a745'
                                }).then(() => {
                                    // Redirect after clicking "OK"
                                    window.location.href = response.redirect_url;
                                });
                            },
                            error: function (data) {
                                $('body').html(data.responseText);
                            }
                        });
                    }
                });
            });
        });
    </script>


    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->
    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright ©
                SozoPropertiesLimited.com 2024</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">E-commerce Coming soon
                <a href="javascript:void(0);" target="_blank">Sozo Properties</a></span>
        </div>
    </footer>
    <!-- partial -->
</div>
<!-- main-panel ends -->
</div>
<!-- page-body-wrapper ends -->
</div>
<!-- container-scroller -->
<!-- plugins:js -->
<script src="/assets/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="/assets/vendors/chart.js/Chart.min.js"></script>
<script src="/assets/vendors/progressbar.js/progressbar.min.js"></script>
<script src="/assets/vendors/jvectormap/jquery-jvectormap.min.js"></script>
<script src="/assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="/assets/vendors/owl-carousel-2/owl.carousel.min.js"></script>
<script src="/assets/js/jquery.cookie.js" type="text/javascript"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="/assets/js/off-canvas.js"></script>
<script src="/assets/js/hoverable-collapse.js"></script>
<script src="/assets/js/misc.js"></script>
<script src="/assets/js/settings.js"></script>
<script src="/assets/js/todolist.js"></script>
<!-- endinject -->
<!-- Custom js for this page -->
<script src="/assets/js/dashboard.js"></script>
<!-- End custom js for this page -->
</body>

</html>