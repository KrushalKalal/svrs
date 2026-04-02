{{-- This view is now served by the new Referral Network page (referral_network.blade.php) --}}
{{-- Redirect handled by controller, but keeping this as alias just in case --}}
@extends('member.layout.app-layout')
@section('title', 'Referral Network')
@section('nav-title', 'Referral Network')
@section('nav-back') @endsection
@section('nav-back-url', route('member.profile'))

@section('content')
    @php
        $user = auth()->user();
        $activeTab = request('tab', 'overview');
        $membership = \App\Models\MemberMembership::where('user_id', $user->id)->where('status', 1)->first();

        // already computed in controller — use passed vars with fallback
        $level1 = $level1 ?? collect();
        $level2 = $level2 ?? collect();
        $level3 = $level3 ?? collect();
        $earnings = $earnings ?? [1 => 0, 2 => 0, 3 => 0];

        $earn1 = $earnings[1] ?? 0;
        $earn2 = $earnings[2] ?? 0;
        $earn3 = $earnings[3] ?? 0;

        $allEarnings = \App\Models\ReferralReward::with('fromUser')
            ->where('earner_id', $user->id)->latest()->get();
    @endphp

    {{-- Segment tabs --}}
    <div style="padding:16px 20px 0;">
        <div class="segment-ctrl" id="refNetTabs">
            <button class="{{ $activeTab === 'overview'  ? 'active' : '' }}" onclick="switchRefNet('overview')">Overview</button>
            <button class="{{ $activeTab === 'team'      ? 'active' : '' }}" onclick="switchRefNet('team')">Team</button>
            <button class="{{ $activeTab === 'earnings'  ? 'active' : '' }}" onclick="switchRefNet('earnings')">Earnings</button>
        </div>
    </div>

    {{-- ══ OVERVIEW TAB ══ --}}
    <div id="refNetOverview" style="{{ $activeTab === 'overview' ? '' : 'display:none;' }}">
        <div style="margin:16px 20px 0;">
            <div class="gold-card" style="padding:20px;text-align:center;">
                <p style="font-size:12px;color:rgba(240,165,0,0.7);margin-bottom:8px;letter-spacing:0.5px;">Your Referral Code</p>
                <div style="font-size:28px;font-weight:800;letter-spacing:4px;font-family:'Space Mono',monospace;color:var(--gold);margin-bottom:16px;">
                    {{ $membership->refer_code ?? $user->member_code }}
                </div>
                <div style="display:flex;gap:10px;justify-content:center;">
                    <button onclick="copyText('{{ $membership->refer_code ?? $user->member_code }}')"
                        style="flex:1;padding:10px;border-radius:10px;background:rgba(240,165,0,0.12);border:1px solid rgba(240,165,0,0.3);color:var(--gold);font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;">
                        <i class="fa fa-copy"></i> Copy Code
                    </button>
                    @if($membership && $membership->refer_link)
                    <button onclick="copyText_val('{{ $membership->refer_link }}')"
                        style="flex:1;padding:10px;border-radius:10px;background:rgba(0,212,170,0.12);border:1px solid rgba(0,212,170,0.3);color:var(--accent);font-size:13px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;">
                        <i class="fa fa-link"></i> Copy Link
                    </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="section-label">Statistics</div>
        <div style="margin:0 20px;display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <div class="app-card app-card-inner">
                <div style="width:36px;height:36px;border-radius:10px;background:rgba(0,212,170,0.12);display:flex;align-items:center;justify-content:center;font-size:16px;color:var(--accent);margin-bottom:10px;">
                    <i class="fa fa-users"></i>
                </div>
                <p style="font-size:24px;font-weight:800;color:var(--accent);">{{ $level1->count() + $level2->count() + $level3->count() }}</p>
                <p style="font-size:12px;color:var(--muted);">Total Referrals</p>
            </div>
            <div class="app-card app-card-inner">
                <div style="width:36px;height:36px;border-radius:10px;background:rgba(0,212,170,0.12);display:flex;align-items:center;justify-content:center;font-size:16px;color:var(--accent);margin-bottom:10px;">
                    <i class="fa fa-shield-halved"></i>
                </div>
                <p style="font-size:24px;font-weight:800;color:var(--accent);">{{ $level1->where('status', 1)->count() }}</p>
                <p style="font-size:12px;color:var(--muted);">Active Members</p>
            </div>
        </div>

        <div class="section-label">How It Works</div>
        <div style="margin:0 20px;" class="app-card">
            @foreach([
                ['1', 'gold',        'Level 1', 'Direct referrals — highest reward %'],
                ['2', 'teal',        'Level 2', "Your referral's referrals"],
                ['3', 'accent-blue', 'Level 3', 'Third level network'],
            ] as [$num, $color, $title, $desc])
            <div class="list-row">
                <div style="width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:800;color:var(--{{ $color }});flex-shrink:0;border:1.5px solid rgba(240,165,0,0.25);">
                    {{ $num }}
                </div>
                <div class="list-body">
                    <div class="title" style="color:var(--{{ $color }});">{{ $title }}</div>
                    <div class="sub">{{ $desc }}</div>
                </div>
            </div>
            @endforeach
        </div>
        <div style="height:8px;"></div>
    </div>

    {{-- ══ TEAM TAB ══ --}}
    <div id="refNetTeam" style="{{ $activeTab === 'team' ? '' : 'display:none;' }}">
        <div style="padding:12px 20px 0;">
            <div class="segment-ctrl" id="teamLevelTabs">
                <button class="active" onclick="switchTeamLevel('l1', this)">L1 ({{ $level1->count() }})</button>
                <button onclick="switchTeamLevel('l2', this)">L2 ({{ $level2->count() }})</button>
                <button onclick="switchTeamLevel('l3', this)">L3 ({{ $level3->count() }})</button>
            </div>
        </div>

        @foreach([['l1', $level1, 1], ['l2', $level2, 2], ['l3', $level3, 3]] as [$pid, $members, $lvl])
        <div id="teamLevel_{{ $pid }}" style="{{ $pid === 'l1' ? '' : 'display:none;' }}padding:10px 20px 0;">
            @if($members->isEmpty())
                <div class="empty-state"><i class="fa fa-users"></i><p>No members at this level</p></div>
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
                        <div style="display:flex;flex-direction:column;gap:4px;align-items:flex-end;">
                            @if($m->status == 1)<span class="badge-app badge-green" style="font-size:10px;">Active</span>
                            @elseif($m->status == 0)<span class="badge-app badge-red" style="font-size:10px;">Inactive</span>
                            @else<span class="badge-app badge-gold" style="font-size:10px;">Pending</span>@endif
                            @if($m->is_refer_member)<span class="badge-app badge-teal" style="font-size:10px;">Refer</span>@endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
        @endforeach
        <div style="height:8px;"></div>
    </div>

    {{-- ══ EARNINGS TAB ══ --}}
    <div id="refNetEarnings" style="{{ $activeTab === 'earnings' ? '' : 'display:none;' }}">
        <div style="margin:16px 20px 0;">
            <div class="gold-card" style="padding:16px;">
                <p style="font-size:12px;color:rgba(240,165,0,0.7);text-align:center;margin-bottom:14px;">Total Coin Earnings by Level</p>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;text-align:center;">
                    <div>
                        <p style="font-size:11px;color:rgba(240,165,0,0.7);margin-bottom:4px;">L1</p>
                        <p style="font-size:20px;font-weight:800;color:var(--gold);">{{ number_format($earn1, 4) }}</p>
                        <p style="font-size:10px;color:var(--muted);">SVRS</p>
                    </div>
                    <div style="border-left:1px solid rgba(240,165,0,0.2);border-right:1px solid rgba(240,165,0,0.2);">
                        <p style="font-size:11px;color:rgba(0,212,170,0.7);margin-bottom:4px;">L2</p>
                        <p style="font-size:20px;font-weight:800;color:var(--accent);">{{ number_format($earn2, 4) }}</p>
                        <p style="font-size:10px;color:var(--muted);">SVRS</p>
                    </div>
                    <div>
                        <p style="font-size:11px;color:rgba(59,130,246,0.7);margin-bottom:4px;">L3</p>
                        <p style="font-size:20px;font-weight:800;color:var(--accent-blue);">{{ number_format($earn3, 4) }}</p>
                        <p style="font-size:10px;color:var(--muted);">SVRS</p>
                    </div>
                </div>
            </div>
        </div>

        <div style="padding:12px 20px 0;">
            @if($allEarnings->isEmpty())
                <div class="empty-state"><i class="fa fa-coins"></i><p>No referral earnings yet</p></div>
            @else
                <div class="app-card" style="overflow:hidden;">
                    @foreach($allEarnings as $rw)
                    <div class="list-row">
                        <div class="list-icon {{ $rw->level == 1 ? 'gold' : ($rw->level == 2 ? 'teal' : 'blue') }}" style="width:40px;height:40px;border-radius:12px;font-size:13px;">
                            L{{ $rw->level }}
                        </div>
                        <div class="list-body">
                            <div class="title">{{ $rw->fromUser->full_name ?? '—' }}</div>
                            <div class="sub">{{ $rw->percentage }}% • {{ $rw->created_at->format('d M Y, h:i A') }}</div>
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
        <div style="height:8px;"></div>
    </div>

@endsection

@push('scripts')
<script>
var _refNetActive = '{{ $activeTab }}';
function switchRefNet(tab) {
    ['overview','team','earnings'].forEach(function(t) {
        var el = document.getElementById('refNet' + t.charAt(0).toUpperCase() + t.slice(1));
        if (el) el.style.display = (t === tab) ? '' : 'none';
    });
    document.querySelectorAll('#refNetTabs button').forEach(function(b, i) {
        b.classList.toggle('active', ['overview','team','earnings'][i] === tab);
    });
}
function switchTeamLevel(level, btn) {
    ['l1','l2','l3'].forEach(function(l) {
        var el = document.getElementById('teamLevel_' + l);
        if (el) el.style.display = (l === level) ? '' : 'none';
    });
    document.querySelectorAll('#teamLevelTabs button').forEach(function(b){ b.classList.remove('active'); });
    if (btn) btn.classList.add('active');
}
function copyText_val(val) {
    navigator.clipboard.writeText(val);
    if (typeof toastr !== 'undefined') toastr.success('Copied!');
}
document.addEventListener('DOMContentLoaded', function() { switchRefNet(_refNetActive); });
</script>
@endpush