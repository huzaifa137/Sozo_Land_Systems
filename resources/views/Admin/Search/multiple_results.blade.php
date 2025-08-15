@include('includes.header')
<!-- partial -->
<div class="main-panel">
  <div class="content-wrapper">

    <div class="row">

      <div class="col-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title" style="padding-left: 1rem;color:green">Records found </h4>


            <style>
              a {
                color: white;
              }
            </style>
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


            <?php 
									
									   $userRight = DB::table('admin_registers')->where('id', Session('LoggedAdmin'))->value('admin_category');
									   
									?>

            <div class="row ">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    {{-- <h4 class="card-title">Order Status</h4> --}}
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr>
                            <th>No.</th>
                            <th> Client Name </th>
                            <th> NIN No </th>
                            <th> Estate </th>
                            <th> Plot No </th>
                            <th> Location </th>
                            <th> Amount Payed </th>
                            <th colspan="2" style="text-align:center;"> Action </th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($result as $key => $item)

                    <tr>

                    <td><a href="{{'view-reciept/' . $item->id}}">{{$key + 1}}</a></td>
                    <td>
                      <span><a href="{{'view-reciept/' . $item->id}}">{{$item->firstname}}
                        {{$item->lastname}}</a></span>
                    </td>
                    <td> <a href="{{'view-reciept/' . $item->id}}">{{$item->NIN}}</a> </td>
                    <td> <a href="{{'view-reciept/' . $item->id}}">{{$item->estate}}</a> </td>
                    <td> <a href="{{'view-reciept/' . $item->id}}">{{$item->plot_number}}</a> </td>
                    <td> <a href="{{'view-reciept/' . $item->id}}">{{$item->location}}</a> </td>
                    <td> <a href="{{'view-reciept/' . $item->id}}">{{$item->amount_payed}}</a> </td>

                    <td><a href="{{'view-reciept/' . $item->id}}" class="btn btn-outline-info btn-icon-text">
                      <i class="mdi mdi-eye btn-icon-prepend"></i> View </a> </td>

                    @if($userRight == 'SuperAdmin')

              <td><a href="{{ 'edit/' . $item->id . '/' . $LoggedAdminInfo['id'] }}"
                class="btn btn-outline-warning btn-icon-text">
                <i class="mdi mdi-eye btn-icon-prepend"></i> Edit </a>
              </td>

              <td><a href="{{ 'delete/' . $item->id . '/' . $item->plot_number . '/' . $item->estate }}"
                onclick=" return confirm('Please confirm you want to delete this record ?')"
                class="btn btn-outline-danger btn-icon-text">
                <i class="mdi mdi-eye btn-icon-prepend"></i> delete </a>
              @endif

                    </tr>
                    <tr>


            @endforeach

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

<script type="text/javascript"></script>
<script src="/assets/js/jquery.min.js"></script>

<script>
  $(document).ready(function () {
    $("#land_plot").change(function () {

      var land_plot = $(this).val();

      if (land_plot == 'House') {
        $('#land_estate_field').show();
        $('#plot_estate_field').hide();
      }
      else {
        $('#land_estate_field').hide();
        $('#plot_estate_field').show();

      }

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