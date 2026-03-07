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
                                <th>Member</th>
                                <th>Message</th>
                                <th>Attachment</th>
                                <th>Reply</th>
                                <th>Reply Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($supports as $support)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $support->created_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        {{ $support->member->member_code ?? '-' }}<br>
                                        {{ $support->member->first_name ?? '-' }} - {{ $support->member->last_name ?? '-' }}
                                    </td>
                                    <td>{{ $support->message }}</td>
                                    <td>
                                        @if ($support->attachment)
                                            <a href="{{ asset($support->attachment) }}" class="btn btn-sm btn-primary"
                                                target="_blank">View</a>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $support->reply ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $support->replied_at ? \Carbon\Carbon::parse($support->replied_at)->format('d-m-Y H:i') : '-' }}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary replyBtn"
                                            data-id="{{ $support->id }}" data-message="{{ $support->message }}"
                                            data-bs-toggle="modal" data-bs-target="#replyModal">
                                            <i class="ti ti-message-circle"></i> Reply
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="replyModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="replyForm">
                @csrf
                <input type="hidden" name="support_id" id="support_id">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reply to Support</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">User Message</label>
                            <textarea class="form-control" id="user_message" readonly></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Your Reply</label>
                            <textarea name="reply" id="reply" class="form-control" rows="4"></textarea>
                            <span class="text-danger" id="reply_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="replySubmitBtn">
                            <span class="btn-text">Send Reply</span>
                            <span class="btn-loader d-none">
                                <span class="spinner-border spinner-border-sm"></span> Sending...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            let table = $('#SupportTable').DataTable();
        });
    </script>
    <script>
        $(document).on('click', '.replyBtn', function() {

            let id = $(this).data('id');
            let message = $(this).data('message');

            $('#support_id').val(id);
            $('#user_message').val(message);
        });
        $('#replyForm').on('submit', function(e) {
            e.preventDefault();

            let btn = $('#replySubmitBtn');
            btn.prop('disabled', true);
            btn.find('.btn-text').addClass('d-none');
            btn.find('.btn-loader').removeClass('d-none');

            $('#reply_error').text('');

            $.ajax({
                url: "{{ route('admin.customer.support.reply') }}",
                type: "POST",
                data: $(this).serialize(),

                success: function(res) {
                    toastr.success('Reply Sent Successfully');
                    $('#replyModal').modal('hide');
                    location.reload();
                },

                error: function(xhr) {
                    if (xhr.status === 422) {
                        $('#reply_error').text(xhr.responseJSON.errors.reply[0]);
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
