
<?php $__env->startSection('title', config('app.name') . ' || Income Report'); ?>

<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Income Report</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a></li>
                        <li class="breadcrumb-item">Reports</li>
                        <li class="breadcrumb-item active">Income Report</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white text-center">
                    <div class="card-body">
                        <h6>Level 1 Earnings</h6>
                        <h3><?php echo e(number_format($totalLevel1, 4)); ?></h3>
                        <small>SVRS Coins (0.5%)</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white text-center">
                    <div class="card-body">
                        <h6>Level 2 Earnings</h6>
                        <h3><?php echo e(number_format($totalLevel2, 4)); ?></h3>
                        <small>SVRS Coins (0.05%)</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white text-center">
                    <div class="card-body">
                        <h6>Level 3 Earnings</h6>
                        <h3><?php echo e(number_format($totalLevel3, 4)); ?></h3>
                        <small>SVRS Coins (0.01%)</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark text-center">
                    <div class="card-body">
                        <h6>Total Coin Income</h6>
                        <h3><?php echo e(number_format($totalCoins, 4)); ?></h3>
                        <small>SVRS Coins (All Levels)</small>
                    </div>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs mb-3">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#l1Tab">Level 1
                    (<?php echo e($level1Rewards->count()); ?>)</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#l2Tab">Level 2
                    (<?php echo e($level2Rewards->count()); ?>)</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#l3Tab">Level 3
                    (<?php echo e($level3Rewards->count()); ?>)</a></li>
        </ul>

        <div class="tab-content">

            
            <div class="tab-pane fade show active" id="l1Tab">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tblL1">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>From Member</th>
                                        <th>Member Code</th>
                                        <th>Base Qty (SVRS)</th>
                                        <th>Rate</th>
                                        <th>Reward (SVRS)</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $level1Rewards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e($rw->fromUser->full_name ?? '-'); ?></td>
                                            <td><span class="badge bg-primary"><?php echo e($rw->fromUser->member_code ?? '-'); ?></span>
                                            </td>
                                            <td><?php echo e(number_format($rw->base_quantity, 4)); ?></td>
                                            <td><?php echo e($rw->percentage); ?>%</td>
                                            <td class="text-success fw-bold">+<?php echo e(number_format($rw->reward_quantity, 4)); ?></td>
                                            <td><?php echo e($rw->created_at->format('d M Y h:i A')); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <?php if($level1Rewards->isNotEmpty()): ?>
                                <p class="text-end text-success fw-bold mt-2">Level 1 Total:
                                    +<?php echo e(number_format($totalLevel1, 4)); ?> SVRS</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="tab-pane fade" id="l2Tab">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tblL2">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>From Member</th>
                                        <th>Member Code</th>
                                        <th>Base Qty (SVRS)</th>
                                        <th>Rate</th>
                                        <th>Reward (SVRS)</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $level2Rewards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e($rw->fromUser->full_name ?? '-'); ?></td>
                                            <td><span class="badge bg-primary"><?php echo e($rw->fromUser->member_code ?? '-'); ?></span>
                                            </td>
                                            <td><?php echo e(number_format($rw->base_quantity, 4)); ?></td>
                                            <td><?php echo e($rw->percentage); ?>%</td>
                                            <td class="text-success fw-bold">+<?php echo e(number_format($rw->reward_quantity, 4)); ?></td>
                                            <td><?php echo e($rw->created_at->format('d M Y h:i A')); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <?php if($level2Rewards->isNotEmpty()): ?>
                                <p class="text-end text-success fw-bold mt-2">Level 2 Total:
                                    +<?php echo e(number_format($totalLevel2, 4)); ?> SVRS</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="tab-pane fade" id="l3Tab">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tblL3">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>From Member</th>
                                        <th>Member Code</th>
                                        <th>Base Qty (SVRS)</th>
                                        <th>Rate</th>
                                        <th>Reward (SVRS)</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $level3Rewards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e($rw->fromUser->full_name ?? '-'); ?></td>
                                            <td><span class="badge bg-primary"><?php echo e($rw->fromUser->member_code ?? '-'); ?></span>
                                            </td>
                                            <td><?php echo e(number_format($rw->base_quantity, 4)); ?></td>
                                            <td><?php echo e($rw->percentage); ?>%</td>
                                            <td class="text-success fw-bold">+<?php echo e(number_format($rw->reward_quantity, 4)); ?></td>
                                            <td><?php echo e($rw->created_at->format('d M Y h:i A')); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                            <?php if($level3Rewards->isNotEmpty()): ?>
                                <p class="text-end text-success fw-bold mt-2">Level 3 Total:
                                    +<?php echo e(number_format($totalLevel3, 4)); ?> SVRS</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Earning Rate Reference</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Level</th>
                            <th>Rate</th>
                            <th>Example (100 SVRS buy)</th>
                            <th>Your Earning</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Level 1 (Direct)</td>
                            <td><span class="badge bg-primary">0.5%</span></td>
                            <td>Member buys 100 SVRS</td>
                            <td class="text-success fw-bold">+0.5 SVRS</td>
                        </tr>
                        <tr>
                            <td>Level 2 (Indirect)</td>
                            <td><span class="badge bg-success">0.05%</span></td>
                            <td>Member buys 100 SVRS</td>
                            <td class="text-success fw-bold">+0.05 SVRS</td>
                        </tr>
                        <tr>
                            <td>Level 3 (Deep)</td>
                            <td><span class="badge bg-info">0.01%</span></td>
                            <td>Member buys 100 SVRS</td>
                            <td class="text-success fw-bold">+0.01 SVRS</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Init ALL tabs lazily on shown — same fix as wallet ledger
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                var target = $(e.target).attr('href');
                if (target === '#l1Tab' && !$.fn.DataTable.isDataTable('#tblL1')) {
                    $('#tblL1').DataTable({ order: [[6, 'desc']], pageLength: 25 });
                }
                if (target === '#l2Tab' && !$.fn.DataTable.isDataTable('#tblL2')) {
                    $('#tblL2').DataTable({ order: [[6, 'desc']], pageLength: 25 });
                }
                if (target === '#l3Tab' && !$.fn.DataTable.isDataTable('#tblL3')) {
                    $('#tblL3').DataTable({ order: [[6, 'desc']], pageLength: 25 });
                }
            });

            // Trigger active tab manually so tblL1 inits properly
            $('a[data-bs-toggle="tab"].active').trigger('shown.bs.tab');
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.main-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Qubeta\svrs\resources\views/member/reports/income_report.blade.php ENDPATH**/ ?>