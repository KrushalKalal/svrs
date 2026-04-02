@extends('member.layout.app-layout')
@section('title', 'Referral Tree')@section('nav-title', 'Referral Tree')@section('nav-back') @endsection
@section('nav-back-url', route('member.profile'))
 
@section('content')

    {{-- Level count cards --}}
    <div style="padding:16px 20px 0;display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;">
        <div class="app-card app-card-inner" style="text-align:center;padding:12px 8px;">
            <p style="font-size:22px;font-weight:800;color:var(--accent-blue);">{{ $level1->count() }}</p>
            <p style="font-size:11px;color:var(--muted);">Level 1</p>
            <p style="font-size:10px;color:var(--green);">{{ $level1->where('is_refer_member', 1)->where('status', 1)->count() }} refer</p>
        </div>
        <div class="app-card app-card-inner" style="text-align:center;padding:12px 8px;">
            <p style="font-size:22px;font-weight:800;color:var(--green);">{{ $level2->count() }}</p>
            <p style="font-size:11px;color:var(--muted);">Level 2</p>
            <p style="font-size:10px;color:var(--green);">{{ $level2->where('is_refer_member', 1)->where('status', 1)->count() }} refer</p>
        </div>
        <div class="app-card app-card-inner" style="text-align:center;padding:12px 8px;">
            <p style="font-size:22px;font-weight:800;color:var(--accent);">{{ $level3->count() }}</p>
            <p style="font-size:11px;color:var(--muted);">Level 3</p>
            <p style="font-size:10px;color:var(--green);">{{ $level3->where('is_refer_member', 1)->where('status', 1)->count() }} refer</p>
        </div>
    </div>

    {{-- Level tabs --}}
    <div style="padding:16px 20px 0;">
        <div class="segment-ctrl" id="treeTabs">
            <button class="active" onclick="switchTree('l1',this)">Level 1 ({{ $level1->count() }})</button>
            <button onclick="switchTree('l2',this)">Level 2 ({{ $level2->count() }})</button>
            <button onclick="switchTree('l3',this)">Level 3 ({{ $level3->count() }})</button>
        </div>
    </div>

    @foreach([['l1', $level1, 1], ['l2', $level2, 2], ['l3', $level3, 3]] as [$pid, $members, $lvl])
        <div id="treePanel{{ strtoupper($pid) }}" style="{{ $pid === 'l1' ? '' : 'display:none;' }}padding:12px 20px 0;">
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

    <div style="height:8px;"></div>
@endsection
 
@push('scripts')
    <script>
    function switchTree(tab, btn) {
        ['l1','l2','l3'].forEach(function(t) {
            var el = document.getElementById('treePanel' + t.toUpperCase());
            if (el) el.style.display = (t === tab) ? '' : 'none';
        });
        document.querySelectorAll('#treeTabs button').forEach(function(b){ b.classList.remove('active'); });
        if (btn) btn.classList.add('active');
    }
    </script>
@endpush