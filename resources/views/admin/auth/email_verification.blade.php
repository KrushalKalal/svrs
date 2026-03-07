<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') . ' || Email Verification' }}</title>
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
                            <h6 class="title">Email OTP Verification</h6>
                            <p class="fs-17">Please enter the OTP received to confirm your account ownership. A code
                                has been send to {{ substr($user->email, 0, 5) . '****' . strstr($user->email, '@') }}
                            </p>
                        </div>
                        <form id="otpForm">
                            @csrf
                            <div class="d-flex align-items-center mb-3 otp-group">
                                <input type="text" inputmode="numeric" autocomplete="one-time-code"
                                    class="form-control otp-input-box rounded w-100 py-sm-3 py-2 text-center fs-26 fw-bold me-3"
                                    id="digit-1" data-next="digit-2" maxlength="1">

                                <input type="text" inputmode="numeric" autocomplete="one-time-code"
                                    class="form-control otp-input-box rounded w-100 py-sm-3 py-2 text-center fs-26 fw-bold me-3"
                                    id="digit-2" data-next="digit-3" data-previous="digit-1" maxlength="1">

                                <input type="text" inputmode="numeric" autocomplete="one-time-code"
                                    class="form-control otp-input-box rounded w-100 py-sm-3 py-2 text-center fs-26 fw-bold me-3"
                                    id="digit-3" data-next="digit-4" data-previous="digit-2" maxlength="1">

                                <input type="text" inputmode="numeric" autocomplete="one-time-code"
                                    class="form-control otp-input-box rounded w-100 py-sm-3 py-2 text-center fs-26 fw-bold me-3"
                                    id="digit-4" data-next="digit-5" data-previous="digit-3" maxlength="1">

                                <input type="text" inputmode="numeric" autocomplete="one-time-code"
                                    class="form-control otp-input-box rounded w-100 py-sm-3 py-2 text-center fs-26 fw-bold me-3"
                                    id="digit-5" data-next="digit-6" data-previous="digit-4" maxlength="1">

                                <input type="text" inputmode="numeric" autocomplete="one-time-code"
                                    class="form-control otp-input-box rounded w-100 py-sm-3 py-2 text-center fs-26 fw-bold me-3"
                                    id="digit-6" data-previous="digit-5" maxlength="1">
                            </div>
                            <div class="text-danger text-center mt-2 otp_error"></div>
                            <div class="mt-2">
                                <button type="submit" class="btn-action style-1" id="loginBtn">
                                    <span class="btn-text">Verify & Proceed</span>
                                    <span class="spinner-border spinner-border-sm d-none btn-spinner"></span>
                                </button>
                            </div>
                        </form>
                        <div class="bottom center mt-3">
                            <p class="with">Didn't get the OTP? <a href="javascript:void(0);" id="resendOtp"
                                    class="text-primary">Resend OTP</a></p>
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

        /* ---------------- OTP INPUT AUTO MOVE ---------------- */
        $('.otp-input-box').on('keyup', function(e) {

            let input = $(this);

            if (input.val().length === 1) {
                let next = input.data('next');
                if (next) $('#' + next).focus();
            }

            if (e.key === "Backspace") {
                let prev = input.data('previous');
                if (prev) $('#' + prev).focus();
            }
        });


        /* ---------------- OTP FORM SUBMIT ---------------- */
        $('#otpForm').on('submit', function(e) {
            e.preventDefault();

            $('.otp_error').text('');

            // Collect OTP
            let otp = '';
            let isEmpty = false;

            $('.otp-input-box').each(function() {
                let val = $(this).val().trim();

                if (val === '') {
                    isEmpty = true;
                }

                otp += val;
            });

            // ✅ Correct Validation
            if (isEmpty || otp.length !== 6) {
                $('.otp_error').text('Please enter complete 6 digit OTP.');
                return;
            }


            let $btn = $('#loginBtn');

            // ✅ SHOW BUTTON LOADER
            $btn.prop('disabled', true);
            $btn.find('.btn-text').text('Verifying...');
            $btn.find('.btn-spinner').removeClass('d-none');

            $.ajax({
                url: "{{ route('admin.verify.otp') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    email: '{{ $user->email }}',
                    otp: otp
                },

                success: function(response) {

                    if (response.success) {
                        toastr.success(response.message);
                        window.location.href = response.redirect_url;
                    } else {
                        $('.otp_error').text(response.message);
                    }
                },

                error: function(xhr) {

                    if (xhr.status === 422) {
                        $('.otp_error').text('Invalid OTP. Please try again.');
                    } else {
                        $('.otp_error').text('Something went wrong.');
                    }
                },

                complete: function() {
                    // ✅ HIDE BUTTON LOADER
                    $btn.prop('disabled', false);
                    $btn.find('.btn-text').text('Verify & Proceed');
                    $btn.find('.btn-spinner').addClass('d-none');
                }
            });
        });


        /* ---------------- RESEND OTP ---------------- */
        $('#resendOtp').on('click', function() {

            let $link = $(this);
            $('.otp_error').text('');

            $link.text('Sending...').css('pointer-events', 'none');

            $.ajax({
                url: "{{ route('admin.resend.otp') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    email: '{{ $user->email }}'
                },

                success: function(response) {
                    if (response.success) {
                        toastr.success('OTP Resent Successfully');
                    } else {
                        $('.otp_error').text(response.message);
                    }
                },

                error: function() {
                    $('.otp_error').text('Failed to resend OTP.');
                },

                complete: function() {
                    $link.text('Resend OTP').css('pointer-events', 'auto');
                }
            });
        });

    });
</script>

</html>
