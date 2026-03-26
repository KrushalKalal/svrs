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
                            <a href="{{ route('member.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                        </li>
                        <li class="breadcrumb-item active">Add New Member</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="ti ti-user-plus me-2"></i>Register New Member</h4>
                    </div>
                    <div class="card-body">

                        {{-- Member info alert --}}
                        <div class="alert alert-info d-flex align-items-start mb-4">
                            <i class="ti ti-info-circle fs-5 me-2 mt-1"></i>
                            <div>
                                <strong>Registering under your account.</strong><br>
                                New member will be placed under your Sponsor ID:
                                <span class="badge bg-primary ms-1">{{ auth('admin')->user()->member_code }}</span>
                                @if(auth('admin')->user()->is_refer_member)
                                    <br><small class="text-success mt-1 d-block">
                                        <i class="ti ti-coins me-1"></i>
                                        You will earn referral rewards when this member activates and buys coins.
                                    </small>
                                @endif
                            </div>
                        </div>

                        <form id="addMemberForm" enctype="multipart/form-data">
                            @csrf

                            {{-- Sponsor ID - pre-filled readonly --}}
                            <input type="hidden" name="sponsor_id" value="{{ auth('admin')->user()->member_code }}">

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Sponsor ID <span
                                            class="badge bg-secondary ms-1">Auto-filled</span></label>
                                    <input type="text" class="form-control bg-light fw-bold"
                                        value="{{ auth('admin')->user()->member_code }}" readonly>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control" placeholder="First Name">
                                    <small class="text-danger error-text first_name_error"></small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control" placeholder="Last Name">
                                    <small class="text-danger error-text last_name_error"></small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                    <input type="text" name="mobile" class="form-control" maxlength="10"
                                        placeholder="10-digit mobile">
                                    <small class="text-danger error-text mobile_error"></small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" placeholder="email@example.com">
                                    <small class="text-danger error-text email_error"></small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="passwordField" class="form-control"
                                            placeholder="Min 6 characters">
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePass('passwordField', this)">
                                            <i class="fe fe-eye"></i>
                                        </button>
                                    </div>
                                    <small class="text-danger error-text password_error"></small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" name="password_confirmation" id="confirmPassField"
                                            class="form-control" placeholder="Repeat password">
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePass('confirmPassField', this)">
                                            <i class="fe fe-eye"></i>
                                        </button>
                                    </div>
                                    <small class="text-danger error-text password_confirmation_error"></small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Deposit Amount (₹) <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" id="amount" class="form-control"
                                        min="{{ $deposit->min_amount ?? 200 }}" max="{{ $deposit->max_amount ?? 2000 }}"
                                        placeholder="₹{{ $deposit->min_amount ?? 200 }} - ₹{{ $deposit->max_amount ?? 2000 }}">
                                    <small class="text-muted">Min ₹{{ $deposit->min_amount ?? 200 }}, Max
                                        ₹{{ $deposit->max_amount ?? 2000 }}</small>
                                    <small class="text-danger error-text amount_error"></small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Payment Screenshot <span class="text-danger">*</span></label>
                                    <input type="file" name="attachment" class="form-control" accept="image/*"
                                        id="screenshotInput">
                                    <small class="text-danger error-text attachment_error"></small>
                                    <div id="screenshotPreview" class="mt-2 d-none">
                                        <img id="previewImg" src="" alt="Preview"
                                            style="max-height:120px;border-radius:6px;border:1px solid #ddd;">
                                    </div>
                                </div>

                            </div>

                            <div class="d-flex gap-2 mt-2">
                                <button type="submit" class="btn btn-primary px-5" id="submitBtn">
                                    <span class="btn-text"><i class="ti ti-user-plus me-1"></i>Register Member</span>
                                    <span class="btn-loader d-none"><span
                                            class="spinner-border spinner-border-sm me-1"></span>Registering...</span>
                                </button>
                                <a href="{{ route('member.dashboard') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            {{-- Payment Info --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Payment Information</h4>
                    </div>
                    <div class="card-body text-center">
                        @if(isset($contact) && $contact->qr_image)
                            <img src="{{ asset($contact->qr_image) }}" alt="QR Code" class="img-fluid mb-3"
                                style="max-width:200px;">
                            <p class="text-muted">Scan to pay</p>
                        @endif
                        <div class="text-start mt-3">
                            <h6>Bank Transfer</h6>
                            <ul class="list-unstyled">
                                <li><strong>Bank:</strong> {{ $contact->bank ?? '' }}</li>
                                <li><strong>Account Name:</strong> {{ $contact->account_name ?? '' }}</li>
                                <li><strong>Account Number:</strong> {{ $contact->account_number ?? '' }}</li>
                                <li><strong>IFSC:</strong> {{ $contact->ifsc_code ?? '' }}</li>
                                <li><strong>Branch:</strong> {{ $contact->branch ?? '' }}</li>
                            </ul>
                            @if($deposit)
                                <div class="alert alert-info py-2">
                                    <strong>Note:</strong> Min ₹{{ $deposit->min_amount }}
                                    @if($deposit->max_amount) | Max ₹{{ $deposit->max_amount }} @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('new_member'))
            @php $nm = session('new_member'); @endphp
            <div class="card border-success mt-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="ti ti-circle-check me-2"></i>Member Registered Successfully</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Member Code</p>
                            <h5 class="text-primary">{{ $nm['member_code'] }}</h5>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Name</p>
                            <h5>{{ $nm['name'] }}</h5>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Mobile</p>
                            <h5>{{ $nm['mobile'] }}</h5>
                        </div>
                        <div class="col-md-3">
                            <p class="text-muted mb-1">Status</p>
                            <span class="badge bg-warning text-dark fs-6">Pending Activation</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        function togglePass(fieldId, btn) {
            var f = document.getElementById(fieldId);
            if (f.type === 'password') {
                f.type = 'text';
                btn.innerHTML = '<i class="fe fe-eye-off"></i>';
            } else {
                f.type = 'password';
                btn.innerHTML = '<i class="fe fe-eye"></i>';
            }
        }

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

        $('#addMemberForm').on('submit', function (e) {
            e.preventDefault();
            $('.error-text').text('');
            var formData = new FormData(this);
            $('#submitBtn .btn-text').addClass('d-none');
            $('#submitBtn .btn-loader').removeClass('d-none');
            $('#submitBtn').prop('disabled', true);

            $.ajax({
                url: "{{ route('member.add.member.store') }}",
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