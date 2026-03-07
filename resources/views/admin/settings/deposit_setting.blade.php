@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Deposit Setting')

@section('content')
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Deposit Setting</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">Wallet</li>
                        <li class="breadcrumb-item active">Deposit Setting Update</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form id="depositSettingForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Minimum Deposit Amount (₹) <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="min_amount" class="form-control"
                                value="{{ $setting->min_amount ?? '' }}" required>
                            <span class="text-danger error-text min_amount_error"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Maximum Deposit Amount (₹) <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="max_amount" class="form-control"
                                value="{{ $setting->max_amount ?? '' }}" required>
                            <span class="text-danger error-text max_amount_error"></span>
                        </div>
                        <div class="text-end">
                            <button type="submit" id="saveBtn" class="btn btn-primary">
                                Update Deposit Setting
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $('#depositSettingForm').submit(function(e) {
            e.preventDefault();

            let minAmount = parseFloat($('input[name="min_amount"]').val());
            let maxAmount = parseFloat($('input[name="max_amount"]').val());

            // Clear old errors
            $('.error-text').text('');
            $('.form-control').removeClass('is-invalid');

            // ✅ JS VALIDATION START
            let hasError = false;

            if (!minAmount || minAmount <= 0) {
                $('.min_amount_error').text('Minimum amount must be greater than 0');
                $('input[name="min_amount"]').addClass('is-invalid');
                hasError = true;
            }

            if (!maxAmount || maxAmount <= 0) {
                $('.max_amount_error').text('Maximum amount must be greater than 0');
                $('input[name="max_amount"]').addClass('is-invalid');
                hasError = true;
            }

            if (minAmount && maxAmount && minAmount >= maxAmount) {
                $('.max_amount_error').text('Maximum amount must be greater than Minimum amount');
                $('input[name="max_amount"]').addClass('is-invalid');
                hasError = true;
            }

            if (hasError) {
                return; // ❌ STOP AJAX if validation fails
            }
            // ✅ JS VALIDATION END

            let saveBtn = $('#saveBtn');
            let originalText = saveBtn.html();

            saveBtn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm"></span> Saving...'
            );

            $.ajax({
                url: "{{ route('admin.deposit.setting.update') }}",
                type: "POST",
                data: $(this).serialize(),

                success: function(res) {
                    if (res.status === true) {
                        toastr.success(res.message);
                    } else {
                        toastr.error(res.message ?? "Something went wrong");
                    }

                    saveBtn.prop('disabled', false).html(originalText);
                },

                error: function(xhr) {

                    saveBtn.prop('disabled', false).html(originalText);

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;

                        $.each(errors, function(field, messages) {
                            $('.' + field + '_error').text(messages[0]);
                            $('input[name="' + field + '"]').addClass('is-invalid');
                        });
                    } else {
                        toastr.error("Something went wrong!");
                    }
                }
            });
        });
    </script>
@endsection
