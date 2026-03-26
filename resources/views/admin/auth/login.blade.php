<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') . ' || Login' }}</title>
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
                            <p class="fs-17">Sign in</p>
                        </div>
                        <form id="adminLoginForm">
                            @csrf
                            <div class="form-group">
                                <label>Email<span>*</span> </label>
                                <input type="email" class="form-control" name="email" placeholder="example@gmail.com">
                                <span class="text-danger error-email"></span>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control mb-0"
                                        placeholder="Enter Password">
                                    <span class="input-group-text toggle-password"><i class="fa fa-eye"></i></span>
                                </div>
                                <span class="text-danger error-password"></span>
                            </div>
                            <div class="form-check">
                                <div class="left">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                    <label class="form-check-label" for="exampleCheck1">Remember me</label>
                                </div>
                                <p>
                                    <a href="{{ route('admin.forgot.password') }}" class="link-danger">Forgot
                                        Password?</a>
                                </p>
                            </div>
                            <button type="submit" class="btn-action style-1" id="loginBtn">
                                <span>Sign In</span>
                                <span class="spinner-border spinner-border-sm d-none btn-spinner"></span>
                            </button>
                        </form>
                        <div class="bottom center mt-3">
                            <p class="with">
                                Dont have an account ? <a href="{{ route('front.sign.up') }}">Sign up</a>
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
    $(document).on('click', '.toggle-password', function () {

        let input = $(this).closest('.input-group').find('input');
        let icon = $(this).find('i');

        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
</script>
<script>
    $(document).ready(function () {

        $("#adminLoginForm").on('submit', function (e) {
            e.preventDefault();

            // Reset errors
            $(".error-email").text("");
            $(".error-password").text("");
            $("input").removeClass("is-invalid");

            let email = $("input[name=email]").val().trim();
            let password = $("input[name=password]").val().trim();
            let hasError = false;

            // Frontend validation
            if (email === "") {
                $(".error-email").text("Email is required");
                $("input[name=email]").addClass("is-invalid");
                hasError = true;
            } else if (!/^\S+@\S+\.\S+$/.test(email)) {
                $(".error-email").text("Enter a valid email");
                $("input[name=email]").addClass("is-invalid");
                hasError = true;
            }

            if (password === "") {
                $(".error-password").text("Password is required");
                $("input[name=password]").addClass("is-invalid");
                hasError = true;
            }

            if (hasError) return; // Stop submit if validation fails

            let formData = $(this).serialize();

            // Button Loader
            $("#loginBtn .btn-text").addClass("d-none");
            $("#loginBtn .btn-spinner").removeClass("d-none");
            $("#loginBtn").prop("disabled", true);

            $.ajax({
                url: "{{ route('admin.login.submit') }}",
                type: "POST",
                data: formData,
                success: function (response) {
                    if (response.status === true) {
                        toastr.success("Login successful!");
                        window.location.href = response.redirect_url;
                    } else {
                        toastr.error(response.message ?? "Something went wrong!");
                    }
                },
                error: function (xhr) {

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;

                        if (errors.email) {
                            $(".error-email").text(errors.email[0]);
                            $("input[name=email]").addClass("is-invalid");
                            toastr.error(errors.email[0]);
                        }
                        if (errors.password) {
                            $(".error-password").text(errors.password[0]);
                            $("input[name=password]").addClass("is-invalid");
                            toastr.error(errors.password[0]);
                        }
                    } else if (xhr.status === 401) {
                        toastr.error("Invalid credentials!");
                    } else {
                        toastr.error("Login failed, try again.");
                    }
                },
                complete: function () {
                    $("#loginBtn .btn-text").removeClass("d-none");
                    $("#loginBtn .btn-spinner").addClass("d-none");
                    $("#loginBtn").prop("disabled", false);
                }
            });
        });

    });
</script>

</html>