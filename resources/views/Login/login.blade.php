<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    {{-- <link rel="stylesheet" href="/assets/css/style.css"> --}}
    <title>..:: SOZO LAND SYSTEM LOGIN ::..</title>
</head>

<body>


    <div style="display: flex;justify-content:center;align-content:center">
        <a href="#"><img src="logo.png" alt="COMESA_logo" class="responsive"></a>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="signup-form">
                    <form action="{{ route('admin-send') }}" class="mt-5 border p-4 bg-light shadow"
                        method="POST">
                        @csrf
                        <h2 class="mb-2 text-primary" style="text-align: center">SOZO LAND SYSTEMS <br> LOGIN</h2>

                        @if(Session::get('fail'))
                            <div class="alert alert-danger">
                                {{ Session::get('fail') }}
                            </div>
                        @endif


                        @if(Session::get('success'))
                            <div class="alert alert-success">
                                {{ Session::get('success') }}
                            </div>
                        @endif

                        <div class="row">

                            <div class="mb-3 col-md-12">
                                <label>Email<span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control"
                                    placeholder="Enter Email" value="{{ old('email') }}" required>
                                <span class="text-danger">@error('email'){{ $message }}@enderror</span>
                            </div>

                            <div class="mb-3 col-md-12">
                                <label>Password<span class="text-danger">*</span></label>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Enter Password" required>
                                <span class="text-danger">@error('password'){{ $message }}@enderror</span>
                            </div>


                            <div class="mb-4 col-md-12">
                                <label class="mb-1">Captcha<span class="text-danger">*</span></label>
                                    <input type="text" name="captcha" id="captcha" class="form-control" placeholder="Enter captcha" required>
                                    <span class="text-danger">@error('captcha'){{$message}}@enderror</span>
                            </div>


                            <div class="mb-1 col-md-12">
                                <div class="captcha">
                                    <span style="width: 50%; height: 50%;">{!! captcha_img('flat') !!}</span>
                                    <button type="button" class="btn btn-danger reload" id="reload">&#x21bb;</button>                                    
                                </div>
                            </div>

                            <div class="col-md-12">
                                <button class="btn btn-primary float-end" id="supplier_login_in" style="height:1rem;">Login</button>
                            </div>
                        </div>
                    </form>
                    {{-- <p class="text-center mt-3 text-secondary">If you don't have account, Please <a href="{{route('supplier-registration') }}">Signup
                    Now</a></p> --}}
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript"></script>
    <script src="/assets/js/jquery.min.js"></script>

    <script>
        $('#reload').click(function () {
            $.ajax({

                type: 'GET',
                url: 'reload-captcha',
                success: function (data) {
                    $(".captcha span").html(data.captcha)
                }
            });
        });

    </script>



</body>


</html>
