@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Contact Settings')

@section('content')
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Contact Settings</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">Settings</li>
                        <li class="breadcrumb-item active" aria-current="page">Contact Settings</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form id="contactSettingsForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Bank Name</label>
                            <input type="text" name="bank" class="form-control" value="{{ $contact->bank ?? '' }}"
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Account Name</label>
                            <input type="text" name="account_name" class="form-control"
                                value="{{ $contact->account_name ?? '' }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Account Number</label>
                            <input type="text" name="account_number" class="form-control"
                                value="{{ $contact->account_number ?? '' }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">IFSC Code</label>
                            <input type="text" name="ifsc_code" class="form-control"
                                value="{{ $contact->ifsc_code ?? '' }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Branch</label>
                            <input type="text" name="branch" class="form-control" value="{{ $contact->branch ?? '' }}"
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">QR Image</label>
                            <input type="file" name="qr_image" class="form-control">

                            @if (!empty($contact->qr_image))
                                <img src="{{ asset($contact->qr_image) }}" width="120" class="mt-2">
                            @endif
                        </div>
                        <div class="text-end mt-3">
                            <button type="submit" id="saveBtn" class="btn btn-primary">
                                <span class="btn-text">Save Settings</span>
                                <span class="spinner-border spinner-border-sm d-none" id="loader"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $("#contactSettingsForm").submit(function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            let btn = $("#saveBtn");
            btn.prop("disabled", true);
            $(".btn-text").text("Saving...");
            $("#loader").removeClass("d-none");

            $.ajax({
                url: "{{ route('admin.contact.setting.update') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,

                success: function(res) {
                    if (res.status === true) {
                        toastr.success("Contact settings saved successfully!", "Success");
                    } else {
                        toastr.error(res.message ?? "Something went wrong", "Error");
                    }
                },

                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            toastr.error(value);
                        });
                    } else {
                        toastr.error("Failed to save settings");
                    }
                },

                complete: function() {
                    btn.prop("disabled", false);
                    $(".btn-text").text("Save Contact Settings");
                    $("#loader").addClass("d-none");
                }
            });
        });
    </script>
@endsection
