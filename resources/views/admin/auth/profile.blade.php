@extends('admin.layout.main-layout')

@section('title', config('app.name') . ' || Profile')

@section('content')
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Profile</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">Pages</li>
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="border-bottom mb-3">
                    <ul class="nav nav-tabs mb-3" id="profileTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#kycTab" type="button">
                                KYC Details
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#bankTab" type="button">
                                Banking Details
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#passwordTab" type="button">
                                Change Password
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="kycTab">
                        <h6 class="mb-3">Basic Information</h6>
                        <form id="adminProfileForm" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <div class="row mb-3">
                                <div class="col-md-12 d-flex align-items-center bg-light rounded p-3">
                                    <div class="avatar avatar-xxl rounded-circle border border-dashed me-3 text-dark">
                                        @if (auth()->user()->profile_image)
                                            <img src="{{ asset(auth()->user()->profile_image) }}" class="rounded-circle"
                                                width="100">
                                        @else
                                            <i class="ti ti-user fs-16"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <label class="form-label">Profile Photo</label>
                                        <input type="file" name="photo" class="form-control">
                                        <small class="text-muted">Recommended size: 150x150</small><br>
                                        @if (auth()->user()->role)
                                            <span class="badge bg-primary text-uppercase">
                                                {{ auth()->user()->role }}
                                            </span>
                                        @endif
                                        <small class="text-danger error-text photo_error"></small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="first_name" class="form-control"
                                        value="{{ auth()->user()->first_name }}">
                                    <small class="text-danger error-text first_name_error"></small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name" class="form-control"
                                        value="{{ auth()->user()->last_name }}">
                                    <small class="text-danger error-text last_name_error"></small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ auth()->user()->email }}" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Mobile</label>
                                    <input type="text" name="mobile" class="form-control"
                                        value="{{ auth()->user()->mobile }}" readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Member ID</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->member_code }}"
                                        readonly>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Joining Date</label>
                                    <input type="text" class="form-control"
                                        value="{{ auth()->user()->created_at->format('d M Y') }}" readonly>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary" id="saveBtn">
                                    <span class="btn-text">Update Profile</span>
                                    <span class="spinner-border spinner-border-sm d-none btn-spinner"></span>
                                </button>
                            </div>
                        </form>
                    </div>                   
                    <div class="tab-pane fade" id="bankTab">
                        <h6 class="mb-3">Bank Information</h6>
                    
                        <form id="bankForm">
                            @csrf
                    
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Account Holder Name</label>
                                    <input type="text" name="account_name" class="form-control" value="{{ $bankdetail->account_holder_name ?? '' }}">
                                    <small class="text-danger error-text account_name_error"></small>
                                </div>
                    
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Account Number</label>
                                    <input type="text" name="account_number" class="form-control" value="{{ $bankdetail->account_number ?? '' }}">
                                    <small class="text-danger error-text account_number_error"></small>
                                </div>
                    
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">IFSC Code</label>
                                    <input type="text" name="ifsc_code" class="form-control" value="{{ $bankdetail->ifsc_code ?? '' }}">
                                    <small class="text-danger error-text ifsc_code_error"></small>
                                </div>
                    
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Bank Name</label>
                                    <input type="text" name="bank_name" class="form-control" value="{{ $bankdetail->ifsc_code ?? '' }}">
                                    <small class="text-danger error-text bank_name_error"></small>
                                </div>
                    
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Branch</label>
                                    <input type="text" name="branch_name" class="form-control" value="{{ $bankdetail->branch_name ?? '' }}">
                                    <small class="text-danger error-text branch_name_error"></small>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">UPI ID</label>
                                    <input type="text" name="upi" class="form-control" value="{{ $bankdetail->upi ?? '' }}">
                                    <small class="text-danger error-text upi_error"></small>
                                </div>
                            </div>
                    
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary" id="bankBtn">
                                    <span class="btn-text">Save Bank Details</span>
                                    <span class="spinner-border spinner-border-sm d-none btn-loader"></span>
                                </button>
                            </div>
                        </form>
                    </div>                    
                    <div class="tab-pane fade" id="passwordTab">
                        <form id="changePasswordForm">
                            @csrf
                            <h6 class="mb-3">Change Password</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" name="current_password" class="form-control">
                                    <small class="text-danger error-text current_password_error"></small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">New Password</label>
                                    <input type="password" name="new_password" class="form-control">
                                    <small class="text-danger error-text new_password_error"></small>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="new_password_confirmation" class="form-control">
                                    <small class="text-danger error-text new_password_confirmation_error"></small>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary" id="passwordBtn">
                                    <span class="btn-text">Update Password</span>
                                    <span class="spinner-border spinner-border-sm d-none btn-spinner"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {

            $("#adminProfileForm").on("submit", function(e) {
                e.preventDefault();

                // Clear old errors
                $(".error-text").text('');
                $(".form-control").removeClass('is-invalid');

                let formData = new FormData(this);

                let $btn = $("#saveBtn");

                // Show Loader
                $btn.prop("disabled", true);
                $btn.find(".btn-text").text("Updating...");
                $btn.find(".btn-spinner").removeClass("d-none");

                $.ajax({
                    url: "{{ route('admin.profile.update') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: function(res) {

                        if (res.status === true) {
                            toastr.success(res.message);
                        } else {
                            toastr.error(res.message);
                        }
                    },

                    error: function(xhr) {

                        if (xhr.status === 422) {

                            let errors = xhr.responseJSON.errors;

                            $.each(errors, function(key, value) {

                                // Show error text
                                $("." + key + "_error").text(value[0]);

                                // Highlight input
                                $("[name=" + key + "]").addClass("is-invalid");
                            });

                        } else {
                            toastr.error("Something went wrong!");
                        }
                    },

                    complete: function() {

                        // Hide Loader
                        $btn.prop("disabled", false);
                        $btn.find(".btn-text").text("Update Profile");
                        $btn.find(".btn-spinner").addClass("d-none");
                    }
                });
            });

        });
    </script>
    <script>
        $('#bankForm').submit(function(e) {
            e.preventDefault();

            let btn = $('#bankBtn');

            // Clear old errors
            $('.error-text').text('');
            $('.form-control').removeClass('is-invalid');

            // Show loader
            btn.prop('disabled', true);
            btn.find('.btn-text').text('Saving...');
            btn.find('.btn-loader').removeClass('d-none');

            $.ajax({
                url: "{{ route('admin.bank.details') }}",
                type: "POST",
                data: $(this).serialize(),

                success: function(res) {

                    btn.prop('disabled', false);
                    btn.find('.btn-text').text('Save Bank Details');
                    btn.find('.btn-loader').addClass('d-none');

                    toastr.success(res.message);
                },

                error: function(xhr) {

                    btn.prop('disabled', false);
                    btn.find('.btn-text').text('Save Bank Details');
                    btn.find('.btn-loader').addClass('d-none');

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;

                        $.each(errors, function(key, value) {
                            $('.' + key + '_error').text(value[0]);
                            $('[name="' + key + '"]').addClass('is-invalid');
                        });
                    } else {
                        toastr.error('Something went wrong!');
                    }
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {

            $("#changePasswordForm").on("submit", function(e) {
                e.preventDefault();

                $(".error-text").text('');
                $(".form-control").removeClass('is-invalid');

                let formData = $(this).serialize();
                let $btn = $("#passwordBtn");

                // Show Loader
                $btn.prop("disabled", true);
                $btn.find(".btn-text").text("Updating...");
                $btn.find(".btn-spinner").removeClass("d-none");

                $.ajax({
                    url: "{{ route('admin.password.update') }}",
                    type: "POST",
                    data: formData,

                    success: function(res) {
                        if (res.status) {
                            toastr.success(res.message);
                            $("#changePasswordForm")[0].reset();
                        } else {
                            toastr.error(res.message);
                        }
                    },

                    error: function(xhr) {

                        if (xhr.status === 422) {

                            let errors = xhr.responseJSON.errors;

                            $.each(errors, function(key, value) {
                                $("." + key + "_error").text(value[0]);
                                $("[name=" + key + "]").addClass("is-invalid");
                            });

                        } else {
                            toastr.error("Something went wrong!");
                        }
                    },

                    complete: function() {
                        // Hide Loader
                        $btn.prop("disabled", false);
                        $btn.find(".btn-text").text("Update Password");
                        $btn.find(".btn-spinner").addClass("d-none");
                    }
                });
            });

        });
    </script>

@endsection
