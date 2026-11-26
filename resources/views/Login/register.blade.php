@include('includes.header')

<div class="main-panel">
    <div class="content-wrapper">

        <div class="sz-page-head">
            <div>
                <h2>Register a user</h2>
                <p>Create a new account and choose what the person is allowed to do in Sozo Land Systems.</p>
            </div>
            <div class="sz-actions">
                <a class="btn btn-secondary" href="{{ route('user-information') }}"><i class="mdi mdi-account-multiple"></i> All users</a>
            </div>
        </div>



        <section class="sz-panel" aria-label="Registration form" style="max-width: 860px;">
            <header>
                <h3>Account details</h3>
                <span>Fields marked * are required</span>
            </header>

            <form action="{{ route('admin-registration') }}" method="POST" autocomplete="off">
                @csrf

                <div class="row">
                    <div class="col-md-12 form-group">
                        <label for="username">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" id="username" class="form-control"
                            placeholder="Enter username" value="{{ old('username') }}" required>
                        <small class="text-danger">@error('username'){{ $message }}@enderror</small>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="firstname">First name <span class="text-danger">*</span></label>
                        <input type="text" name="firstname" id="firstname" class="form-control"
                            placeholder="Enter first name" value="{{ old('firstname') }}" required>
                        <small class="text-danger">@error('firstname'){{ $message }}@enderror</small>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="lastname">Last name <span class="text-danger">*</span></label>
                        <input type="text" name="lastname" id="lastname" class="form-control"
                            placeholder="Enter last name" value="{{ old('lastname') }}" required>
                        <small class="text-danger">@error('lastname'){{ $message }}@enderror</small>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control"
                            placeholder="name@example.com" value="{{ old('email') }}" required>
                        <small class="text-danger">@error('email'){{ $message }}@enderror</small>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="admin_category">Role <span class="text-danger">*</span></label>
                        <select class="form-control" name="admin_category" id="admin_category" required>
                            <option value="">Select a role</option>
                            <option value="SuperAdmin" {{ old('admin_category') == 'SuperAdmin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="Admin" {{ old('admin_category') == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="Sales" {{ old('admin_category') == 'Sales' ? 'selected' : '' }}>Sales</option>
                        </select>
                        <small class="text-danger">@error('admin_category'){{ $message }}@enderror</small>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="password">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Enter password" autocomplete="new-password" required>
                        <small class="text-danger">@error('password'){{ $message }}@enderror</small>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="confirm_password">Confirm password <span class="text-danger">*</span></label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control"
                            placeholder="Repeat the password" autocomplete="new-password" required>
                        <small class="text-danger">@error('confirm_password'){{ $message }}@enderror</small>
                    </div>
                </div>

                <div class="d-flex justify-content-end flex-wrap mt-3" style="gap:.75rem;">
                    <a href="{{ url('admin-dashboard') }}" class="btn btn-secondary">Back to dashboard</a>
                    <button type="submit" class="btn btn-primary"><i class="mdi mdi-account-plus"></i> Register user</button>
                </div>
            </form>
        </section>

    </div>
    <!-- content-wrapper ends -->
    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="d-block text-center text-sm-left d-sm-inline-block">&copy; {{ date('Y') }} Sozo Property Consultants Limited</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Sozo Land Systems</span>
        </div>
    </footer>
</div>
<!-- main-panel ends -->
</div>
<!-- page-body-wrapper ends -->
</div>
<!-- container-scroller ends -->
<script src="/assets/vendors/js/vendor.bundle.base.js"></script>
</body>

</html>
