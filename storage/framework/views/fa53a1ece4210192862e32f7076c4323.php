<?php $__env->startSection('title', 'Dashboard — SVRS Coin'); ?>
<?php $__env->startSection('nav-title', 'Dashboard'); ?>


<?php $__env->startSection('content'); ?>

    
    <div class="px py"
        style="padding-bottom: 0; display:flex; align-items:center; justify-content:space-between; padding-top:20px;">
        <div>
            <p style="font-size:13px; color:var(--muted); margin-bottom:2px;">Welcome back</p>
            <h2 style="font-size:22px; font-weight:800;"><?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?></h2>
        </div>
        <div class="stat-pill <?php echo e($user->status == 1 ? 'green' : 'gold'); ?>">
            <i class="fa fa-circle" style="font-size:7px;"></i>
            <?php echo e($user->status == 1 ? 'Active' : 'Pending'); ?>

        </div>
    </div>

    
    <?php if(!$user->is_refer_member): ?>
        <div style="margin-top:16px;">
            <a href="<?php echo e(route('member.membership')); ?>" class="upgrade-banner">
                <div class="ub-icon"><i class="fa fa-medal"></i></div>
                <div class="ub-body">
                    <div class="ub-title">Upgrade to Refer &amp; Earn</div>
                    <div class="ub-sub">Unlock referrals, gold rewards &amp; milestones</div>
                </div>
                <i class="fa fa-chevron-right ub-arrow"></i>
            </a>
        </div>
    <?php else: ?>
        <div style="margin: 16px 20px 0;">
            <div class="accent-card" style="padding:14px 16px; display:flex; align-items:center; gap:10px;">
                <div
                    style="width:36px;height:36px;border-radius:10px;background:rgba(0,212,170,0.15);display:flex;align-items:center;justify-content:center;color:var(--accent);font-size:18px;">
                    <i class="fa fa-circle-check"></i>
                </div>
                <div>
                    <div style="font-size:14px;font-weight:700;">Refer &amp; Earn Active</div>
                    <div style="font-size:12px;color:var(--muted);">Earn coins on every referral buy</div>
                </div>
                <?php if($user->is_refer_member): ?>
                    <div class="badge-app badge-teal" style="margin-left:auto;">Active</div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    
    <div class="section-label">Quick Actions</div>
    <div class="quick-grid">
        <a href="<?php echo e(route('member.coin')); ?>" class="quick-card">
            <div class="qc-icon" style="background:rgba(240,165,0,0.12);color:var(--gold);">
                <i class="fa fa-bitcoin-sign"></i>
            </div>
            <div class="qc-body">
                <div class="qc-title">Buy Coin</div>
                <div class="qc-sub">Trade SVRS</div>
            </div>
            <i class="fa fa-chevron-right qc-arrow"></i>
        </a>

        <a href="<?php echo e(route('member.my.wallet')); ?>" class="quick-card">
            <div class="qc-icon" style="background:rgba(0,212,170,0.12);color:var(--accent);">
                <i class="fa fa-wallet"></i>
            </div>
            <div class="qc-body">
                <div class="qc-title">My Wallet</div>
                <div class="qc-sub">₹<?php echo e(number_format($wallet->balance ?? 0, 2)); ?></div>
            </div>
            <i class="fa fa-chevron-right qc-arrow"></i>
        </a>

        <?php if($user->is_refer_member): ?>
            <a href="<?php echo e(route('member.add.member')); ?>" class="quick-card">
                <div class="qc-icon" style="background:rgba(59,130,246,0.12);color:var(--accent-blue);">
                    <i class="fa fa-user-plus"></i>
                </div>
                <div class="qc-body">
                    <div class="qc-title">Add Me...</div>
                    <div class="qc-sub">Register u...</div>
                </div>
                <i class="fa fa-chevron-right qc-arrow"></i>
            </a>

            <a href="<?php echo e(route('member.reports')); ?>" class="quick-card">
                <div class="qc-icon" style="background:rgba(16,185,129,0.12);color:var(--green);">
                    <i class="fa fa-chart-bar"></i>
                </div>
                <div class="qc-body">
                    <div class="qc-title">Reports</div>
                    <div class="qc-sub">Income &amp; ...</div>
                </div>
                <i class="fa fa-chevron-right qc-arrow"></i>
            </a>
        <?php else: ?>
            <a href="<?php echo e(route('member.profile')); ?>" class="quick-card">
                <div class="qc-icon" style="background:rgba(59,130,246,0.12);color:var(--accent-blue);">
                    <i class="fa fa-user"></i>
                </div>
                <div class="qc-body">
                    <div class="qc-title">My Profile</div>
                    <div class="qc-sub">View &amp; edit...</div>
                </div>
                <i class="fa fa-chevron-right qc-arrow"></i>
            </a>

            <a href="<?php echo e(route('member.reports')); ?>" class="quick-card">
                <div class="qc-icon" style="background:rgba(16,185,129,0.12);color:var(--green);">
                    <i class="fa fa-chart-bar"></i>
                </div>
                <div class="qc-body">
                    <div class="qc-title">Reports</div>
                    <div class="qc-sub">Income &amp; ...</div>
                </div>
                <i class="fa fa-chevron-right qc-arrow"></i>
            </a>
        <?php endif; ?>
    </div>

    
    <div class="section-label">Overview</div>
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; padding:0 20px;">

        
        <div class="app-card app-card-inner" style="position:relative;">
            <div class="list-icon teal"
                style="margin-bottom:10px; width:36px;height:36px;border-radius:10px;font-size:16px;">
                <i class="fa fa-users"></i>
            </div>
            <p style="font-size:12px;color:var(--muted);margin-bottom:4px;">Total Referrals</p>
            <p style="font-size:22px;font-weight:800;"><?php echo e($totalReferrals); ?></p>
            <p style="font-size:11px;color:var(--muted);margin-top:2px;"><?php echo e($activeReferrals); ?> active</p>
        </div>

        
        <div class="app-card app-card-inner">
            <div class="list-icon gold"
                style="margin-bottom:10px; width:36px;height:36px;border-radius:10px;font-size:16px;">
                <i class="fa fa-coins"></i>
            </div>
            <p style="font-size:12px;color:var(--muted);margin-bottom:4px;">Referral Earn...</p>
            <p style="font-size:22px;font-weight:800;"><?php echo e(number_format($referralEarnings, 2)); ?></p>
            <p style="font-size:11px;color:var(--muted);margin-top:2px;">SVRS coins ea...</p>
        </div>

        
        <div class="app-card app-card-inner">
            <div
                style="width:36px;height:36px;border-radius:10px;background:rgba(240,165,0,0.12);display:flex;align-items:center;justify-content:center;font-size:18px;margin-bottom:10px;">
                ⭐
            </div>
            <p style="font-size:12px;color:var(--muted);margin-bottom:4px;">Gold Coins</p>
            <p style="font-size:22px;font-weight:800;color:var(--gold);"><?php echo e(number_format($goldWallet->balance ?? 0)); ?>

                <span style="font-size:14px;font-weight:600;">G</span></p>
            <p style="font-size:11px;color:var(--muted);margin-top:2px;">≈
                ₹<?php echo e(number_format(($goldWallet->balance ?? 0) / 10, 2)); ?></p>
        </div>

        
        <a href="<?php echo e(route('member.my.wallet')); ?>" class="app-card app-card-inner" style="text-decoration:none;">
            <div class="list-icon teal"
                style="margin-bottom:10px;width:36px;height:36px;border-radius:10px;font-size:16px;background:rgba(0,212,170,0.12);">
                <i class="fa fa-wallet"></i>
            </div>
            <p style="font-size:12px;color:var(--muted);margin-bottom:4px;">Wallet Balan...</p>
            <p style="font-size:20px;font-weight:800;color:var(--accent);">₹<?php echo e(number_format($wallet->balance ?? 0, 2)); ?>

            </p>
            <p style="font-size:11px;color:var(--accent);margin-top:4px;">Tap to man... <i class="fa fa-chevron-right"
                    style="font-size:9px;"></i></p>
        </a>
    </div>

    
    <?php if($user->is_refer_member): ?>
        <div class="section-label">Milestone Progress</div>
        <div style="padding:0 20px; display:flex; flex-direction:column; gap:12px;">
            <?php
                $milestones = [
                    ['name' => 'First Reward', 'need' => 10, 'coins' => 10000],
                    ['name' => 'Option A', 'need' => 15, 'coins' => 5000],
                    ['name' => 'Option B', 'need' => 20, 'coins' => 10000],
                ];
            ?>
            <?php $__currentLoopData = $milestones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $pct = min(100, ($activeReferrals / $m['need']) * 100); ?>
                <div class="milestone-card <?php echo e($pct >= 100 ? 'completed' : ''); ?>">
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
                        <div>
                            <p style="font-size:14px;font-weight:700;"><?php echo e($m['name']); ?></p>
                            <p style="font-size:12px;color:var(--muted);"><?php echo e(number_format($m['coins'])); ?> G-Coins on
                                <?php echo e($m['need']); ?> referrals</p>
                        </div>
                        <?php if($pct >= 100): ?>
                            <div class="badge-app badge-green"><i class="fa fa-check"
                                    style="margin-right:4px;font-size:10px;"></i>Eligible</div>
                        <?php endif; ?>
                    </div>
                    <div class="progress-app">
                        <div class="progress-fill <?php echo e($pct >= 100 ? 'green' : ''); ?>" style="width:<?php echo e($pct); ?>%;"></div>
                    </div>
                    <p style="font-size:12px;color:var(--muted);margin-top:6px;"><?php echo e($activeReferrals); ?> / <?php echo e($m['need']); ?></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <a href="<?php echo e(route('member.my.rewards')); ?>" class="btn-app btn-gold" style="margin-top:4px;">
                <i class="fa fa-trophy"></i> View All Milestones &amp; Claim
            </a>
        </div>
    <?php endif; ?>

    
    <div style="height:8px;"></div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('member.layout.app-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Qubeta\svrs\resources\views/member/dashboard.blade.php ENDPATH**/ ?>