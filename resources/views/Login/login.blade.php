<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <link rel="shortcut icon" href="/img/favicon.jpg" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>SOZO ESTATE SYSTEM | Secure Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,170.7C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-repeat: no-repeat;
            background-position: bottom;
            background-size: cover;
            opacity: 0.6;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        .logo-wrapper {
            text-align: center;
            margin-bottom: 30px;
            animation: fadeInDown 1s ease;
        }

        .logo-wrapper img {
            max-width: 200px;
            height: auto;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.2));
            transition: transform 0.3s ease;
        }

        .logo-wrapper img:hover {
            transform: scale(1.05);
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
            padding: 40px 30px;
            animation: fadeInUp 1s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .login-header h2 {
            color: #333;
            font-weight: 700;
            font-size: 28px;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }

        .login-header h2 span {
            color: #667eea;
        }

        .login-header p {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
            font-size: 18px;
            z-index: 2;
        }

        .form-control {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: white;
            font-family: 'Poppins', sans-serif;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: #aaa;
            font-size: 14px;
        }

        .text-danger {
            font-size: 12px;
            margin-top: 5px;
            display: block;
            color: #ff4757;
            font-weight: 500;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            border: none;
        }

        .alert i {
            margin-right: 10px;
            font-size: 18px;
        }

        .alert-danger {
            background: rgba(255, 71, 87, 0.1);
            color: #ff4757;
            border-left: 4px solid #ff4757;
        }

        .alert-success {
            background: rgba(0, 184, 148, 0.1);
            color: #00b894;
            border-left: 4px solid #00b894;
        }

        .captcha-container {
            margin-bottom: 25px;
        }

        .captcha-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: white;
        }

        .captcha-box span {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .reload-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .reload-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(180deg);
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
        }

        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .btn-login i {
            font-size: 18px;
        }

        .login-footer {
            text-align: center;
            margin-top: 25px;
            color: #666;
            font-size: 14px;
        }

        .login-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-footer a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .ip-badge {
            position: absolute;
            top: 20px;
            right: 30px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            padding: 8px 15px;
            border-radius: 30px;
            color: white;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            z-index: 2;
        }

        .ip-badge i {
            font-size: 14px;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 15px;
            }

            .login-card {
                padding: 30px 20px;
            }

            .ip-badge {
                top: 10px;
                right: 15px;
                font-size: 11px;
                padding: 5px 10px;
            }
        }

        /* ===== FIXED PASSWORD FIELD STYLES ===== */
        /* Password wrapper */
        .password-wrapper {
            position: relative;
            width: 100%;
        }

        /* Input styling */
        .password-wrapper input.form-control {
            padding-left: 45px;
            /* space for lock icon */
            padding-right: 45px;
            /* space for eye icon */
            width: 100%;
            height: 52px;
            /* Fixed height for consistency */
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: white;
            font-family: 'Poppins', sans-serif;
        }

        .password-wrapper input.form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        /* Lock icon on the left */
        .password-wrapper i.fa-lock {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
            pointer-events: none;
            font-size: 18px;
            z-index: 3;
        }

        /* Eye icon on the right - FIXED POSITIONING */
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #667eea;
            z-index: 3;
            font-size: 18px;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #764ba2;
        }

        label span.text-danger {
            display: inline-block;
            vertical-align: middle;
            margin-left: 3px;
        }

        /* Hide any conflicting icons */
        .input-group i.fa-lock {
            display: none;
        }
    </style>
</head>

<body>


    <div class="login-container">
        <div class="logo-wrapper">
            {{-- <img src="logo.png" alt="COMESA Logo"> --}}
        </div>

        <div class="login-card">
            <div class="login-header">
                <h2>SOZO <span>ESTATES</span></h2>
                <p>Secure Access to Land Management System</p>
            </div>

            @if(Session::get('fail'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ Session::get('fail') }}
                </div>
            @endif

            @if(Session::get('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ Session::get('success') }}
                </div>
            @endif

            <form action="{{ route('admin-send') }}" method="POST" id="loginForm">
                @csrf
                <input type="hidden" name="user_registered_ip" value="{{ $user_registered_ip }}">

                <!-- Email Field -->
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email Address <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" class="form-control" placeholder="Enter your email address"
                            value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Password <span class="text-danger">*</span></label>
                    <div class="password-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Enter your password" required>
                        <span class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    @error('password')
                        <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-login" id="loginBtn">
                    <i class="fas fa-sign-in-alt" id="btnIcon"></i>
                    <span class="btn-text" id="btnText">SECURE LOGIN</span>
                    <span class="spinner-border spinner-border-sm ms-2 d-none" id="btnSpinner" role="status"
                        aria-hidden="true"></span>
                </button>

                <!-- Footer -->
                <div class="login-footer">
                    <p style="margin-top: 10px; font-size: 12px;">
                        <i class="fas fa-shield-alt"></i> Protected by advanced security
                    </p>
                </div>
            </form>

            <!-- Password Toggle JS -->
            <script>
                const togglePassword = document.getElementById('togglePassword');
                const passwordField = document.getElementById('password');

                togglePassword.addEventListener('click', function () {
                    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordField.setAttribute('type', type);

                    const icon = this.querySelector('i');
                    if (icon.classList.contains('fa-eye')) {
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });

                // Login Button Loading State
                const loginForm = document.getElementById('loginForm');
                loginForm.addEventListener('submit', function (e) {
                    const loginBtn = document.getElementById('loginBtn');
                    const btnIcon = document.getElementById('btnIcon');
                    const btnText = document.getElementById('btnText');
                    const btnSpinner = document.getElementById('btnSpinner');

                    loginBtn.disabled = true;
                    btnIcon.classList.remove('fa-sign-in-alt');
                    btnIcon.classList.add('fa-spinner', 'fa-spin');
                    btnText.textContent = 'LOGGING IN...';
                    btnSpinner.classList.remove('d-none');

                    // Safety timeout to re-enable button after 30s
                    setTimeout(() => {
                        if (loginBtn.disabled) {
                            loginBtn.disabled = false;
                            btnIcon.classList.remove('fa-spinner', 'fa-spin');
                            btnIcon.classList.add('fa-sign-in-alt');
                            btnText.textContent = 'SECURE LOGIN';
                            btnSpinner.classList.add('d-none');
                        }
                    }, 30000);
                });

                // Re-enable button on validation errors
                window.addEventListener('load', function () {
                    @if($errors->any() || Session::get('fail'))
                        const loginBtn = document.getElementById('loginBtn');
                        const btnIcon = document.getElementById('btnIcon');
                        const btnText = document.getElementById('btnText');
                        const btnSpinner = document.getElementById('btnSpinner');

                        loginBtn.disabled = false;
                        btnIcon.classList.remove('fa-spinner', 'fa-spin');
                        btnIcon.classList.add('fa-sign-in-alt');
                        btnText.textContent = 'SECURE LOGIN';
                        btnSpinner.classList.add('d-none');
                    @endif
    });
            </script>
        </div>
    </div>

    <script src="/assets/js/jquery.min.js"></script>
    <script>
        $('#reload').click(function () {
            $.ajax({
                type: 'GET',
                url: 'reload-captcha',
                success: function (data) {
                    $(".captcha-box span").html(data.captcha);
                }
            });
        });

        // Add smooth input animations
        $('.form-control').focus(function () {
            $(this).parent('.input-group, .password-wrapper').css('transform', 'scale(1.02)');
        }).blur(function () {
            $(this).parent('.input-group, .password-wrapper').css('transform', 'scale(1)');
        });

        // Toggle password visibility - FIXED VERSION
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            // Toggle the type attribute
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);

            // Toggle the eye icon
            const icon = this.querySelector('i');
            if (icon.classList.contains('fa-eye')) {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Login button functionality
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            const loginBtn = document.getElementById('loginBtn');
            const btnIcon = document.getElementById('btnIcon');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');

            // Disable button and show loading state
            loginBtn.disabled = true;
            btnIcon.classList.remove('fa-sign-in-alt');
            btnIcon.classList.add('fa-spinner', 'fa-spin');
            btnText.textContent = 'LOGGING IN...';
            btnSpinner.classList.remove('d-none');

            // Re-enable button after 30 seconds (safety timeout in case something goes wrong)
            setTimeout(function () {
                if (loginBtn.disabled) {
                    loginBtn.disabled = false;
                    btnIcon.classList.remove('fa-spinner', 'fa-spin');
                    btnIcon.classList.add('fa-sign-in-alt');
                    btnText.textContent = 'SECURE LOGIN';
                    btnSpinner.classList.add('d-none');
                }
            }, 30000);
        });

        // Re-enable button if there are validation errors (when page reloads with errors)
        window.addEventListener('load', function () {
            @if($errors->any() || Session::get('fail'))
                const loginBtn = document.getElementById('loginBtn');
                const btnIcon = document.getElementById('btnIcon');
                const btnText = document.getElementById('btnText');
                const btnSpinner = document.getElementById('btnSpinner');

                loginBtn.disabled = false;
                btnIcon.classList.remove('fa-spinner', 'fa-spin');
                btnIcon.classList.add('fa-sign-in-alt');
                btnText.textContent = 'SECURE LOGIN';
                btnSpinner.classList.add('d-none');
            @endif
        });


        window.addEventListener('load', function () {
            @if($errors->any())
                const firstError = document.querySelector('.text-danger');
                if (firstError) {
                    const input = firstError.previousElementSibling.querySelector('input');
                    if (input) input.focus();
                }
            @endif
});
    </script>
</body>

</html>