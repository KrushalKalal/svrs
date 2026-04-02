@extends('member.layout.app-layout')
@section('title', 'Reports')
@section('nav-title', 'Reports')
@section('nav-back') @endsection
@section('nav-back-url', route('member.dashboard'))

@section('content')
    @php $user = auth()->user(); @endphp

    {{-- ══ TOP REPORT TABS ══ --}}
    <div style="padding:16px 20px 0;">
        <div class="segment-ctrl" id="reportTopTabs">
            <button class="active" onclick="switchReport('wallet', this)">Wallet</button>
            @if($user->is_refer_member)
                <button onclick="switchReport('income', this)">Income</button>
                <button onclick="switchReport('tree', this)">Tree</button>
            @endif
        </div>
    </div>


    {{-- ══════════════════════════════
         TAB 1 — WALLET LEDGER
    ══════════════════════════════ --}}
    <div id="reportTab_wallet">

        {{-- Summary cards --}}
        <div style="padding:12px 20px 0;display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <div class="app-card app-card-inner">
                <p style="font-size:11px;color:var(--muted);margin-bottom:3px;">INR Balance</p>
                <p style="font-size:18px;font-weight:800;color:var(--green);">₹{{ number_format($wallet->balance ?? 0, 2) }}</p>
            </div>
            <div class="app-card app-card-inner">
                <p style="font-size:11px;color:var(--muted);margin-bottom:3px;">Total Deposited</p>
                <p style="font-size:18px;font-weight:800;color:var(--accent-blue);">
                    ₹{{ number_format($walletTransactions->where('type','credit')->where('status',1)->sum('amount'), 2) }}
                </p>
            </div>
            <div class="app-card app-card-inner">
                <p style="font-size:11px;color:var(--muted);margin-bottom:3px;">Total Withdrawn</p>
                <p style="font-size:18px;font-weight:800;color:var(--red);">
                    ₹{{ number_format($walletTransactions->where('type','debit')->where('status',1)->sum('amount'), 2) }}
                </p>
            </div>
            <div class="app-card app-card-inner">
                <p style="font-size:11px;color:var(--muted);margin-bottom:3px;">Referral Coins</p>
                <p style="font-size:18px;font-weight:800;color:var(--gold);">
                    {{ number_format($referralRewards->sum('reward_quantity'), 4) }}
                </p>
            </div>
        </div>

        {{-- Wallet sub-tabs --}}
        <div style="padding:12px 20px 0;">
            <div class="segment-ctrl" id="walletSubTabs">
                <button class="active" onclick="switchWalletSub('inr', this)">
                    Wallet ({{ $walletTransactions->count() }})
                </button>
                <button onclick="switchWalletSub('coin', this)">
                    Coins ({{ $coinTrades->count() }})
                </button>
                @if($user->is_refer_member)
                    <button onclick="switchWalletSub('ref', this)">
                        Referral ({{ $referralRewards->count() }})
                    </button>
                    <button onclick="switchWalletSub('gold', this)">Gold</button>
                @endif
            </div>
        </div>

        {{-- INR --}}
        <div id="walletSub_inr" style="padding:10px 20px 0;">
            @if($walletTransactions->isEmpty())
                <div class="empty-state"><i class="fa fa-receipt"></i><p>No INR transactions yet</p></div>
            @else
                <div class="app-card" style="overflow:hidden;">
                    @foreach($walletTransactions as $txn)
                        <div class="list-row">
                            <div class="list-icon {{ $txn->type === 'credit' ? 'teal' : 'red' }}"
                                style="width:40px;height:40px;border-radius:12px;font-size:15px;">
                                <i class="fa fa-arrow-{{ $txn->type === 'credit' ? 'down' : 'up' }}"></i>
                            </div>
                            <div class="list-body">
                                <div class="title">{{ $txn->remark ?? ucfirst($txn->type) }}</div>
                                <div class="sub">{{ $txn->created_at->format('d M Y, h:i A') }}</div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-size:14px;font-weight:700;color:{{ $txn->type === 'credit' ? 'var(--green)' : 'var(--red)' }};">
                                    {{ $txn->type === 'credit' ? '+' : '-' }}₹{{ number_format($txn->amount, 2) }}
                                </div>
                                <div style="margin-top:3px;">
                                    @if($txn->status == 1)<span class="badge-app badge-green" style="font-size:10px;">Approved</span>
                                    @elseif($txn->status == 0)<span class="badge-app badge-red" style="font-size:10px;">Rejected</span>
                                    @else<span class="badge-app badge-gold" style="font-size:10px;">Pending</span>@endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Coins --}}
        <div id="walletSub_coin" style="display:none;padding:10px 20px 0;">
            @if($coinTrades->isEmpty())
                <div class="empty-state"><i class="fa fa-coins"></i><p>No coin trades yet</p></div>
            @else
                <div class="app-card" style="overflow:hidden;">
                    @foreach($coinTrades as $trade)
                        <div class="list-row">
                            <div class="list-icon {{ $trade->type === 'buy' ? 'green' : ($trade->type === 'reward' ? 'gold' : 'red') }}"
                                style="width:40px;height:40px;border-radius:12px;font-size:15px;">
                                <i class="fa fa-{{ $trade->type === 'buy' ? 'arrow-down' : ($trade->type === 'reward' ? 'gift' : 'arrow-up') }}"></i>
                            </div>
                            <div class="list-body">
                                <div class="title">{{ ucfirst($trade->type) }}</div>
                                <div class="sub">{{ $trade->created_at->format('d M Y, h:i A') }}</div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-size:14px;font-weight:700;">{{ number_format($trade->quantity, 4) }} SVRS</div>
                                <div style="font-size:11px;color:var(--muted);">₹{{ number_format($trade->price, 4) }}/coin</div>
                                <div style="font-size:11px;color:var(--muted);">
                                    ₹{{ number_format($trade->total ?? ($trade->price * $trade->quantity), 2) }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Referral (refer members only) --}}
        @if($user->is_refer_member)
            <div id="walletSub_ref" style="display:none;padding:10px 20px 0;">
                @if($referralRewards->isEmpty())
                    <div class="empty-state"><i class="fa fa-share-nodes"></i><p>No referral rewards yet</p></div>
                @else
                    <div class="app-card" style="overflow:hidden;">
                        @foreach($referralRewards as $rw)
                            <div class="list-row">
                                <div class="list-icon blue" style="width:40px;height:40px;border-radius:12px;font-size:15px;">
                                    <i class="fa fa-user-group"></i>
                                </div>
                                <div class="list-body">
                                    <div class="title">{{ $rw->fromUser->full_name ?? '—' }}</div>
                                    <div class="sub">Level {{ $rw->level }} • {{ $rw->percentage }}% • {{ $rw->created_at->format('d M Y') }}</div>
                                </div>
                                <div style="text-align:right;">
                                    <div style="font-size:14px;font-weight:700;color:var(--green);">+{{ number_format($rw->reward_quantity, 4) }}</div>
                                    <div style="font-size:11px;color:var(--muted);">SVRS</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Gold --}}
            <div id="walletSub_gold" style="display:none;padding:10px 20px 0;">
                @if($goldTransactions->isEmpty())
                    <div class="empty-state"><i class="fa fa-coins"></i><p>No G-Coin transactions yet</p></div>
                @else
                    <div class="app-card" style="overflow:hidden;">
                        @foreach($goldTransactions as $gt)
                            <div class="list-row">
                                <div class="list-icon {{ $gt->type === 'credit' ? 'gold' : 'red' }}"
                                    style="width:40px;height:40px;border-radius:12px;font-size:15px;">
                                    <i class="fa fa-{{ $gt->type === 'credit' ? 'arrow-down' : 'arrow-up' }}"></i>
                                </div>
                                <div class="list-body">
                                    <div class="title">{{ $gt->remark ?? ucfirst($gt->type) }}</div>
                                    <div class="sub">{{ $gt->created_at->format('d M Y, h:i A') }}</div>
                                </div>
                                <div style="text-align:right;">
                                    <div style="font-size:14px;font-weight:700;color:{{ $gt->type === 'credit' ? 'var(--gold)' : 'var(--red)' }};">
                                        {{ $gt->type === 'credit' ? '+' : '-' }}{{ number_format($gt->amount) }} G
                                    </div>
                                    <div style="font-size:11px;color:var(--muted);">₹{{ number_format($gt->amount / 10, 2) }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

    </div>{{-- /reportTab_wallet --}}


    {{-- ══════════════════════════════
         TAB 2 — INCOME REPORT (refer members only)
    ══════════════════════════════ --}}
    @if($user->is_refer_member)
    <div id="reportTab_income" style="display:none;">

        {{-- Summary --}}
        <div style="padding:12px 20px 0;display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <div class="app-card app-card-inner">
                <p style="font-size:11px;color:var(--muted);margin-bottom:3px;">Level 1 (0.5%)</p>
                <p style="font-size:18px;font-weight:800;color:var(--accent-blue);">{{ number_format($totalLevel1, 4) }}</p>
                <p style="font-size:10px;color:var(--muted);">SVRS Coins</p>
            </div>
            <div class="app-card app-card-inner">
                <p style="font-size:11px;color:var(--muted);margin-bottom:3px;">Level 2 (0.05%)</p>
                <p style="font-size:18px;font-weight:800;color:var(--green);">{{ number_format($totalLevel2, 4) }}</p>
                <p style="font-size:10px;color:var(--muted);">SVRS Coins</p>
            </div>
            <div class="app-card app-card-inner">
                <p style="font-size:11px;color:var(--muted);margin-bottom:3px;">Level 3 (0.01%)</p>
                <p style="font-size:18px;font-weight:800;color:var(--accent);">{{ number_format($totalLevel3, 4) }}</p>
                <p style="font-size:10px;color:var(--muted);">SVRS Coins</p>
            </div>
            <div class="app-card app-card-inner">
                <p style="font-size:11px;color:var(--muted);margin-bottom:3px;">Total Income</p>
                <p style="font-size:18px;font-weight:800;color:var(--gold);">{{ number_format($totalCoins, 4) }}</p>
                <p style="font-size:10px;color:var(--muted);">SVRS Coins</p>
            </div>
        </div>

        {{-- Level sub-tabs --}}
        <div style="padding:12px 20px 0;">
            <div class="segment-ctrl" id="incomeSubTabs">
                <button class="active" onclick="switchIncomeSub('l1', this)">Level 1 ({{ $level1Rewards->count() }})</button>
                <button onclick="switchIncomeSub('l2', this)">Level 2 ({{ $level2Rewards->count() }})</button>
                <button onclick="switchIncomeSub('l3', this)">Level 3 ({{ $level3Rewards->count() }})</button>
            </div>
        </div>

        @foreach([['l1', $level1Rewards, $totalLevel1], ['l2', $level2Rewards, $totalLevel2], ['l3', $level3Rewards, $totalLevel3]] as [$pid, $rewards, $total])
            <div id="incomeSub_{{ $pid }}" style="{{ $pid === 'l1' ? '' : 'display:none;' }}padding:10px 20px 0;">
                @if($rewards->isEmpty())
                    <div class="empty-state">
                        <i class="fa fa-coins"></i>
                        <p>No {{ strtoupper($pid) }} rewards yet</p>
                    </div>
                @else
                    <div class="app-card" style="overflow:hidden;">
                        @foreach($rewards as $rw)
                            <div class="list-row">
                                <div class="list-icon blue" style="width:40px;height:40px;border-radius:12px;font-size:15px;">
                                    <i class="fa fa-user"></i>
                                </div>
                                <div class="list-body">
                                    <div class="title">{{ $rw->fromUser->full_name ?? '—' }}</div>
                                    <div class="sub">
                                        {{ $rw->fromUser->member_code ?? '—' }} • {{ $rw->percentage }}% •
                                        Base: {{ number_format($rw->base_quantity, 4) }} SVRS
                                    </div>
                                    <div class="sub">{{ $rw->created_at->format('d M Y, h:i A') }}</div>
                                </div>
                                <div style="text-align:right;">
                                    <div style="font-size:14px;font-weight:700;color:var(--green);">
                                        +{{ number_format($rw->reward_quantity, 4) }}
                                    </div>
                                    <div style="font-size:11px;color:var(--muted);">SVRS</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p style="font-size:13px;font-weight:700;color:var(--green);text-align:right;padding:10px 20px 0;">
                        Total: +{{ number_format($total, 4) }} SVRS
                    </p>
                @endif
            </div>
        @endforeach

        {{-- Earning rate reference --}}
        <div class="section-label">Earning Rate Reference</div>
        <div style="margin:0 20px;" class="app-card">
            @foreach([
                ['blue', '1', 'Level 1 (Direct)', '0.5% per buy', '+0.5 SVRS / 100'],
                ['teal', '2', 'Level 2',           '0.05% per buy', '+0.05 SVRS / 100'],
                ['gold', '3', 'Level 3',           '0.01% per buy', '+0.01 SVRS / 100'],
            ] as [$color, $num, $title, $sub, $rate])
                <div class="list-row">
                    <div class="list-icon {{ $color }}" style="width:36px;height:36px;border-radius:10px;font-size:14px;">
                        <i class="fa fa-{{ $num }}"></i>
                    </div>
                    <div class="list-body">
                        <div class="title">{{ $title }}</div>
                        <div class="sub">{{ $sub }}</div>
                    </div>
                    <div style="color:var(--green);font-weight:700;font-size:12px;">{{ $rate }}</div>
                </div>
            @endforeach
        </div>

    </div>{{-- /reportTab_income --}}


    {{-- ══════════════════════════════
         TAB 3 — REFERRAL TREE (refer members only)
    ══════════════════════════════ --}}
    <div id="reportTab_tree" style="display:none;">

        {{-- Count cards --}}
        <div style="padding:12px 20px 0;display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;">
            <div class="app-card app-card-inner" style="text-align:center;padding:12px 8px;">
                <p style="font-size:22px;font-weight:800;color:var(--accent-blue);">{{ $treeLevel1->count() }}</p>
                <p style="font-size:11px;color:var(--muted);">Level 1</p>
                <p style="font-size:10px;color:var(--green);">
                    {{ $treeLevel1->where('is_refer_member', 1)->where('status', 1)->count() }} refer
                </p>
            </div>
            <div class="app-card app-card-inner" style="text-align:center;padding:12px 8px;">
                <p style="font-size:22px;font-weight:800;color:var(--green);">{{ $treeLevel2->count() }}</p>
                <p style="font-size:11px;color:var(--muted);">Level 2</p>
                <p style="font-size:10px;color:var(--green);">
                    {{ $treeLevel2->where('is_refer_member', 1)->where('status', 1)->count() }} refer
                </p>
            </div>
            <div class="app-card app-card-inner" style="text-align:center;padding:12px 8px;">
                <p style="font-size:22px;font-weight:800;color:var(--accent);">{{ $treeLevel3->count() }}</p>
                <p style="font-size:11px;color:var(--muted);">Level 3</p>
                <p style="font-size:10px;color:var(--green);">
                    {{ $treeLevel3->where('is_refer_member', 1)->where('status', 1)->count() }} refer
                </p>
            </div>
        </div>

        {{-- Level sub-tabs --}}
        <div style="padding:12px 20px 0;">
            <div class="segment-ctrl" id="treeSubTabs">
                <button class="active" onclick="switchTreeSub('l1', this)">Level 1 ({{ $treeLevel1->count() }})</button>
                <button onclick="switchTreeSub('l2', this)">Level 2 ({{ $treeLevel2->count() }})</button>
                <button onclick="switchTreeSub('l3', this)">Level 3 ({{ $treeLevel3->count() }})</button>
            </div>
        </div>

        @foreach([['l1', $treeLevel1, 1], ['l2', $treeLevel2, 2], ['l3', $treeLevel3, 3]] as [$pid, $members, $lvl])
            <div id="treeSub_{{ $pid }}" style="{{ $pid === 'l1' ? '' : 'display:none;' }}padding:10px 20px 0;">
                @if($members->isEmpty())
                    <div class="empty-state"><i class="fa fa-users"></i><p>No Level {{ $lvl }} members yet</p></div>
                @else
                    <div class="app-card" style="overflow:hidden;">
                        @foreach($members as $m)
                            <div class="list-row">
                                <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,var(--gold),var(--gold-dark));display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:800;color:#000;flex-shrink:0;">
                                    {{ strtoupper(substr($m->first_name, 0, 1)) }}
                                </div>
                                <div class="list-body">
                                    <div class="title">{{ $m->full_name }}</div>
                                    <div class="sub" style="font-family:'Space Mono',monospace;font-size:11px;">{{ $m->member_code }}</div>
                                    <div class="sub">Joined {{ $m->created_at->format('d M Y') }}</div>
                                </div>
                                <div style="text-align:right;display:flex;flex-direction:column;gap:4px;align-items:flex-end;">
                                    @if($m->status == 1)<span class="badge-app badge-green" style="font-size:10px;">Active</span>
                                    @elseif($m->status == 0)<span class="badge-app badge-red" style="font-size:10px;">Inactive</span>
                                    @else<span class="badge-app badge-gold" style="font-size:10px;">Pending</span>@endif
                                    @if($m->is_refer_member)
                                        <span class="badge-app badge-teal" style="font-size:10px;">Refer</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach

    </div>{{-- /reportTab_tree --}}
    @endif {{-- /is_refer_member --}}

    <div style="height:8px;"></div>
@endsection

@push('scripts')
<script>
// ── Top report tab switcher ──
function switchReport(tab, btn) {
    ['wallet', 'income', 'tree'].forEach(function(t) {
        var el = document.getElementById('reportTab_' + t);
        if (el) el.style.display = (t === tab) ? '' : 'none';
    });
    document.querySelectorAll('#reportTopTabs button').forEach(function(b) {
        b.classList.remove('active');
    });
    if (btn) btn.classList.add('active');
}

// ── Wallet sub-tab switcher ──
function switchWalletSub(tab, btn) {
    ['inr', 'coin', 'ref', 'gold'].forEach(function(p) {
        var el = document.getElementById('walletSub_' + p);
        if (el) el.style.display = (p === tab) ? '' : 'none';
    });
    document.querySelectorAll('#walletSubTabs button').forEach(function(b) {
        b.classList.remove('active');
    });
    if (btn) btn.classList.add('active');
}

// ── Income sub-tab switcher ──
function switchIncomeSub(tab, btn) {
    ['l1', 'l2', 'l3'].forEach(function(p) {
        var el = document.getElementById('incomeSub_' + p);
        if (el) el.style.display = (p === tab) ? '' : 'none';
    });
    document.querySelectorAll('#incomeSubTabs button').forEach(function(b) {
        b.classList.remove('active');
    });
    if (btn) btn.classList.add('active');
}

// ── Tree sub-tab switcher ──
function switchTreeSub(tab, btn) {
    ['l1', 'l2', 'l3'].forEach(function(p) {
        var el = document.getElementById('treeSub_' + p);
        if (el) el.style.display = (p === tab) ? '' : 'none';
    });
    document.querySelectorAll('#treeSubTabs button').forEach(function(b) {
        b.classList.remove('active');
    });
    if (btn) btn.classList.add('active');
}
</script>
@endpush