<?php $__env->startSection('title', config('app.name') . ' || Gold Coin Wallet'); ?>

<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Gold Coin Wallet</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a></li>
                        <li class="breadcrumb-item active">Gold Coin Wallet</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-warning text-center">
                    <div class="card-body py-4">
                        <i class="ti ti-coin" style="font-size:3rem;color:#f0a500;"></i>
                        <h2 class="mt-2 mb-0" style="color:#f0a500;"><?php echo e(number_format($wallet->balance ?? 0)); ?></h2>
                        <p class="text-muted mb-0">Available G-Coins</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body py-4">
                        <i class="ti ti-currency-rupee" style="font-size:3rem;" class="text-success"></i>
                        <h2 class="mt-2 mb-0 text-success">&#8377;<?php echo e(number_format(($wallet->balance ?? 0) / 10, 2)); ?></h2>
                        <p class="text-muted mb-0">INR Equivalent</p>
                        <small class="text-muted">10 G-Coins = &#8377;1</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body py-4">
                        <i class="ti ti-trophy" style="font-size:3rem;" class="text-primary"></i>
                        <h2 class="mt-2 mb-0 text-primary"><?php echo e(number_format($wallet->total_earned ?? 0)); ?></h2>
                        <p class="text-muted mb-0">Total Earned (All Time)</p>
                        <small class="text-muted">approx &#8377;<?php echo e(number_format(($wallet->total_earned ?? 0) / 10, 2)); ?>

                            INR</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-info d-flex align-items-start mb-4">
            <i class="ti ti-info-circle fs-5 me-2 mt-1"></i>
            <div>
                <strong>G-Coin Wallet Info:</strong> G-Coins are display-only rewards.
                10 G-Coins = &#8377;1 INR equivalent value.
                Coins are credited when admin approves your milestone claims.
                Lifetime maximum: 25,000 G-Coins (&#8377;2,500).
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Transaction History</h4>
                <a href="<?php echo e(route('member.my.rewards')); ?>" class="btn btn-sm btn-warning">
                    <i class="ti ti-gift me-1"></i>Claim More Rewards
                </a>
            </div>
            <div class="card-body">
                <?php if($transactions->isEmpty()): ?>
                    <div class="text-center py-5">
                        <i class="ti ti-coin-off fs-1 text-muted"></i>
                        <p class="text-muted mt-2">No transactions yet. Claim your first milestone reward!</p>
                        <a href="<?php echo e(route('member.my.rewards')); ?>" class="btn btn-warning">View Milestones</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="goldTxnTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>G-Coins</th>
                                    <th>INR Value</th>
                                    <th>Remark</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $txn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($txn->type == 'credit' ? 'success' : 'danger'); ?>">
                                                <?php echo e(ucfirst($txn->type)); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <span class="<?php echo e($txn->type == 'credit' ? 'text-success' : 'text-danger'); ?> fw-bold">
                                                <?php echo e($txn->type == 'credit' ? '+' : '-'); ?><?php echo e(number_format($txn->amount)); ?>

                                            </span>
                                        </td>
                                        <td>&#8377;<?php echo e(number_format($txn->amount / 10, 2)); ?></td>
                                        <td><?php echo e($txn->remark ?? '-'); ?></td>
                                        <td><?php echo e($txn->created_at->format('d M Y h:i A')); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            <?php if($transactions->isNotEmpty()): ?>
                $('#goldTxnTable').DataTable({ order: [[5, 'desc']] });
            <?php endif; ?>
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.main-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Qubeta\svrs\resources\views/member/rewards/gold_wallet.blade.php ENDPATH**/ ?>