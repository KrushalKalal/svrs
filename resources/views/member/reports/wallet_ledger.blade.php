@extends('member.layout.app-layout')
@section('title', 'Wallet Ledger')
@section('nav-title', 'Wallet Ledger')
@section('nav-back') @endsection
@section('nav-back-url', route('member.profile'))

@section('content')

    {{-- 4 summary stats --}}
    <div style="padding:16px 20px 0;display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div class="app-card app-card-inner">
            <p style="font-size:11px;color:var(--muted);margin-bottom:3px;">INR Balance</p>
            <p style="font-size:18px;font-weight:800;color:var(--green);">₹{{ number_format($wallet->balance??0,2) }}</p>
        </div>
        <div class="app-card app-card-inner">
            <p style="font-size:11px;color:var(--muted);margin-bottom:3px;">Total Deposited</p>
            <p style="font-size:18px;font-weight:800;color:var(--accent-blue);">₹{{ number_format($walletTransactions->where('type','credit')->where('status',1)->sum('amount'),2) }}</p>
        </div>
        <div class="app-card app-card-inner">
            <p style="font-size:11px;color:var(--muted);margin-bottom:3px;">Total Withdrawn</p>
            <p style="font-size:18px;font-weight:800;color:var(--red);">₹{{ number_format($walletTransactions->where('type','debit')->where('status',1)->sum('amount'),2) }}</p>
        </div>
        <div class="app-card app-card-inner">
            <p style="font-size:11px;color:var(--muted);margin-bottom:3px;">Referral Coins</p>
            <p style="font-size:18px;font-weight:800;color:var(--gold);">{{ number_format($referralRewards->sum('reward_quantity'),4) }}</p>
        </div>
    </div>

    {{-- Tab switcher --}}
    <div style="padding:16px 20px 0;">
        <div class="segment-ctrl" id="ledgerTabs">
            <button class="active" onclick="switchLedger('inr',this)">INR</button>
            <button onclick="switchLedger('coin',this)">Coins</button>
            @if(auth()->user()->is_refer_member)
                <button onclick="switchLedger('ref',this)">Referral</button>
                <button onclick="switchLedger('gold',this)">Gold</button>
            @endif
        </div>
    </div>

    {{-- INR Transactions --}}
    <div id="panelInr" style="padding:12px 20px 0;">
        @if($walletTransactions->isEmpty())
            <div class="empty-state"><i class="fa fa-receipt"></i><p>No INR transactions yet</p></div>
        @else
            <div class="app-card" style="overflow:hidden;">
                @foreach($walletTransactions as $txn)
                    <div class="list-row">
                        <div class="list-icon {{ $txn->type==='credit'?'teal':'red' }}" style="width:40px;height:40px;border-radius:12px;font-size:15px;">
                            <i class="fa fa-arrow-{{ $txn->type==='credit'?'down':'up' }}"></i>
                        </div>
                        <div class="list-body">
                            <div class="title">{{ $txn->remark ?? ucfirst($txn->type) }}</div>
                            <div class="sub">{{ $txn->created_at->format('d M Y, h:i A') }}</div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:14px;font-weight:700;color:{{ $txn->type==='credit'?'var(--green)':'var(--red)' }};">
                                {{ $txn->type==='credit'?'+':'-' }}₹{{ number_format($txn->amount,2) }}
                            </div>
                            <div style="margin-top:3px;">
                                @if($txn->status==1)<span class="badge-app badge-green" style="font-size:10px;">Approved</span>
                                @elseif($txn->status==0)<span class="badge-app badge-red" style="font-size:10px;">Rejected</span>
                                @else<span class="badge-app badge-gold" style="font-size:10px;">Pending</span>@endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Coin Trades --}}
    <div id="panelCoin" style="display:none;padding:12px 20px 0;">
        @if($coinTrades->isEmpty())
            <div class="empty-state"><i class="fa fa-coins"></i><p>No coin trades yet</p></div>
        @else
            <div class="app-card" style="overflow:hidden;">
                @foreach($coinTrades as $trade)
                    <div class="list-row">
                        <div class="list-icon {{ $trade->type==='buy'?'green':($trade->type==='reward'?'gold':'red') }}" style="width:40px;height:40px;border-radius:12px;font-size:15px;">
                            <i class="fa fa-{{ $trade->type==='buy'?'arrow-down':($trade->type==='reward'?'gift':'arrow-up') }}"></i>
                        </div>
                        <div class="list-body">
                            <div class="title">{{ ucfirst($trade->type) }}</div>
                            <div class="sub">{{ $trade->created_at->format('d M Y, h:i A') }}</div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:14px;font-weight:700;">{{ number_format($trade->quantity,4) }} SVRS</div>
                            <div style="font-size:11px;color:var(--muted);">₹{{ number_format($trade->price,4) }}/coin</div>
                            <div style="font-size:11px;color:var(--muted);">₹{{ number_format($trade->total??($trade->price*$trade->quantity),2) }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Referral Rewards --}}
    @if(auth()->user()->is_refer_member)
        <div id="panelRef" style="display:none;padding:12px 20px 0;">
            @if($referralRewards->isEmpty())
                <div class="empty-state"><i class="fa fa-share-nodes"></i><p>No referral rewards yet</p></div>
            @else
                <div class="app-card" style="overflow:hidden;">
                    @foreach($referralRewards as $rw)
                        <div class="list-row">
                            <div class="list-icon blue" style="width:40px;height:40px;border-radius:12px;font-size:15px;"><i class="fa fa-user-group"></i></div>
                            <div class="list-body">
                                <div class="title">{{ $rw->fromUser->full_name ?? '—' }}</div>
                                <div class="sub">Level {{ $rw->level }} • {{ $rw->percentage }}% • {{ $rw->created_at->format('d M Y') }}</div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-size:14px;font-weight:700;color:var(--green);">+{{ number_format($rw->reward_quantity,4) }}</div>
                                <div style="font-size:11px;color:var(--muted);">SVRS</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Gold Coins --}}
        <div id="panelGold" style="display:none;padding:12px 20px 0;">
            @if($goldTransactions->isEmpty())
                <div class="empty-state"><i class="fa fa-coins"></i><p>No G-Coin transactions yet</p></div>
            @else
                <div class="app-card" style="overflow:hidden;">
                    @foreach($goldTransactions as $gt)
                        <div class="list-row">
                            <div class="list-icon {{ $gt->type==='credit'?'gold':'red' }}" style="width:40px;height:40px;border-radius:12px;font-size:15px;">
                                <i class="fa fa-{{ $gt->type==='credit'?'arrow-down':'arrow-up' }}"></i>
                            </div>
                            <div class="list-body">
                                <div class="title">{{ $gt->remark ?? ucfirst($gt->type) }}</div>
                                <div class="sub">{{ $gt->created_at->format('d M Y, h:i A') }}</div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-size:14px;font-weight:700;color:{{ $gt->type==='credit'?'var(--gold)':'var(--red)' }};">
                                    {{ $gt->type==='credit'?'+':'-' }}{{ number_format($gt->amount) }} G
                                </div>
                                <div style="font-size:11px;color:var(--muted);">₹{{ number_format($gt->amount/10,2) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <div style="height:8px;"></div>
@endsection

@push('scripts')
<script>
var panels = ['inr','coin','ref','gold'];
function switchLedger(tab, btn) {
    panels.forEach(function(p) {
        var el = document.getElementById('panel' + p.charAt(0).toUpperCase() + p.slice(1));
        if (el) el.style.display = (p === tab) ? '' : 'none';
    });
    document.querySelectorAll('#ledgerTabs button').forEach(function(b){ b.classList.remove('active'); });
    if (btn) btn.classList.add('active');
}
</script>
@endpush