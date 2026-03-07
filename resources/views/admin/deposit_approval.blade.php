@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Deposit Approval')
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="card mt-4">
                <div class="card-header">
                    <h4>Deposit Approval</h4>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered" id="DepositTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Member</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Remark</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $txn)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $txn->user->first_name ?? '' }} {{ $txn->user->last_name ?? '' }}
                                        <br>
                                        {{ $txn->user->mobile ?? '' }}
                                        <br>
                                        {{ $txn->user->member_code ?? '' }}
                                    </td>
                                    <td>{{ $txn->created_at->format('d M Y h:i A') }}</td>
                                    <td>
                                        @if ($txn->type == 'credit')
                                            <span class="badge bg-success">Credit</span>
                                        @else
                                            <span class="badge bg-danger">Debit</span>
                                        @endif
                                    </td>
                                    <td>₹{{ number_format($txn->amount, 2) }}</td>
                                    <td>
                                        @if ($txn->status === 1)
                                            <span class="badge bg-success">Approved</span>
                                        @elseif ($txn->status === 0)
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $txn->remark }}</td>
                                    <td>
                                        @if ($txn->invoice)
                                            <a href="{{ asset($txn->invoice) }}" target="_blank"
                                                class="btn btn-sm btn-info">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @endif
                                        @if ($txn->status === 2)
                                            <button class="btn btn-sm btn-success change-status"
                                                data-id="{{ $txn->id }}" data-status="1">
                                                <i class="fa fa-check"></i>
                                            </button>

                                            <button class="btn btn-sm btn-danger change-status"
                                                data-id="{{ $txn->id }}" data-status="0">
                                                <i class="fa fa-ban"></i>
                                            </button>
                                        @else
                                            <span class="text-muted">No Action</span>
                                        @endif
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
        $(document).on('click', '.change-status', function() {

            let button = $(this);
            let id = button.data('id');
            let status = button.data('status');

            let actionText = status == 1 ? 'Approve' : 'Reject';

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to " + actionText + " this transaction!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: status == 1 ? '#28a745' : '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, ' + actionText + ' it!'
            }).then((result) => {

                if (result.isConfirmed) {

                    button.prop('disabled', true);

                    $.ajax({
                        url: "{{ route('admin.deposit.change.status') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            status: status
                        },
                        success: function(res) {

                            if (res.success) {
                                Swal.fire('Success!', res.message, 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Error!', res.message, 'error');
                                button.prop('disabled', false);
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'Something went wrong!', 'error');
                            button.prop('disabled', false);
                        }
                    });
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            let table = $('#DepositTable').DataTable();
        });
    </script>
@endsection
