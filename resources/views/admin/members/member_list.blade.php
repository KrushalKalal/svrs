@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Members List')
@section('content')
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Members List</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">Members</li>
                        <li class="breadcrumb-item active" aria-current="page">All Members</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">All Members</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="customersTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Joining Date</th>
                                        <th>Name</th>
                                        <th>Member ID</th>
                                        <th>Sponsor ID</th>
                                        <th>Amount</th>
                                        <th>Attachment</th>
                                        <th>Status</th>
                                        <th width="180">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($members as $items)
                                        <tr id="row_{{ $items->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $items->created_at->format('d M Y') }}</td>
                                            <td>
                                                {{ $items->first_name }} {{ $items->last_name }}
                                                <br>
                                                {{ $items->mobile }}
                                            </td>
                                            <td>{{ $items->member_code }}</td>
                                            <td>{{ $items->sponsor_id ?? '-' }}</td>
                                            <td>
                                                ₹{{ number_format($items->amount ?? 0, 2) }}
                                            </td>
                                            <td>
                                                @if ($items->attachment)
                                                    <a href="{{ asset($items->attachment) }}" target="_blank"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fa fa-eye"></i> View
                                                    </a>
                                                    <a href="{{ asset($items->attachment) }}" download
                                                        class="btn btn-sm btn-secondary">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                @else
                                                    <span class="text-muted">No File</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($items->status == 1)
                                                    <span class="badge bg-success status-badge">Active</span>
                                                @elseif ($items->status == 0)
                                                    <span class="badge bg-danger status-badge">Inactive</span>
                                                @else
                                                    <span class="badge bg-warning text-dark status-badge">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-success change-status"
                                                    data-id="{{ $items->id }}" data-status="1">
                                                    <i class="fa fa-check"></i>
                                                </button>

                                                <button class="btn btn-sm btn-danger change-status"
                                                    data-id="{{ $items->id }}" data-status="0">
                                                    <i class="fa fa-ban"></i>
                                                </button>
                                                @if ($items->bankDetail)                                                    
                                                    <a href="{{ route('admin.member.bankdetails', $items->bankDetail->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
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
        </div>
    </div>
    <script>
        $(document).on('click', '.change-status', function() {

            let button = $(this);
            let id = button.data('id');
            let status = button.data('status');
            let actionText = status == 1 ? 'Activate' : 'Inactivate';

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to " + actionText + " this member!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, ' + actionText + ' it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    button.prop('disabled', true).text('Updating...');
                    $.ajax({
                        url: "{{ route('admin.member.update.status') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                            status: status
                        },
                        success: function(res) {
                            if (res.success) {

                                let badge = $('#row_' + id).find('.status-badge');

                                if (status == 1) {
                                    badge.removeClass().addClass(
                                        'badge bg-success status-badge').text('Active');
                                } else {
                                    badge.removeClass().addClass('badge bg-danger status-badge')
                                        .text('Inactive');
                                }
                                Swal.fire({
                                    title: 'Updated!',
                                    text: res.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    location.reload();
                                });

                            } else {
                                Swal.fire('Error!', res.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'Something went wrong!', 'error');
                        },
                        complete: function() {
                            button.prop('disabled', false)
                                .text(status == 1 ? 'Activate' : 'Inactivate');
                        }
                    });

                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            let table = $('#customersTable').DataTable();
        });
    </script>
@endsection
