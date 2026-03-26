@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Wallet Ledger')

@section('content')
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Wallet Ledger</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a></li>
                        <li class="breadcrumb-item">Reports</li>
                        <li class="breadcrumb-item active">Wallet Ledger</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <p class="text-muted mb-1">INR Wallet Balance</p>
                        <h4 class="text-success">&#8377;{{ number_format($wallet->balance ?? 0, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <p class="text-muted mb-1">Total Deposited</p>
                        <h4 class="text-primary">
                            &#8377;{{ number_format($walletTransactions->where('type', 'credit')->where('status', 1)->sum('amount'), 2) }}
                        </h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <p class="text-muted mb-1">Total Withdrawn</p>
                        <h4 class="text-danger">
                            &#8377;{{ number_format($walletTransactions->where('type', 'debit')->where('status', 1)->sum('amount'), 2) }}
                        </h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <p class="text-muted mb-1">Referral Coins Earned</p>
                        <h4 class="text-warning">{{ number_format($referralRewards->sum('reward_quantity'), 4) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs mb-3">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#walletTab">INR Transactions</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#coinTab">Coin Trades</a></li>
            @if(auth()->user()->is_refer_member)
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#referralTab">Referral Rewards</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#goldTab">Gold Coins</a></li>
            @endif
        </ul>

        <div class="tab-content">

            {{-- INR Transactions: 6 columns --}}
            <div class="tab-pane fade show active" id="walletTab">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tblWallet">
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
                                                @else<span class="badge bg-warning text-dark">Pending</span>
                                                @endif
                                            </td>
                                            <td>{{ $txn->created_at->format('d M Y h:i A') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No transactions found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Coin Trades: 6 columns --}}
            <div class="tab-pane fade" id="coinTab">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tblCoin">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Type</th>
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
                                            <td>&#8377;{{ number_format($trade->price, 4) }}</td>
                                            <td>{{ number_format($trade->quantity, 4) }}</td>
                                            <td>&#8377;{{ number_format($trade->total, 2) }}</td>
                                            <td>{{ $trade->created_at->format('d M Y h:i A') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No trades found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            @if(auth()->user()->is_refer_member)

                {{-- Referral Rewards: 7 columns — NO tfoot --}}
                <div class="tab-pane fade" id="referralTab">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="tblReferral">
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
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">No referral rewards yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Gold Coins: 6 columns — NO tfoot --}}
                <div class="tab-pane fade" id="goldTab">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="tblGold">
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
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">No G-Coin transactions yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            @endif
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Init visible tabs immediately
            $('#tblWallet').DataTable({ order: [[5, 'desc']], pageLength: 25 });

            // Init hidden tabs only when shown — fixes column count mismatch on hidden tabs
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                var target = $(e.target).attr('href');
                if (target === '#coinTab' && !$.fn.DataTable.isDataTable('#tblCoin')) {
                    $('#tblCoin').DataTable({ order: [[5, 'desc']], pageLength: 25 });
                }
                @if(auth()->user()->is_refer_member)
                    if (target === '#referralTab' && !$.fn.DataTable.isDataTable('#tblReferral')) {
                        $('#tblReferral').DataTable({ order: [[6, 'desc']], pageLength: 25 });
                    }
                    if (target === '#goldTab' && !$.fn.DataTable.isDataTable('#tblGold')) {
                        $('#tblGold').DataTable({ order: [[5, 'desc']], pageLength: 25 });
                    }
                @endif
                    });
        });
    </script>
@endsection