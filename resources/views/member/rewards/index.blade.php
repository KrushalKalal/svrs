@extends('member.layout.app-layout')
@section('title', 'My Rewards')
@section('nav-title', 'My Rewards')
@section('nav-back') @endsection
@section('nav-back-url', route('member.profile'))

@section('content')

    {{-- Active referral count --}}
    <div style="margin:16px 20px 0;">
        <div class="blue-card" style="padding:14px 16px;display:flex;align-items:center;gap:10px;">
            <div class="list-icon teal" style="width:36px;height:36px;border-radius:10px;font-size:16px;flex-shrink:0;"><i
                    class="fa fa-users"></i></div>
            <div>
                <div style="font-size:14px;font-weight:700;">Active Refer Members (Level 1):
                    {{ $eligibility['referral_count'] }}</div>
                <div style="font-size:12px;color:var(--muted);">Only activated members who paid ₹519 count toward
                    milestones.</div>
            </div>
        </div>
    </div>

    {{-- Milestone cards --}}
    <div style="padding:16px 20px 0;display:flex;flex-direction:column;gap:12px;">
        @foreach($eligibility['milestones'] as $milestone)
            @php
                $claimed = $milestone['claim'] && $milestone['claim']->status == 1;
                $pending = $milestone['claim'] && $milestone['claim']->status == 2;
                $canClaim = $milestone['can_claim'] ?? false;
                $pct = $milestone['tier']->required_referrals > 0
                    ? min(100, ($milestone['progress'] / $milestone['tier']->required_referrals) * 100) : 0;
            @endphp
            <div class="milestone-card {{ $claimed ? 'completed' : '' }}">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                    <div>
                        <p style="font-size:15px;font-weight:700;">{{ $milestone['tier']->name }}</p>
                        <p style="font-size:12px;color:var(--muted);">=
                            ₹{{ number_format($milestone['tier']->g_coins / 10, 2) }} INR value</p>
                    </div>
                    <div style="text-align:right;">
                        <p style="font-size:26px;font-weight:800;color:var(--gold);line-height:1;">
                            {{ number_format($milestone['tier']->g_coins) }}</p>
                        <p style="font-size:12px;color:var(--muted);">G-Coins</p>
                    </div>
                </div>
                <div style="margin-bottom:8px;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
                        <span style="font-size:12px;color:var(--muted);">Progress</span>
                        <span style="font-size:12px;color:var(--muted);">{{ $milestone['progress'] }} /
                            {{ $milestone['tier']->required_referrals }}</span>
                    </div>
                    <div class="progress-app">
                        <div class="progress-fill {{ $claimed ? 'green' : '' }}" style="width:{{ $pct }}%;"></div>
                    </div>
                    <p style="font-size:11px;color:var(--muted);margin-top:4px;">Need
                        {{ $milestone['tier']->required_referrals }} active refer members</p>
                </div>

                @if($claimed)
                    <div class="badge-app badge-green" style="font-size:12px;padding:8px 14px;width:100%;justify-content:center;">
                        <i class="fa fa-circle-check" style="margin-right:6px;"></i>
                        Approved on {{ $milestone['claim']->approved_at?->format('d M Y') }}
                    </div>
                @elseif($pending)
                    <div class="badge-app badge-gold" style="font-size:12px;padding:8px 14px;width:100%;justify-content:center;">
                        <i class="fa fa-clock" style="margin-right:6px;"></i>Claim under review
                    </div>
                @elseif($canClaim)
                    <button class="btn-app btn-gold claim-btn" style="padding:12px;" data-tier="{{ $milestone['tier']->id }}"
                        data-name="{{ $milestone['tier']->name }}" data-coins="{{ number_format($milestone['tier']->g_coins) }}">
                        <i class="fa fa-gift"></i> Claim {{ number_format($milestone['tier']->g_coins) }} G-Coins
                    </button>
                @elseif(isset($milestone['eligible']) && $milestone['eligible'] && !$canClaim)
                    <div style="font-size:12px;color:var(--muted);padding:8px 0;">
                        <i class="fa fa-lock"
                            style="margin-right:6px;"></i>{{ $milestone['reason'] ?? 'Claim previous milestone first' }}
                    </div>
                @else
                    <div style="font-size:12px;color:var(--muted);padding:8px 0;">
                        <i class="fa fa-lock" style="margin-right:6px;"></i>
                        Need {{ $milestone['tier']->required_referrals - $milestone['progress'] }} more referrals
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    {{-- How it works --}}
    <div class="section-label">How It Works</div>
    <div style="margin:0 20px;" class="app-card">
        <div class="list-row">
            <div class="list-icon blue" style="width:36px;height:36px;border-radius:10px;font-size:16px;"><i
                    class="fa fa-users"></i></div>
            <div class="list-body">
                <div class="title">10 Referrals</div>
                <div class="sub">First Reward: 10,000 G-Coins</div>
            </div>
        </div>
        <div class="list-row">
            <div class="list-icon gold" style="width:36px;height:36px;border-radius:10px;font-size:16px;"><i
                    class="fa fa-trophy"></i></div>
            <div class="list-body">
                <div class="title">15 Referrals</div>
                <div class="sub">Option A: 5,000 G-Coins</div>
            </div>
        </div>
        <div class="list-row">
            <div class="list-icon teal" style="width:36px;height:36px;border-radius:10px;font-size:16px;"><i
                    class="fa fa-award"></i></div>
            <div class="list-body">
                <div class="title">20 Referrals</div>
                <div class="sub">Option B: 10,000 G-Coins</div>
            </div>
        </div>
    </div>
    <div style="margin:12px 20px 0;">
        <div class="alert-app gold-alert">
            <i class="fa fa-circle-info" style="color:var(--gold);"></i>
            <span><strong>10 G-Coins = ₹1 INR</strong> &nbsp;|&nbsp; Lifetime max: 25,000 G-Coins (₹2,500) &nbsp;|&nbsp;
                Must claim in order.</span>
        </div>
    </div>

    <div style="height:8px;"></div>

@endsection

@push('scripts')
    <script>
        $(document).on('click', '.claim-btn', function () {
            var tierId = $(this).data('tier');
            var name = $(this).data('name');
            var coins = $(this).data('coins');
            Swal.fire({
                background: '#0D1626', color: '#fff',
                title: 'Claim ' + coins + ' G-Coins?',
                text: name + ' — Your claim will be reviewed by admin.',
                showCancelButton: true,
                confirmButtonText: 'Yes, Claim!',
                confirmButtonColor: '#F0A500',
                cancelButtonColor: '#1E2D45',
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('member.claim.reward') }}",
                        type: 'POST',
                        data: { tier_id: tierId, _token: "{{ csrf_token() }}" },
                        success: function (res) {
                            if (res.status) { toastr.success(res.message); setTimeout(function () { location.reload(); }, 1500); }
                            else toastr.error(res.message);
                        },
                        error: function () { toastr.error('Something went wrong.'); }
                    });
                }
            });
        });
    </script>
@endpush


