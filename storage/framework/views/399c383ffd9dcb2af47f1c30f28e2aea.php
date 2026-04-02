<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title><?php echo e(config('app.name')); ?> — Sign Up</title>

    <link rel="shortcut icon" href="<?php echo e(asset('front/assets/images/logo/favicon.png')); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

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
            --border2: rgba(30, 45, 69, 0.6);
            --max-w: 430px;
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
            min-height: 100%;
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
            box-shadow: 0 0 60px rgba(0,0,0,0.8);
            position: relative;
            overflow: hidden;
        }

        #app::before {
            content: '';
            position: absolute;
            top: -120px; left: 50%;
            transform: translateX(-50%);
            width: 320px; height: 320px;
            background: radial-gradient(circle, rgba(240,165,0,0.10) 0%, transparent 70%);
            pointer-events: none; z-index: 0;
        }
        #app::after {
            content: '';
            position: absolute;
            bottom: -80px; right: -60px;
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(0,212,170,0.07) 0%, transparent 70%);
            pointer-events: none; z-index: 0;
        }

        .scroll-body {
            flex: 1;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
            padding: 28px 24px 40px;
            position: relative;
            z-index: 1;
        }
        .scroll-body::-webkit-scrollbar { display: none; }

        .logo-wrap {
            text-align: center;
            margin-bottom: 32px;
        }
        .logo-icon {
            width: 68px; height: 68px;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            font-size: 30px; margin: 0 auto 14px;
            box-shadow: 0 8px 32px rgba(240,165,0,0.35);
        }
        .logo-wrap h1 { font-size: 22px; font-weight: 800; letter-spacing: -0.5px; }
        .logo-wrap p  { font-size: 13px; color: var(--muted); margin-top: 4px; }

        .sec-label {
            font-size: 12px; font-weight: 700;
            color: var(--muted);
            text-transform: uppercase; letter-spacing: 0.8px;
            margin: 24px 0 10px;
            display: flex; align-items: center; gap: 8px;
        }
        .sec-label::before {
            content: '';
            width: 3px; height: 13px;
            background: var(--gold);
            border-radius: 2px;
        }

        .form-group { margin-bottom: 12px; }

        .form-label {
            display: block;
            font-size: 12px; font-weight: 600;
            color: var(--muted); margin-bottom: 7px;
        }

        .input-wrap { position: relative; }

        .input-icon {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%);
            color: var(--muted); font-size: 15px;
            pointer-events: none;
        }

        .input-field {
            width: 100%;
            background: var(--bg-card2);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            padding: 13px 44px;
            font-size: 14px; color: var(--white);
            font-family: 'DM Sans', sans-serif;
            outline: none;
            transition: border-color 0.2s;
        }
        .input-field:focus       { border-color: var(--gold); }
        .input-field::placeholder { color: var(--muted); }
        .input-field[readonly]   { color: var(--muted); cursor: not-allowed; }

        .toggle-pw {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: var(--muted); cursor: pointer;
            font-size: 14px; transition: color 0.2s;
        }

        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

        .err-text { font-size: 11px; color: var(--red);   margin-top: 4px; display: block; min-height: 16px; }
        .suc-text { font-size: 11px; color: var(--green); margin-top: 4px; display: block; }

        .sponsor-pill {
            display: none;
            align-items: center; gap: 6px;
            padding: 7px 12px; border-radius: 8px;
            font-size: 12px; font-weight: 600;
            margin-top: 6px;
        }
        .sponsor-pill.ok  { display:flex; background:rgba(16,185,129,0.1); color:var(--green); border:1px solid rgba(16,185,129,0.2); }
        .sponsor-pill.bad { display:flex; background:rgba(239,68,68,0.1);  color:var(--red);   border:1px solid rgba(239,68,68,0.2); }

        .btn-submit {
            width: 100%; padding: 15px;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: #000; border: none; border-radius: 14px;
            font-size: 15px; font-weight: 800;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer; letter-spacing: 0.3px;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            box-shadow: 0 6px 24px rgba(240,165,0,0.3);
            transition: all 0.2s;
            margin-top: 8px;
        }
        .btn-submit:active    { transform: scale(0.98); }
        .btn-submit:disabled  { opacity: 0.65; cursor: not-allowed; }

        .login-row {
            text-align: center; margin-top: 20px;
            font-size: 13px; color: var(--muted);
        }
        .login-row a { color: var(--gold); font-weight: 700; text-decoration: none; }

        .spin {
            width: 17px; height: 17px; border-radius: 50%;
            border: 2px solid rgba(0,0,0,0.2);
            border-top-color: #000;
            animation: spin 0.65s linear infinite;
            display: inline-block;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        #toast-container > div {
            border-radius: 12px !important;
            font-family: 'DM Sans', sans-serif !important;
        }
    </style>
</head>
<body>
<div id="app">
<div class="scroll-body">

    
    <div class="logo-wrap">
        <div class="logo-icon">
            <i class="fa-solid fa-dollar-sign" style="color:#000;"></i>
        </div>
        <h1><?php echo e(config('app.name', 'SVRS Coin')); ?></h1>
        <p>Create your account</p>
    </div>

    <form id="registerForm">
        <?php echo csrf_field(); ?>

        
        <div class="sec-label">Personal Details</div>

        <div class="two-col">
            <div class="form-group">
                <label class="form-label">First Name *</label>
                <div class="input-wrap">
                    <i class="fa fa-user input-icon"></i>
                    <input type="text" name="first_name" class="input-field" placeholder="First name">
                </div>
                <span class="err-text first_name_error"></span>
            </div>
            <div class="form-group">
                <label class="form-label">Last Name *</label>
                <div class="input-wrap">
                    <i class="fa fa-user input-icon"></i>
                    <input type="text" name="last_name" class="input-field" placeholder="Last name">
                </div>
                <span class="err-text last_name_error"></span>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Email Address *</label>
            <div class="input-wrap">
                <i class="fa fa-envelope input-icon"></i>
                <input type="email" name="email" class="input-field" placeholder="you@example.com" autocomplete="email">
            </div>
            <span class="err-text email_error"></span>
        </div>

        <div class="form-group">
            <label class="form-label">Mobile Number *</label>
            <div class="input-wrap">
                <i class="fa fa-phone input-icon"></i>
                <input type="text" name="mobile" class="input-field" placeholder="10-digit mobile" maxlength="10">
            </div>
            <span class="err-text mobile_error"></span>
        </div>

        
        <div class="sec-label">Security</div>

        <div class="form-group">
            <label class="form-label">Password *</label>
            <div class="input-wrap">
                <i class="fa fa-lock input-icon"></i>
                <input type="password" name="password" id="pw1" class="input-field" placeholder="Min 6 characters">
                <button type="button" class="toggle-pw" onclick="tpw('pw1',this)"><i class="fa fa-eye-slash"></i></button>
            </div>
            <span class="err-text password_error"></span>
        </div>

        <div class="form-group">
            <label class="form-label">Confirm Password *</label>
            <div class="input-wrap">
                <i class="fa fa-lock input-icon"></i>
                <input type="password" name="password_confirmation" id="pw2" class="input-field" placeholder="Repeat password">
                <button type="button" class="toggle-pw" onclick="tpw('pw2',this)"><i class="fa fa-eye-slash"></i></button>
            </div>
        </div>

        
        <div class="sec-label">Referral / Sponsor</div>

        <?php if($sponsor): ?>
            <div class="form-group">
                <label class="form-label">Sponsor ID</label>
                <div class="input-wrap">
                    <i class="fa fa-users input-icon"></i>
                    <input type="text" class="input-field"
                        value="<?php echo e($sponsor->member_code); ?>" readonly
                        style="font-family:'Space Mono',monospace;color:var(--gold);padding-right:44px;">
                    <input type="hidden" name="sponsor_id" value="<?php echo e($sponsor->member_code); ?>">
                    <i class="fa fa-circle-check" style="position:absolute;right:14px;top:50%;transform:translateY(-50%);color:var(--green);font-size:16px;"></i>
                </div>
                <span class="suc-text">✔ Sponsor: <?php echo e($sponsor->first_name); ?> <?php echo e($sponsor->last_name); ?></span>
            </div>
        <?php else: ?>
            <div class="form-group">
                <label class="form-label">Sponsor ID <span style="color:var(--muted);font-weight:400;">(Optional)</span></label>
                <div class="input-wrap">
                    <i class="fa fa-users input-icon"></i>
                    <input type="text" name="sponsor_id" id="sponsorInput" class="input-field"
                        placeholder="Enter sponsor code"
                        style="font-family:'Space Mono',monospace;padding-right:44px;">
                    <span id="sponsorLoader" style="display:none;position:absolute;right:14px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:12px;">
                        <i class="fa fa-spinner fa-spin"></i>
                    </span>
                </div>
                <div id="sponsorPill" class="sponsor-pill"></div>
                <span class="err-text sponsor_id_error"></span>
            </div>
        <?php endif; ?>

        
        <button type="submit" class="btn-submit" id="submitBtn">
            <i class="fa fa-user-plus"></i>
            <span id="submitTxt">Create Account</span>
            <span id="submitSpin" style="display:none;" class="spin"></span>
        </button>

    </form>

    <div class="login-row">
        Already have an account? <a href="<?php echo e(route('admin.login')); ?>">Sign In</a>
    </div>

</div>
</div>

<script>
    toastr.options = { positionClass:'toast-top-center', timeOut:3500, closeButton:true };

    // Password toggle
    function tpw(id, btn) {
        var f = document.getElementById(id);
        var show = f.type === 'password';
        f.type = show ? 'text' : 'password';
        btn.innerHTML = show ? '<i class="fa fa-eye"></i>' : '<i class="fa fa-eye-slash"></i>';
    }

    // Sponsor check (only if not coming from referral link)
    <?php if(!$sponsor): ?>
        var _sTimer;
        $('#sponsorInput').on('input', function() {
            clearTimeout(_sTimer);
            var code = $(this).val().trim();
            var pill = document.getElementById('sponsorPill');
            pill.className = 'sponsor-pill';
            pill.textContent = '';

            if (!code) return;

            _sTimer = setTimeout(function() {
                document.getElementById('sponsorLoader').style.display = '';
                $.ajax({
                    url: "<?php echo e(route('front.check.sponsor')); ?>",
                    type: 'POST',
                    data: { sponsor_id: code, _token: "<?php echo e(csrf_token()); ?>" },
                    success: function(res) {
                        if (res.status) {
                            pill.className = 'sponsor-pill ok';
                            pill.innerHTML = '<i class="fa fa-circle-check"></i> Sponsor: ' + res.name;
                        } else {
                            pill.className = 'sponsor-pill bad';
                            pill.innerHTML = '<i class="fa fa-circle-xmark"></i> ' + (res.message || 'Invalid Sponsor ID');
                        }
                    },
                    complete: function() { 
                        document.getElementById('sponsorLoader').style.display = 'none'; 
                    }
                });
            }, 600);
        });
    <?php endif; ?>

    // Form Submit
    $('#registerForm').on('submit', function(e) {
        e.preventDefault();

        $('.err-text').text('');
        var btn = document.getElementById('submitBtn');
        btn.disabled = true;
        document.getElementById('submitTxt').style.display = 'none';
        document.getElementById('submitSpin').style.display = 'inline-block';

        $.ajax({
            url: "<?php echo e(route('front.register.user')); ?>",
            type: 'POST',
            data: new FormData(this),
            contentType: false,
            processData: false,
            headers: { 'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>" },
            success: function(res) {
                if (res.status) {
                    toastr.success(res.message || 'Registration Successful!');
                    document.getElementById('registerForm').reset();
                    
                    <?php if(!$sponsor): ?>
                        var pill = document.getElementById('sponsorPill');
                        if (pill) { 
                            pill.className = 'sponsor-pill'; 
                            pill.textContent = ''; 
                        }
                    <?php endif; ?>
                } else {
                    toastr.error(res.message || 'Something went wrong.');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    $.each(xhr.responseJSON.errors, function(k, v) {
                        $('.' + k + '_error').text(v[0]);
                    });
                    toastr.error('Please fix the errors below.');
                } else {
                    toastr.error('Something went wrong. Please try again.');
                }
            },
            complete: function() {
                btn.disabled = false;
                document.getElementById('submitTxt').style.display = '';
                document.getElementById('submitSpin').style.display = 'none';
            }
        });
    });
</script>
</body>
</html><?php /**PATH D:\Qubeta\svrs\resources\views/front/signup.blade.php ENDPATH**/ ?>