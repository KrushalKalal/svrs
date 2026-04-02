<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>SVRS Coin — Sign In</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        :root {
            --bg: #050B18;
            --bg-card: #0D1626;
            --bg-card2: #111C30;
            --gold: #F0A500;
            --gold-light: #FFCC44;
            --gold-dark: #B87800;
            --accent: #00D4AA;
            --red: #EF4444;
            --green: #10B981;
            --white: #FFFFFF;
            --muted: #6B7A9A;
            --border: #1E2D45;
            --max-w: 430px;
            --radius: 16px;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            background: #020810;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--white);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: stretch;
            -webkit-font-smoothing: antialiased;
        }

        #app {
            width: 100%;
            max-width: var(--max-w);
            min-height: 100vh;
            background: var(--bg);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 32px 24px;
            box-shadow: 0 0 60px rgba(0, 0, 0, 0.8);
            position: relative;
            overflow: hidden;
        }

        /* Background glow */
        #app::before {
            content: '';
            position: absolute;
            top: -120px;
            left: 50%;
            transform: translateX(-50%);
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(240, 165, 0, 0.12) 0%, transparent 70%);
            pointer-events: none;
        }

        #app::after {
            content: '';
            position: absolute;
            bottom: -80px;
            right: -60px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(0, 212, 170, 0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        .logo-wrap {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-wrap .logo-icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 16px;
            box-shadow: 0 8px 32px rgba(240, 165, 0, 0.35);
        }

        .logo-wrap h1 {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .logo-wrap p {
            font-size: 14px;
            color: var(--muted);
            margin-top: 4px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 8px;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 16px;
            pointer-events: none;
        }

        .input-field {
            width: 100%;
            background: var(--bg-card2);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 14px 44px;
            font-size: 15px;
            color: var(--white);
            font-family: 'DM Sans', sans-serif;
            outline: none;
            transition: border-color 0.2s;
        }

        .input-field:focus {
            border-color: var(--gold);
        }

        .input-field::placeholder {
            color: var(--muted);
        }

        .toggle-pw {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--muted);
            cursor: pointer;
            font-size: 15px;
            transition: color 0.2s;
        }

        .toggle-pw:hover {
            color: var(--white70);
        }

        .error-msg {
            font-size: 12px;
            color: var(--red);
            margin-top: 5px;
            display: none;
        }

        .error-msg.show {
            display: block;
        }

        .extras {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            font-size: 13px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--muted);
            cursor: pointer;
        }

        .remember input[type="checkbox"] {
            accent-color: var(--gold);
            width: 16px;
            height: 16px;
        }

        .forgot {
            color: var(--gold);
            text-decoration: none;
            font-weight: 600;
        }

        .forgot:hover {
            color: var(--gold-light);
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: #000;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 800;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 6px 24px rgba(240, 165, 0, 0.35);
            transition: all 0.2s;
            letter-spacing: 0.3px;
        }

        .btn-login:active {
            transform: scale(0.98);
        }

        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .spin {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(0, 0, 0, 0.2);
            border-top-color: #000;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            display: inline-block;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .signup-row {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: var(--muted);
        }

        .signup-row a {
            color: var(--gold);
            font-weight: 700;
            text-decoration: none;
        }

        .signup-row a:hover {
            color: var(--gold-light);
        }

        /* Toast override */
        #toast-container>div {
            border-radius: 12px !important;
            font-family: 'DM Sans', sans-serif !important;
        }
    </style>
</head>

<body>

    <div id="app">
        {{-- Logo --}}
        <div class="logo-wrap">
            <div class="logo-icon">
                <i class="fa-solid fa-dollar-sign" style="color:#000;"></i>

            </div>
            <h1>SVRS Coin</h1>
            <p>Sign in to your account</p>
        </div>

        {{-- Form --}}
        <form id="loginForm">
            @csrf

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-wrap">
                    <i class="fa fa-envelope input-icon"></i>
                    <input type="email" name="email" class="input-field" placeholder="example@gmail.com"
                        autocomplete="email">
                </div>
                <p class="error-msg" id="emailErr"></p>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrap">
                    <i class="fa fa-lock input-icon"></i>
                    <input type="password" name="password" id="pwField" class="input-field" placeholder="Enter password"
                        autocomplete="current-password">
                    <button type="button" class="toggle-pw" onclick="togglePw()">
                        <i class="fa fa-eye-slash" id="pwIcon"></i>
                    </button>
                </div>
                <p class="error-msg" id="pwErr"></p>
            </div>

            <div class="extras">
                <label class="remember">
                    <input type="checkbox" name="remember"> Remember me
                </label>
                <a href="{{ route('admin.forgot.password') }}" class="forgot">Forgot Password?</a>
            </div>

            <button type="submit" class="btn-login" id="loginBtn">
                <span id="btnLabel">Sign In</span>
            </button>
        </form>

        <div class="signup-row">
            Don't have an account? <a href="{{ route('front.sign.up') }}">Sign up</a>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        toastr.options = { positionClass: 'toast-top-center', timeOut: 3000, progressBar: true };

        function togglePw() {
            const f = document.getElementById('pwField');
            const i = document.getElementById('pwIcon');
            if (f.type === 'password') {
                f.type = 'text'; i.className = 'fa fa-eye';
            } else {
                f.type = 'password'; i.className = 'fa fa-eye-slash';
            }
        }

        function setLoading(on) {
            const btn = document.getElementById('loginBtn');
            const lbl = document.getElementById('btnLabel');
            btn.disabled = on;
            lbl.innerHTML = on ? '<span class="spin"></span>' : 'Sign In';
        }

        $('#loginForm').on('submit', function (e) {
            e.preventDefault();
            // Reset errors
            ['emailErr', 'pwErr'].forEach(id => {
                document.getElementById(id).textContent = '';
                document.getElementById(id).classList.remove('show');
            });

            const email = $('input[name=email]').val().trim();
            const password = $('input[name=password]').val().trim();
            let valid = true;

            if (!email) {
                showErr('emailErr', 'Email is required');
                valid = false;
            } else if (!/^\S+@\S+\.\S+$/.test(email)) {
                showErr('emailErr', 'Enter a valid email');
                valid = false;
            }
            if (!password) {
                showErr('pwErr', 'Password is required');
                valid = false;
            }
            if (!valid) return;

            setLoading(true);
            $.ajax({
                url: "{{ route('admin.login.submit') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function (res) {
                    if (res.status) {
                        toastr.success('Login successful!');
                        window.location.href = res.redirect_url;
                    } else {
                        toastr.error(res.message || 'Login failed.');
                        setLoading(false);
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errs = xhr.responseJSON.errors;
                        if (errs.email) showErr('emailErr', errs.email[0]);
                        if (errs.password) showErr('pwErr', errs.password[0]);
                    } else if (xhr.status === 401) {
                        toastr.error('Invalid email or password.');
                    } else {
                        toastr.error('Server error. Try again.');
                    }
                    setLoading(false);
                }
            });
        });

        function showErr(id, msg) {
            const el = document.getElementById(id);
            el.textContent = msg;
            el.classList.add('show');
        }
    </script>
</body>

</html>