<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') . ' || Reset Password' }}</title>
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
                            <p class="fs-17">Reset Password</p>
                        </div>
                        <form id="resetPasswordForm">
                            @csrf
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control mb-0"
                                        placeholder="Enter Password">
                                    <span class="input-group-text toggle-password" data-target="password">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="confirm_password" id="confirm_password"
                                        class="form-control mb-0" placeholder="Confirm Password">
                                    <span class="input-group-text toggle-password" data-target="confirm_password">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                            <div id="formError" class="alert alert-danger d-none"></div>
                            <div class="mt-2">
                                <button type="submit" class="btn-action style-1 w-100" id="resetBtn">
                                    <span class="btn-text">Reset Password</span>
                                    <span class="spinner-border spinner-border-sm d-none btn-spinner"></span>
                                </button>
                            </div>
                        </form>
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

        /* ---------- SHOW / HIDE PASSWORD ---------- */
        $(".toggle-password").on("click", function() {
            let target = $(this).data("target");
            let input = $("#" + target);
            let icon = $(this).find("i");

            if (input.attr("type") === "password") {
                input.attr("type", "text");
                icon.removeClass("fa-eye").addClass("fa-eye-slash");
            } else {
                input.attr("type", "password");
                icon.removeClass("fa-eye-slash").addClass("fa-eye");
            }
        });

        /* ---------- FORM SUBMIT ---------- */
        $("#resetPasswordForm").on("submit", function(e) {
            e.preventDefault();

            let errorBox = $("#formError");
            errorBox.addClass("d-none").html("");

            let password = $("#password").val().trim();
            let confirm = $("#confirm_password").val().trim();

            /* ---------- FRONT VALIDATION ---------- */

            if (password === "" || confirm === "") {
                showError("All fields are required.");
                return;
            }

            if (password.length < 6) {
                showError("Password must be at least 6 characters.");
                return;
            }

            if (password !== confirm) {
                showError("Passwords do not match.");
                return;
            }

            /* ---------- BUTTON LOADER ---------- */
            let $btn = $("#resetBtn");
            $btn.prop("disabled", true);
            $btn.find(".btn-text").text("Processing...");
            $btn.find(".btn-spinner").removeClass("d-none");

            $.ajax({
                url: "{{ route('admin.reset.password.submit') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    password: password,
                    confirm_password: confirm,
                    email: "{{ $user->email }}"
                },

                success: function(res) {
                    if (res.success) {
                        window.location.href = res.redirect_url;
                    } else {
                        showError(res.message || "Unable to reset password.");
                    }
                },

                error: function(xhr) {

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;

                        // Show ONLY FIRST validation error
                        let firstError = Object.values(errors)[0][0];
                        showError(firstError);
                    } else {
                        showError("Something went wrong. Please try again.");
                    }
                },

                complete: function() {
                    $btn.prop("disabled", false);
                    $btn.find(".btn-text").text("Reset Password");
                    $btn.find(".btn-spinner").addClass("d-none");
                }
            });

            /* ---------- COMMON ERROR FUNCTION ---------- */
            function showError(message) {
                errorBox.removeClass("d-none").html(message);
            }
        });

    });
</script>

</html>
