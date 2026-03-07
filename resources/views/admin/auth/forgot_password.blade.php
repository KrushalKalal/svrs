<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') . ' || Forgot Password' }}</title>
    <link rel="stylesheet" href="{{ asset('front/app/dist/app.css') }}">
    <link rel="stylesheet" href="{{ asset('front/app/dist/swiper-bundle.min.css') }}">
    <link rel="shortcut icon" href="{{ asset('front/assets/images/logo/favicon.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('front/assets/images/logo/favicon.png') }}">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <div class="preloader">
        <div class=" loader">
            <div class="square"></div>
            <div class="path">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
            <p class="text-load">Loading :</p>
        </div>
    </div>
    <div class="mouse-cursor cursor-outer"></div>
    <div class="mouse-cursor cursor-inner"></div>
    <section class="sign-in p-0">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-md-6">
                    <div class="sign-in__main" style="height: fit-content;">
                        <div class="top center">
                            <img id="site-logo" src="{{ asset('front/assets/images/logo/logo-main.png') }}"
                                alt="Monteno" width="165" height="40">
                            <p class="fs-17">Forgot Password?</p>
                        </div>
                        <form id="forgotPasswordForm">
                            @csrf
                            <div class="form-group mb-1">
                                <label>Email<span>*</span> </label>
                                <input type="email" class="form-control" name="email" id="email"
                                    placeholder="example@gmail.com">
                                <span class="text-danger email_error"></span>
                            </div>
                            <div class="mt-2">
                                <button type="submit" class="btn-action style-1" id="loginBtn">
                                    <span class="btn-text">Submit</span>
                                    <span class="spinner-border spinner-border-sm d-none btn-spinner"></span>
                                </button>
                            </div>
                        </form>
                        <div class="bottom center mt-3">
                            <p class="with">
                                Return to <a href="{{ route('admin.login') }}">Sign up</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
    <script src="{{ asset('front/app/js/jquery.min.js') }}"></script>
    <script src="{{ asset('front/app/js/jquery.easing.js') }}"></script>
    <script src="{{ asset('front/app/js/app.js') }}"></script>
    <script src="{{ asset('front/app/js/mouse.js') }}"></script>
</body>
<script>
    $(document).ready(function() {

        $('#forgotPasswordForm').on('submit', function(e) {
            e.preventDefault();

            // Clear old errors
            $('.email_error').text('');

            let email = $('#email').val();

            if (!email) {
                $('.email_error').text('Email field is required.');
                return;
            }

            let $btn = $('#loginBtn');

            // ✅ Show Loader
            $btn.prop('disabled', true);
            $btn.find('.btn-text').text('Checking...');
            $btn.find('.btn-spinner').removeClass('d-none');

            $.ajax({
                url: "{{ route('admin.forgot.password.check') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    email: email
                },

                success: function(res) {
                    if (res.exists) {
                        toastr.success('OTP sent successfully.');

                        window.location.href =
                            "{{ route('admin.otp.verification', ':id') }}"
                            .replace(':id', res.user_id);
                    } else {
                        $('.email_error').text('Email does not exist.');
                    }
                },

                error: function(xhr) {

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;

                        if (errors.email) {
                            $('.email_error').text(errors.email[0]);
                        }
                    } else {
                        toastr.error('Something went wrong.');
                    }
                },

                complete: function() {
                    // ✅ Hide Loader
                    $btn.prop('disabled', false);
                    $btn.find('.btn-text').text('Submit');
                    $btn.find('.btn-spinner').addClass('d-none');
                }
            });
        });

    });
</script>
</html>
