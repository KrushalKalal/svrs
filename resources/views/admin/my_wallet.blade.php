@extends('member.layout.app-layout')
@section('title', 'INR Wallet — SVRS Coin')@section('nav-title', 'INR Wallet')
@section('nav-back') @endsection
@section('nav-back-url', route('member.dashboard'))

@section('nav-actions')
    <button class="nav-action-btn" onclick="location.reload()" title="Refresh">
        <i class="fa fa-rotate-right"></i>
    </button>
@endsection

@section('content')

    {{-- Segment: Balance / Transactions --}}
    <div style="padding:16px 20px 0;">
        <div class="segment-ctrl">
            <button class="active" id="tabBalance" onclick="switchTab('balance')">Balance</button>
            <button id="tabTxn" onclick="switchTab('txn')">Transactions</button>
        </div>
    </div>

    {{-- ── BALANCE TAB ── --}}
    <div id="panelBalance">

        {{-- Wallet card --}}
        <div style="padding:16px 20px 0;">
            <div class="accent-card" style="padding:20px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                    <div style="width:40px;height:40px;background:rgba(0,212,170,0.15);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;color:var(--accent);">
                        <i class="fa fa-wallet"></i>
                    </div>
                    <div class="badge-app badge-teal">INR Wallet</div>
                </div>
                <p style="font-size:13px;color:var(--muted);margin-bottom:4px;">Available Balance</p>
                <p class="big-amount teal">₹{{ number_format($wallet->balance ?? 0, 2) }}</p>

                <div style="display:flex;gap:10px;margin-top:20px;">
                    <button onclick="openSheet('depositSheet')" class="btn-app btn-outline-teal" style="flex:1;padding:12px;font-size:14px;border-radius:var(--radius-sm);">
                        <i class="fa fa-circle-plus"></i> Deposit
                    </button>
                    @if($hasBankDetail)
                        <button onclick="openSheet('withdrawSheet')" class="btn-app btn-gold" style="flex:1;padding:12px;font-size:14px;border-radius:var(--radius-sm);">
                            <i class="fa fa-circle-arrow-up"></i> Withdraw
                        </button>
                    @else
                        <a href="{{ route('member.profile') }}" class="btn-app btn-outline-gold" style="flex:1;padding:12px;font-size:14px;border-radius:var(--radius-sm);text-decoration:none;">
                            <i class="fa fa-bank"></i> Add Bank
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Deposit Limits --}}
        @if($depositsetting)
            <div class="section-label">Deposit Limits</div>
            <div style="padding:0 20px;display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="app-card app-card-inner" style="text-align:center;">
                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(16,185,129,0.12);display:flex;align-items:center;justify-content:center;margin:0 auto 8px;">
                        <i class="fa fa-arrow-down" style="color:var(--green);font-size:14px;"></i>
                    </div>
                    <p style="font-size:11px;color:var(--muted);">Minimum</p>
                    <p style="font-size:18px;font-weight:800;color:var(--green);">₹{{ number_format($depositsetting->min_amount, 2) }}</p>
                </div>
                <div class="app-card app-card-inner" style="text-align:center;">
                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(239,68,68,0.12);display:flex;align-items:center;justify-content:center;margin:0 auto 8px;">
                        <i class="fa fa-arrow-up" style="color:var(--red);font-size:14px;"></i>
                    </div>
                    <p style="font-size:11px;color:var(--muted);">Maximum</p>
                    <p style="font-size:18px;font-weight:800;color:var(--red);">₹{{ number_format($depositsetting->max_amount ?? 0, 2) }}</p>
                </div>
            </div>
        @endif

        {{-- Transaction Summary --}}
        <div class="section-label">Transaction Summary</div>
        <div style="margin:0 20px;" class="app-card">
            @php
                $approved = $transactions->where('status', 1);
                $credits = $approved->where('type', 'credit');
                $debits = $approved->where('type', 'debit');
            @endphp
            <div class="info-row px" style="padding-top:14px;">
                <span class="key">Total Transactions</span>
                <span class="val">{{ $transactions->count() }}</span>
            </div>
            <div class="info-row px">
                <span class="key">Credits</span>
                <span class="val" style="color:var(--green);">+{{ $credits->count() }}</span>
            </div>
            <div class="info-row px" style="padding-bottom:14px;">
                <span class="key">Debits</span>
                <span class="val" style="color:var(--red);">−{{ $debits->count() }}</span>
            </div>
        </div>

        @if($pendingDeposit > 0 || $pendingWithdraw > 0)
            <div style="margin:12px 20px 0;">
                <div class="alert-app gold-alert">
                    <i class="fa fa-circle-info" style="color:var(--gold);"></i>
                    <span>
                        @if($pendingDeposit > 0) Pending deposit: ₹{{ number_format($pendingDeposit, 2) }}. @endif
                        @if($pendingWithdraw > 0) Pending withdrawal: ₹{{ number_format($pendingWithdraw, 2) }}. @endif
                        Deposits require admin approval before crediting.
                    </span>
                </div>
            </div>
        @endif
    </div>

    {{-- ── TRANSACTIONS TAB ── --}}
    <div id="panelTxn" style="display:none;">
        <div style="padding:16px 20px 0;">
            @if($transactions->isEmpty())
                <div class="empty-state">
                    <i class="fa fa-receipt"></i>
                    <p>No transactions yet</p>
                </div>
            @else
                <div class="app-card" style="overflow:hidden;">
                    @foreach($transactions as $txn)
                        <div class="list-row">
                            <div class="list-icon {{ $txn->type === 'credit' ? 'teal' : 'red' }}" style="width:40px;height:40px;border-radius:12px;font-size:15px;">
                                <i class="fa fa-arrow-{{ $txn->type === 'credit' ? 'down' : 'up' }}"></i>
                            </div>
                            <div class="list-body">
                                <div class="title">{{ $txn->remark ?? ucfirst($txn->type) }}</div>
                                <div class="sub">{{ $txn->created_at->format('d M Y, h:i A') }}</div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-size:15px;font-weight:700;color:{{ $txn->type === 'credit' ? 'var(--green)' : 'var(--red)' }};">
                                    {{ $txn->type === 'credit' ? '+' : '-' }}₹{{ number_format($txn->amount, 2) }}
                                </div>
                                <div style="margin-top:3px;">
                                    @if($txn->status == 1)
                                        <span class="badge-app badge-green" style="font-size:10px;">Approved</span>
                                    @elseif($txn->status == 0)
                                        <span class="badge-app badge-red" style="font-size:10px;">Rejected</span>
                                    @else
                                        <span class="badge-app badge-gold" style="font-size:10px;">Pending</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div style="height:8px;"></div>

@endsection

{{-- ── DEPOSIT BOTTOM SHEET ── --}}
@push('scripts')
    <script>
        // Tab switch
        function switchTab(tab) {
            const isBalance = tab === 'balance';
            document.getElementById('panelBalance').style.display = isBalance ? '' : 'none';
            document.getElementById('panelTxn').style.display     = isBalance ? 'none' : '';
            document.getElementById('tabBalance').classList.toggle('active', isBalance);
            document.getElementById('tabTxn').classList.toggle('active', !isBalance);
        }

        // Pct quick pick — Withdraw
        const maxBal = {{ $wallet->balance ?? 0 }};
        function setPct(pct) {
            document.querySelectorAll('.pct-btn').forEach(b => b.classList.remove('active'));
            event.target.classList.add('active');
            const val = (maxBal * pct / 100).toFixed(2);
            document.getElementById('wdAmount').value = val;
        }

        // Deposit submit
        $('#depositForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const btn = document.getElementById('depositBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spin"></span>';
            $.ajax({
                url: "{{ route('member.wallet.add.money') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    if (res.status) {
                        toastr.success(res.message);
                        closeSheet('depositSheet');
                        setTimeout(() => location.reload(), 1200);
                    } else { toastr.error(res.message); }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let e = xhr.responseJSON.errors;
                        $.each(e, (k,v) => toastr.error(v[0]));
                    } else toastr.error('Something went wrong.');
                },
                complete: function() {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa fa-circle-plus"></i> Submit Deposit';
                }
            });
        });

        // Withdraw submit
        $('#withdrawForm').on('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('wdBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spin" style="border-top-color:#000;"></span>';
            $.ajax({
                url: "{{ route('member.wallet.withdraw.request') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if (res.status) {
                        toastr.success(res.message);
                        closeSheet('withdrawSheet');
                        setTimeout(() => location.reload(), 1200);
                    } else toastr.error(res.message);
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let e = xhr.responseJSON.errors;
                        $.each(e, (k,v) => toastr.error(v[0]));
                    } else toastr.error('Something went wrong.');
                },
                complete: function() {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa fa-circle-arrow-up"></i> Submit Withdrawal';
                }
            });
        });

        // Image preview
        document.getElementById('screenshotInput')?.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => {
                const preview = document.getElementById('ssPreview');
                preview.style.display = '';
                preview.querySelector('img').src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    </script>
@endpush

{{-- Deposit Sheet --}}
<div class="sheet-overlay" id="depositSheet">
    <div class="bottom-sheet">
        <div class="sheet-handle"></div>
        <div class="sheet-title">
            <div style="width:36px;height:36px;border-radius:10px;background:rgba(0,212,170,0.12);display:flex;align-items:center;justify-content:center;color:var(--accent);">
                <i class="fa fa-circle-plus"></i>
            </div>
            Deposit Funds
        </div>

        @if($depositsetting)
            <div style="display:flex;gap:8px;margin-bottom:16px;">
                <div class="badge-app badge-teal"><i class="fa fa-arrow-down" style="margin-right:4px;font-size:10px;"></i>Min ₹{{ number_format($depositsetting->min_amount, 2) }}</div>
                <div class="badge-app badge-red"><i class="fa fa-arrow-up" style="margin-right:4px;font-size:10px;"></i>Max ₹{{ number_format($depositsetting->max_amount ?? 0, 2) }}</div>
            </div>
        @endif

        {{-- Payment details collapsible --}}
        <div class="app-card" style="margin-bottom:16px;">
            <div class="list-row" onclick="togglePaymentDetails()" style="cursor:pointer;">
                <div class="list-icon gold" style="width:36px;height:36px;border-radius:10px;font-size:15px;"><i class="fa fa-building-columns"></i></div>
                <div class="list-body"><div class="title" style="color:var(--gold);">Payment Details</div></div>
                <i class="fa fa-chevron-down list-chevron" id="pdChevron"></i>
            </div>
            <div id="paymentDetails" style="display:none;padding:0 16px 16px;">
                <div class="info-row"><span class="key">Bank</span><span class="val">{{ $contact->bank ?? '—' }}</span></div>
                <div class="info-row"><span class="key">Account Name</span><span class="val">{{ $contact->account_name ?? '—' }}</span></div>
                <div class="info-row" style="cursor:pointer;" onclick="copyText('{{ $contact->account_number ?? '' }}')">
                    <span class="key">Account No.</span>
                    <span class="val" style="font-family:'Space Mono',monospace;">{{ $contact->account_number ?? '—' }} <i class="fa fa-copy" style="font-size:11px;color:var(--muted);margin-left:4px;"></i></span>
                </div>
                <div class="info-row" style="cursor:pointer;" onclick="copyText('{{ $contact->ifsc_code ?? '' }}')">
                    <span class="key">IFSC</span>
                    <span class="val" style="font-family:'Space Mono',monospace;">{{ $contact->ifsc_code ?? '—' }} <i class="fa fa-copy" style="font-size:11px;color:var(--muted);margin-left:4px;"></i></span>
                </div>
                <div class="info-row" style="border-bottom:none;"><span class="key">Branch</span><span class="val">{{ $contact->branch ?? '—' }}</span></div>
                @if(isset($contact) && $contact->qr_image)
                    <div style="text-align:center;margin-top:12px;">
                        <img src="{{ asset($contact->qr_image) }}" style="width:140px;border-radius:10px;background:#fff;padding:8px;">
                        <p style="font-size:12px;color:var(--muted);margin-top:6px;">Scan to Pay</p>
                    </div>
                @endif
            </div>
        </div>

        <form id="depositForm" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom:14px;">
                <label class="input-label">Amount (₹)</label>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="color:var(--muted);font-size:18px;">₹</span>
                    <input type="number" name="amount" class="input-app"
                        placeholder="Enter amount"
                        min="{{ $depositsetting->min_amount ?? 0 }}"
                        max="{{ $depositsetting->max_amount ?? '' }}">
                </div>
            </div>

            <div style="margin-bottom:20px;">
                <label class="input-label">Upload Payment Screenshot</label>
                <div class="app-card list-row" style="cursor:pointer;" onclick="document.getElementById('screenshotInput').click()">
                    <div class="list-icon blue" style="width:36px;height:36px;border-radius:10px;font-size:15px;"><i class="fa fa-upload"></i></div>
                    <div class="list-body">
                        <div class="title">Upload Payment Screenshot</div>
                        <div class="sub">Tap to select from gallery</div>
                    </div>
                    <i class="fa fa-chevron-right list-chevron"></i>
                </div>
                <input type="file" id="screenshotInput" name="screenshot" accept="image/*" style="display:none;">
                <div id="ssPreview" style="display:none;margin-top:10px;text-align:center;">
                    <img style="max-width:100%;border-radius:10px;max-height:160px;">
                </div>
            </div>

            <button type="submit" class="btn-app btn-teal" id="depositBtn">
                <i class="fa fa-circle-plus"></i> Submit Deposit
            </button>
        </form>
    </div>
</div>

{{-- Withdraw Sheet --}}
<div class="sheet-overlay" id="withdrawSheet">
    <div class="bottom-sheet">
        <div class="sheet-handle"></div>
        <div class="sheet-title">
            <div style="width:36px;height:36px;border-radius:10px;background:rgba(240,165,0,0.12);display:flex;align-items:center;justify-content:center;color:var(--gold);">
                <i class="fa fa-circle-arrow-up"></i>
            </div>
            Withdraw Funds
        </div>

        <div class="app-card app-card-inner" style="margin-bottom:16px;background:rgba(240,165,0,0.06);border-color:rgba(240,165,0,0.15);">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                <i class="fa fa-coins" style="color:var(--gold);font-size:14px;"></i>
                <span style="font-size:13px;color:var(--gold);font-weight:600;">10 Gold Coins = ₹1 INR</span>
            </div>
            <div class="info-row" style="border:none;padding:0;">
                <span class="key">Available Balance</span>
                <span class="val" style="font-size:16px;">₹{{ number_format($wallet->balance ?? 0, 2) }}</span>
            </div>
        </div>

        <form id="withdrawForm">
            @csrf
            <div style="margin-bottom:14px;">
                <label class="input-label">Withdrawal Amount (₹)</label>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="color:var(--muted);font-size:18px;">₹</span>
                    <input type="number" name="amount" id="wdAmount" class="input-app" placeholder="Withdrawal Amount (₹)">
                </div>
            </div>

            <div class="pct-row" style="margin-bottom:20px;">
                <button type="button" class="pct-btn" onclick="setPct(25)">25%</button>
                <button type="button" class="pct-btn" onclick="setPct(50)">50%</button>
                <button type="button" class="pct-btn" onclick="setPct(75)">75%</button>
                <button type="button" class="pct-btn" onclick="setPct(100)">100%</button>
            </div>

            <button type="submit" class="btn-app btn-gold" id="wdBtn">
                <i class="fa fa-circle-arrow-up"></i> Submit Withdrawal
            </button>
        </form>
    </div>
</div>

@push('scripts')
    <script>
    function togglePaymentDetails() {
        const el = document.getElementById('paymentDetails');
        const ch = document.getElementById('pdChevron');
        const open = el.style.display === '';
        el.style.display = open ? 'none' : '';
        ch.className = open ? 'fa fa-chevron-down list-chevron' : 'fa fa-chevron-up list-chevron';
    }
    </script>
@endpush