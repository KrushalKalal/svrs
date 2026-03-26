@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Referral Rewards')

@section('content')
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Referral Rewards</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a></li>
                        <li class="breadcrumb-item active">Referral Rewards</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white text-center">
                    <div class="card-body">
                        <h6>Level 1 Total</h6>
                        <h3>{{ number_format($rewards->where('level', 1)->sum('reward_quantity'), 4) }}</h3>
                        <small>SVRS Coins</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white text-center">
                    <div class="card-body">
                        <h6>Level 2 Total</h6>
                        <h3>{{ number_format($rewards->where('level', 2)->sum('reward_quantity'), 4) }}</h3>
                        <small>SVRS Coins</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white text-center">
                    <div class="card-body">
                        <h6>Level 3 Total</h6>
                        <h3>{{ number_format($rewards->where('level', 3)->sum('reward_quantity'), 4) }}</h3>
                        <small>SVRS Coins</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">All Referral Reward Transactions</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="rewardTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Earner</th>
                                <th>Earner Code</th>
                                <th>From Member</th>
                                <th>From Code</th>
                                <th>Level</th>
                                <th>Base Qty (SVRS)</th>
                                <th>Rate</th>
                                <th>Reward (SVRS)</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rewards as $rw)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $rw->earner->full_name ?? '-' }}</td>
                                    <td><span class="badge bg-primary">{{ $rw->earner->member_code ?? '-' }}</span></td>
                                    <td>{{ $rw->fromUser->full_name ?? '-' }}</td>
                                    <td><span class="badge bg-secondary">{{ $rw->fromUser->member_code ?? '-' }}</span></td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $rw->level == 1 ? 'primary' : ($rw->level == 2 ? 'success' : 'info') }}">
                                            Level {{ $rw->level }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($rw->base_quantity, 4) }}</td>
                                    <td>{{ $rw->percentage }}%</td>
                                    <td class="text-success fw-bold">+{{ number_format($rw->reward_quantity, 4) }}</td>
                                    <td>{{ $rw->created_at->format('d M Y h:i A') }}</td>
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
            $('#rewardTable').DataTable({ order: [[9, 'desc']] });
        });
    </script>
@endsection