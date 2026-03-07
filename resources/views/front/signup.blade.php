<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} | @yield('title', 'Sign up') </title>
    <link rel="stylesheet" href="{{ asset('front/app/dist/app.css') }}">
    <link rel="stylesheet" href="{{ asset('front/app/dist/swiper-bundle.min.css') }}">
    <link rel="shortcut icon" href="{{ asset('front/assets/images/logo/favicon.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('front/assets/images/logo/favicon.png') }}">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<style>
    .qr-placeholder {
        max-width: 200px;
        margin-bottom: 20px;
    }
</style>

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

    <section class="sign-in sign-up p-0">
        <div class="container">
            <div class="row">
                <div class="row">
                    <div class="col-md-6">
                        <div class="sign-in__main">
                            <div class="top center">
                                <img id="site-logo" src="{{ asset('front/assets/images/logo/logo-main.png') }}"
                                    alt="Monteno" width="165" height="40">
                                <p class="fs-17">Sign up</p>
                            </div>
                            <form id="registerForm" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" name="first_name" class="form-control mb-0"
                                            placeholder="Enter First Name">
                                        <small class="text-danger error-text first_name_error"></small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" name="last_name" class="form-control mb-0"
                                            placeholder="Enter Last Name">
                                        <small class="text-danger error-text last_name_error"></small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control mb-0"
                                            placeholder="Enter Email">
                                        <small class="text-danger error-text email_error"></small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Mobile Number <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="mobile" class="form-control mb-0"
                                            placeholder="Enter Mobile Number">
                                        <small class="text-danger error-text mobile_error"></small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="password" id="password"
                                                class="form-control mb-0" placeholder="Enter Password">
                                            <span class="input-group-text toggle-password"><i
                                                    class="fa fa-eye"></i></span>
                                        </div>
                                        <small class="text-danger error-text password_error"></small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Confirm Password <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" name="password_confirmation" id="confirm_password"
                                                class="form-control mb-0" placeholder="Confirm Password">
                                            <span class="input-group-text toggle-password"><i
                                                    class="fa fa-eye"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Sponsor ID <span class="text-danger">*</span></label>
                                        <input type="text" name="sponsor_id" class="form-control mb-0"
                                            placeholder="Enter Sponsor ID">
                                        <small class="text-danger error-text sponsor_id_error"></small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Amount <span class="text-danger">*</span></label>
                                        <input type="number" name="amount" class="form-control mb-0"
                                            placeholder="Enter Amount" min="{{ $depositsetting->min_amount ?? 0 }}"
                                            max="{{ $depositsetting->max_amount ?? '' }}" id="amount">
                                        <small class="text-danger error-text amount_error"></small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Payment Attachment <span
                                                class="text-danger">*</span></label>
                                        <input type="file" name="attachment" class="form-control mb-0">
                                        <small class="text-danger error-text attachment_error"></small>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-center">
                                    <div class="bottom center">
                                        <p class="with">
                                            Already have an account ? <a href="{{ route('admin.login') }}">Login</a>
                                        </p>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" id="signupBtn" class="btn-action style-1 w-auto">
                                            <span class="btn-text">Sign Up</span>
                                            <span class="btn-loader" style="display:none;">
                                                <i class="fa fa-spinner fa-spin"></i> Processing...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="sign-in__main">
                            <div class="top center">
                                <h5 class="text-dark">Payment Information</h5>
                            </div>
                            <div class="text-center">
                                <img src="{{ asset($contact->qr_image) }}" alt="QR Code"
                                    class="qr-placeholder img-fluid">
                                <p class="text-muted">Scan to pay</p>
                            </div>
                            <!-- Bank Details -->
                            <div class="bank-details mt-4">
                                <h5>Bank Transfer</h5>
                                <ul class="list-unstyled text-black">
                                    <li><strong>Bank:</strong> {{ $contact->bank ?? '' }}</li>
                                    <li><strong>Account Name:</strong> {{ $contact->account_name ?? '' }}</li>
                                    <li><strong>Account Number:</strong> {{ $contact->account_number ?? '' }}</li>
                                    <li><strong>IFSC Code:</strong> {{ $contact->ifsc_code ?? '' }}</li>
                                    <li><strong>Branch:</strong> {{ $contact->branch ?? '' }}</li>
                                </ul>
                                @if ($depositsetting)
                                    <div class="alert alert-info py-2 mt-3">
                                        <strong>Note:</strong>
                                        Minimum Deposit : ₹{{ $depositsetting->min_amount }}
                                        @if ($depositsetting->max_amount)
                                            | Maximum Deposit : ₹{{ $depositsetting->max_amount }}
                                        @endif
                                    </div>
                                @endif
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
    let minAmount = {{ $depositsetting->min_amount ?? 0 }};
    let maxAmount = {{ $depositsetting->max_amount ?? 'null' }};

    $('#amount').on('keyup change', function() {

        let amount = parseFloat($(this).val());

        if (amount < minAmount) {
            $('.amount_error').text('Minimum deposit is ₹' + minAmount);
        } else if (maxAmount && amount > maxAmount) {
            $('.amount_error').text('Maximum deposit is ₹' + maxAmount);
        } else {
            $('.amount_error').text('');
        }
    });
</script>
<script>
    $(document).on('click', '.toggle-password', function() {

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
    $('input[name="sponsor_id"]').on('blur', function() {

        let sponsorId = $(this).val().trim();

        if (sponsorId === '') {
            $('.sponsor_id_error').text('');
            return;
        }

        $.ajax({
            url: "{{ route('front.check.sponsor') }}",
            type: "POST",
            data: {
                sponsor_id: sponsorId,
                _token: "{{ csrf_token() }}"
            },
            success: function(res) {
                if (res.status) {
                    $('.sponsor_id_error')
                        .removeClass('text-danger')
                        .addClass('text-success')
                        .text('✔ Sponsor Found : ' + res.name);
                } else {
                    $('.sponsor_id_error')
                        .removeClass('text-success')
                        .addClass('text-danger')
                        .text('✖ Invalid Sponsor ID');
                }
            }
        });

    });
</script>
<script>
    $('#registerForm').on('submit', function(e) {
        e.preventDefault();

        let amount = parseFloat($('#amount').val());

        if (amount < minAmount) {
            $('.amount_error').text('Minimum deposit is ₹' + minAmount);
            toastr.error('Invalid Deposit Amount');
            return;
        }

        if (maxAmount && amount > maxAmount) {
            $('.amount_error').text('Maximum deposit is ₹' + maxAmount);
            toastr.error('Invalid Deposit Amount');
            return;
        }

        let formData = new FormData(this);

        $('.error-text').text('');

        $.ajax({
            url: "{{ route('front.register.user') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },

            beforeSend: function() {
                $('#signupBtn').prop('disabled', true);
                $('#signupBtn .btn-text').hide();
                $('#signupBtn .btn-loader').show();
            },

            success: function(res) {
                if (res.status) {
                    toastr.success(res.message);
                    $('#registerForm')[0].reset();
                    $('.sponsor_id_error').text('');
                }
            },

            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('.' + key + '_error').text(value[0]);
                    });
                    toastr.error("Please Fix Validation Errors");
                }
            },

            complete: function() {
                $('#signupBtn').prop('disabled', false);
                $('#signupBtn .btn-text').show();
                $('#signupBtn .btn-loader').hide();
            }
        });
    });
</script>

</html>
