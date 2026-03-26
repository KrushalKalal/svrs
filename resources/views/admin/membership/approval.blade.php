@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Membership Approval')

@section('content')
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Membership Approval</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a></li>
                        <li class="breadcrumb-item active">Membership Approval</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Membership Requests</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="membershipTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Member</th>
                                <th>Member Code</th>
                                <th>Amount</th>
                                <th>Screenshot</th>
                                <th>Refer Code</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($memberships as $m)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $m->user->full_name ?? '-' }}</td>
                                    <td><span class="badge bg-primary">{{ $m->user->member_code ?? '-' }}</span></td>
                                    <td>&#8377;{{ number_format($m->amount, 2) }}</td>
                                    <td>
                                        @if($m->payment_screenshot)
                                            <a href="{{ asset($m->payment_screenshot) }}" target="_blank">
                                                <img src="{{ asset($m->payment_screenshot) }}" width="60" height="60"
                                                    style="object-fit:cover;border-radius:4px;">
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($m->refer_code)
                                            <span class="badge bg-success">{{ $m->refer_code }}</span>
                                        @else
                                            <span class="text-muted">Not generated</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($m->status == 1)
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($m->status == 0)
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $m->created_at->format('d M Y') }}</td>
                                    <td>
                                        @if($m->status == 2)
                                            <button class="btn btn-sm btn-success approve-btn" data-id="{{ $m->id }}"
                                                data-status="1">
                                                <i class="ti ti-check"></i> Approve
                                            </button>
                                            <button class="btn btn-sm btn-danger reject-btn" data-id="{{ $m->id }}" data-status="0">
                                                <i class="ti ti-x"></i> Reject
                                            </button>
                                        @else
                                            <span class="text-muted">Processed</span>
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
        $(document).ready(function () {
            $('#membershipTable').DataTable({ order: [[7, 'desc']] });

            $(document).on('click', '.approve-btn, .reject-btn', function () {
                var id = $(this).data('id');
                var status = $(this).data('status');
                var label = status == 1 ? 'Approve' : 'Reject';

                Swal.fire({
                    title: label + ' this membership?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, ' + label,
                    confirmButtonColor: status == 1 ? '#28a745' : '#dc3545',
                }).then(function (result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.membership.change.status') }}",
                            type: 'POST',
                            data: { id: id, status: status, _token: "{{ csrf_token() }}" },
                            success: function (res) {
                                if (res.success) {
                                    toastr.success(res.message);
                                    setTimeout(function () { location.reload(); }, 1000);
                                } else {
                                    toastr.error(res.message);
                                }
                            },
                            error: function () { toastr.error('Something went wrong'); }
                        });
                    }
                });
            });
        });
    </script>
@endsection