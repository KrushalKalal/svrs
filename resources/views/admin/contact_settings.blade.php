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
            <form id="contactSettingsForm">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $contact->email ?? '' }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ $contact->phone ?? '' }}" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Address</label>
                        <textarea name="address" class="form-control" rows="3">{{ $contact->address ?? '' }}</textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Google Map Link</label>
                        <input type="text" name="map_link" class="form-control" value="{{ $contact->map_link ?? '' }}">
                    </div>

                    <hr class="mt-4">
                    <h5 class="fw-bold mb-3">Social Media Links</h5>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Facebook</label>
                        <input type="text" name="facebook" class="form-control" value="{{ $contact->facebook ?? '' }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Twitter</label>
                        <input type="text" name="twitter" class="form-control" value="{{ $contact->twitter ?? '' }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">LinkedIn</label>
                        <input type="text" name="linkedin" class="form-control" value="{{ $contact->linkedin ?? '' }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Instagram</label>
                        <input type="text" name="instagram" class="form-control" value="{{ $contact->instagram ?? '' }}">
                    </div>

                    <div class="text-end mt-3">
                        <button type="submit" id="saveBtn" class="btn btn-primary">
                            <span class="btn-text">Save Contact Settings</span>
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
        url: "{{ route('admin.contact.settings.update') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,

        success: function(res) {
            if(res.status === true){
                toastr.success("Contact settings saved successfully!", "Success");
            } else {
                toastr.error(res.message ?? "Something went wrong", "Error");
            }
        },

        error: function(xhr){
            if(xhr.responseJSON && xhr.responseJSON.errors){
                $.each(xhr.responseJSON.errors, function(key, value){
                    toastr.error(value);
                });
            } else {
                toastr.error("Failed to save settings");
            }
        },

        complete: function(){
            btn.prop("disabled", false);
            $(".btn-text").text("Save Contact Settings");
            $("#loader").addClass("d-none");
        }
    });
});
</script>
@endsection