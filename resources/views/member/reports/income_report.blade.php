@extends('member.layout.app-layout')
@section('title', 'Income Report')
@section('nav-title', 'Income Report')
@section('nav-back') @endsection
@section('nav-back-url', route('member.profile'))

@section('content')

    {{-- Summary --}}
    <div style="padding:16px 20px 0;display:grid;grid-template-columns:1fr 1fr;gap:12px;">
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

    {{-- Level tabs --}}
    <div style="padding:16px 20px 0;">
        <div class="segment-ctrl" id="incomeTabs">
            <button class="active" onclick="switchIncome('l1',this)">Level 1 ({{ $level1Rewards->count() }})</button>
            <button onclick="switchIncome('l2',this)">Level 2 ({{ $level2Rewards->count() }})</button>
            <button onclick="switchIncome('l3',this)">Level 3 ({{ $level3Rewards->count() }})</button>
        </div>
    </div>

    @foreach([['l1', $level1Rewards, $totalLevel1], ['l2', $level2Rewards, $totalLevel2], ['l3', $level3Rewards, $totalLevel3]] as [$pid, $rewards, $total])
        <div id="panel{{ strtoupper($pid) }}" style="{{ $pid === 'l1' ? '' : 'display:none;' }}padding:12px 20px 0;">
            @if($rewards->isEmpty())
                <div class="empty-state"><i class="fa fa-coins"></i>
                    <p>No {{ strtoupper($pid) }} rewards yet</p>
                </div>
            @else
                <div class="app-card" style="overflow:hidden;">
                    @foreach($rewards as $rw)
                        <div class="list-row">
                            <div class="list-icon blue" style="width:40px;height:40px;border-radius:12px;font-size:15px;"><i
                                    class="fa fa-user"></i></div>
                            <div class="list-body">
                                <div class="title">{{ $rw->fromUser->full_name ?? '—' }}</div>
                                <div class="sub">{{ $rw->fromUser->member_code ?? '—' }} • {{ $rw->percentage }}% • Base:
                                    {{ number_format($rw->base_quantity, 4) }} SVRS
                                </div>
                                <div class="sub">{{ $rw->created_at->format('d M Y, h:i A') }}</div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-size:14px;font-weight:700;color:var(--green);">
                                    +{{ number_format($rw->reward_quantity, 4) }}</div>
                                <div style="font-size:11px;color:var(--muted);">SVRS</div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <p style="font-size:13px;font-weight:700;color:var(--green);text-align:right;padding:10px 0 0;">Total:
                    +{{ number_format($total, 4) }} SVRS</p>
            @endif
        </div>
    @endforeach

    {{-- Rate reference --}}
    <div class="section-label">Earning Rate Reference</div>
    <div style="margin:0 20px;" class="app-card">
        <div class="list-row">
            <div class="list-icon blue" style="width:36px;height:36px;border-radius:10px;font-size:14px;"><i
                    class="fa fa-1"></i></div>
            <div class="list-body">
                <div class="title">Level 1 (Direct)</div>
                <div class="sub">0.5% per buy</div>
            </div>
            <div style="color:var(--green);font-weight:700;font-size:13px;">+0.5 SVRS / 100</div>
        </div>
        <div class="list-row">
            <div class="list-icon teal" style="width:36px;height:36px;border-radius:10px;font-size:14px;"><i
                    class="fa fa-2"></i></div>
            <div class="list-body">
                <div class="title">Level 2</div>
                <div class="sub">0.05% per buy</div>
            </div>
            <div style="color:var(--green);font-weight:700;font-size:13px;">+0.05 SVRS / 100</div>
        </div>
        <div class="list-row">
            <div class="list-icon gold" style="width:36px;height:36px;border-radius:10px;font-size:14px;"><i
                    class="fa fa-3"></i></div>
            <div class="list-body">
                <div class="title">Level 3</div>
                <div class="sub">0.01% per buy</div>
            </div>
            <div style="color:var(--green);font-weight:700;font-size:13px;">+0.01 SVRS / 100</div>
        </div>
    </div>

    <div style="height:8px;"></div>
@endsection

@push('scripts')
    <script>
        function switchIncome(tab, btn) {
            ['l1', 'l2', 'l3'].forEach(function (t) {
                var el = document.getElementById('panel' + t.toUpperCase());
                if (el) el.style.display = (t === tab) ? '' : 'none';
            });
            document.querySelectorAll('#incomeTabs button').forEach(function (b) { b.classList.remove('active'); });
            if (btn) btn.classList.add('active');
        }
    </script>
@endpush