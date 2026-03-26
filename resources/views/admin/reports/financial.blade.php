@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Financial Report')

@section('content')
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Financial Report</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a></li>
                        <li class="breadcrumb-item active">Financial Report</li>
                    </ol>
                </nav>
            </div>
            <form class="d-flex gap-2 align-items-center" method="GET" action="{{ route('admin.reports.financial') }}">
                <input type="date" name="from" class="form-control form-control-sm" value="{{ $from }}">
                <span>to</span>
                <input type="date" name="to" class="form-control form-control-sm" value="{{ $to }}">
                <button class="btn btn-primary btn-sm">Filter</button>
            </form>
        </div>

        {{-- Summary Cards --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-success text-white text-center">
                    <div class="card-body">
                        <i class="ti ti-arrow-down-circle fs-2"></i>
                        <h6 class="mt-1">Total Deposits</h6>
                        <h3>&#8377;{{ number_format($totalDeposits, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white text-center">
                    <div class="card-body">
                        <i class="ti ti-arrow-up-circle fs-2"></i>
                        <h6 class="mt-1">Total Withdrawals</h6>
                        <h3>&#8377;{{ number_format($totalWithdrawals, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary text-white text-center">
                    <div class="card-body">
                        <i class="ti ti-id-badge fs-2"></i>
                        <h6 class="mt-1">Membership Fees</h6>
                        <h3>&#8377;{{ number_format($totalMembershipFees, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark text-center">
                    <div class="card-body">
                        <i class="ti ti-coin fs-2"></i>
                        <h6 class="mt-1">Referral Coins Issued</h6>
                        <h3>{{ number_format($totalReferralRewardCoins, 4) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-primary text-center">
                    <div class="card-body">
                        <h2 class="text-primary">{{ $totalMembers }}</h2>
                        <p class="text-muted mb-0">Total Members</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-success text-center">
                    <div class="card-body">
                        <h2 class="text-success">{{ $activeMembers }}</h2>
                        <p class="text-muted mb-0">Active Members</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-info text-center">
                    <div class="card-body">
                        <h2 class="text-info">{{ $referMembers }}</h2>
                        <p class="text-muted mb-0">Refer Members</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-warning text-center">
                    <div class="card-body">
                        <h2 class="text-warning">{{ $pendingActivations }}</h2>
                        <p class="text-muted mb-0">Pending Activations</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Member Reports Table --}}
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0"><i class="ti ti-users me-2"></i>Member Reports</h5>
                <small class="text-muted">Click icons to view Wallet Ledger, Income Report or Referral Tree</small>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="tblMembers">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Member</th>
                                <th>Code</th>
                                <th>Sponsor</th>
                                <th>Status</th>
                                <th>Refer</th>
                                <th>Joined</th>
                                <th class="text-center">Reports</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($members as $m)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $m->profile_image ? url($m->profile_image) : asset('admin/img/avatar.png') }}"
                                                class="rounded-circle" width="32" height="32" style="object-fit:cover;">
                                            <div>
                                                <div class="fw-semibold">{{ $m->full_name }}</div>
                                                <small class="text-muted">{{ $m->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-primary">{{ $m->member_code }}</span></td>
                                    <td>{{ $m->sponsor_id ?? '-' }}</td>
                                    <td>
                                        @if($m->status == 1)<span class="badge bg-success">Active</span>
                                        @elseif($m->status == 0)<span class="badge bg-danger">Inactive</span>
                                        @else<span class="badge bg-warning text-dark">Pending</span>@endif
                                    </td>
                                    <td>
                                        @if($m->is_refer_member)<span class="badge bg-success">Yes</span>
                                        @else<span class="badge bg-secondary">No</span>@endif
                                    </td>
                                    <td>{{ $m->created_at->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.reports.wallet.ledger', $m->id) }}"
                                            class="btn btn-sm btn-outline-primary" title="Wallet Ledger">
                                            <i class="ti ti-wallet"></i>
                                        </a>
                                        <a href="{{ route('admin.reports.income', $m->id) }}"
                                            class="btn btn-sm btn-outline-success" title="Income Report">
                                            <i class="ti ti-chart-bar"></i>
                                        </a>
                                        <a href="{{ route('admin.reports.referral.tree', $m->id) }}"
                                            class="btn btn-sm btn-outline-info" title="Referral Tree">
                                            <i class="ti ti-share"></i>
                                        </a>
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
            $('#tblMembers').DataTable({ order: [[6, 'desc']], pageLength: 25 });
        });
    </script>
@endsection