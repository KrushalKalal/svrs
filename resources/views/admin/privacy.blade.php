@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Privacy Policy')

@section('content')
<div class="content">

    <!-- Breadcrumb -->
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Privacy Policy</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">Policy</li>
                    <li class="breadcrumb-item active">Privacy Policy</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- /Breadcrumb -->

    <div class="card">
        <div class="card-body">
            <form id="privacyForm">
                @csrf

                <!-- CONTENT -->
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Privacy Policy Content <span class="text-danger">*</span>
                    </label>
                    <textarea class="summernote" name="content">
                        {!! $policy->content ?? '' !!}
                    </textarea>
                </div>

                <div class="text-end">
                    <button type="submit" id="saveBtn" class="btn btn-primary">
                        Save Privacy Policy
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    $('#privacyForm').submit(function(e) {
        e.preventDefault();

        let saveBtn = $('#saveBtn');
        let originalText = saveBtn.html();

        saveBtn.prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm"></span> Saving...'
        );

        let formData = new FormData(this);
        formData.append('content', $('.summernote').summernote('code'));

        $.ajax({
            url: "{{ route('admin.update.privacy') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,

            beforeSend: function () {
                toastr.info("Saving...", "Please wait");
            },

            success: function (res) {
                if (res.status === true || res.success) {
                    toastr.success("Privacy Policy updated successfully!", "Success");
                } else {
                    toastr.error(res.message ?? "Something went wrong", "Error");
                }

                saveBtn.prop('disabled', false).html(originalText);
            },

            error: function (xhr) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        toastr.error(value, "Validation Error");
                    });
                } else {
                    toastr.error("Failed to save data!", "Error");
                }

                saveBtn.prop('disabled', false).html(originalText);
            }
        });
    });
</script>
@endsection
