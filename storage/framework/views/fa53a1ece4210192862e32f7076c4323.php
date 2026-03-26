<?php $__env->startSection('title', config('app.name') . ' || Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Dashboard</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <span class="badge bg-<?php echo e($user->status == 1 ? 'success' : 'warning'); ?>  px-3 py-2">
                    <?php echo e($user->status == 1 ? 'Active' : 'Pending Activation'); ?>

                </span>
                <?php if($user->is_refer_member): ?>
                    <span class="badge bg-primary  px-3 py-2 ms-1">
                        <i class="ti ti-share me-1"></i>Refer and Earn Active
                    </span>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="row">

            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Wallet Balance</p>
                                <h4 class="mb-0">&#8377;<?php echo e(number_format($wallet->balance ?? 0, 2)); ?></h4>
                            </div>
                            <div class="avatar bg-soft-primary rounded-circle">
                                <i class="ti ti-wallet text-primary"></i>
                            </div>
                        </div>
                        <a href="<?php echo e(route('member.my.wallet')); ?>" class="btn btn-sm btn-outline-primary mt-3 w-100">View
                            Wallet</a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Coin Balance</p>
                                <h4 class="mb-0"><?php echo e(number_format($coinBalance, 4)); ?> <small
                                        class="text-muted">SVRS</small></h4>
                                <small class="text-success">approx &#8377;<?php echo e(number_format($coinValue, 2)); ?></small>
                            </div>
                            <div class="avatar bg-soft-warning rounded-circle">
                                <i class="ti ti-coins  text-warning"></i>
                            </div>
                        </div>
                        <a href="<?php echo e(route('member.coin')); ?>" class="btn btn-sm btn-outline-warning mt-3 w-100">Trade
                            Coins</a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Total Referrals</p>
                                <h4 class="mb-0"><?php echo e($totalReferrals); ?></h4>
                                <small class="text-success"><?php echo e($activeReferrals); ?> Active Refer Members</small>
                            </div>
                            <div class="avatar bg-soft-success rounded-circle">
                                <i class="ti ti-users  text-success"></i>
                            </div>
                        </div>
                        <?php if($user->is_refer_member): ?>
                            <a href="<?php echo e(route('member.my.referrals')); ?>" class="btn btn-sm btn-outline-success mt-3 w-100">View
                                Referrals</a>
                        <?php else: ?>
                            <a href="<?php echo e(route('member.membership')); ?>"
                                class="btn btn-sm btn-outline-secondary mt-3 w-100">Unlock Referrals</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted mb-1">Referral Earnings</p>
                                <h4 class="mb-0"><?php echo e(number_format($referralEarnings, 4)); ?> <small
                                        class=" text-muted">SVRS</small></h4>
                            </div>
                            <div class="avatar bg-soft-info rounded-circle">
                                <i class="ti ti-share  text-info"></i>
                            </div>
                        </div>
                        <?php if($user->is_refer_member): ?>
                            <a href="<?php echo e(route('member.reports.income')); ?>" class="btn btn-sm btn-outline-info mt-3 w-100">Income
                                Report</a>
                        <?php else: ?>
                            <div class="mt-3 text-center"><small class="text-muted">Pay &#8377;519 to unlock</small></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">

            
            <?php if($user->is_refer_member): ?>
                <div class="col-md-4">
                    <div class="card border-warning">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="ti ti-coin me-2"></i>Gold Coin Wallet</h5>
                        </div>
                        <div class="card-body text-center">
                            <h2 style="color:#f0a500;"><?php echo e(number_format($goldWallet->balance ?? 0)); ?></h2>
                            <p class="text-muted mb-1">G-Coins</p>
                            <p class="text-success fw-bold">approx
                                &#8377;<?php echo e(number_format(($goldWallet->balance ?? 0) / 10, 2)); ?> INR</p>
                            <div class="d-flex gap-2">
                                <a href="<?php echo e(route('member.my.rewards')); ?>" class="btn btn-warning btn-sm flex-fill">Claim
                                    Rewards</a>
                                <a href="<?php echo e(route('member.gold.wallet')); ?>"
                                    class="btn btn-outline-warning btn-sm flex-fill">Wallet</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            
            <div class="col-md-<?php echo e($user->is_refer_member ? '4' : '6'); ?>">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="ti ti-id-badge me-2"></i>Membership Status</h5>
                    </div>
                    <div class="card-body">
                        <?php if($user->is_refer_member): ?>
                            <div class="text-center py-2">
                                <i class="ti ti-circle-check fs-1 text-success"></i>
                                <p class="mt-2 mb-1 fw-bold text-success">Refer and Earn Active</p>
                                <p class="text-muted small">You are eligible to refer members and earn coin rewards</p>
                                <a href="<?php echo e(route('member.membership')); ?>" class="btn btn-sm btn-success">View Details</a>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-2">
                                <i class="ti ti-lock fs-1 text-secondary"></i>
                                <p class="mt-2 mb-1 fw-bold">Refer and Earn Locked</p>
                                <p class="text-muted small">Pay &#8377;519 one-time fee to unlock referral features and earn
                                    SVRS coins</p>
                                <a href="<?php echo e(route('member.membership')); ?>" class="btn btn-sm btn-primary">Unlock Now —
                                    &#8377;519</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="col-md-<?php echo e($user->is_refer_member ? '4' : '6'); ?>">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="ti ti-grid-dots me-2"></i>Quick Links</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="<?php echo e(route('member.coin')); ?>"
                                class="list-group-item list-group-item-action d-flex align-items-center py-2">
                                <i class="fe fe-dollar-sign me-2 text-primary"></i> Coin Chart and Trade
                            </a>
                            <a href="<?php echo e(route('member.my.wallet')); ?>"
                                class="list-group-item list-group-item-action d-flex align-items-center py-2">
                                <i class="fe fe-credit-card me-2 text-success"></i> My Wallet
                            </a>
                            <a href="<?php echo e(route('member.add.member')); ?>"
                                class="list-group-item list-group-item-action d-flex align-items-center py-2">
                                <i class="ti ti-user-plus me-2 text-info"></i> Add New Member
                            </a>
                            <a href="<?php echo e(route('member.customer.support')); ?>"
                                class="list-group-item list-group-item-action d-flex align-items-center py-2">
                                <i class="fe fe-headphones me-2 text-warning"></i> Customer Support
                            </a>
                            <a href="<?php echo e(route('member.profile')); ?>"
                                class="list-group-item list-group-item-action d-flex align-items-center py-2">
                                <i class="fe fe-user me-2 text-secondary"></i> My Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        
        <?php if($user->is_refer_member): ?>
            <div class="card mt-2">
                <div class="card-header">
                    <h5 class="mb-0"><i class="ti ti-trophy me-2"></i>Gold Coin Milestone Progress</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <?php
                            $milestones = [
                                ['name' => 'First Reward', 'need' => 10, 'coins' => 10000],
                                ['name' => 'Option A', 'need' => 15, 'coins' => 5000],
                                ['name' => 'Option B', 'need' => 20, 'coins' => 10000],
                            ];
                        ?>
                        <?php $__currentLoopData = $milestones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-4">
                                <p class="mb-1 fw-bold"><?php echo e($m['name']); ?></p>
                                <small class="text-muted"><?php echo e(number_format($m['coins'])); ?> G-Coins on <?php echo e($m['need']); ?>

                                    referrals</small>
                                <div class="progress mt-2" style="height:12px;">
                                    <?php $pct = min(100, ($activeReferrals / $m['need']) * 100); ?>
                                    <div class="progress-bar bg-<?php echo e($pct >= 100 ? 'success' : 'warning'); ?>"
                                        style="width:<?php echo e($pct); ?>%"></div>
                                </div>
                                <small class="text-muted"><?php echo e($activeReferrals); ?> / <?php echo e($m['need']); ?></small>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="text-center mt-3">
                        <a href="<?php echo e(route('member.my.rewards')); ?>" class="btn btn-warning">View All Milestones and
                            Claim</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.main-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Qubeta\svrs\resources\views/member/dashboard.blade.php ENDPATH**/ ?>