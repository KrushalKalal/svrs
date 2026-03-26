@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Wallet Ledger')

@section('content')
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Wallet Ledger &mdash; {{ $member->full_name }}</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.reports.financial') }}">Reports</a></li>
                        <li class="breadcrumb-item active">Wallet Ledger</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.reports.financial') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i>Back
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <img src="{{ $member->profile_image ? asset($member->profile_image) : asset('admin/img/avatar.png') }}"
                            class="rounded-circle" width="80" height="80" style="object-fit:cover;">
                        <h5 class="mt-2 mb-0">{{ $member->full_name }}</h5>
                        <span class="badge bg-primary">{{ $member->member_code }}</span>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-3 text-center border-end">
                                <p class="text-muted mb-1">INR Wallet</p>
                                <h5 class="text-success">&#8377;{{ number_format($member->wallet->balance ?? 0, 2) }}</h5>
                            </div>
                            <div class="col-md-3 text-center border-end">
                                <p class="text-muted mb-1">Refer Member</p>
                                <h5>
                                    @if($member->is_refer_member)<span class="badge bg-success">Yes</span>
                                    @else<span class="badge bg-secondary">No</span>@endif
                                </h5>
                            </div>
                            <div class="col-md-3 text-center border-end">
                                <p class="text-muted mb-1">Status</p>
                                <h5>
                                    @if($member->status == 1)<span class="badge bg-success">Active</span>
                                    @elseif($member->status == 0)<span class="badge bg-danger">Inactive</span>
                                    @else<span class="badge bg-warning text-dark">Pending</span>@endif
                                </h5>
                            </div>
                            <div class="col-md-3 text-center">
                                <p class="text-muted mb-1">Refer Code</p>
                                <h6>{{ $membership?->refer_code ?? 'N/A' }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs mb-3">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#wTab">INR Transactions</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#cTab">Coin Trades</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#rTab">Referral Rewards</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#gTab">Gold Coins</a></li>
        </ul>

        <div class="tab-content">

            {{-- 6 columns --}}
            <div class="tab-pane fade show active" id="wTab">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tblW">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Remark</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($walletTransactions as $txn)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><span
                                                    class="badge bg-{{ $txn->type == 'credit' ? 'success' : 'danger' }}">{{ ucfirst($txn->type) }}</span>
                                            </td>
                                            <td class="{{ $txn->type == 'credit' ? 'text-success' : 'text-danger' }} fw-bold">
                                                {{ $txn->type == 'credit' ? '+' : '-' }}&#8377;{{ number_format($txn->amount, 2) }}
                                            </td>
                                            <td>{{ $txn->remark ?? '-' }}</td>
                                            <td>
                                                @if($txn->status == 1)<span class="badge bg-success">Approved</span>
                                                @elseif($txn->status == 0)<span class="badge bg-danger">Rejected</span>
                                                @else<span class="badge bg-warning text-dark">Pending</span>@endif
                                            </td>
                                            <td>{{ $txn->created_at->format('d M Y h:i A') }}</td>
                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 7 columns --}}
            <div class="tab-pane fade" id="cTab">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tblC">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Type</th>
                                        <th>Coin</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($coinTrades as $trade)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><span
                                                    class="badge bg-{{ $trade->type == 'buy' ? 'success' : ($trade->type == 'reward' ? 'warning text-dark' : 'danger') }}">{{ ucfirst($trade->type) }}</span>
                                            </td>
                                            <td>{{ $trade->coin->name ?? 'SVRS' }}</td>
                                            <td>&#8377;{{ number_format($trade->price, 4) }}</td>
                                            <td>{{ number_format($trade->quantity, 4) }}</td>
                                            <td>&#8377;{{ number_format($trade->total, 2) }}</td>
                                            <td>{{ $trade->created_at->format('d M Y h:i A') }}</td>
                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 7 columns --}}
            <div class="tab-pane fade" id="rTab">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tblR">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>From Member</th>
                                        <th>Level</th>
                                        <th>Base Qty</th>
                                        <th>Rate</th>
                                        <th>Reward (SVRS)</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($referralRewards as $rw)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $rw->fromUser->full_name ?? '-' }}</td>
                                            <td><span class="badge bg-primary">Level {{ $rw->level }}</span></td>
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

            {{-- 6 columns --}}
            <div class="tab-pane fade" id="gTab">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tblG">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Type</th>
                                        <th>G-Coins</th>
                                        <th>INR Value</th>
                                        <th>Remark</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($goldTransactions as $gt)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><span
                                                    class="badge bg-{{ $gt->type == 'credit' ? 'success' : 'danger' }}">{{ ucfirst($gt->type) }}</span>
                                            </td>
                                            <td class="{{ $gt->type == 'credit' ? 'text-success' : 'text-danger' }} fw-bold">
                                                {{ $gt->type == 'credit' ? '+' : '-' }}{{ number_format($gt->amount) }}
                                            </td>
                                            <td>&#8377;{{ number_format($gt->amount / 10, 2) }}</td>
                                            <td>{{ $gt->remark ?? '-' }}</td>
                                            <td>{{ $gt->created_at->format('d M Y h:i A') }}</td>
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
            $('#tblW').DataTable({ order: [[5, 'desc']], pageLength: 25 });
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                var t = $(e.target).attr('href');
                if (t === '#cTab' && !$.fn.DataTable.isDataTable('#tblC'))
                    $('#tblC').DataTable({ order: [[6, 'desc']], pageLength: 25 });
                if (t === '#rTab' && !$.fn.DataTable.isDataTable('#tblR'))
                    $('#tblR').DataTable({ order: [[6, 'desc']], pageLength: 25 });
                if (t === '#gTab' && !$.fn.DataTable.isDataTable('#tblG'))
                    $('#tblG').DataTable({ order: [[5, 'desc']], pageLength: 25 });
            });
        });
    </script>
@endsection