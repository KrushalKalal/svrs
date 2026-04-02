
<?php $__env->startSection('title', 'Reports'); ?>
<?php $__env->startSection('nav-title', 'Reports'); ?>
<?php $__env->startSection('nav-back'); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('nav-back-url', route('member.dashboard')); ?>

<?php $__env->startSection('content'); ?>
    <?php $user = auth()->user(); ?>

    
    <div style="padding:16px 20px 0;">
        <div class="segment-ctrl" id="reportTopTabs">
            <button class="active" onclick="switchReport('wallet', this)">Wallet</button>
            <?php if($user->is_refer_member): ?>
                <button onclick="switchReport('income', this)">Income</button>
                <button onclick="switchReport('tree', this)">Tree</button>
            <?php endif; ?>
        </div>
    </div>


    
    <div id="reportTab_wallet">

        
        <div style="padding:12px 20px 0;display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <div class="app-card app-card-inner">
                <p style="font-size:11px;color:var(--muted);margin-bottom:3px;">INR Balance</p>
                <p style="font-size:18px;font-weight:800;color:var(--green);">₹<?php echo e(number_format($wallet->balance ?? 0, 2)); ?></p>
            </div>
            <div class="app-card app-card-inner">
                <p style="font-size:11px;color:var(--muted);margin-bottom:3px;">Total Deposited</p>
                <p style="font-size:18px;font-weight:800;color:var(--accent-blue);">
                    ₹<?php echo e(number_format($walletTransactions->where('type','credit')->where('status',1)->sum('amount'), 2)); ?>

                </p>
            </div>
            <div class="app-card app-card-inner">
                <p style="font-size:11px;color:var(--muted);margin-bottom:3px;">Total Withdrawn</p>
                <p style="font-size:18px;font-weight:800;color:var(--red);">
                    ₹<?php echo e(number_format($walletTransactions->where('type','debit')->where('status',1)->sum('amount'), 2)); ?>

                </p>
            </div>
            <div class="app-card app-card-inner">
                <p style="font-size:11px;color:var(--muted);margin-bottom:3px;">Referral Coins</p>
                <p style="font-size:18px;font-weight:800;color:var(--gold);">
                    <?php echo e(number_format($referralRewards->sum('reward_quantity'), 4)); ?>

                </p>
            </div>
        </div>

        
        <div style="padding:12px 20px 0;">
            <div class="segment-ctrl" id="walletSubTabs">
                <button class="active" onclick="switchWalletSub('inr', this)">
                    Wallet (<?php echo e($walletTransactions->count()); ?>)
                </button>
                <button onclick="switchWalletSub('coin', this)">
                    Coins (<?php echo e($coinTrades->count()); ?>)
                </button>
                <?php if($user->is_refer_member): ?>
                    <button onclick="switchWalletSub('ref', this)">
                        Referral (<?php echo e($referralRewards->count()); ?>)
                    </button>
                    <button onclick="switchWalletSub('gold', this)">Gold</button>
                <?php endif; ?>
            </div>
        </div>

        
        <div id="walletSub_inr" style="padding:10px 20px 0;">
            <?php if($walletTransactions->isEmpty()): ?>
                <div class="empty-state"><i class="fa fa-receipt"></i><p>No INR transactions yet</p></div>
            <?php else: ?>
                <div class="app-card" style="overflow:hidden;">
                    <?php $__currentLoopData = $walletTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $txn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="list-row">
                            <div class="list-icon <?php echo e($txn->type === 'credit' ? 'teal' : 'red'); ?>"
                                style="width:40px;height:40px;border-radius:12px;font-size:15px;">
                                <i class="fa fa-arrow-<?php echo e($txn->type === 'credit' ? 'down' : 'up'); ?>"></i>
                            </div>
                            <div class="list-body">
                                <div class="title"><?php echo e($txn->remark ?? ucfirst($txn->type)); ?></div>
                                <div class="sub"><?php echo e($txn->created_at->format('d M Y, h:i A')); ?></div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-size:14px;font-weight:700;color:<?php echo e($txn->type === 'credit' ? 'var(--green)' : 'var(--red)'); ?>;">
                                    <?php echo e($txn->type === 'credit' ? '+' : '-'); ?>₹<?php echo e(number_format($txn->amount, 2)); ?>

                                </div>
                                <div style="margin-top:3px;">
                                    <?php if($txn->status == 1): ?><span class="badge-app badge-green" style="font-size:10px;">Approved</span>
                                    <?php elseif($txn->status == 0): ?><span class="badge-app badge-red" style="font-size:10px;">Rejected</span>
                                    <?php else: ?><span class="badge-app badge-gold" style="font-size:10px;">Pending</span><?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>

        
        <div id="walletSub_coin" style="display:none;padding:10px 20px 0;">
            <?php if($coinTrades->isEmpty()): ?>
                <div class="empty-state"><i class="fa fa-coins"></i><p>No coin trades yet</p></div>
            <?php else: ?>
                <div class="app-card" style="overflow:hidden;">
                    <?php $__currentLoopData = $coinTrades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="list-row">
                            <div class="list-icon <?php echo e($trade->type === 'buy' ? 'green' : ($trade->type === 'reward' ? 'gold' : 'red')); ?>"
                                style="width:40px;height:40px;border-radius:12px;font-size:15px;">
                                <i class="fa fa-<?php echo e($trade->type === 'buy' ? 'arrow-down' : ($trade->type === 'reward' ? 'gift' : 'arrow-up')); ?>"></i>
                            </div>
                            <div class="list-body">
                                <div class="title"><?php echo e(ucfirst($trade->type)); ?></div>
                                <div class="sub"><?php echo e($trade->created_at->format('d M Y, h:i A')); ?></div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-size:14px;font-weight:700;"><?php echo e(number_format($trade->quantity, 4)); ?> SVRS</div>
                                <div style="font-size:11px;color:var(--muted);">₹<?php echo e(number_format($trade->price, 4)); ?>/coin</div>
                                <div style="font-size:11px;color:var(--muted);">
                                    ₹<?php echo e(number_format($trade->total ?? ($trade->price * $trade->quantity), 2)); ?>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>

        
        <?php if($user->is_refer_member): ?>
            <div id="walletSub_ref" style="display:none;padding:10px 20px 0;">
                <?php if($referralRewards->isEmpty()): ?>
                    <div class="empty-state"><i class="fa fa-share-nodes"></i><p>No referral rewards yet</p></div>
                <?php else: ?>
                    <div class="app-card" style="overflow:hidden;">
                        <?php $__currentLoopData = $referralRewards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-row">
                                <div class="list-icon blue" style="width:40px;height:40px;border-radius:12px;font-size:15px;">
                                    <i class="fa fa-user-group"></i>
                                </div>
                                <div class="list-body">
                                    <div class="title"><?php echo e($rw->fromUser->full_name ?? '—'); ?></div>
                                    <div class="sub">Level <?php echo e($rw->level); ?> • <?php echo e($rw->percentage); ?>% • <?php echo e($rw->created_at->format('d M Y')); ?></div>
                                </div>
                                <div style="text-align:right;">
                                    <div style="font-size:14px;font-weight:700;color:var(--green);">+<?php echo e(number_format($rw->reward_quantity, 4)); ?></div>
                                    <div style="font-size:11px;color:var(--muted);">SVRS</div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>

            
            <div id="walletSub_gold" style="display:none;padding:10px 20px 0;">
                <?php if($goldTransactions->isEmpty()): ?>
                    <div class="empty-state"><i class="fa fa-coins"></i><p>No G-Coin transactions yet</p></div>
                <?php else: ?>
                    <div class="app-card" style="overflow:hidden;">
                        <?php $__currentLoopData = $goldTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-row">
                                <div class="list-icon <?php echo e($gt->type === 'credit' ? 'gold' : 'red'); ?>"
                                    style="width:40px;height:40px;border-radius:12px;font-size:15px;">
                                    <i class="fa fa-<?php echo e($gt->type === 'credit' ? 'arrow-down' : 'arrow-up'); ?>"></i>
                                </div>
                                <div class="list-body">
                                    <div class="title"><?php echo e($gt->remark ?? ucfirst($gt->type)); ?></div>
                                    <div class="sub"><?php echo e($gt->created_at->format('d M Y, h:i A')); ?></div>
                                </div>
                                <div style="text-align:right;">
                                    <div style="font-size:14px;font-weight:700;color:<?php echo e($gt->type === 'credit' ? 'var(--gold)' : 'var(--red)'); ?>;">
                                        <?php echo e($gt->type === 'credit' ? '+' : '-'); ?><?php echo e(number_format($gt->amount)); ?> G
                                    </div>
                                    <div style="font-size:11px;color:var(--muted);">₹<?php echo e(number_format($gt->amount / 10, 2)); ?></div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>


    
    <?php if($user->is_refer_member): ?>
    <div id="reportTab_income" style="display:none;">

        
        <div style="padding:12px 20px 0;display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <div class="app-card app-card-inner">
                <p style="font-size:11px;color:var(--muted);margin-bottom:3px;">Level 1 (0.5%)</p>
                <p style="font-size:18px;font-weight:800;color:var(--accent-blue);"><?php echo e(number_format($totalLevel1, 4)); ?></p>
                <p style="font-size:10px;color:var(--muted);">SVRS Coins</p>
            </div>
            <div class="app-card app-card-inner">
                <p style="font-size:11px;color:var(--muted);margin-bottom:3px;">Level 2 (0.05%)</p>
                <p style="font-size:18px;font-weight:800;color:var(--green);"><?php echo e(number_format($totalLevel2, 4)); ?></p>
                <p style="font-size:10px;color:var(--muted);">SVRS Coins</p>
            </div>
            <div class="app-card app-card-inner">
                <p style="font-size:11px;color:var(--muted);margin-bottom:3px;">Level 3 (0.01%)</p>
                <p style="font-size:18px;font-weight:800;color:var(--accent);"><?php echo e(number_format($totalLevel3, 4)); ?></p>
                <p style="font-size:10px;color:var(--muted);">SVRS Coins</p>
            </div>
            <div class="app-card app-card-inner">
                <p style="font-size:11px;color:var(--muted);margin-bottom:3px;">Total Income</p>
                <p style="font-size:18px;font-weight:800;color:var(--gold);"><?php echo e(number_format($totalCoins, 4)); ?></p>
                <p style="font-size:10px;color:var(--muted);">SVRS Coins</p>
            </div>
        </div>

        
        <div style="padding:12px 20px 0;">
            <div class="segment-ctrl" id="incomeSubTabs">
                <button class="active" onclick="switchIncomeSub('l1', this)">Level 1 (<?php echo e($level1Rewards->count()); ?>)</button>
                <button onclick="switchIncomeSub('l2', this)">Level 2 (<?php echo e($level2Rewards->count()); ?>)</button>
                <button onclick="switchIncomeSub('l3', this)">Level 3 (<?php echo e($level3Rewards->count()); ?>)</button>
            </div>
        </div>

        <?php $__currentLoopData = [['l1', $level1Rewards, $totalLevel1], ['l2', $level2Rewards, $totalLevel2], ['l3', $level3Rewards, $totalLevel3]]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$pid, $rewards, $total]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div id="incomeSub_<?php echo e($pid); ?>" style="<?php echo e($pid === 'l1' ? '' : 'display:none;'); ?>padding:10px 20px 0;">
                <?php if($rewards->isEmpty()): ?>
                    <div class="empty-state">
                        <i class="fa fa-coins"></i>
                        <p>No <?php echo e(strtoupper($pid)); ?> rewards yet</p>
                    </div>
                <?php else: ?>
                    <div class="app-card" style="overflow:hidden;">
                        <?php $__currentLoopData = $rewards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-row">
                                <div class="list-icon blue" style="width:40px;height:40px;border-radius:12px;font-size:15px;">
                                    <i class="fa fa-user"></i>
                                </div>
                                <div class="list-body">
                                    <div class="title"><?php echo e($rw->fromUser->full_name ?? '—'); ?></div>
                                    <div class="sub">
                                        <?php echo e($rw->fromUser->member_code ?? '—'); ?> • <?php echo e($rw->percentage); ?>% •
                                        Base: <?php echo e(number_format($rw->base_quantity, 4)); ?> SVRS
                                    </div>
                                    <div class="sub"><?php echo e($rw->created_at->format('d M Y, h:i A')); ?></div>
                                </div>
                                <div style="text-align:right;">
                                    <div style="font-size:14px;font-weight:700;color:var(--green);">
                                        +<?php echo e(number_format($rw->reward_quantity, 4)); ?>

                                    </div>
                                    <div style="font-size:11px;color:var(--muted);">SVRS</div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <p style="font-size:13px;font-weight:700;color:var(--green);text-align:right;padding:10px 20px 0;">
                        Total: +<?php echo e(number_format($total, 4)); ?> SVRS
                    </p>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <div class="section-label">Earning Rate Reference</div>
        <div style="margin:0 20px;" class="app-card">
            <?php $__currentLoopData = [
                ['blue', '1', 'Level 1 (Direct)', '0.5% per buy', '+0.5 SVRS / 100'],
                ['teal', '2', 'Level 2',           '0.05% per buy', '+0.05 SVRS / 100'],
                ['gold', '3', 'Level 3',           '0.01% per buy', '+0.01 SVRS / 100'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$color, $num, $title, $sub, $rate]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="list-row">
                    <div class="list-icon <?php echo e($color); ?>" style="width:36px;height:36px;border-radius:10px;font-size:14px;">
                        <i class="fa fa-<?php echo e($num); ?>"></i>
                    </div>
                    <div class="list-body">
                        <div class="title"><?php echo e($title); ?></div>
                        <div class="sub"><?php echo e($sub); ?></div>
                    </div>
                    <div style="color:var(--green);font-weight:700;font-size:12px;"><?php echo e($rate); ?></div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

    </div>


    
    <div id="reportTab_tree" style="display:none;">

        
        <div style="padding:12px 20px 0;display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;">
            <div class="app-card app-card-inner" style="text-align:center;padding:12px 8px;">
                <p style="font-size:22px;font-weight:800;color:var(--accent-blue);"><?php echo e($treeLevel1->count()); ?></p>
                <p style="font-size:11px;color:var(--muted);">Level 1</p>
                <p style="font-size:10px;color:var(--green);">
                    <?php echo e($treeLevel1->where('is_refer_member', 1)->where('status', 1)->count()); ?> refer
                </p>
            </div>
            <div class="app-card app-card-inner" style="text-align:center;padding:12px 8px;">
                <p style="font-size:22px;font-weight:800;color:var(--green);"><?php echo e($treeLevel2->count()); ?></p>
                <p style="font-size:11px;color:var(--muted);">Level 2</p>
                <p style="font-size:10px;color:var(--green);">
                    <?php echo e($treeLevel2->where('is_refer_member', 1)->where('status', 1)->count()); ?> refer
                </p>
            </div>
            <div class="app-card app-card-inner" style="text-align:center;padding:12px 8px;">
                <p style="font-size:22px;font-weight:800;color:var(--accent);"><?php echo e($treeLevel3->count()); ?></p>
                <p style="font-size:11px;color:var(--muted);">Level 3</p>
                <p style="font-size:10px;color:var(--green);">
                    <?php echo e($treeLevel3->where('is_refer_member', 1)->where('status', 1)->count()); ?> refer
                </p>
            </div>
        </div>

        
        <div style="padding:12px 20px 0;">
            <div class="segment-ctrl" id="treeSubTabs">
                <button class="active" onclick="switchTreeSub('l1', this)">Level 1 (<?php echo e($treeLevel1->count()); ?>)</button>
                <button onclick="switchTreeSub('l2', this)">Level 2 (<?php echo e($treeLevel2->count()); ?>)</button>
                <button onclick="switchTreeSub('l3', this)">Level 3 (<?php echo e($treeLevel3->count()); ?>)</button>
            </div>
        </div>

        <?php $__currentLoopData = [['l1', $treeLevel1, 1], ['l2', $treeLevel2, 2], ['l3', $treeLevel3, 3]]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$pid, $members, $lvl]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div id="treeSub_<?php echo e($pid); ?>" style="<?php echo e($pid === 'l1' ? '' : 'display:none;'); ?>padding:10px 20px 0;">
                <?php if($members->isEmpty()): ?>
                    <div class="empty-state"><i class="fa fa-users"></i><p>No Level <?php echo e($lvl); ?> members yet</p></div>
                <?php else: ?>
                    <div class="app-card" style="overflow:hidden;">
                        <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-row">
                                <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,var(--gold),var(--gold-dark));display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:800;color:#000;flex-shrink:0;">
                                    <?php echo e(strtoupper(substr($m->first_name, 0, 1))); ?>

                                </div>
                                <div class="list-body">
                                    <div class="title"><?php echo e($m->full_name); ?></div>
                                    <div class="sub" style="font-family:'Space Mono',monospace;font-size:11px;"><?php echo e($m->member_code); ?></div>
                                    <div class="sub">Joined <?php echo e($m->created_at->format('d M Y')); ?></div>
                                </div>
                                <div style="text-align:right;display:flex;flex-direction:column;gap:4px;align-items:flex-end;">
                                    <?php if($m->status == 1): ?><span class="badge-app badge-green" style="font-size:10px;">Active</span>
                                    <?php elseif($m->status == 0): ?><span class="badge-app badge-red" style="font-size:10px;">Inactive</span>
                                    <?php else: ?><span class="badge-app badge-gold" style="font-size:10px;">Pending</span><?php endif; ?>
                                    <?php if($m->is_refer_member): ?>
                                        <span class="badge-app badge-teal" style="font-size:10px;">Refer</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>
    <?php endif; ?> 

    <div style="height:8px;"></div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// ── Top report tab switcher ──
function switchReport(tab, btn) {
    ['wallet', 'income', 'tree'].forEach(function(t) {
        var el = document.getElementById('reportTab_' + t);
        if (el) el.style.display = (t === tab) ? '' : 'none';
    });
    document.querySelectorAll('#reportTopTabs button').forEach(function(b) {
        b.classList.remove('active');
    });
    if (btn) btn.classList.add('active');
}

// ── Wallet sub-tab switcher ──
function switchWalletSub(tab, btn) {
    ['inr', 'coin', 'ref', 'gold'].forEach(function(p) {
        var el = document.getElementById('walletSub_' + p);
        if (el) el.style.display = (p === tab) ? '' : 'none';
    });
    document.querySelectorAll('#walletSubTabs button').forEach(function(b) {
        b.classList.remove('active');
    });
    if (btn) btn.classList.add('active');
}

// ── Income sub-tab switcher ──
function switchIncomeSub(tab, btn) {
    ['l1', 'l2', 'l3'].forEach(function(p) {
        var el = document.getElementById('incomeSub_' + p);
        if (el) el.style.display = (p === tab) ? '' : 'none';
    });
    document.querySelectorAll('#incomeSubTabs button').forEach(function(b) {
        b.classList.remove('active');
    });
    if (btn) btn.classList.add('active');
}

// ── Tree sub-tab switcher ──
function switchTreeSub(tab, btn) {
    ['l1', 'l2', 'l3'].forEach(function(p) {
        var el = document.getElementById('treeSub_' + p);
        if (el) el.style.display = (p === tab) ? '' : 'none';
    });
    document.querySelectorAll('#treeSubTabs button').forEach(function(b) {
        b.classList.remove('active');
    });
    if (btn) btn.classList.add('active');
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('member.layout.app-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Qubeta\svrs\resources\views/member/reports/index.blade.php ENDPATH**/ ?>