<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#0d1a16">
    <title>Sign in | Sozo Land Systems</title>
    <link rel="icon" type="image/png" href="/assets/sozo/favicon.png" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Instrument+Sans:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="/assets/vendors/mdi/css/materialdesignicons.min.css">
    <style>
        :root {
            --pine: #0d1a16; --canopy: #13251f; --moss: #1b3329; --rule: #2a4538;
            --chalk: #eceae0; --muted: #93a79c; --faint: #647a6f;
            --brass: #d2a44e; --brass-ink: #1d1605; --brick: #e0645c; --lake: #54b6a0;
            --display: "Fraunces", "Iowan Old Style", Georgia, serif;
            --text: "Instrument Sans", "Segoe UI", system-ui, -apple-system, Roboto, sans-serif;
        }
        html[data-sz-theme="dark"] { color-scheme: dark; --pine: #08080a; --canopy: #111114; --moss: #19191d; --rule: #2e2e35; --chalk: #ededf0; --muted: #a3a3ad; --faint: #74747e; --brass: #e0b24f; --brass-ink: #1a1304; --brick: #ef6b62; --lake: #4fc2a8; }
        html[data-sz-theme="light"] { color-scheme: light; --pine: #ece3cd; --canopy: #f5eedb; --moss: #e9dfc6; --rule: #d3c7a8; --chalk: #2b2517; --muted: #645a43; --faint: #877c63; --brass: #a06908; --brass-ink: #fffaf0; --brick: #c5372d; --lake: #0e8a6c; }
        html[data-sz-theme="light"] .scene { --chalk: #eceae0; --muted: #93a79c; --brass: #d2a44e; --rule: #2a4538; }
        html[data-sz-theme="light"] .panel { background: radial-gradient(700px 400px at 100% 0%, rgba(160, 105, 8, .08), transparent 60%), var(--pine); }
        html[data-sz-theme="light"] .alert.fail { color: #8a1f17; } html[data-sz-theme="light"] .alert.ok { color: #0a5c48; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { -webkit-text-size-adjust: 100%; }
        body {
            min-height: 100vh; min-height: 100dvh; font-family: var(--text); color: var(--chalk);
            background: var(--pine); -webkit-font-smoothing: antialiased; line-height: 1.5;
        }
        .login { display: grid; grid-template-columns: minmax(0, 1.12fr) minmax(0, 1fr); min-height: 100vh; min-height: 100dvh; }

        /* Left: the estate */
        .scene {
            position: relative; display: flex; flex-direction: column; justify-content: space-between;
            padding: clamp(1.6rem, 4vw, 3.4rem); overflow: hidden; isolation: isolate;
            background:
                linear-gradient(180deg, rgba(8, 16, 13, .55) 0%, rgba(8, 16, 13, .15) 38%, rgba(8, 16, 13, .88) 100%),
                url("https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=1800&q=70") center / cover no-repeat,
                linear-gradient(135deg, #1d3a2f, #0d1a16);
        }
        .scene svg.plan { position: absolute; inset: 0; width: 100%; height: 100%; z-index: -1; opacity: .5; mix-blend-mode: screen; }
        .brand { display: flex; align-items: center; gap: .8rem; color: var(--chalk); text-decoration: none; width: fit-content; }
        .brand img { height: 104px; width: auto; display: block; }
        .pitch h1 { font-family: var(--display); font-weight: 600; letter-spacing: -.025em; line-height: 1.02; font-size: clamp(2.2rem, 4.6vw, 4rem); max-width: 14ch; text-shadow: 0 2px 30px rgba(0, 0, 0, .35); }
        .pitch p { margin-top: 1.1rem; max-width: 44ch; color: rgba(236, 234, 224, .82); font-size: 1.02rem; }
        .modules { display: flex; flex-wrap: wrap; gap: .6rem; margin-top: 1.7rem; }
        .modules span { display: inline-flex; align-items: center; gap: .5rem; padding: .5rem .9rem; border-radius: 99px; font-size: .86rem; font-weight: 500; background: rgba(13, 26, 22, .6); border: 1px solid rgba(236, 234, 224, .16); backdrop-filter: blur(8px); }
        .modules i { color: var(--brass); font-size: 1.1rem; }
        .credit { margin-top: 1.2rem; font-size: .74rem; color: rgba(236, 234, 224, .55); }

        /* Right: the form */
        .panel { display: flex; align-items: center; justify-content: center; padding: clamp(1.5rem, 4vw, 3.5rem); background: radial-gradient(700px 400px at 100% 0%, rgba(210, 164, 78, .08), transparent 60%), var(--pine); }
        .form-wrap { width: 100%; max-width: 410px; }
        .form-wrap h2 { font-family: var(--display); font-size: 2.15rem; font-weight: 600; letter-spacing: -.02em; line-height: 1.1; }
        .form-wrap > p { margin: .6rem 0 2rem; color: var(--muted); }
        .alert { display: flex; gap: .7rem; align-items: flex-start; padding: .9rem 1.05rem; margin-bottom: 1.4rem; border-radius: 11px; border: 1px solid; border-left-width: 4px; font-size: .92rem; font-weight: 500; }
        .alert i { font-size: 1.2rem; margin-top: -.05rem; }
        .alert.fail { background: rgba(224, 100, 92, .1); border-color: var(--brick); color: #f4b8b3; }
        .alert.ok { background: rgba(84, 182, 160, .1); border-color: var(--lake); color: #a8e0d3; }
        .back-btn { display: flex; align-items: center; justify-content: center; gap: .5rem; width: 100%; margin-top: 1.1rem; padding: .85rem 1rem; border-radius: 11px; border: 1px solid var(--rule); background: transparent; color: var(--chalk); font: 600 .95rem var(--text); text-decoration: none; transition: background .18s ease, border-color .18s ease, color .18s ease, transform .12s ease; }
        .back-btn i { font-size: 1.15rem; color: var(--brass); transition: transform .18s ease, color .18s ease; }
        .back-btn:hover { background: var(--moss); border-color: var(--brass); color: var(--chalk); transform: translateY(-1px); }
        .back-btn:hover i { transform: translateX(-3px); }
        .back-btn:focus-visible { outline: 2px solid var(--brass); outline-offset: 3px; }
        .field { margin-bottom: 1.2rem; }
        .field label { display: block; margin-bottom: .45rem; font-size: .88rem; font-weight: 500; color: var(--muted); }
        .control { position: relative; }
        .control > i.lead { position: absolute; left: .95rem; top: 50%; transform: translateY(-50%); color: var(--faint); font-size: 1.25rem; pointer-events: none; transition: color .15s ease; }
        .control input {
            width: 100%; height: 52px; padding: 0 3rem 0 2.8rem; font: inherit; font-size: 1rem; color: var(--chalk);
            background: var(--canopy); border: 1px solid var(--rule); border-radius: 12px; outline: none;
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .control input::placeholder { color: var(--faint); }
        .control input:focus { border-color: var(--brass); box-shadow: 0 0 0 4px rgba(210, 164, 78, .16); }
        .control:focus-within > i.lead { color: var(--brass); }
        .control input:-webkit-autofill { -webkit-box-shadow: 0 0 0 40px var(--canopy) inset; -webkit-text-fill-color: var(--chalk); caret-color: var(--chalk); }
        .reveal { position: absolute; right: .4rem; top: 50%; transform: translateY(-50%); width: 42px; height: 42px; display: grid; place-items: center; border: 0; background: transparent; color: var(--muted); border-radius: 9px; cursor: pointer; font-size: 1.3rem; }
        .reveal:hover { color: var(--chalk); background: rgba(236, 234, 224, .06); }
        .error { display: flex; align-items: center; gap: .4rem; margin-top: .45rem; color: var(--brick); font-size: .84rem; font-weight: 500; }
        .submit {
            width: 100%; height: 54px; margin-top: .6rem; display: inline-flex; align-items: center; justify-content: center; gap: .6rem;
            font: inherit; font-size: 1rem; font-weight: 600; color: var(--brass-ink); background: var(--brass);
            border: 0; border-radius: 12px; cursor: pointer; transition: background .16s ease, transform .12s ease;
        }
        .submit:hover:not(:disabled) { background: #e0b862; }
        .submit:active:not(:disabled) { transform: translateY(1px); }
        .submit:disabled { opacity: .7; cursor: progress; }
        .submit .spin { width: 18px; height: 18px; border-radius: 50%; border: 2px solid rgba(29, 22, 5, .3); border-top-color: var(--brass-ink); animation: turn .7s linear infinite; display: none; }
        .submit.busy .spin { display: inline-block; }
        .fine { margin-top: 1.6rem; display: flex; align-items: center; gap: .55rem; color: var(--faint); font-size: .82rem; }
        .fine i { color: var(--brass); font-size: 1.1rem; }
        :focus-visible { outline: 2px solid var(--brass); outline-offset: 2px; }
        @keyframes turn { to { transform: rotate(360deg); } }
        @keyframes rise { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
        .form-wrap { animation: rise .7s cubic-bezier(.2, .8, .2, 1) both; }

        @media (max-width: 899.98px) {
            .login { grid-template-columns: 1fr; }
            .scene { min-height: 300px; padding: 1.5rem; }
            .pitch h1 { font-size: 2rem; max-width: none; }
            .pitch p, .modules, .credit { display: none; }
            .panel { align-items: flex-start; padding-top: 2rem; }
        }
        @media (prefers-reduced-motion: reduce) { .form-wrap { animation: none; } .submit .spin { animation-duration: 2s; } }
    </style>
    @include('includes.ui-extras')
</head>

<body>
    <main class="login">
        <section class="scene" aria-label="Sozo Properties">
            <svg class="plan" viewBox="0 0 800 800" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
                <g fill="none" stroke="#d2a44e" stroke-opacity=".5" stroke-width="1.2">
                    <rect x="60" y="80" width="300" height="220" rx="6"/><rect x="380" y="80" width="360" height="130" rx="6"/>
                    <rect x="380" y="230" width="170" height="250" rx="6"/><rect x="570" y="230" width="170" height="250" rx="6"/>
                    <rect x="60" y="320" width="300" height="160" rx="6"/><rect x="60" y="500" width="680" height="220" rx="6"/>
                    <path d="M60 190h300M210 80v220M380 145h360M465 230v250M655 230v250M60 400h300M210 320v160M60 610h680M230 500v220M400 500v220M570 500v220" stroke-dasharray="4 5"/>
                </g>
            </svg>

            <a class="brand" href="{{ url('/') }}" aria-label="Sozo Property Consultants">
                <img src="/assets/sozo/logo-light.png" alt="Sozo Property Consultants" width="403" height="379">
            </a>

            <div class="pitch">
                <h1>Every plot, receipt and agreement in one register.</h1>
                <p>Sell land, track instalments and issue agreements for Sozo Properties without chasing paper.</p>
                <div class="modules" aria-label="What is inside">
                    <span><i class="mdi mdi-terrain"></i> Estates and plots</span>
                    <span><i class="mdi mdi-file-document-outline"></i> Receipts and agreements</span>
                    <span><i class="mdi mdi-home-city-outline"></i> Houses</span>
                    <span><i class="mdi mdi-bell-ring-outline"></i> Payment reminders</span>
                </div>
                <p class="credit">Photo from Unsplash</p>
            </div>
        </section>

        <section class="panel">
            <div class="form-wrap">
                <h2>Welcome back</h2>
                <p>Sign in to manage estates, plots, receipts and agreements.</p>

                <form action="{{ route('admin-send') }}" method="POST" id="loginForm" novalidate>
                    @csrf
                    <input type="hidden" name="user_registered_ip" value="{{ $user_registered_ip }}">

                    <div class="field">
                        <label for="email">Email address</label>
                        <div class="control">
                            <i class="mdi mdi-email-outline lead"></i>
                            <input type="email" id="email" name="email" placeholder="you@sozoproperties.com" value="{{ old('email') }}" autocomplete="username" autofocus required>
                        </div>
                        @error('email')
                            <span class="error"><i class="mdi mdi-alert-outline"></i> {{ $message }}</span>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <div class="control">
                            <i class="mdi mdi-lock-outline lead"></i>
                            <input type="password" id="password" name="password" placeholder="Enter your password" autocomplete="current-password" required>
                            <button type="button" class="reveal" id="togglePassword" aria-label="Show password" aria-pressed="false"><i class="mdi mdi-eye-outline"></i></button>
                        </div>
                        @error('password')
                            <span class="error"><i class="mdi mdi-alert-outline"></i> {{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="submit" id="loginBtn">
                        <span class="spin" aria-hidden="true"></span>
                        <span id="btnText">Sign in</span>
                    </button>
                </form>

                <p class="fine"><i class="mdi mdi-shield-check-outline"></i> Access is limited to authorised Sozo staff.</p>
                <a class="back-btn" href="{{ url('/') }}"><i class="mdi mdi-arrow-left"></i> Back to website</a>
            </div>
        </section>
    </main>

    <script>
        (function () {
            var form = document.getElementById('loginForm');
            var btn = document.getElementById('loginBtn');
            var label = document.getElementById('btnText');
            var pass = document.getElementById('password');
            var eye = document.getElementById('togglePassword');
            var timer;

            function reset() {
                btn.disabled = false;
                btn.classList.remove('busy');
                label.textContent = 'Sign in';
                clearTimeout(timer);
            }

            eye.addEventListener('click', function () {
                var show = pass.type === 'password';
                pass.type = show ? 'text' : 'password';
                eye.setAttribute('aria-pressed', show ? 'true' : 'false');
                eye.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
                eye.firstElementChild.className = 'mdi ' + (show ? 'mdi-eye-off-outline' : 'mdi-eye-outline');
            });

            form.addEventListener('submit', function () {
                btn.disabled = true;
                btn.classList.add('busy');
                label.textContent = 'Signing in';
                timer = setTimeout(reset, 30000);
            });

            // Coming back with the browser's back button should not leave the button stuck.
            window.addEventListener('pageshow', reset);
        })();
    </script>
</body>

</html>
