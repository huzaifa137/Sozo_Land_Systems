<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
    {{-- <link rel="stylesheet" href="/assets/css/style.css"> --}}
    <link rel="stylesheet" href="/assets/images">
    <title>..:: Admin - Register Portal ::..</title>
</head>
<body>
    <div  style="display: flex;justify-content:center;align-content:center">
      <a href="#"><img src="logo.png" alt="COMESA_logo" class="responsive"></a>  
    </div>
   
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="signup-form">
                    <form action="{{ route('admin-registration')}}" class="mt-5 border p-4 bg-light shadow" method="POST">
                        @csrf
                        <h2 class="mb-2 text-primary" style="text-align: center">SOZO LAND SYSTEMS <br> REGISTRATION</h2>

                        @if (Session::get('success'))
                           <div class="alert alert-success">
                            {{Session::get('success')}}
                           </div>
                        @endif

                        @if (Session::get('fail'))
                           <div class="alert alert-danger">
                            {{Session::get('fail')}}
                           </div>
                        @endif


                        <div class="row">

                            <div class="mb-3 col-md-12">
                                <label>Username<span class="text-danger">*</span></label>
                                <input type="text" name="username" id="username" class="form-control" placeholder="Enter username" value="{{old('username')}}" required>
                                <span class="text-danger">@error('username'){{$message}}@enderror</span>
                            </div>



                            <div class="mb-3 col-md-12">
                                <label>Email<span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" value="{{old('email')}}" required>
                                <span class="text-danger">@error('email'){{$message}}@enderror</span>
                            </div>

                            <div class="mb-3 col-md-12">
                                <label>Admin Category<span class="text-danger">*</span></label>
                                <select class="form-control" name="admin_category" id="" required>
                                    <option value="">--Select System Admin Category----</option>
                                    <option value="SuperAdmin">1.Super Admin</option>
                                    <option value="Admin">2. Admin</option>
                                </select>
                                <span class="text-danger">@error('email'){{$message}}@enderror</span>
                            </div>

                            <div class="mb-3 col-md-12">
                                <label>Password<span class="text-danger">*</span></label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password" required>
                                <span class="text-danger">@error('password'){{$message}}@enderror</span>
                            </div>

                            <div class="mb-3 col-md-12">
                                <label>Confirm Password<span class="text-danger">*</span></label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Enter Password" required>
                                <span class="text-danger">@error('confirm_password'){{$message}}@enderror</span>
                            </div>
                           
                            {{-- <div class="col-md-12">
                               <button class="btn btn-primary float-end" >Register Admin</button>
                            </div> --}}
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary float-end" >Register Admin</button>
                             </div>
                        </div>
                    </form>
                    {{-- <p class="text-center mt-3 text-secondary">If you don't have account, Please <a href="#">Signup Now</a></p> --}}
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript"></script>
    <script src="/assets/js/jquery.min.js"></script>

     <script>

    //  $('#supplier_sign_in').click(function(){

    //     var username = $('#username').val();
    //     var email = $('#email').val();
    //     var password = $('#password').val();
        

    //     var form_data = new FormData();

    //     form_data.append('email',email);
    //     form_data.append('username',username);
    //     form_data.append('password',password);
        

    //                 $ajax({
    //                  type: "post",

    //                     processData: false,
    //                     contentType: false,
    //                     cache: false,
    //                     data		: form_data,								
    //                     headers		:{	'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},

    //                     url			:'/admin.save',
    //                     success		:function(data){
    //                         if(data.status){
    //                             alert(data.message);
    //                         }
    //                     },
    //                     error: function(data)
    //                     {
    //                         $('body').html(data.responseText);
    //                     }
    //                 });
    //         }); 

        </script>



</body>

    
</html>