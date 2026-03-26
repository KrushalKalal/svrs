@extends('admin.layout.main-layout')
@section('title', config('app.name') . ' || My Wallet')

@php
    $isAdmin = auth()->user()->role === 'admin';
    $addMoneyRoute      = $isAdmin ? route('admin.wallet.add.money')       : route('member.wallet.add.money');
    $withdrawRoute      = $isAdmin ? route('admin.wallet.withdraw.request') : route('member.wallet.withdraw.request');
    $profileRoute       = $isAdmin ? route('admin.profile')                 : route('member.profile');
@endphp

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row mb-4">

                {{-- Balance Card --}}
                <div class="col-md-4">
                    <div class="card shadow border-0 rounded-4">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-wallet text-primary" style="font-size:40px;"></i>
                            </div>
                            <h6 class="text-muted mt-2">Current Balance</h6>
                            <h2 class="fw-bold text-success mt-1">
                                ₹ {{ number_format($wallet->balance ?? 0, 2) }}
                            </h2>
                            <div class="d-flex justify-content-around mt-3">
                                @if($pendingDeposit > 0)
                                    <div>
                                        <h6 class="text-muted mt-2">Pending Deposit</h6>
                                        <h2 class="fw-bold text-info mt-1">₹ {{ number_format($pendingDeposit, 2) }}</h2>
                                    </div>
                                @endif
                                @if($pendingWithdraw > 0)
                                    <div>
                                        <h6 class="text-muted mt-2">Pending Withdraw</h6>
                                        <h2 class="fw-bold text-danger mt-1">₹ {{ number_format($pendingWithdraw, 2) }}</h2>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Add Money Card --}}
                <div class="col-md-4">
                    <div class="card shadow border-0 rounded-4">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-money-bill-transfer text-success" style="font-size:40px;"></i>
                            </div>
                            <button class="btn btn-success px-4 mt-2" data-bs-toggle="modal" data-bs-target="#addMoneyModal">
                                <i class="fe fe-arrow-down me-1"></i> Add Money
                            </button>
                            @if($depositsetting)
                                <div class="alert alert-info py-2 mt-3 mb-0">
                                    <strong>Note:</strong> Minimum Deposit: ₹{{ $depositsetting->min_amount }}
                                    @if($depositsetting->max_amount) | Maximum Deposit: ₹{{ $depositsetting->max_amount }} @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Withdraw Card --}}
                <div class="col-md-4">
                    <div class="card shadow border-0 rounded-4">
                        <div class="card-body text-center p-4">
                            <div class="mb-3">
                                <i class="fas fa-hand-holding-dollar text-danger" style="font-size:40px;"></i>
                            </div>
                            @if($hasBankDetail)
                                <button class="btn btn-danger px-4 mt-2" data-bs-toggle="modal" data-bs-target="#withdrawMoneyModal">
                                    <i class="fe fe-arrow-up me-1"></i> Withdraw
                                </button>
                                @if($withdrawal)
                                    <div class="alert alert-warning py-2 mt-3 mb-0">
                                        <strong>Note:</strong> Minimum Withdrawal: ₹{{ $withdrawal->min_amount }}
                                        @if($withdrawal->max_amount) | Maximum Withdrawal: ₹{{ $withdrawal->max_amount }} @endif
                                    </div>
                                @endif
                            @else
                                <a href="{{ $profileRoute }}" class="btn btn-primary mt-2">
                                    <i class="fe fe-plus me-1"></i> Add Bank Details
                                </a>
                                <div class="alert alert-info py-2 mt-3 mb-0">
                                    <strong>Note:</strong> Add your bank account to enable withdrawals.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            {{-- Transaction History --}}
            <div class="card mt-4">
                <div class="card-header">
                    <h4>Transaction History</h4>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $txn)
                                <tr>
                                    <td>{{ $txn->created_at->format('d M Y h:i A') }}</td>
                                    <td>
                                        @if($txn->type == 'credit')
                                            <span class="badge bg-success">Credit</span>
                                        @else
                                            <span class="badge bg-danger">Debit</span>
                                        @endif
                                    </td>
                                    <td>₹{{ number_format($txn->amount, 2) }}</td>
                                    <td>
                                        @if($txn->status === 1)
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($txn->status === 0)
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ $txn->remark }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted">No transactions found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Money Modal --}}
    <div class="modal fade" id="addMoneyModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Money To Wallet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6 border-end">
                            <h6 class="mb-3">Scan & Pay</h6>
                            <div class="text-center mb-3">
                                <img src="{{ asset($contact->qr_image) }}" class="img-fluid rounded" style="max-height:200px;">
                            </div>
                            <div class="bg-light p-3 rounded">
                                <h6 class="mb-2">Bank Details</h6>
                                <p class="mb-1"><strong>Bank Name:</strong> {{ $contact->bank ?? '' }}</p>
                                <p class="mb-1"><strong>Account Name:</strong> {{ $contact->account_name ?? '' }}</p>
                                <p class="mb-1"><strong>Account No:</strong> {{ $contact->account_number ?? '' }}</p>
                                <p class="mb-1"><strong>IFSC Code:</strong> {{ $contact->ifsc_code ?? '' }}</p>
                                <p class="mb-0"><strong>Branch:</strong> {{ $contact->branch ?? '' }}</p>
                            </div>
                            <small class="text-muted d-block mt-2">* Upload payment screenshot after transfer.</small>
                        </div>
                        <div class="col-6">
                            <h6 class="mb-3">Submit Details</h6>
                            <form id="addMoneyForm" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Amount <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" class="form-control mb-0"
                                        placeholder="Enter Amount"
                                        min="{{ $depositsetting->min_amount ?? 0 }}"
                                        max="{{ $depositsetting->max_amount ?? '' }}"
                                        id="addamounts">
                                    <small class="text-danger error-text amount_error"></small>
                                </div>
                                <div class="mb-3">
                                    <label>Upload Screenshot <span class="text-danger">*</span></label>
                                    <input type="file" name="screenshot" class="form-control">
                                    <span class="text-danger error-text screenshot_error"></span>
                                </div>
                                <button type="submit" class="btn btn-success w-100" id="submitBtn">
                                    <span id="btnText">Submit Payment Request</span>
                                    <span id="btnLoader" class="spinner-border spinner-border-sm d-none"></span>
                                </button>
                            </form>
                            @if($depositsetting)
                                <div class="alert alert-info py-2 mt-3">
                                    <strong>Note:</strong> Minimum Deposit: ₹{{ $depositsetting->min_amount }}
                                    @if($depositsetting->max_amount) | Maximum Deposit: ₹{{ $depositsetting->max_amount }} @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Withdraw Modal --}}
    <div class="modal fade" id="withdrawMoneyModal">
        <div class="modal-dialog">
            <form id="withdrawForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Withdraw Money</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label>Enter Amount</label>
                        <input type="number" name="amount" id="withdrawAmount" class="form-control">
                        <small class="text-danger error-text amount_error"></small>
                    </div>
                    @if($withdrawal)
                        <div class="alert alert-info py-2 m-2">
                            <strong>Note:</strong> Minimum Withdrawal: ₹{{ $withdrawal->min_amount }}
                            @if($withdrawal->max_amount) | Maximum Withdrawal: ₹{{ $withdrawal->max_amount }} @endif
                        </div>
                    @endif
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" id="withdrawBtn">
                            <span id="withdrawText">Withdraw</span>
                            <span id="withdrawLoader" class="spinner-border spinner-border-sm d-none"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        var addMoneyRoute  = "{{ $addMoneyRoute }}";
        var withdrawRoute  = "{{ $withdrawRoute }}";

        let minAmount  = {{ $depositsetting->min_amount ?? 0 }};
        let maxAmount  = {{ $depositsetting->max_amount ?? 'null' }};
        let minWithdraw = {{ $withdrawal->min_amount ?? 0 }};
        let maxWithdraw = {{ $withdrawal->max_amount ?? 'null' }};

        // Amount validation
        $('#addamounts').on('keyup change', function () {
            let amount = parseFloat($(this).val());
            if (!amount) { $('.amount_error').text('Amount is required'); return; }
            if (amount < minAmount) {
                $('.amount_error').text('Minimum deposit is ₹' + minAmount);
            } else if (maxAmount && amount > maxAmount) {
                $('.amount_error').text('Maximum deposit is ₹' + maxAmount);
            } else {
                $('.amount_error').text('');
            }
        });

        // Add Money
        $('#addMoneyForm').on('submit', function (e) {
            e.preventDefault();
            let amount = parseFloat($('#addamounts').val());
            if (!amount || amount < minAmount || (maxAmount && amount > maxAmount)) {
                toastr.error('Invalid Deposit Amount');
                return;
            }
            let formData = new FormData(this);
            $('.error-text').text('');
            $.ajax({
                url: addMoneyRoute,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#submitBtn').prop('disabled', true);
                    $('#btnText').addClass('d-none');
                    $('#btnLoader').removeClass('d-none');
                },
                success: function (res) {
                    if (res.status) {
                        toastr.success(res.message);
                        $('#addMoneyForm')[0].reset();
                        setTimeout(function () { location.reload(); }, 1000);
                    } else {
                        toastr.error(res.message);
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, value) {
                            $('.' + key + '_error').text(value[0]);
                        });
                        toastr.error("Please fix validation errors");
                    }
                },
                complete: function () {
                    $('#submitBtn').prop('disabled', false);
                    $('#btnText').removeClass('d-none');
                    $('#btnLoader').addClass('d-none');
                }
            });
        });

        // Withdraw
        $('#withdrawForm').on('submit', function (e) {
            e.preventDefault();
            let amount = parseFloat($('#withdrawAmount').val());
            $('.error-text').text('');
            if (!amount) { $('.amount_error').text('Amount is required'); return; }
            if (amount < minWithdraw) { $('.amount_error').text('Minimum withdrawal is ₹' + minWithdraw); return; }
            if (maxWithdraw && amount > maxWithdraw) { $('.amount_error').text('Maximum withdrawal is ₹' + maxWithdraw); return; }

            $.ajax({
                url: withdrawRoute,
                type: "POST",
                data: $(this).serialize(),
                beforeSend: function () {
                    $('#withdrawBtn').prop('disabled', true);
                    $('#withdrawText').addClass('d-none');
                    $('#withdrawLoader').removeClass('d-none');
                },
                success: function (res) {
                    if (res.status) {
                        toastr.success(res.message);
                        $('#withdrawForm')[0].reset();
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        toastr.error(res.message);
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, val) { $('.' + key + '_error').text(val[0]); });
                    }
                },
                complete: function () {
                    $('#withdrawBtn').prop('disabled', false);
                    $('#withdrawText').removeClass('d-none');
                    $('#withdrawLoader').addClass('d-none');
                }
            });
        });
    </script>
@endsection