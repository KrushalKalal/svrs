@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Income Report')

@section('content')
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Income Report &mdash; {{ $member->full_name }}</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.reports.financial') }}">Reports</a></li>
                        <li class="breadcrumb-item active">Income Report</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('admin.reports.wallet.ledger', $member->id) }}" class="btn btn-outline-primary me-1">
                    <i class="ti ti-wallet me-1"></i>Wallet Ledger
                </a>
                <a href="{{ route('admin.reports.referral.tree', $member->id) }}" class="btn btn-outline-info me-1">
                    <i class="ti ti-share me-1"></i>Referral Tree
                </a>
                <a href="{{ route('admin.reports.financial') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Back
                </a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white text-center">
                    <div class="card-body">
                        <h6>Level 1</h6>
                        <h3>{{ number_format($totalLevel1, 4) }}</h3><small>SVRS (0.5%)</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white text-center">
                    <div class="card-body">
                        <h6>Level 2</h6>
                        <h3>{{ number_format($totalLevel2, 4) }}</h3><small>SVRS (0.05%)</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white text-center">
                    <div class="card-body">
                        <h6>Level 3</h6>
                        <h3>{{ number_format($totalLevel3, 4) }}</h3><small>SVRS (0.01%)</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark text-center">
                    <div class="card-body">
                        <h6>Total</h6>
                        <h3>{{ number_format($totalCoins, 4) }}</h3><small>SVRS (All Levels)</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Gold Claims: 7 columns --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Gold Coin Claim History</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tblGoldClaims">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tier</th>
                                <th>G-Coins</th>
                                <th>INR Value</th>
                                <th>Referrals at Claim</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($goldClaims as $claim)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><span class="badge bg-info">{{ $claim->tier->name ?? '-' }}</span></td>
                                    <td>{{ number_format($claim->g_coins_claimed) }}</td>
                                    <td>&#8377;{{ number_format($claim->g_coins_claimed / 10, 2) }}</td>
                                    <td>{{ $claim->referral_count_at_claim }}</td>
                                    <td>
                                        @if($claim->status == 1)<span class="badge bg-success">Approved</span>
                                        @elseif($claim->status == 0)<span class="badge bg-danger">Rejected</span>
                                        @else<span class="badge bg-warning text-dark">Pending</span>@endif
                                    </td>
                                    <td>{{ $claim->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Level Tabs --}}
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#l1">Level 1
                    ({{ $level1Rewards->count() }})</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#l2">Level 2
                    ({{ $level2Rewards->count() }})</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#l3">Level 3
                    ({{ $level3Rewards->count() }})</a></li>
        </ul>

        <div class="tab-content">

            {{-- Level 1: 7 cols --}}
            <div class="tab-pane fade show active" id="l1">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tblL1">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>From Member</th>
                                        <th>Member Code</th>
                                        <th>Base Qty</th>
                                        <th>Rate</th>
                                        <th>Reward (SVRS)</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($level1Rewards as $rw)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $rw->fromUser->full_name ?? '-' }}</td>
                                            <td><span class="badge bg-primary">{{ $rw->fromUser->member_code ?? '-' }}</span>
                                            </td>
                                            <td>{{ number_format($rw->base_quantity, 4) }}</td>
                                            <td>{{ $rw->percentage }}%</td>
                                            <td class="text-success fw-bold">+{{ number_format($rw->reward_quantity, 4) }}</td>
                                            <td>{{ $rw->created_at->format('d M Y h:i A') }}</td>
                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Level 2: 7 cols --}}
            <div class="tab-pane fade" id="l2">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tblL2">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>From Member</th>
                                        <th>Member Code</th>
                                        <th>Base Qty</th>
                                        <th>Rate</th>
                                        <th>Reward (SVRS)</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($level2Rewards as $rw)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $rw->fromUser->full_name ?? '-' }}</td>
                                            <td><span class="badge bg-primary">{{ $rw->fromUser->member_code ?? '-' }}</span>
                                            </td>
                                            <td>{{ number_format($rw->base_quantity, 4) }}</td>
                                            <td>{{ $rw->percentage }}%</td>
                                            <td class="text-success fw-bold">+{{ number_format($rw->reward_quantity, 4) }}</td>
                                            <td>{{ $rw->created_at->format('d M Y h:i A') }}</td>
                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Level 3: 7 cols --}}
            <div class="tab-pane fade" id="l3">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tblL3">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>From Member</th>
                                        <th>Member Code</th>
                                        <th>Base Qty</th>
                                        <th>Rate</th>
                                        <th>Reward (SVRS)</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($level3Rewards as $rw)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $rw->fromUser->full_name ?? '-' }}</td>
                                            <td><span class="badge bg-primary">{{ $rw->fromUser->member_code ?? '-' }}</span>
                                            </td>
                                            <td>{{ number_format($rw->base_quantity, 4) }}</td>
                                            <td>{{ $rw->percentage }}%</td>
                                            <td class="text-success fw-bold">+{{ number_format($rw->reward_quantity, 4) }}</td>
                                            <td>{{ $rw->created_at->format('d M Y h:i A') }}</td>
                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#tblGoldClaims').DataTable({ order: [[6, 'desc']], pageLength: 25 });
            $('#tblL1').DataTable({ order: [[6, 'desc']], pageLength: 25 });
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                var t = $(e.target).attr('href');
                if (t === '#l2' && !$.fn.DataTable.isDataTable('#tblL2'))
                    $('#tblL2').DataTable({ order: [[6, 'desc']], pageLength: 25 });
                if (t === '#l3' && !$.fn.DataTable.isDataTable('#tblL3'))
                    $('#tblL3').DataTable({ order: [[6, 'desc']], pageLength: 25 });
            });
        });
    </script>
@endsection