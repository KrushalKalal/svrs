@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Create Coin')

@section('content')
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Create Coin</h2>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form id="CreateCoinForm" enctype="multipart/form-data">
                    @csrf

                    <div class="row">

                        <!-- Coin Name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Coin Name</label>
                            <input type="text" name="name" class="form-control">
                            <span class="text-danger error-text name_error"></span>
                        </div>

                        <!-- Image -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Coin Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <span class="text-danger error-text image_error"></span>
                        </div>

                        <!-- Start Price -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Price</label>
                            <input type="number" step="0.01" name="start_price" class="form-control">
                            <span class="text-danger error-text start_price_error"></span>
                        </div>

                        <!-- End Price -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Price</label>
                            <input type="number" step="0.01" name="end_price" class="form-control">
                            <span class="text-danger error-text end_price_error"></span>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="">Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <span class="text-danger error-text status_error"></span>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-success" id="submitBtn">
                            <span class="btn-text">Create Coin</span>
                            <span class="btn-loader d-none">
                                <span class="spinner-border spinner-border-sm"></span> Saving...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {

            $('#CreateCoinForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                // Button Loader ON
                $('#submitBtn .btn-text').addClass('d-none');
                $('#submitBtn .btn-loader').removeClass('d-none');
                $('#submitBtn').prop('disabled', true);

                $('.error-text').text('');

                $.ajax({
                    url: "{{ route('admin.coin.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {

                        $('#submitBtn .btn-text').removeClass('d-none');
                        $('#submitBtn .btn-loader').addClass('d-none');
                        $('#submitBtn').prop('disabled', false);

                        if (res.status == true) {
                            toastr.success(res.message);
                            $('#CreateCoinForm')[0].reset();
                            setTimeout(function () {
                                window.location.href = "{{ route('admin.coin.list') }}";
                            }, 1000);
                        }
                    },
                    error: function(xhr) {

                        $('#submitBtn .btn-text').removeClass('d-none');
                        $('#submitBtn .btn-loader').addClass('d-none');
                        $('#submitBtn').prop('disabled', false);

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
