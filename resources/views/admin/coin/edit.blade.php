@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Edit Coin')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Edit Coin</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form id="UpdateCoinForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $coin->id }}">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Coin Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $coin->name }}">
                        <span class="text-danger error-text name_error"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Coin Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <span class="text-danger error-text image_error"></span>
                        @if($coin->image)
                            <div class="mt-2">
                                <img src="{{ asset($coin->image) }}" width="120">
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start Price</label>
                        <input type="number" step="0.01" name="start_price" class="form-control"
                            value="{{ $coin->start_price }}">
                        <span class="text-danger error-text start_price_error"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">End Price</label>
                        <input type="number" step="0.01" name="end_price" class="form-control"
                            value="{{ $coin->end_price }}">
                        <span class="text-danger error-text end_price_error"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control">
                            <option value="1" {{ $coin->status == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $coin->status == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <span class="text-danger error-text status_error"></span>
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary" id="updateBtn">
                        <span class="btn-text">Update Coin</span>
                        <span class="btn-loader d-none">
                            <span class="spinner-border spinner-border-sm"></span> Updating...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {

    $('#UpdateCoinForm').on('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);

        $('#updateBtn .btn-text').addClass('d-none');
        $('#updateBtn .btn-loader').removeClass('d-none');
        $('#updateBtn').prop('disabled', true);

        $('.error-text').text('');

        $.ajax({
            url: "{{ route('admin.coin.update') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,

            success: function(res) {
                $('#updateBtn .btn-text').removeClass('d-none');
                $('#updateBtn .btn-loader').addClass('d-none');
                $('#updateBtn').prop('disabled', false);

                if (res.status == true) {
                    toastr.success(res.message);
                    setTimeout(function () {
                        window.location.href = "{{ route('admin.coin.list') }}";
                    }, 1000);
                }
            },

            error: function(xhr) {
                $('#updateBtn .btn-text').removeClass('d-none');
                $('#updateBtn .btn-loader').addClass('d-none');
                $('#updateBtn').prop('disabled', false);

                if (xhr.status === 422) {
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        $('.' + key + '_error').text(value[0]);
                    });
                } else {
                    toastr.error('Something went wrong');
                }
            }
        });
    });

});
</script>
@endsection