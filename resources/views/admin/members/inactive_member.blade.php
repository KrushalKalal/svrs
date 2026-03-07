@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Inactive Members')
@section('content')
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Inactive Members</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}"><i class="ti ti-smart-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">Members</li>
                        <li class="breadcrumb-item active" aria-current="page">Inactive Members</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Inactive Members</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="customersTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Joining Date</th>
                                        <th>Name</th>
                                        <th>Mobile Number</th>
                                        <th>Member ID</th>
                                        <th>Sponsor ID</th>
                                        <th>Amount</th>
                                        <th>Attachment</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>                                
                                <tbody>
                                    @foreach ($members as $items)
                                        <tr id="row_{{ $items->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $items->created_at->format('d M Y') }}</td>
                                            <td>
                                                {{ $items->first_name }} {{ $items->last_name }}                                                
                                            </td>
                                            <td>{{ $items->mobile }}</td>                                            
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
        $(document).ready(function() {
            let table = $('#customersTable').DataTable();
        });
    </script>
@endsection
