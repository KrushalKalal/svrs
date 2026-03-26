@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Referral Tree')

@section('content')
<div class="content">
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h2 class="mb-1">Referral Tree &mdash; {{ $member->full_name }}</h2>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.reports.financial') }}">Reports</a></li>
                    <li class="breadcrumb-item active">Referral Tree</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.reports.income', $member->id) }}" class="btn btn-outline-success me-1">
                <i class="ti ti-chart-bar me-1"></i>Income Report
            </a>
            <a href="{{ route('admin.reports.wallet.ledger', $member->id) }}" class="btn btn-outline-primary me-1">
                <i class="ti ti-wallet me-1"></i>Wallet Ledger
            </a>
            <a href="{{ route('admin.reports.financial') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-primary text-center">
                <div class="card-body">
                    <h2 class="text-primary">{{ $level1->count() }}</h2>
                    <p class="text-muted mb-0">Level 1 (Direct)</p>
                    <small class="text-success">{{ $level1->where('is_refer_member',1)->where('status',1)->count() }} Refer Members</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success text-center">
                <div class="card-body">
                    <h2 class="text-success">{{ $level2->count() }}</h2>
                    <p class="text-muted mb-0">Level 2</p>
                    <small class="text-success">{{ $level2->where('is_refer_member',1)->where('status',1)->count() }} Refer Members</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-info text-center">
                <div class="card-body">
                    <h2 class="text-info">{{ $level3->count() }}</h2>
                    <p class="text-muted mb-0">Level 3</p>
                    <small class="text-success">{{ $level3->where('is_refer_member',1)->where('status',1)->count() }} Refer Members</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Visual Tree --}}
    <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0">Visual Referral Tree</h5></div>
        <div class="card-body" style="overflow-x:auto;">
            <ul class="tree">
                <li>
                    <div class="tree-node-box me-node">
                        <i class="ti ti-user-circle me-1"></i>
                        <strong>{{ $member->full_name }}</strong><br>
                        <small>{{ $member->member_code }}</small>
                    </div>
                    @if($level1->count())
                    <ul>
                        @foreach($level1 as $l1)
                        <li>
                            <div class="tree-node-box {{ $l1->is_refer_member ? 'refer-node' : 'normal-node' }}">
                                <i class="ti ti-user me-1"></i>{{ $l1->full_name }}<br>
                                <small>{{ $l1->member_code }}</small><br>
                                @if($l1->status==1)<span class="badge bg-success" style="font-size:9px;">Active</span>
                                @else<span class="badge bg-warning text-dark" style="font-size:9px;">Pending</span>@endif
                            </div>
                            @php $l1Kids = $level2->where('sponsor_id', $l1->member_code); @endphp
                            @if($l1Kids->count())
                            <ul>
                                @foreach($l1Kids as $l2)
                                <li>
                                    <div class="tree-node-box {{ $l2->is_refer_member ? 'refer-node' : 'normal-node' }}">
                                        <i class="ti ti-user me-1"></i>{{ $l2->full_name }}<br>
                                        <small>{{ $l2->member_code }}</small><br>
                                        @if($l2->status==1)<span class="badge bg-success" style="font-size:9px;">Active</span>
                                        @else<span class="badge bg-warning text-dark" style="font-size:9px;">Pending</span>@endif
                                    </div>
                                    @php $l2Kids = $level3->where('sponsor_id', $l2->member_code); @endphp
                                    @if($l2Kids->count())
                                    <ul>
                                        @foreach($l2Kids as $l3)
                                        <li>
                                            <div class="tree-node-box {{ $l3->is_refer_member ? 'refer-node' : 'normal-node' }}">
                                                <i class="ti ti-user me-1"></i>{{ $l3->full_name }}<br>
                                                <small>{{ $l3->member_code }}</small><br>
                                                @if($l3->status==1)<span class="badge bg-success" style="font-size:9px;">Active</span>
                                                @else<span class="badge bg-warning text-dark" style="font-size:9px;">Pending</span>@endif
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </li>
            </ul>
            <div class="mt-3">
                <span class="badge bg-primary me-2">Root Member</span>
                <span class="badge bg-success me-2">Refer Member</span>
                <span class="badge bg-secondary">Normal Member</span>
            </div>
        </div>
    </div>

    {{-- Level Tabs: 8 columns each --}}
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#t1">Level 1 ({{ $level1->count() }})</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#t2">Level 2 ({{ $level2->count() }})</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#t3">Level 3 ({{ $level3->count() }})</a></li>
    </ul>

    <div class="tab-content">

        <div class="tab-pane fade show active" id="t1">
            <div class="card"><div class="card-body"><div class="table-responsive">
                <table class="table table-bordered table-striped" id="tblT1">
                    <thead><tr><th>#</th><th>Name</th><th>Member Code</th><th>Sponsor</th><th>Status</th><th>Refer</th><th>Reports</th><th>Joined</th></tr></thead>
                    <tbody>
                        @forelse($level1 as $m)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $m->full_name }}</td>
                                <td><span class="badge bg-primary">{{ $m->member_code }}</span></td>
                                <td>{{ $m->sponsor_id ?? '-' }}</td>
                                <td>@if($m->status==1)<span class="badge bg-success">Active</span>@elseif($m->status==0)<span class="badge bg-danger">Inactive</span>@else<span class="badge bg-warning text-dark">Pending</span>@endif</td>
                                <td>@if($m->is_refer_member)<span class="badge bg-success">Yes</span>@else<span class="badge bg-secondary">No</span>@endif</td>
                                <td>
                                    <a href="{{ route('admin.reports.wallet.ledger', $m->id) }}" class="btn btn-sm btn-outline-primary"><i class="ti ti-wallet"></i></a>
                                    <a href="{{ route('admin.reports.income', $m->id) }}" class="btn btn-sm btn-outline-success"><i class="ti ti-chart-bar"></i></a>
                                    <a href="{{ route('admin.reports.referral.tree', $m->id) }}" class="btn btn-sm btn-outline-info"><i class="ti ti-share"></i></a>
                                </td>
                                <td>{{ $m->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div></div></div>
        </div>

        <div class="tab-pane fade" id="t2">
            <div class="card"><div class="card-body"><div class="table-responsive">
                <table class="table table-bordered table-striped" id="tblT2">
                    <thead><tr><th>#</th><th>Name</th><th>Member Code</th><th>Sponsor</th><th>Status</th><th>Refer</th><th>Reports</th><th>Joined</th></tr></thead>
                    <tbody>
                        @forelse($level2 as $m)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $m->full_name }}</td>
                                <td><span class="badge bg-primary">{{ $m->member_code }}</span></td>
                                <td>{{ $m->sponsor_id ?? '-' }}</td>
                                <td>@if($m->status==1)<span class="badge bg-success">Active</span>@elseif($m->status==0)<span class="badge bg-danger">Inactive</span>@else<span class="badge bg-warning text-dark">Pending</span>@endif</td>
                                <td>@if($m->is_refer_member)<span class="badge bg-success">Yes</span>@else<span class="badge bg-secondary">No</span>@endif</td>
                                <td>
                                    <a href="{{ route('admin.reports.wallet.ledger', $m->id) }}" class="btn btn-sm btn-outline-primary"><i class="ti ti-wallet"></i></a>
                                    <a href="{{ route('admin.reports.income', $m->id) }}" class="btn btn-sm btn-outline-success"><i class="ti ti-chart-bar"></i></a>
                                    <a href="{{ route('admin.reports.referral.tree', $m->id) }}" class="btn btn-sm btn-outline-info"><i class="ti ti-share"></i></a>
                                </td>
                                <td>{{ $m->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div></div></div>
        </div>

        <div class="tab-pane fade" id="t3">
            <div class="card"><div class="card-body"><div class="table-responsive">
                <table class="table table-bordered table-striped" id="tblT3">
                    <thead><tr><th>#</th><th>Name</th><th>Member Code</th><th>Sponsor</th><th>Status</th><th>Refer</th><th>Reports</th><th>Joined</th></tr></thead>
                    <tbody>
                        @forelse($level3 as $m)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $m->full_name }}</td>
                                <td><span class="badge bg-primary">{{ $m->member_code }}</span></td>
                                <td>{{ $m->sponsor_id ?? '-' }}</td>
                                <td>@if($m->status==1)<span class="badge bg-success">Active</span>@elseif($m->status==0)<span class="badge bg-danger">Inactive</span>@else<span class="badge bg-warning text-dark">Pending</span>@endif</td>
                                <td>@if($m->is_refer_member)<span class="badge bg-success">Yes</span>@else<span class="badge bg-secondary">No</span>@endif</td>
                                <td>
                                    <a href="{{ route('admin.reports.wallet.ledger', $m->id) }}" class="btn btn-sm btn-outline-primary"><i class="ti ti-wallet"></i></a>
                                    <a href="{{ route('admin.reports.income', $m->id) }}" class="btn btn-sm btn-outline-success"><i class="ti ti-chart-bar"></i></a>
                                    <a href="{{ route('admin.reports.referral.tree', $m->id) }}" class="btn btn-sm btn-outline-info"><i class="ti ti-share"></i></a>
                                </td>
                                <td>{{ $m->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div></div></div>
        </div>

    </div>
</div>

<style>
    .tree{list-style:none;padding:0}.tree ul{list-style:none;padding-left:40px;margin-top:8px;position:relative}.tree ul::before{content:'';position:absolute;left:0;top:0;bottom:0;border-left:2px dashed #ccc}.tree li{position:relative;padding:5px 0}.tree li::before{content:'';position:absolute;left:-40px;top:28px;width:40px;border-top:2px dashed #ccc}.tree-node-box{display:inline-block;padding:8px 12px;border-radius:8px;font-size:12px;text-align:center;min-width:130px;border:2px solid}.me-node{border-color:#0d6efd;background:#e7f1ff;color:#0d6efd}.refer-node{border-color:#198754;background:#d1e7dd;color:#146c43}.normal-node{border-color:#6c757d;background:#f8f9fa;color:#495057}
</style>

<script>
    $(document).ready(function () {
        $('#tblT1').DataTable({ order: [[7, 'desc']], pageLength: 25 });
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            var t = $(e.target).attr('href');
            if (t === '#t2' && !$.fn.DataTable.isDataTable('#tblT2'))
                $('#tblT2').DataTable({ order: [[7, 'desc']], pageLength: 25 });
            if (t === '#t3' && !$.fn.DataTable.isDataTable('#tblT3'))
                $('#tblT3').DataTable({ order: [[7, 'desc']], pageLength: 25 });
        });
    });
</script>
@endsection