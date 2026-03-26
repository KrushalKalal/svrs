
<?php $__env->startSection('title', config('app.name') . ' || Financial Report'); ?>

<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Financial Report</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a></li>
                        <li class="breadcrumb-item active">Financial Report</li>
                    </ol>
                </nav>
            </div>
            <form class="d-flex gap-2 align-items-center" method="GET" action="<?php echo e(route('admin.reports.financial')); ?>">
                <input type="date" name="from" class="form-control form-control-sm" value="<?php echo e($from); ?>">
                <span>to</span>
                <input type="date" name="to" class="form-control form-control-sm" value="<?php echo e($to); ?>">
                <button class="btn btn-primary btn-sm">Filter</button>
            </form>
        </div>

        
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-success text-white text-center">
                    <div class="card-body">
                        <i class="ti ti-arrow-down-circle fs-2"></i>
                        <h6 class="mt-1">Total Deposits</h6>
                        <h3>&#8377;<?php echo e(number_format($totalDeposits, 2)); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white text-center">
                    <div class="card-body">
                        <i class="ti ti-arrow-up-circle fs-2"></i>
                        <h6 class="mt-1">Total Withdrawals</h6>
                        <h3>&#8377;<?php echo e(number_format($totalWithdrawals, 2)); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-primary text-white text-center">
                    <div class="card-body">
                        <i class="ti ti-id-badge fs-2"></i>
                        <h6 class="mt-1">Membership Fees</h6>
                        <h3>&#8377;<?php echo e(number_format($totalMembershipFees, 2)); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark text-center">
                    <div class="card-body">
                        <i class="ti ti-coin fs-2"></i>
                        <h6 class="mt-1">Referral Coins Issued</h6>
                        <h3><?php echo e(number_format($totalReferralRewardCoins, 4)); ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-primary text-center">
                    <div class="card-body">
                        <h2 class="text-primary"><?php echo e($totalMembers); ?></h2>
                        <p class="text-muted mb-0">Total Members</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-success text-center">
                    <div class="card-body">
                        <h2 class="text-success"><?php echo e($activeMembers); ?></h2>
                        <p class="text-muted mb-0">Active Members</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-info text-center">
                    <div class="card-body">
                        <h2 class="text-info"><?php echo e($referMembers); ?></h2>
                        <p class="text-muted mb-0">Refer Members</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-warning text-center">
                    <div class="card-body">
                        <h2 class="text-warning"><?php echo e($pendingActivations); ?></h2>
                        <p class="text-muted mb-0">Pending Activations</p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0"><i class="ti ti-users me-2"></i>Member Reports</h5>
                <small class="text-muted">Click icons to view Wallet Ledger, Income Report or Referral Tree</small>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="tblMembers">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Member</th>
                                <th>Code</th>
                                <th>Sponsor</th>
                                <th>Status</th>
                                <th>Refer</th>
                                <th>Joined</th>
                                <th class="text-center">Reports</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="<?php echo e($m->profile_image ? url($m->profile_image) : asset('admin/img/avatar.png')); ?>"
                                                class="rounded-circle" width="32" height="32" style="object-fit:cover;">
                                            <div>
                                                <div class="fw-semibold"><?php echo e($m->full_name); ?></div>
                                                <small class="text-muted"><?php echo e($m->email); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-primary"><?php echo e($m->member_code); ?></span></td>
                                    <td><?php echo e($m->sponsor_id ?? '-'); ?></td>
                                    <td>
                                        <?php if($m->status == 1): ?><span class="badge bg-success">Active</span>
                                        <?php elseif($m->status == 0): ?><span class="badge bg-danger">Inactive</span>
                                        <?php else: ?><span class="badge bg-warning text-dark">Pending</span><?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($m->is_refer_member): ?><span class="badge bg-success">Yes</span>
                                        <?php else: ?><span class="badge bg-secondary">No</span><?php endif; ?>
                                    </td>
                                    <td><?php echo e($m->created_at->format('d M Y')); ?></td>
                                    <td class="text-center">
                                        <a href="<?php echo e(route('admin.reports.wallet.ledger', $m->id)); ?>"
                                            class="btn btn-sm btn-outline-primary" title="Wallet Ledger">
                                            <i class="ti ti-wallet"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.reports.income', $m->id)); ?>"
                                            class="btn btn-sm btn-outline-success" title="Income Report">
                                            <i class="ti ti-chart-bar"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.reports.referral.tree', $m->id)); ?>"
                                            class="btn btn-sm btn-outline-info" title="Referral Tree">
                                            <i class="ti ti-share"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#tblMembers').DataTable({ order: [[6, 'desc']], pageLength: 25 });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.main-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Qubeta\svrs\resources\views/admin/reports/financial.blade.php ENDPATH**/ ?>