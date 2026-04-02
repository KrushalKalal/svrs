<?php $__env->startSection('title', 'INR Wallet — SVRS Coin'); ?><?php $__env->startSection('nav-title', 'INR Wallet'); ?>
<?php $__env->startSection('nav-back'); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('nav-back-url', route('member.dashboard')); ?>

<?php $__env->startSection('nav-actions'); ?>
    <button class="nav-action-btn" onclick="location.reload()" title="Refresh">
        <i class="fa fa-rotate-right"></i>
    </button>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    
    <div style="padding:16px 20px 0;">
        <div class="segment-ctrl">
            <button class="active" id="tabBalance" onclick="switchTab('balance')">Balance</button>
            <button id="tabTxn" onclick="switchTab('txn')">Transactions</button>
        </div>
    </div>

    
    <div id="panelBalance">

        
        <div style="padding:16px 20px 0;">
            <div class="accent-card" style="padding:20px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                    <div style="width:40px;height:40px;background:rgba(0,212,170,0.15);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;color:var(--accent);">
                        <i class="fa fa-wallet"></i>
                    </div>
                    <div class="badge-app badge-teal">INR Wallet</div>
                </div>
                <p style="font-size:13px;color:var(--muted);margin-bottom:4px;">Available Balance</p>
                <p class="big-amount teal">₹<?php echo e(number_format($wallet->balance ?? 0, 2)); ?></p>

                <div style="display:flex;gap:10px;margin-top:20px;">
                    <button onclick="openSheet('depositSheet')" class="btn-app btn-outline-teal" style="flex:1;padding:12px;font-size:14px;border-radius:var(--radius-sm);">
                        <i class="fa fa-circle-plus"></i> Deposit
                    </button>
                    <?php if($hasBankDetail): ?>
                        <button onclick="openSheet('withdrawSheet')" class="btn-app btn-gold" style="flex:1;padding:12px;font-size:14px;border-radius:var(--radius-sm);">
                            <i class="fa fa-circle-arrow-up"></i> Withdraw
                        </button>
                    <?php else: ?>
                        <a href="<?php echo e(route('member.profile')); ?>" class="btn-app btn-outline-gold" style="flex:1;padding:12px;font-size:14px;border-radius:var(--radius-sm);text-decoration:none;">
                            <i class="fa fa-bank"></i> Add Bank
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <?php if($depositsetting): ?>
            <div class="section-label">Deposit Limits</div>
            <div style="padding:0 20px;display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div class="app-card app-card-inner" style="text-align:center;">
                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(16,185,129,0.12);display:flex;align-items:center;justify-content:center;margin:0 auto 8px;">
                        <i class="fa fa-arrow-down" style="color:var(--green);font-size:14px;"></i>
                    </div>
                    <p style="font-size:11px;color:var(--muted);">Minimum</p>
                    <p style="font-size:18px;font-weight:800;color:var(--green);">₹<?php echo e(number_format($depositsetting->min_amount, 2)); ?></p>
                </div>
                <div class="app-card app-card-inner" style="text-align:center;">
                    <div style="width:32px;height:32px;border-radius:8px;background:rgba(239,68,68,0.12);display:flex;align-items:center;justify-content:center;margin:0 auto 8px;">
                        <i class="fa fa-arrow-up" style="color:var(--red);font-size:14px;"></i>
                    </div>
                    <p style="font-size:11px;color:var(--muted);">Maximum</p>
                    <p style="font-size:18px;font-weight:800;color:var(--red);">₹<?php echo e(number_format($depositsetting->max_amount ?? 0, 2)); ?></p>
                </div>
            </div>
        <?php endif; ?>

        
        <div class="section-label">Transaction Summary</div>
        <div style="margin:0 20px;" class="app-card">
            <?php
                $approved = $transactions->where('status', 1);
                $credits = $approved->where('type', 'credit');
                $debits = $approved->where('type', 'debit');
            ?>
            <div class="info-row px" style="padding-top:14px;">
                <span class="key">Total Transactions</span>
                <span class="val"><?php echo e($transactions->count()); ?></span>
            </div>
            <div class="info-row px">
                <span class="key">Credits</span>
                <span class="val" style="color:var(--green);">+<?php echo e($credits->count()); ?></span>
            </div>
            <div class="info-row px" style="padding-bottom:14px;">
                <span class="key">Debits</span>
                <span class="val" style="color:var(--red);">−<?php echo e($debits->count()); ?></span>
            </div>
        </div>

        <?php if($pendingDeposit > 0 || $pendingWithdraw > 0): ?>
            <div style="margin:12px 20px 0;">
                <div class="alert-app gold-alert">
                    <i class="fa fa-circle-info" style="color:var(--gold);"></i>
                    <span>
                        <?php if($pendingDeposit > 0): ?> Pending deposit: ₹<?php echo e(number_format($pendingDeposit, 2)); ?>. <?php endif; ?>
                        <?php if($pendingWithdraw > 0): ?> Pending withdrawal: ₹<?php echo e(number_format($pendingWithdraw, 2)); ?>. <?php endif; ?>
                        Deposits require admin approval before crediting.
                    </span>
                </div>
            </div>
        <?php endif; ?>
    </div>

    
    <div id="panelTxn" style="display:none;">
        <div style="padding:16px 20px 0;">
            <?php if($transactions->isEmpty()): ?>
                <div class="empty-state">
                    <i class="fa fa-receipt"></i>
                    <p>No transactions yet</p>
                </div>
            <?php else: ?>
                <div class="app-card" style="overflow:hidden;">
                    <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $txn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="list-row">
                            <div class="list-icon <?php echo e($txn->type === 'credit' ? 'teal' : 'red'); ?>" style="width:40px;height:40px;border-radius:12px;font-size:15px;">
                                <i class="fa fa-arrow-<?php echo e($txn->type === 'credit' ? 'down' : 'up'); ?>"></i>
                            </div>
                            <div class="list-body">
                                <div class="title"><?php echo e($txn->remark ?? ucfirst($txn->type)); ?></div>
                                <div class="sub"><?php echo e($txn->created_at->format('d M Y, h:i A')); ?></div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-size:15px;font-weight:700;color:<?php echo e($txn->type === 'credit' ? 'var(--green)' : 'var(--red)'); ?>;">
                                    <?php echo e($txn->type === 'credit' ? '+' : '-'); ?>₹<?php echo e(number_format($txn->amount, 2)); ?>

                                </div>
                                <div style="margin-top:3px;">
                                    <?php if($txn->status == 1): ?>
                                        <span class="badge-app badge-green" style="font-size:10px;">Approved</span>
                                    <?php elseif($txn->status == 0): ?>
                                        <span class="badge-app badge-red" style="font-size:10px;">Rejected</span>
                                    <?php else: ?>
                                        <span class="badge-app badge-gold" style="font-size:10px;">Pending</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div style="height:8px;"></div>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
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
        const maxBal = <?php echo e($wallet->balance ?? 0); ?>;
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
                url: "<?php echo e(route('member.wallet.add.money')); ?>",
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
                url: "<?php echo e(route('member.wallet.withdraw.request')); ?>",
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
<?php $__env->stopPush(); ?>


<div class="sheet-overlay" id="depositSheet">
    <div class="bottom-sheet">
        <div class="sheet-handle"></div>
        <div class="sheet-title">
            <div style="width:36px;height:36px;border-radius:10px;background:rgba(0,212,170,0.12);display:flex;align-items:center;justify-content:center;color:var(--accent);">
                <i class="fa fa-circle-plus"></i>
            </div>
            Deposit Funds
        </div>

        <?php if($depositsetting): ?>
            <div style="display:flex;gap:8px;margin-bottom:16px;">
                <div class="badge-app badge-teal"><i class="fa fa-arrow-down" style="margin-right:4px;font-size:10px;"></i>Min ₹<?php echo e(number_format($depositsetting->min_amount, 2)); ?></div>
                <div class="badge-app badge-red"><i class="fa fa-arrow-up" style="margin-right:4px;font-size:10px;"></i>Max ₹<?php echo e(number_format($depositsetting->max_amount ?? 0, 2)); ?></div>
            </div>
        <?php endif; ?>

        
        <div class="app-card" style="margin-bottom:16px;">
            <div class="list-row" onclick="togglePaymentDetails()" style="cursor:pointer;">
                <div class="list-icon gold" style="width:36px;height:36px;border-radius:10px;font-size:15px;"><i class="fa fa-building-columns"></i></div>
                <div class="list-body"><div class="title" style="color:var(--gold);">Payment Details</div></div>
                <i class="fa fa-chevron-down list-chevron" id="pdChevron"></i>
            </div>
            <div id="paymentDetails" style="display:none;padding:0 16px 16px;">
                <div class="info-row"><span class="key">Bank</span><span class="val"><?php echo e($contact->bank ?? '—'); ?></span></div>
                <div class="info-row"><span class="key">Account Name</span><span class="val"><?php echo e($contact->account_name ?? '—'); ?></span></div>
                <div class="info-row" style="cursor:pointer;" onclick="copyText('<?php echo e($contact->account_number ?? ''); ?>')">
                    <span class="key">Account No.</span>
                    <span class="val" style="font-family:'Space Mono',monospace;"><?php echo e($contact->account_number ?? '—'); ?> <i class="fa fa-copy" style="font-size:11px;color:var(--muted);margin-left:4px;"></i></span>
                </div>
                <div class="info-row" style="cursor:pointer;" onclick="copyText('<?php echo e($contact->ifsc_code ?? ''); ?>')">
                    <span class="key">IFSC</span>
                    <span class="val" style="font-family:'Space Mono',monospace;"><?php echo e($contact->ifsc_code ?? '—'); ?> <i class="fa fa-copy" style="font-size:11px;color:var(--muted);margin-left:4px;"></i></span>
                </div>
                <div class="info-row" style="border-bottom:none;"><span class="key">Branch</span><span class="val"><?php echo e($contact->branch ?? '—'); ?></span></div>
                <?php if(isset($contact) && $contact->qr_image): ?>
                    <div style="text-align:center;margin-top:12px;">
                        <img src="<?php echo e(asset($contact->qr_image)); ?>" style="width:140px;border-radius:10px;background:#fff;padding:8px;">
                        <p style="font-size:12px;color:var(--muted);margin-top:6px;">Scan to Pay</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <form id="depositForm" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div style="margin-bottom:14px;">
                <label class="input-label">Amount (₹)</label>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="color:var(--muted);font-size:18px;">₹</span>
                    <input type="number" name="amount" class="input-app"
                        placeholder="Enter amount"
                        min="<?php echo e($depositsetting->min_amount ?? 0); ?>"
                        max="<?php echo e($depositsetting->max_amount ?? ''); ?>">
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
                <span class="val" style="font-size:16px;">₹<?php echo e(number_format($wallet->balance ?? 0, 2)); ?></span>
            </div>
        </div>

        <form id="withdrawForm">
            <?php echo csrf_field(); ?>
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

<?php $__env->startPush('scripts'); ?>
    <script>
    function togglePaymentDetails() {
        const el = document.getElementById('paymentDetails');
        const ch = document.getElementById('pdChevron');
        const open = el.style.display === '';
        el.style.display = open ? 'none' : '';
        ch.className = open ? 'fa fa-chevron-down list-chevron' : 'fa fa-chevron-up list-chevron';
    }
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('member.layout.app-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Qubeta\svrs\resources\views/admin/my_wallet.blade.php ENDPATH**/ ?>