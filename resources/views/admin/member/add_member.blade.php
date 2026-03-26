@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Add New Member')

@section('content')
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Add New Member</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                        </li>
                        <li class="breadcrumb-item active">Add New Member</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            {{-- FORM --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="ti ti-user-plus me-2"></i>Register New Member</h4>
                    </div>
                    <div class="card-body">

                        {{-- Sponsor Info Box --}}
                        <div class="alert alert-secondary d-flex align-items-center mb-4">
                            <i class="ti ti-id-badge fs-5 me-2"></i>
                            <div>
                                <strong>Registering under:</strong>
                                {{ $authUser->first_name }} {{ $authUser->last_name }}
                                <span class="badge bg-dark ms-2">{{ $authUser->member_code }}</span>
                            </div>
                        </div>

                        <form id="addMemberForm" enctype="multipart/form-data">
                            @csrf

                            {{-- Hidden sponsor_id --}}
                            <input type="hidden" name="sponsor_id" value="{{ $authUser->member_code }}">

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control mb-0" placeholder="First Name">
                                    <small class="text-danger error-text first_name_error"></small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control mb-0" placeholder="Last Name">
                                    <small class="text-danger error-text last_name_error"></small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control mb-0" placeholder="Enter Email">
                                    <small class="text-danger error-text email_error"></small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                    <input type="text" name="mobile" class="form-control mb-0" maxlength="10" placeholder="10-digit mobile">
                                    <small class="text-danger error-text mobile_error"></small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="passwordField" class="form-control mb-0" placeholder="Min 6 characters">
                                        <span class="input-group-text toggle-password" data-target="passwordField" style="cursor:pointer;">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                    </div>
                                    <small class="text-danger error-text password_error"></small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" name="password_confirmation" id="confirmPassField" class="form-control mb-0" placeholder="Repeat password">
                                        <span class="input-group-text toggle-password" data-target="confirmPassField" style="cursor:pointer;">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Amount <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" id="amount" class="form-control mb-0"
                                        placeholder="Enter Amount"
                                        min="{{ $deposit->min_amount ?? 200 }}"
                                        max="{{ $deposit->max_amount ?? 2000 }}">
                                    <small class="text-muted">Min ₹{{ $deposit->min_amount ?? 200 }}, Max ₹{{ $deposit->max_amount ?? 2000 }}</small>
                                    <small class="text-danger error-text amount_error"></small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Payment Attachment <span class="text-danger">*</span></label>
                                    <input type="file" name="attachment" class="form-control mb-0" accept="image/*" id="screenshotInput">
                                    <small class="text-danger error-text attachment_error"></small>
                                    <div id="screenshotPreview" class="mt-2 d-none">
                                        <img id="previewImg" src="" alt="Preview" style="max-height:120px;border-radius:6px;border:1px solid #ddd;">
                                    </div>
                                </div>

                            </div>

                            <div class="d-flex justify-content-between align-center mt-2">
                                <div>
                                    <a href="{{ route('admin.member.list') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                                </div>
                                <div>
                                    <button type="submit" id="submitBtn" class="btn btn-primary px-5">
                                        <span class="btn-text"><i class="ti ti-user-plus me-1"></i>Register Member</span>
                                        <span class="btn-loader d-none">
                                            <span class="spinner-border spinner-border-sm me-1"></span>Registering...
                                        </span>
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>

                {{-- Success flash --}}
                @if(session('new_member'))
                    @php $nm = session('new_member'); @endphp
                    <div class="card border-success mt-3">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="ti ti-circle-check me-2"></i>Member Registered Successfully</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <p class="text-muted mb-1">Member Code</p>
                                    <h5 class="text-primary">{{ $nm['member_code'] }}</h5>
                                </div>
                                <div class="col-md-4">
                                    <p class="text-muted mb-1">Name</p>
                                    <h5>{{ $nm['name'] }}</h5>
                                </div>
                                <div class="col-md-4">
                                    <p class="text-muted mb-1">Status</p>
                                    <span class="badge bg-warning text-dark fs-6">Pending Activation</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- PAYMENT INFO --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Payment Information</h4>
                    </div>
                    <div class="card-body text-center">
                        @if(isset($contact) && $contact->qr_image)
                            <img src="{{ asset($contact->qr_image) }}" alt="QR Code" class="img-fluid mb-3" style="max-width:200px;">
                            <p class="text-muted">Scan to pay</p>
                        @endif
                        <div class="text-start mt-3">
                            <h6>Bank Transfer</h6>
                            <ul class="list-unstyled text-dark">
                                <li><strong>Bank:</strong> {{ $contact->bank ?? '' }}</li>
                                <li><strong>Account Name:</strong> {{ $contact->account_name ?? '' }}</li>
                                <li><strong>Account Number:</strong> {{ $contact->account_number ?? '' }}</li>
                                <li><strong>IFSC:</strong> {{ $contact->ifsc_code ?? '' }}</li>
                                <li><strong>Branch:</strong> {{ $contact->branch ?? '' }}</li>
                            </ul>
                            @if($deposit)
                                <div class="alert alert-info py-2 mt-2">
                                    <strong>Note:</strong> Min ₹{{ $deposit->min_amount }}
                                    @if($deposit->max_amount) | Max ₹{{ $deposit->max_amount }} @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
    // Toggle password
    $(document).on('click', '.toggle-password', function () {
        let targetId = $(this).data('target');
        let input = $('#' + targetId);
        let icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Screenshot preview
    $('#screenshotInput').on('change', function () {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#previewImg').attr('src', e.target.result);
                $('#screenshotPreview').removeClass('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    // Amount validation
    let minAmount = {{ $deposit->min_amount ?? 200 }};
    let maxAmount = {{ $deposit->max_amount ?? 2000 }};
    $('#amount').on('keyup change', function () {
        let val = parseFloat($(this).val());
        if (val < minAmount) {
            $('.amount_error').text('Minimum deposit is ₹' + minAmount);
        } else if (maxAmount && val > maxAmount) {
            $('.amount_error').text('Maximum deposit is ₹' + maxAmount);
        } else {
            $('.amount_error').text('');
        }
    });

    // Form submit
    $('#addMemberForm').on('submit', function (e) {
        e.preventDefault();
        $('.error-text').text('');
        var formData = new FormData(this);
        $('#submitBtn .btn-text').addClass('d-none');
        $('#submitBtn .btn-loader').removeClass('d-none');
        $('#submitBtn').prop('disabled', true);

        $.ajax({
            url: "{{ route('admin.add.member.store') }}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (res) {
                if (res.status) {
                    toastr.success(res.message ?? 'Member registered successfully!');
                    setTimeout(function () { location.reload(); }, 1500);
                } else {
                    toastr.error(res.message ?? 'Something went wrong.');
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    $.each(xhr.responseJSON.errors, function (field, messages) {
                        $('.' + field + '_error').text(messages[0]);
                    });
                    toastr.error('Please fix validation errors.');
                } else {
                    toastr.error('Server error. Please try again.');
                }
            },
            complete: function () {
                $('#submitBtn .btn-text').removeClass('d-none');
                $('#submitBtn .btn-loader').addClass('d-none');
                $('#submitBtn').prop('disabled', false);
            }
        });
    });
    </script>
@endsection