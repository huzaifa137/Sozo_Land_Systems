@include('includes.header')
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">

                    <div class="row ">
                        <div class="col-12 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Add Expenditure</h4>

                                    <form action="{{ route('store-expenditure') }}" method="post">
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
                                            <div class="col-md-6">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Expenditure Name</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" name="expenditure_name" id=""
                                                            class="form-control" required>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Total Amount </label>
                                                    <div class="col-sm-9">
                                                        <input type="number" name="total_amount" id=""
                                                            class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">

                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6" id="dynamic-inputs-container">

                                            </div>
                                        </div>

                                        <br>
                                        <button class="btn btn-primary" type="button" onclick="addInputBox()">Add
                                            Expenditure Expense</button>

                                        <button class="btn btn-success" type="submit">Save Expenditure</button>

                                    </form>


                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright ©
                            SozoPropertiesLimited.com 2023</span>
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

    <script type="text/javascript"></script>
    <script src="/assets/js/jquery.min.js"></script>

    <script>
        function addInputBox() {

            var input = document.createElement('input');
            input.type = 'text';
            input.name = 'dynamic_inputs[]'; // Use an array to handle multiple dynamic inputs
            input.className = 'form-control';
            input.placeholder = 'Enter reason for exependiture with amount'

            // Create the second input box
            var input2 = document.createElement('input');
            input2.type = 'number';
            input2.name = 'dynamic_inputs2[]'; // Use an array to handle multiple dynamic inputs
            input2.className = 'form-control';
            input2.placeholder = 'Enter amount'

            // Create a new div to contain the input and a remove button
            var container = document.createElement('div');

            // Create a remove button to remove the input box
            var removeButton = document.createElement('button');
            removeButton.textContent = 'Remove';
            removeButton.type = 'button';


            // removeButton.className = 'btn btn-primary';
            removeButton.onclick = function() {
                container.remove();
            };

            // Append the input and remove button to the container
            container.appendChild(input);
            container.appendChild(document.createElement('br')); // Add a line break for separation
            container.appendChild(input2);
            container.appendChild(removeButton);

            // Append the container to the dynamic-inputs-container
            document.getElementById('dynamic-inputs-container').appendChild(container);

        }
    </script>

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
