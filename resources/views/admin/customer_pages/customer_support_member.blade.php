@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Customer Support')
@section('content')
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Customer Support</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Customer Support</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form id="CustomerSupportForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Message</label>
                            <textarea name="message" id="message" class="form-control" rows="4" placeholder="Enter your message..."></textarea>
                            <span class="text-danger" id="message_error"></span>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Attachment</label>
                            <input type="file" id="attachment" name="attachment" class="form-control"
                                accept="image/jpeg,image/png,image/jpg">
                            <small class="text-muted">Allowed: jpg, jpeg, png (Max: 2MB)</small>
                            <br><span class="text-danger" id="attachment_error"></span>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" id="supportBtn" class="btn btn-primary">
                                <span class="btn-text">Submit</span>
                                <span class="btn-loader d-none">
                                    <span class="spinner-border spinner-border-sm"></span>
                                    Please wait...
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Customer Support List</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="SupportTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Message</th>
                                <th>Attachment</th>
                                <th>Reply</th>
                                <th>Reply Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($supports as $support)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $support->created_at->format('d-m-Y H:i') }}</td>  
                                    <td>{{ $support->message }}</td>                               
                                    <td>
                                        @if ($support->attachment)
                                            <a href="{{ asset($support->attachment) }}" class="btn btn-sm btn-primary" target="_blank">View</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        {{ $support->reply ?? '-' }}
                                    </td>                
                                    <td>
                                        {{ $support->replied_at ? \Carbon\Carbon::parse($support->replied_at)->format('d-m-Y H:i') : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            let table = $('#SupportTable').DataTable();
        });
    </script>
    <script>
        $('#CustomerSupportForm').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);
            let btn = $('#supportBtn');

            // Clear old errors
            $('.text-danger').html('');
            $('.form-control').removeClass('is-invalid');

            btn.prop('disabled', true);
            btn.find('.btn-text').addClass('d-none');
            btn.find('.btn-loader').removeClass('d-none');

            $.ajax({
                url: "{{ route('admin.customer.support.store') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,

                success: function(res) {
                    toastr.success(res.message ?? 'Submitted Successfully');
                    $('#CustomerSupportForm')[0].reset();
                },

                error: function(xhr) {

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;

                        $.each(errors, function(field, messages) {

                            // Add red border
                            $('#' + field).addClass('is-invalid');

                            // Show error text
                            $('#' + field + '_error').text(messages[0]);

                        });

                    } else {
                        toastr.error('Something went wrong!');
                    }
                },

                complete: function() {
                    btn.prop('disabled', false);
                    btn.find('.btn-text').removeClass('d-none');
                    btn.find('.btn-loader').addClass('d-none');
                }
            });
        });
    </script>
@endsection
