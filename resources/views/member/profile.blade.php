@extends('member.layout.app-layout')
@section('title', 'Profile')
@section('nav-title', 'Profile')
@section('hide-member-code') @endsection

@section('nav-actions')
    <a href="{{ route('member.profile') }}?tab=edit" class="nav-action-btn" title="Edit Profile">
        <i class="fa fa-pen"></i>
    </a>
@endsection

@section('content')
    @php $user = auth()->user(); @endphp

    {{-- Profile hero card --}}
    <div style="padding:16px 20px 0;">
        <a href="{{ route('member.profile') }}?tab=account" class="gold-card"
            style="padding:16px;display:flex;align-items:center;gap:14px;text-decoration:none;">
            <div
                style="width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg,var(--gold),var(--gold-dark));display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:800;color:#000;flex-shrink:0;overflow:hidden;border:2px solid rgba(240,165,0,0.5);">
                @if($user->profile_image)
                    <img src="{{ asset($user->profile_image) }}" style="width:100%;height:100%;object-fit:cover;">
                @else
                    {{ strtoupper(substr($user->first_name, 0, 1)) }}
                @endif
            </div>
            <div style="flex:1;min-width:0;">
                <h3 style="font-size:17px;font-weight:800;margin-bottom:2px;">{{ $user->full_name }}</h3>
                <p style="font-size:13px;color:var(--muted);margin-bottom:8px;">{{ $user->email }}</p>
                <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
                    <div class="stat-pill gold" style="cursor:pointer;"
                        onclick="event.preventDefault();copyText('{{ $user->member_code }}')">
                        <i class="fa fa-copy" style="font-size:10px;"></i>
                        {{ $user->member_code }}
                    </div>
                    @if($user->is_refer_member)
                        <div class="badge-app badge-teal">
                            <i class="fa fa-circle-check" style="margin-right:4px;font-size:10px;"></i>Refer Member
                        </div>
                    @endif
                </div>
            </div>
            <i class="fa fa-chevron-right" style="color:var(--muted);font-size:13px;"></i>
        </a>
    </div>

    {{-- Upgrade banner (non-refer members) --}}
    @if(!$user->is_refer_member)
        <div style="margin:12px 20px 0;">
            <a href="{{ route('member.membership') }}" class="upgrade-banner">
                <div class="ub-icon"><i class="fa fa-medal"></i></div>
                <div class="ub-body">
                    <div class="ub-title">Upgrade to Refer &amp; Earn</div>
                    <div class="ub-sub">Unlock referrals, gold rewards &amp; milestones</div>
                </div>
                <i class="fa fa-chevron-right ub-arrow"></i>
            </a>
        </div>
    @endif

    {{-- REFERRAL & REWARDS — refer members only --}}
    @if($user->is_refer_member)
        <div class="section-label">Referral &amp; Rewards</div>
        <div style="margin:0 20px;" class="app-card">
            <a href="{{ route('member.my.referrals') }}" class="list-row">
                <div class="list-icon teal" style="width:40px;height:40px;border-radius:12px;font-size:16px;">
                    <i class="fa fa-users"></i>
                </div>
                <div class="list-body">
                    <div class="title">Referral Network</div>
                    <div class="sub">My team &amp; earnings</div>
                </div>
                <i class="fa fa-chevron-right list-chevron"></i>
            </a>
            <a href="{{ route('member.my.rewards') }}" class="list-row">
                <div class="list-icon gold" style="width:40px;height:40px;border-radius:12px;font-size:16px;">
                    <i class="fa fa-trophy"></i>
                </div>
                <div class="list-body">
                    <div class="title">Milestone Rewards</div>
                    <div class="sub">Claim Gold Coins</div>
                </div>
                <i class="fa fa-chevron-right list-chevron"></i>
            </a>
            {{-- ✅ Points to NEW combined reports page --}}
            <a href="{{ route('member.reports') }}" class="list-row">
                <div class="list-icon blue" style="width:40px;height:40px;border-radius:12px;font-size:16px;">
                    <i class="fa fa-chart-bar"></i>
                </div>
                <div class="list-body">
                    <div class="title">Reports</div>
                    <div class="sub">Income, ledger &amp; referral tree</div>
                </div>
                <i class="fa fa-chevron-right list-chevron"></i>
            </a>
            <a href="{{ route('member.add.member') }}" class="list-row">
                <div class="list-icon teal"
                    style="width:40px;height:40px;border-radius:12px;font-size:16px;background:rgba(0,212,170,0.12);">
                    <i class="fa fa-user-plus"></i>
                </div>
                <div class="list-body">
                    <div class="title">Add New Member</div>
                    <div class="sub">Register a member under you</div>
                </div>
                <i class="fa fa-chevron-right list-chevron"></i>
            </a>
        </div>
    @endif

    {{-- Account --}}
    <div class="section-label">Account</div>
    <div style="margin:0 20px;" class="app-card">
        <a href="{{ route('member.profile') }}?tab=edit" class="list-row">
            <div class="list-icon blue" style="width:40px;height:40px;border-radius:12px;font-size:16px;">
                <i class="fa fa-user"></i>
            </div>
            <div class="list-body">
                <div class="title">My Profile</div>
                <div class="sub">View &amp; edit account details</div>
            </div>
            <i class="fa fa-chevron-right list-chevron"></i>
        </a>
        <a href="{{ route('member.profile') }}?tab=password" class="list-row">
            <div class="list-icon gold" style="width:40px;height:40px;border-radius:12px;font-size:16px;">
                <i class="fa fa-lock"></i>
            </div>
            <div class="list-body">
                <div class="title">Change Password</div>
                <div class="sub">Update your credentials</div>
            </div>
            <i class="fa fa-chevron-right list-chevron"></i>
        </a>
        {{-- ✅ Points to NEW combined reports page --}}
        <a href="{{ route('member.reports') }}" class="list-row">
            <div class="list-icon blue" style="width:40px;height:40px;border-radius:12px;font-size:16px;">
                <i class="fa fa-chart-bar"></i>
            </div>
            <div class="list-body">
                <div class="title">Reports</div>
                <div class="sub">Wallet ledger &amp; transactions</div>
            </div>
            <i class="fa fa-chevron-right list-chevron"></i>
        </a>
    </div>

    {{-- Information --}}
    <div class="section-label">Information</div>
    <div style="margin:0 20px;" class="app-card">
        <a href="#" class="list-row">
            <div class="list-icon teal" style="width:40px;height:40px;border-radius:12px;font-size:16px;">
                <i class="fa fa-shield-halved"></i>
            </div>
            <div class="list-body">
                <div class="title">Privacy Policy</div>
            </div>
            <i class="fa fa-chevron-right list-chevron"></i>
        </a>
        <a href="#" class="list-row">
            <div class="list-icon blue" style="width:40px;height:40px;border-radius:12px;font-size:16px;">
                <i class="fa fa-scale-balanced"></i>
            </div>
            <div class="list-body">
                <div class="title">Terms &amp; Conditions</div>
            </div>
            <i class="fa fa-chevron-right list-chevron"></i>
        </a>
        <a href="{{ route('member.my.wallet') }}" class="list-row">
            <div class="list-icon green" style="width:40px;height:40px;border-radius:12px;font-size:16px;">
                <i class="fa fa-building-columns"></i>
            </div>
            <div class="list-body">
                <div class="title">Deposit Info</div>
                <div class="sub">Min &amp; max deposit limits</div>
            </div>
            <i class="fa fa-chevron-right list-chevron"></i>
        </a>
        <a href="{{ route('member.customer.support') }}" class="list-row">
            <div class="list-icon teal"
                style="width:40px;height:40px;border-radius:12px;font-size:16px;background:rgba(0,212,170,0.12);">
                <i class="fa fa-headset"></i>
            </div>
            <div class="list-body">
                <div class="title">Contact Us</div>
                <div class="sub">Get support</div>
            </div>
            <i class="fa fa-chevron-right list-chevron"></i>
        </a>
    </div>

    {{-- App info --}}
    <div style="margin:16px 20px 0;">
        <div class="app-card app-card-inner" style="display:flex;align-items:center;gap:14px;">
            <div
                style="width:44px;height:44px;border-radius:14px;background:linear-gradient(135deg,var(--gold),var(--gold-dark));display:flex;align-items:center;justify-content:center;font-size:22px;color:#000;flex-shrink:0;">
                <i class="fa-solid fa-dollar-sign"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:14px;font-weight:700;">SVRS Coin</div>
                <div style="font-size:12px;color:var(--muted);">Smart Value Reward System • v1.0.0</div>
            </div>
            <div class="badge-app badge-teal" style="font-size:11px;">Up to date</div>
        </div>
    </div>

    <div style="height:8px;"></div>
@endsection