@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || My Referral Tree')

@section('content')
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">My Referral Tree</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a></li>
                        <li class="breadcrumb-item">Reports</li>
                        <li class="breadcrumb-item active">Referral Tree</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-primary text-center">
                    <div class="card-body">
                        <h2 class="text-primary">{{ $level1->count() }}</h2>
                        <p class="text-muted mb-0">Level 1 (Direct)</p>
                        <small class="text-success">{{ $level1->where('is_refer_member', 1)->where('status', 1)->count() }}
                            Refer Members</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-success text-center">
                    <div class="card-body">
                        <h2 class="text-success">{{ $level2->count() }}</h2>
                        <p class="text-muted mb-0">Level 2</p>
                        <small class="text-success">{{ $level2->where('is_refer_member', 1)->where('status', 1)->count() }}
                            Refer Members</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-info text-center">
                    <div class="card-body">
                        <h2 class="text-info">{{ $level3->count() }}</h2>
                        <p class="text-muted mb-0">Level 3</p>
                        <small class="text-success">{{ $level3->where('is_refer_member', 1)->where('status', 1)->count() }}
                            Refer Members</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Visual Tree --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Visual Referral Tree</h5>
            </div>
            <div class="card-body" style="overflow-x:auto;">
                <ul class="tree">
                    <li>
                        <div class="tree-node-box me-node">
                            <i class="ti ti-user-circle me-1"></i>
                            <strong>{{ $user->full_name }}</strong><br>
                            <small>{{ $user->member_code }}</small>
                        </div>
                        @if($level1->count())
                            <ul>
                                @foreach($level1 as $l1)
                                    <li>
                                        <div class="tree-node-box {{ $l1->is_refer_member ? 'refer-node' : 'normal-node' }}">
                                            <i class="ti ti-user me-1"></i>{{ $l1->full_name }}<br>
                                            <small>{{ $l1->member_code }}</small><br>
                                            @if($l1->status == 1)<span class="badge bg-success" style="font-size:9px;">Active</span>
                                            @else<span class="badge bg-warning text-dark"
                                            style="font-size:9px;">Pending</span>@endif
                                        </div>
                                        @php $l1Kids = $level2->where('sponsor_id', $l1->member_code); @endphp
                                        @if($l1Kids->count())
                                            <ul>
                                                @foreach($l1Kids as $l2)
                                                    <li>
                                                        <div
                                                            class="tree-node-box {{ $l2->is_refer_member ? 'refer-node' : 'normal-node' }}">
                                                            <i class="ti ti-user me-1"></i>{{ $l2->full_name }}<br>
                                                            <small>{{ $l2->member_code }}</small><br>
                                                            @if($l2->status == 1)<span class="badge bg-success"
                                                                style="font-size:9px;">Active</span>
                                                            @else<span class="badge bg-warning text-dark"
                                                            style="font-size:9px;">Pending</span>@endif
                                                        </div>
                                                        @php $l2Kids = $level3->where('sponsor_id', $l2->member_code); @endphp
                                                        @if($l2Kids->count())
                                                            <ul>
                                                                @foreach($l2Kids as $l3)
                                                                    <li>
                                                                        <div
                                                                            class="tree-node-box {{ $l3->is_refer_member ? 'refer-node' : 'normal-node' }}">
                                                                            <i class="ti ti-user me-1"></i>{{ $l3->full_name }}<br>
                                                                            <small>{{ $l3->member_code }}</small><br>
                                                                            @if($l3->status == 1)<span class="badge bg-success"
                                                                                style="font-size:9px;">Active</span>
                                                                            @else<span class="badge bg-warning text-dark"
                                                                            style="font-size:9px;">Pending</span>@endif
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
                    <span class="badge bg-primary me-2">Me (Root)</span>
                    <span class="badge bg-success me-2">Refer Member</span>
                    <span class="badge bg-secondary">Normal Member</span>
                </div>
            </div>
        </div>

        {{-- Level Tables: 7 columns each, NO tfoot --}}
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#t1">Level 1
                    ({{ $level1->count() }})</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#t2">Level 2 ({{ $level2->count() }})</a>
            </li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#t3">Level 3 ({{ $level3->count() }})</a>
            </li>
        </ul>

        <div class="tab-content">
            @foreach(['t1' => [$level1, 1], 't2' => [$level2, 2], 't3' => [$level3, 3]] as $tid => [$members, $lvl])
                <div class="tab-pane fade {{ $tid == 't1' ? 'show active' : '' }}" id="{{ $tid }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="tbl_{{ $tid }}">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Member Code</th>
                                            <th>Sponsor</th>
                                            <th>Status</th>
                                            <th>Refer Member</th>
                                            <th>Joined</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($members as $m)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $m->full_name }}</td>
                                                <td><span class="badge bg-primary">{{ $m->member_code }}</span></td>
                                                <td>{{ $m->sponsor_id ?? '-' }}</td>
                                                <td>
                                                    @if($m->status == 1)<span class="badge bg-success">Active</span>
                                                    @elseif($m->status == 0)<span class="badge bg-danger">Inactive</span>
                                                    @else<span class="badge bg-warning text-dark">Pending</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($m->is_refer_member)<span class="badge bg-success">Yes</span>
                                                    @else<span class="badge bg-secondary">No</span>
                                                    @endif
                                                </td>
                                                <td>{{ $m->created_at->format('d M Y') }}</td>
                                            </tr>
                                        @empty

                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <style>
        .tree {
            list-style: none;
            padding: 0
        }

        .tree ul {
            list-style: none;
            padding-left: 40px;
            margin-top: 8px;
            position: relative
        }

        .tree ul::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            border-left: 2px dashed #ccc
        }

        .tree li {
            position: relative;
            padding: 5px 0
        }

        .tree li::before {
            content: '';
            position: absolute;
            left: -40px;
            top: 28px;
            width: 40px;
            border-top: 2px dashed #ccc
        }

        .tree-node-box {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            text-align: center;
            min-width: 130px;
            border: 2px solid
        }

        .me-node {
            border-color: #0d6efd;
            background: #e7f1ff;
            color: #0d6efd
        }

        .refer-node {
            border-color: #198754;
            background: #d1e7dd;
            color: #146c43
        }

        .normal-node {
            border-color: #6c757d;
            background: #f8f9fa;
            color: #495057
        }
    </style>

    <script>
        $(document).ready(function () {
            // Init ALL tabs lazily on shown — same fix as wallet ledger
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                var target = $(e.target).attr('href');
                if (target === '#t1' && !$.fn.DataTable.isDataTable('#tbl_t1')) {
                    $('#tbl_t1').DataTable({ order: [[6, 'desc']], pageLength: 25 });
                }
                if (target === '#t2' && !$.fn.DataTable.isDataTable('#tbl_t2')) {
                    $('#tbl_t2').DataTable({ order: [[6, 'desc']], pageLength: 25 });
                }
                if (target === '#t3' && !$.fn.DataTable.isDataTable('#tbl_t3')) {
                    $('#tbl_t3').DataTable({ order: [[6, 'desc']], pageLength: 25 });
                }
            });

            // Trigger active tab manually so tbl_t1 inits properly
            $('a[data-bs-toggle="tab"].active').trigger('shown.bs.tab');
        });
    </script>
@endsection