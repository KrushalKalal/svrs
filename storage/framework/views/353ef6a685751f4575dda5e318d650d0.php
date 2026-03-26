
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
                <span class="badge bg-danger px-3 py-2">
                    <i class="ti ti-shield me-1"></i>Admin
                </span>
            </div>
        </div>

        
        <?php if($pendingDeposits + $pendingWithdrawals + $pendingMembership + $pendingRewardClaims > 0): ?>
            <div class="alert alert-warning d-flex flex-wrap gap-3 align-items-center mb-4">
                <i class="ti ti-bell"></i>
                <strong>Pending Actions:</strong>
                <?php if($pendingDeposits): ?>
                    <a href="<?php echo e(route('admin.deposit.approval')); ?>" class="badge bg-danger text-decoration-none">
                        <i class="ti ti-arrow-down-circle me-1"></i><?php echo e($pendingDeposits); ?> Deposits
                    </a>
                <?php endif; ?>
                <?php if($pendingWithdrawals): ?>
                    <a href="<?php echo e(route('admin.withdrawal.approval')); ?>" class="badge bg-warning text-dark text-decoration-none ">
                        <i class="ti ti-arrow-up-circle me-1"></i><?php echo e($pendingWithdrawals); ?> Withdrawals
                    </a>
                <?php endif; ?>
                <?php if($pendingMembership): ?>
                    <a href="<?php echo e(route('admin.membership.approval')); ?>" class="badge bg-primary text-decoration-none ">
                        <i class="ti ti-id-badge me-1"></i><?php echo e($pendingMembership); ?> Memberships
                    </a>
                <?php endif; ?>
                <?php if($pendingRewardClaims): ?>
                    <a href="<?php echo e(route('admin.reward.claims')); ?>" class="badge bg-success text-decoration-none ">
                        <i class="ti ti-trophy me-1"></i><?php echo e($pendingRewardClaims); ?> Reward Claims
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        
        <h6 class="text-muted text-uppercase mb-3 fw-bold"><i class="ti ti-users me-1"></i>Platform Overview</h6>
        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="avatar bg-soft-primary rounded-circle p-3">
                            <i class="ti ti-users  text-primary"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small">Total Members</p>
                            <h3 class="mb-0 fw-bold"><?php echo e($totalMembers); ?></h3>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <a href="<?php echo e(route('admin.member.list')); ?>" class="btn btn-sm btn-outline-primary w-100">View All</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="avatar bg-soft-success rounded-circle p-3">
                            <i class="ti ti-user-check  text-success"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small">Active Members</p>
                            <h3 class="mb-0 fw-bold text-success"><?php echo e($activeMembers); ?></h3>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <a href="<?php echo e(route('admin.activate.member')); ?>" class="btn btn-sm btn-outline-success w-100">View
                            Active</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="avatar bg-soft-warning rounded-circle p-3">
                            <i class="ti ti-clock  text-warning"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small">Pending Activation</p>
                            <h3 class="mb-0 fw-bold text-warning"><?php echo e($pendingMembers); ?></h3>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <a href="<?php echo e(route('admin.id.activate')); ?>" class="btn btn-sm btn-outline-warning w-100">Activate
                            Now</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="avatar bg-soft-info rounded-circle p-3">
                            <i class="ti ti-share  text-info"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small">Refer Members</p>
                            <h3 class="mb-0 fw-bold text-info"><?php echo e($referMembers); ?></h3>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <a href="<?php echo e(route('admin.referral.reward.list')); ?>" class="btn btn-sm btn-outline-info w-100">View
                            Rewards</a>
                    </div>
                </div>
            </div>
        </div>

        
        <h6 class="text-muted text-uppercase mb-3 fw-bold"><i class="ti ti-currency-rupee me-1"></i>Financial Summary</h6>
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-success shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="ti ti-arrow-down-circle fs-2 text-success"></i>
                        <h3 class="mt-2 text-success">&#8377;<?php echo e(number_format($totalDeposits, 2)); ?></h3>
                        <p class="text-muted mb-0 small">Total Deposits (All Time)</p>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="<?php echo e(route('admin.deposit.approval')); ?>"
                            class="btn btn-sm btn-outline-success w-100">Manage</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-danger shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="ti ti-arrow-up-circle fs-2 text-danger"></i>
                        <h3 class="mt-2 text-danger">&#8377;<?php echo e(number_format($totalWithdrawals, 2)); ?></h3>
                        <p class="text-muted mb-0 small">Total Withdrawals</p>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="<?php echo e(route('admin.withdrawal.approval')); ?>"
                            class="btn btn-sm btn-outline-danger w-100">Manage</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-primary shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="ti ti-id-badge fs-2 text-primary"></i>
                        <h3 class="mt-2 text-primary">&#8377;<?php echo e(number_format($totalMembership, 2)); ?></h3>
                        <p class="text-muted mb-0 small">Membership Fees Collected</p>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="<?php echo e(route('admin.membership.approval')); ?>"
                            class="btn btn-sm btn-outline-primary w-100">Manage</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-warning shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="ti ti-coins fs-2 text-warning"></i>
                        <h3 class="mt-2 text-warning"><?php echo e(number_format($totalReferralCoins, 4)); ?></h3>
                        <p class="text-muted mb-0 small">SVRS Referral Coins Issued</p>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="<?php echo e(route('admin.referral.reward.list')); ?>"
                            class="btn btn-sm btn-outline-warning w-100">View</a>
                    </div>
                </div>
            </div>
        </div>

        
        <h6 class="text-muted text-uppercase mb-3 fw-bold"><i class="ti ti-coin me-1"></i>Coin Summary</h6>
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="ti ti-chart-line fs-2 text-primary"></i>
                        <h3 class="mt-2 text-primary">&#8377;<?php echo e(number_format($currentPrice, 4)); ?></h3>
                        <p class="text-muted mb-0 small">Current SVRS Price</p>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="<?php echo e(route('admin.coin.chart')); ?>" class="btn btn-sm btn-outline-primary w-100">Coin
                            Chart</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="ti ti-coins fs-2 text-warning"></i>
                        <h3 class="mt-2 text-warning"><?php echo e(number_format($totalSVRS, 4)); ?></h3>
                        <p class="text-muted mb-0 small">Total SVRS in Circulation</p>
                        <small class="text-muted">&#8377;<?php echo e(number_format($totalSVRS * $currentPrice, 2)); ?> approx
                            value</small>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="<?php echo e(route('admin.coin.history')); ?>" class="btn btn-sm btn-outline-warning w-100">Coin
                            History</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm h-100" style="border-color:#f0a500;">
                    <div class="card-body text-center">
                        <i class="ti ti-coin fs-2" style="color:#f0a500;"></i>
                        <h3 class="mt-2" style="color:#f0a500;"><?php echo e(number_format($totalGoldCoins)); ?></h3>
                        <p class="text-muted mb-0 small">Total G-Coins Approved</p>
                        <small class="text-muted">&#8377;<?php echo e(number_format($totalGoldCoins / 10, 2)); ?> INR value</small>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="<?php echo e(route('admin.reward.claims')); ?>" class="btn btn-sm btn-outline-secondary w-100">Reward
                            Claims</a>
                    </div>
                </div>
            </div>
        </div>

        
        <h6 class="text-muted text-uppercase mb-3 fw-bold"><i class="ti ti-user-shield me-1"></i>Admin Account (SVRS000)
        </h6>
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="avatar bg-soft-success rounded-circle p-3">
                            <i class="ti ti-wallet  text-success"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small">INR Wallet</p>
                            <h4 class="mb-0 text-success">&#8377;<?php echo e(number_format($adminWallet->balance ?? 0, 2)); ?></h4>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <a href="<?php echo e(route('admin.my.wallet')); ?>" class="btn btn-sm btn-outline-success w-100">My Wallet</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="avatar bg-soft-warning rounded-circle p-3">
                            <i class="ti ti-coins  text-warning"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small">My SVRS Coins</p>
                            <h4 class="mb-0 text-warning"><?php echo e(number_format($adminCoinBal, 4)); ?></h4>
                            <small class="text-muted">&#8377;<?php echo e(number_format($adminCoinBal * $currentPrice, 2)); ?></small>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <a href="<?php echo e(route('admin.coin')); ?>" class="btn btn-sm btn-outline-warning w-100">Trade</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="avatar bg-soft-primary rounded-circle p-3">
                            <i class="ti ti-share  text-primary"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small">My Referral Earnings</p>
                            <h4 class="mb-0 text-primary"><?php echo e(number_format($adminReferEarn, 4)); ?></h4>
                            <small class="text-muted">SVRS Coins</small>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <a href="<?php echo e(route('admin.reports.financial')); ?>"
                            class="btn btn-sm btn-outline-primary w-100">Reports</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card shadow-sm h-100" style="border-color:#f0a500;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle p-3" style="background:#fff8e1;">
                            <i class="ti ti-coin " style="color:#f0a500;"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0 small">My G-Coins</p>
                            <h4 class="mb-0" style="color:#f0a500;"><?php echo e(number_format($adminGoldWal->balance ?? 0)); ?></h4>
                            <small
                                class="text-muted">&#8377;<?php echo e(number_format(($adminGoldWal->balance ?? 0) / 10, 2)); ?></small>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <a href="<?php echo e(route('admin.reward.claims')); ?>" class="btn btn-sm btn-outline-secondary w-100">Gold
                            Rewards</a>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="row">

            
            <div class="col-md-7 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="ti ti-clock me-2 text-warning"></i>Pending Deposits</h5>
                        <a href="<?php echo e(route('admin.deposit.approval')); ?>" class="btn btn-sm btn-outline-warning">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Member</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $recentDeposits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($dep->user->full_name ?? '-'); ?><br><small
                                                    class="text-muted"><?php echo e($dep->user->member_code ?? ''); ?></small></td>
                                            <td class="text-success fw-bold">&#8377;<?php echo e(number_format($dep->amount, 2)); ?></td>
                                            <td><small><?php echo e($dep->created_at->format('d M h:i A')); ?></small></td>
                                            <td><a href="<?php echo e(route('admin.deposit.approval')); ?>"
                                                    class="btn btn-xs btn-warning btn-sm py-0 px-2">Approve</a></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">No pending deposits</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-md-5 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="ti ti-grid-dots me-2"></i>Quick Access</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <a href="<?php echo e(route('admin.add.member')); ?>" class="list-group-item list-group-item-action py-2">
                                <i class="ti ti-user-plus me-2 text-primary"></i>Add New Member
                            </a>
                            <a href="<?php echo e(route('admin.id.activate')); ?>" class="list-group-item list-group-item-action py-2">
                                <i class="ti ti-user-check me-2 text-success"></i>Pending Activations
                                <?php if($pendingMembers): ?><span
                                class="badge bg-warning float-end"><?php echo e($pendingMembers); ?></span><?php endif; ?>
                            </a>
                            <a href="<?php echo e(route('admin.membership.approval')); ?>"
                                class="list-group-item list-group-item-action py-2">
                                <i class="ti ti-id-badge me-2 text-info"></i>Membership Approvals
                                <?php if($pendingMembership): ?><span
                                class="badge bg-primary float-end"><?php echo e($pendingMembership); ?></span><?php endif; ?>
                            </a>
                            <a href="<?php echo e(route('admin.deposit.approval')); ?>"
                                class="list-group-item list-group-item-action py-2">
                                <i class="ti ti-arrow-down-circle me-2 text-success"></i>Deposit Approvals
                                <?php if($pendingDeposits): ?><span
                                class="badge bg-danger float-end"><?php echo e($pendingDeposits); ?></span><?php endif; ?>
                            </a>
                            <a href="<?php echo e(route('admin.withdrawal.approval')); ?>"
                                class="list-group-item list-group-item-action py-2">
                                <i class="ti ti-arrow-up-circle me-2 text-danger"></i>Withdrawal Approvals
                                <?php if($pendingWithdrawals): ?><span
                                class="badge bg-danger float-end"><?php echo e($pendingWithdrawals); ?></span><?php endif; ?>
                            </a>
                            <a href="<?php echo e(route('admin.reward.claims')); ?>"
                                class="list-group-item list-group-item-action py-2">
                                <i class="ti ti-trophy me-2 text-warning"></i>Reward Claims
                                <?php if($pendingRewardClaims): ?><span
                                class="badge bg-success float-end"><?php echo e($pendingRewardClaims); ?></span><?php endif; ?>
                            </a>
                            <a href="<?php echo e(route('admin.reports.financial')); ?>"
                                class="list-group-item list-group-item-action py-2">
                                <i class="ti ti-chart-bar me-2 text-secondary"></i>Financial Reports
                            </a>
                            <a href="<?php echo e(route('admin.coin.list')); ?>" class="list-group-item list-group-item-action py-2">
                                <i class="ti ti-coins me-2 text-warning"></i>Manage Coins
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        
        <div class="card shadow-sm mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="ti ti-users me-2 text-primary"></i>Recently Joined Members</h5>
                <a href="<?php echo e(route('admin.member.list')); ?>" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Status</th>
                                <th>Refer</th>
                                <th>Joined</th>
                                <th>Reports</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $recentMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td><?php echo e($m->full_name); ?><br><small class="text-muted"><?php echo e($m->email); ?></small></td>
                                    <td><span class="badge bg-primary"><?php echo e($m->member_code); ?></span></td>
                                    <td>
                                        <?php if($m->status == 1): ?><span class="badge bg-success">Active</span>
                                        <?php elseif($m->status == 0): ?><span class="badge bg-danger">Inactive</span>
                                        <?php else: ?><span class="badge bg-warning text-dark">Pending</span><?php endif; ?>
                                    </td>
                                    <td><?php if($m->is_refer_member): ?><span class="badge bg-success">Yes</span><?php else: ?><span
                                    class="badge bg-secondary">No</span><?php endif; ?></td>
                                    <td><small><?php echo e($m->created_at->format('d M Y')); ?></small></td>
                                    <td>
                                        <a href="<?php echo e(route('admin.reports.wallet.ledger', $m->id)); ?>"
                                            class="btn btn-sm btn-outline-primary py-0 px-1"><i class="ti ti-wallet"></i></a>
                                        <a href="<?php echo e(route('admin.reports.income', $m->id)); ?>"
                                            class="btn btn-sm btn-outline-success py-0 px-1"><i class="ti ti-chart-bar"></i></a>
                                        <a href="<?php echo e(route('admin.reports.referral.tree', $m->id)); ?>"
                                            class="btn btn-sm btn-outline-info py-0 px-1"><i class="ti ti-share"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.main-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Qubeta\svrs\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>