@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || Member Bank Details')

@section('content')
<div class="content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Member Bank Details</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="ti ti-smart-home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">Members</li>
                        <li class="breadcrumb-item active">Bank Details</li>
                    </ol>
                </nav>
            </div>

            <div>
                <a href="{{ route('admin.member.list') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <!-- Member Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="card-title">Member Information</h4>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Member Code</label>
                        <p>{{ $bankDetail->user->member_code }}</p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Full Name</label>
                        <p>{{ $bankDetail->user->first_name }} {{ $bankDetail->user->last_name }}</p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Email</label>
                        <p>{{ $bankDetail->user->email }}</p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Mobile</label>
                        <p>{{ $bankDetail->user->mobile }}</p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Deposit Amount</label>
                        <p>₹ {{ number_format($bankDetail->user->amount, 2) }}</p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Coin Price</label>
                        <p>₹ {{ number_format($bankDetail->user->coin_price, 2) }}</p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Status</label><br>
                        @if($bankDetail->user->status == 1)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Joined Date</label>
                        <p>{{ $bankDetail->user->created_at->format('d M Y h:i A') }}</p>
                    </div>

                </div>
            </div>
        </div>

        <!-- Bank Details -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Bank Information</h4>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Bank Name</label>
                        <p>{{ $bankDetail->bank_name }}</p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Account Holder</label>
                        <p>{{ $bankDetail->account_holder_name }}</p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Account Number</label>
                        <p>{{ $bankDetail->account_number }}</p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">IFSC Code</label>
                        <p>{{ $bankDetail->ifsc_code }}</p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Branch</label>
                        <p>{{ $bankDetail->branch_name }}</p>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">UPI</label>
                        <p>{{ $bankDetail->upi ?? '' }}</p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Added On</label>
                        <p>{{ $bankDetail->created_at->format('d M Y') }}</p>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection