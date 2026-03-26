@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Income Report')

@section('content')
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Income Report</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a></li>
                        <li class="breadcrumb-item">Reports</li>
                        <li class="breadcrumb-item active">Income Report</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white text-center">
                    <div class="card-body">
                        <h6>Level 1 Earnings</h6>
                        <h3>{{ number_format($totalLevel1, 4) }}</h3>
                        <small>SVRS Coins (0.5%)</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white text-center">
                    <div class="card-body">
                        <h6>Level 2 Earnings</h6>
                        <h3>{{ number_format($totalLevel2, 4) }}</h3>
                        <small>SVRS Coins (0.05%)</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white text-center">
                    <div class="card-body">
                        <h6>Level 3 Earnings</h6>
                        <h3>{{ number_format($totalLevel3, 4) }}</h3>
                        <small>SVRS Coins (0.01%)</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark text-center">
                    <div class="card-body">
                        <h6>Total Coin Income</h6>
                        <h3>{{ number_format($totalCoins, 4) }}</h3>
                        <small>SVRS Coins (All Levels)</small>
                    </div>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs mb-3">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#l1Tab">Level 1
                    ({{ $level1Rewards->count() }})</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#l2Tab">Level 2
                    ({{ $level2Rewards->count() }})</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#l3Tab">Level 3
                    ({{ $level3Rewards->count() }})</a></li>
        </ul>

        <div class="tab-content">

            {{-- Level 1: 7 columns, NO tfoot --}}
            <div class="tab-pane fade show active" id="l1Tab">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tblL1">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>From Member</th>
                                        <th>Member Code</th>
                                        <th>Base Qty (SVRS)</th>
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
                            @if($level1Rewards->isNotEmpty())
                                <p class="text-end text-success fw-bold mt-2">Level 1 Total:
                                    +{{ number_format($totalLevel1, 4) }} SVRS</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Level 2: 7 columns, NO tfoot --}}
            <div class="tab-pane fade" id="l2Tab">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tblL2">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>From Member</th>
                                        <th>Member Code</th>
                                        <th>Base Qty (SVRS)</th>
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
                            @if($level2Rewards->isNotEmpty())
                                <p class="text-end text-success fw-bold mt-2">Level 2 Total:
                                    +{{ number_format($totalLevel2, 4) }} SVRS</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Level 3: 7 columns, NO tfoot --}}
            <div class="tab-pane fade" id="l3Tab">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tblL3">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>From Member</th>
                                        <th>Member Code</th>
                                        <th>Base Qty (SVRS)</th>
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
                            @if($level3Rewards->isNotEmpty())
                                <p class="text-end text-success fw-bold mt-2">Level 3 Total:
                                    +{{ number_format($totalLevel3, 4) }} SVRS</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Earning Rate Reference</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Level</th>
                            <th>Rate</th>
                            <th>Example (100 SVRS buy)</th>
                            <th>Your Earning</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Level 1 (Direct)</td>
                            <td><span class="badge bg-primary">0.5%</span></td>
                            <td>Member buys 100 SVRS</td>
                            <td class="text-success fw-bold">+0.5 SVRS</td>
                        </tr>
                        <tr>
                            <td>Level 2 (Indirect)</td>
                            <td><span class="badge bg-success">0.05%</span></td>
                            <td>Member buys 100 SVRS</td>
                            <td class="text-success fw-bold">+0.05 SVRS</td>
                        </tr>
                        <tr>
                            <td>Level 3 (Deep)</td>
                            <td><span class="badge bg-info">0.01%</span></td>
                            <td>Member buys 100 SVRS</td>
                            <td class="text-success fw-bold">+0.01 SVRS</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Init ALL tabs lazily on shown — same fix as wallet ledger
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                var target = $(e.target).attr('href');
                if (target === '#l1Tab' && !$.fn.DataTable.isDataTable('#tblL1')) {
                    $('#tblL1').DataTable({ order: [[6, 'desc']], pageLength: 25 });
                }
                if (target === '#l2Tab' && !$.fn.DataTable.isDataTable('#tblL2')) {
                    $('#tblL2').DataTable({ order: [[6, 'desc']], pageLength: 25 });
                }
                if (target === '#l3Tab' && !$.fn.DataTable.isDataTable('#tblL3')) {
                    $('#tblL3').DataTable({ order: [[6, 'desc']], pageLength: 25 });
                }
            });

            // Trigger active tab manually so tblL1 inits properly
            $('a[data-bs-toggle="tab"].active').trigger('shown.bs.tab');
        });
    </script>
@endsection