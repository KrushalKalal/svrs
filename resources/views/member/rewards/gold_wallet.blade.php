@extends('member.layout.app-layout')
@section('title', 'Gold Coin Wallet')
@section('nav-title', 'Gold Coin Wallet')
@section('nav-back') @endsection
@section('nav-back-url', route('member.profile'))

@section('content')

    {{-- 3 stat cards --}}
    <div style="padding:16px 20px 0;display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div class="gold-card app-card-inner" style="text-align:center;grid-column:1/-1;">
            <i class="fa fa-coins" style="font-size:36px;color:var(--gold);margin-bottom:8px;display:block;"></i>
            <p style="font-size:36px;font-weight:800;color:var(--gold);line-height:1;">{{ number_format($wallet->balance ?? 0) }}</p>
            <p style="font-size:13px;color:var(--muted);margin-top:4px;">Available G-Coins</p>
        </div>
        <div class="app-card app-card-inner" style="text-align:center;">
            <p style="font-size:11px;color:var(--muted);margin-bottom:4px;">INR Equivalent</p>
            <p style="font-size:20px;font-weight:800;color:var(--green);">₹{{ number_format(($wallet->balance ?? 0) / 10, 2) }}</p>
            <p style="font-size:11px;color:var(--muted);">10 G-Coins = ₹1</p>
        </div>
        <div class="app-card app-card-inner" style="text-align:center;">
            <p style="font-size:11px;color:var(--muted);margin-bottom:4px;">Total Earned</p>
            <p style="font-size:20px;font-weight:800;color:var(--accent-blue);">{{ number_format($wallet->total_earned ?? 0) }}</p>
            <p style="font-size:11px;color:var(--muted);">≈ ₹{{ number_format(($wallet->total_earned ?? 0) / 10, 2) }}</p>
        </div>
    </div>

    <div style="margin:12px 20px 0;">
        <div class="alert-app gold-alert">
            <i class="fa fa-circle-info" style="color:var(--gold);"></i>
            <span>G-Coins are display-only rewards. 10 G-Coins = ₹1 INR. Coins are credited when admin approves your milestone claims. Lifetime maximum: 25,000 G-Coins (₹2,500).</span>
        </div>
    </div>

    <div class="section-label">Transaction History</div>

    @if($transactions->isEmpty())
        <div class="empty-state">
            <i class="fa fa-coins"></i>
            <p>No transactions yet. Claim your first milestone reward!</p>
        </div>
        <div style="padding:0 20px;">
            <a href="{{ route('member.my.rewards') }}" class="btn-app btn-gold">View Milestones</a>
        </div>
    @else
        <div style="margin:0 20px;" class="app-card" style="overflow:hidden;">
            @foreach($transactions as $txn)
                <div class="list-row">
                    <div class="list-icon {{ $txn->type==='credit'?'gold':'red' }}" style="width:40px;height:40px;border-radius:12px;font-size:15px;">
                        <i class="fa fa-{{ $txn->type==='credit'?'arrow-down':'arrow-up' }}"></i>
                    </div>
                    <div class="list-body">
                        <div class="title">{{ $txn->remark ?? ucfirst($txn->type) }}</div>
                        <div class="sub">{{ $txn->created_at->format('d M Y, h:i A') }}</div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:15px;font-weight:700;color:{{ $txn->type==='credit'?'var(--gold)':'var(--red)' }};">
                            {{ $txn->type==='credit'?'+':'-' }}{{ number_format($txn->amount) }} G
                        </div>
                        <div style="font-size:11px;color:var(--muted);">₹{{ number_format($txn->amount/10,2) }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div style="height:8px;"></div>
@endsection