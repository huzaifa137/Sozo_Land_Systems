@include('includes.header')
<!-- partial -->
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-12 grid-margin stretch-card">

      </div>
    </div>

    <div class="row">
      <div class="col-xl-6 col-sm-6 grid-margin stretch-card">
        <a href="{{ route('set-reminder') }}" style="text-decoration: none; width: 100%;">
          <div class="card h-100">
            <div class="card-body">
              <div class="row">
                <div class="col-9">
                  <div class="d-flex align-items-center align-self-start">
                    <h3 class="mb-0"></h3>
                  </div>
                </div>
                <div class="col-3">
                  <div class="icon icon-box-success">
                    <span class="mdi mdi-alarm icon-item"></span>
                  </div>
                </div>
              </div>
              <h6 class="text-white font-weight-normal">Set Reminders</h6>
            </div>
          </div>
        </a>
      </div>

      <div class="col-xl-6 col-sm-6 grid-margin stretch-card">
        <a href="{{ route('payment-reminder') }}" style="text-decoration: none; width: 100%;">
          <div class="card h-100">
            <div class="card-body">
              <div class="row">
                <div class="col-9">
                  <div class="d-flex align-items-center align-self-start">
                    <h3 class="mb-0">{{ $records_count }}</h3>
                  </div>
                </div>
                <div class="col-3">
                  <div class="icon icon-box-success">
                    <span class="mdi mdi-alarm icon-item"></span>
                  </div>
                </div>
              </div>
              <h6 class="text-white font-weight-normal">Today's Scheduled Client Reminders</h6>
            </div>
          </div>
        </a>
      </div>
    </div>

    <div class="row ">
      <div class="col-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Setup Client Reminder </h4>

            <form action="{{ route('search-clients-to-setup-reminders') }}" method="POST">
              @csrf

              @if (Session::get('success'))
                <div class="alert alert-success">
                {{ Session::get('success') }}
                </div>
              @endif

              @if (Session::get('error'))
                <div class="alert alert-danger">
                {{ Session::get('error') }}
                </div>
              @endif

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group row">
                    <p class="card-description">Choose the search
                      option :</p>
                    <div class="col-sm-12">
                      <div class="col-sm-12">
                        <select name="land_plot" id="land_plot" class="form-control" required>
                          <option value="">---choose---</option>
                          <option value="Name">Name</option>
                          <option value="Plot">Plot</option>
                          <option value="date_sold">Date Plot Sold</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-4" id="plot_estate_field" style="display: none;">
                  <div class="form-group row">
                    <label class="col-sm-6 col-form-label">Estate</label>
                    <div class="col-sm-12">
                      <div class="col-sm-12">
                        <select name="estate" id="estate" class="form-control">
                          <option value="">---choose estate---</option>
                          @foreach ($estates as $record)
                            <option value="{{ $record->estate_name }}">
                            {{ $record->estate_name }}
                            </option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-4" id="land_estate_field" style="display: none;">
                  <div class="form-group row">
                    <label class="col-sm-6 col-form-label">Land Plot</label>
                    <div class="col-sm-12">
                      <div class="col-sm-12">
                        <input type="text" name="land_plot" id="land_plot" class="form-control"
                          placeholder="Enter plot no">
                      </div>
                    </div>
                  </div>
                </div>


                <div class="col-md-4" id="first_name" style="display: none;">
                  <div class="form-group row">
                    <label class="col-sm-6 col-form-label">First name</label>
                    <div class="col-sm-12">
                      <div class="col-sm-12">
                        <input type="text" name="firstname" id="" class="form-control" placeholder="Enter Firstname">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-4" id="last_name" style="display: none;">
                  <div class="form-group row">
                    <label class="col-sm-6 col-form-label">Last name</label>
                    <div class="col-sm-12">
                      <div class="col-sm-12">
                        <input type="text" name="lastname" id="lastname" class="form-control"
                          placeholder="Enter Lastname">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-4" id="date_sold" style="display: none;">
                  <div class="form-group row">
                    <label class="col-sm-8 col-form-label">Date Plot Sold</label>
                    <div class="col-sm-12">
                      <div class="col-sm-12">
                        <input type="date" name="date_sold" id="date_sold" class="form-control"
                          placeholder="Enter first date">
                      </div>
                    </div>
                  </div>
                </div>


                {{-- <div class="col-md-4" id="end_date" style="display: none;">
                  <div class="form-group row">
                    <label class="col-sm-6 col-form-label">End date</label>
                    <div class="col-sm-12">
                      <div class="col-sm-12">
                        <input type="date" name="end_date" id="end_date" class="form-control"
                          placeholder="Enter last date">
                      </div>
                    </div>
                  </div>
                </div> --}}

              </div>

              <div class="col-md-1" id="plot_estate_field">
                <div class="form-group row">
                  <button type="submit" class="btn btn-primary" style="margin-left:1rem;">Search</button>
                </div>
              </div>


            </form>
          </div>

        </div>
      </div>
    </div>

  </div>

  <script type="text/javascript"></script>
  <script src="/assets/js/jquery.min.js"></script>

  <script>
    $(document).ready(function () {

      $("#land_plot").change(function () {

        var search_name = $(this).val();

        if (search_name == 'Name') {
          $('#first_name').show();
          $('#last_name').show();
          $('#plot_estate_field').hide();
          $('#land_estate_field').hide();
          $('#date_sold').hide();
          $('#end_date').hide();

        }
        else if (search_name == 'Plot') {
          $('#first_name').hide();
          $('#last_name').hide();
          $('#plot_estate_field').show();
          $('#land_estate_field').show();
          $('#date_sold').hide();
          $('#end_date').hide();
        }
        else if (search_name == 'date_sold') {
          $('#first_name').hide();
          $('#last_name').hide();
          $('#plot_estate_field').hide();
          $('#land_estate_field').hide();
          $('#date_sold').show();
          $('#end_date').show();
        }
      });
    });
  </script>

  <!-- content-wrapper ends -->
  <!-- partial:partials/_footer.html -->
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