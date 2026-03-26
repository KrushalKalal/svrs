@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || My Referrals')

@section('content')
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">My Referrals</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a></li>
                        <li class="breadcrumb-item active">My Referrals</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- Earnings Summary --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h6>Level 1 Earnings</h6>
                        <h3>{{ number_format($earnings[1], 4) }} <small style="font-size:14px;">SVRS</small></h3>
                        <small>0.5% per referral buy</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h6>Level 2 Earnings</h6>
                        <h3>{{ number_format($earnings[2], 4) }} <small style="font-size:14px;">SVRS</small></h3>
                        <small>0.05% per referral buy</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h6>Level 3 Earnings</h6>
                        <h3>{{ number_format($earnings[3], 4) }} <small style="font-size:14px;">SVRS</small></h3>
                        <small>0.01% per referral buy</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab Navigation --}}
        <ul class="nav nav-tabs mb-3" id="referralTabs">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#treeView">Tree View</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#level1Tab">Level 1 ({{ $level1->count() }})</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#level2Tab">Level 2 ({{ $level2->count() }})</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#level3Tab">Level 3 ({{ $level3->count() }})</a>
            </li>
        </ul>

        <div class="tab-content">

            {{-- Tree View --}}
            <div class="tab-pane fade show active" id="treeView">
                <div class="card">
                    <div class="card-body" style="overflow-x:auto;">
                        <ul class="tree">
                            <li>
                                <span class="tree-node bg-primary text-white">
                                    <i class="ti ti-user me-1"></i>{{ auth()->user()->full_name }}<br>
                                    <small>{{ auth()->user()->member_code }}</small>
                                </span>
                                @if($level1->count())
                                    <ul>
                                        @foreach($level1 as $l1)
                                            <li>
                                                <span
                                                    class="tree-node {{ $l1->is_refer_member ? 'bg-success' : 'bg-secondary' }} text-white">
                                                    <i class="ti ti-user me-1"></i>{{ $l1->full_name }}<br>
                                                    <small>{{ $l1->member_code }}</small>
                                                </span>
                                                @php $l1Children = $level2->where('sponsor_id', $l1->member_code); @endphp
                                                @if($l1Children->count())
                                                    <ul>
                                                        @foreach($l1Children as $l2)
                                                            <li>
                                                                <span
                                                                    class="tree-node {{ $l2->is_refer_member ? 'bg-success' : 'bg-secondary' }} text-white">
                                                                    <i class="ti ti-user me-1"></i>{{ $l2->full_name }}<br>
                                                                    <small>{{ $l2->member_code }}</small>
                                                                </span>
                                                                @php $l2Children = $level3->where('sponsor_id', $l2->member_code); @endphp
                                                                @if($l2Children->count())
                                                                    <ul>
                                                                        @foreach($l2Children as $l3)
                                                                            <li>
                                                                                <span
                                                                                    class="tree-node {{ $l3->is_refer_member ? 'bg-success' : 'bg-secondary' }} text-white">
                                                                                    <i class="ti ti-user me-1"></i>{{ $l3->full_name }}<br>
                                                                                    <small>{{ $l3->member_code }}</small>
                                                                                </span>
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
                            <span class="badge bg-primary me-2">You</span>
                            <span class="badge bg-success me-2">Refer Member</span>
                            <span class="badge bg-secondary">Normal Member</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="level1Tab">
                <div class="card">
                    <div class="card-body">
                        @include('member.membership.partials.referral_table', ['members' => $level1, 'level' => 1])
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="level2Tab">
                <div class="card">
                    <div class="card-body">
                        @include('member.membership.partials.referral_table', ['members' => $level2, 'level' => 2])
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="level3Tab">
                <div class="card">
                    <div class="card-body">
                        @include('member.membership.partials.referral_table', ['members' => $level3, 'level' => 3])
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .tree {
            list-style: none;
            padding: 0;
        }

        .tree ul {
            list-style: none;
            padding-left: 30px;
            margin-top: 8px;
            position: relative;
        }

        .tree ul::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            border-left: 2px dashed #ccc;
        }

        .tree li {
            position: relative;
            padding: 5px 0;
        }

        .tree li::before {
            content: '';
            position: absolute;
            left: -30px;
            top: 20px;
            width: 30px;
            border-top: 2px dashed #ccc;
        }

        .tree-node {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            text-align: center;
            min-width: 120px;
        }
    </style>
@endsection