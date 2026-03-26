
<?php $__env->startSection('title', config('app.name') . ' || My Referral Tree'); ?>

<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">My Referral Tree</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a></li>
                        <li class="breadcrumb-item">Reports</li>
                        <li class="breadcrumb-item active">Referral Tree</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-primary text-center">
                    <div class="card-body">
                        <h2 class="text-primary"><?php echo e($level1->count()); ?></h2>
                        <p class="text-muted mb-0">Level 1 (Direct)</p>
                        <small class="text-success"><?php echo e($level1->where('is_refer_member', 1)->where('status', 1)->count()); ?>

                            Refer Members</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-success text-center">
                    <div class="card-body">
                        <h2 class="text-success"><?php echo e($level2->count()); ?></h2>
                        <p class="text-muted mb-0">Level 2</p>
                        <small class="text-success"><?php echo e($level2->where('is_refer_member', 1)->where('status', 1)->count()); ?>

                            Refer Members</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-info text-center">
                    <div class="card-body">
                        <h2 class="text-info"><?php echo e($level3->count()); ?></h2>
                        <p class="text-muted mb-0">Level 3</p>
                        <small class="text-success"><?php echo e($level3->where('is_refer_member', 1)->where('status', 1)->count()); ?>

                            Refer Members</small>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Visual Referral Tree</h5>
            </div>
            <div class="card-body" style="overflow-x:auto;">
                <ul class="tree">
                    <li>
                        <div class="tree-node-box me-node">
                            <i class="ti ti-user-circle me-1"></i>
                            <strong><?php echo e($user->full_name); ?></strong><br>
                            <small><?php echo e($user->member_code); ?></small>
                        </div>
                        <?php if($level1->count()): ?>
                            <ul>
                                <?php $__currentLoopData = $level1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li>
                                        <div class="tree-node-box <?php echo e($l1->is_refer_member ? 'refer-node' : 'normal-node'); ?>">
                                            <i class="ti ti-user me-1"></i><?php echo e($l1->full_name); ?><br>
                                            <small><?php echo e($l1->member_code); ?></small><br>
                                            <?php if($l1->status == 1): ?><span class="badge bg-success" style="font-size:9px;">Active</span>
                                            <?php else: ?><span class="badge bg-warning text-dark"
                                            style="font-size:9px;">Pending</span><?php endif; ?>
                                        </div>
                                        <?php $l1Kids = $level2->where('sponsor_id', $l1->member_code); ?>
                                        <?php if($l1Kids->count()): ?>
                                            <ul>
                                                <?php $__currentLoopData = $l1Kids; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li>
                                                        <div
                                                            class="tree-node-box <?php echo e($l2->is_refer_member ? 'refer-node' : 'normal-node'); ?>">
                                                            <i class="ti ti-user me-1"></i><?php echo e($l2->full_name); ?><br>
                                                            <small><?php echo e($l2->member_code); ?></small><br>
                                                            <?php if($l2->status == 1): ?><span class="badge bg-success"
                                                                style="font-size:9px;">Active</span>
                                                            <?php else: ?><span class="badge bg-warning text-dark"
                                                            style="font-size:9px;">Pending</span><?php endif; ?>
                                                        </div>
                                                        <?php $l2Kids = $level3->where('sponsor_id', $l2->member_code); ?>
                                                        <?php if($l2Kids->count()): ?>
                                                            <ul>
                                                                <?php $__currentLoopData = $l2Kids; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l3): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <li>
                                                                        <div
                                                                            class="tree-node-box <?php echo e($l3->is_refer_member ? 'refer-node' : 'normal-node'); ?>">
                                                                            <i class="ti ti-user me-1"></i><?php echo e($l3->full_name); ?><br>
                                                                            <small><?php echo e($l3->member_code); ?></small><br>
                                                                            <?php if($l3->status == 1): ?><span class="badge bg-success"
                                                                                style="font-size:9px;">Active</span>
                                                                            <?php else: ?><span class="badge bg-warning text-dark"
                                                                            style="font-size:9px;">Pending</span><?php endif; ?>
                                                                        </div>
                                                                    </li>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </ul>
                                                        <?php endif; ?>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                </ul>
                <div class="mt-3">
                    <span class="badge bg-primary me-2">Me (Root)</span>
                    <span class="badge bg-success me-2">Refer Member</span>
                    <span class="badge bg-secondary">Normal Member</span>
                </div>
            </div>
        </div>

        
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#t1">Level 1
                    (<?php echo e($level1->count()); ?>)</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#t2">Level 2 (<?php echo e($level2->count()); ?>)</a>
            </li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#t3">Level 3 (<?php echo e($level3->count()); ?>)</a>
            </li>
        </ul>

        <div class="tab-content">
            <?php $__currentLoopData = ['t1' => [$level1, 1], 't2' => [$level2, 2], 't3' => [$level3, 3]]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tid => [$members, $lvl]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="tab-pane fade <?php echo e($tid == 't1' ? 'show active' : ''); ?>" id="<?php echo e($tid); ?>">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="tbl_<?php echo e($tid); ?>">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Member Code</th>
                                            <th>Sponsor</th>
                                            <th>Status</th>
                                            <th>Refer Member</th>
                                            <th>Joined</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td><?php echo e($loop->iteration); ?></td>
                                                <td><?php echo e($m->full_name); ?></td>
                                                <td><span class="badge bg-primary"><?php echo e($m->member_code); ?></span></td>
                                                <td><?php echo e($m->sponsor_id ?? '-'); ?></td>
                                                <td>
                                                    <?php if($m->status == 1): ?><span class="badge bg-success">Active</span>
                                                    <?php elseif($m->status == 0): ?><span class="badge bg-danger">Inactive</span>
                                                    <?php else: ?><span class="badge bg-warning text-dark">Pending</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($m->is_refer_member): ?><span class="badge bg-success">Yes</span>
                                                    <?php else: ?><span class="badge bg-secondary">No</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo e($m->created_at->format('d M Y')); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <style>
        .tree {
            list-style: none;
            padding: 0
        }

        .tree ul {
            list-style: none;
            padding-left: 40px;
            margin-top: 8px;
            position: relative
        }

        .tree ul::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            border-left: 2px dashed #ccc
        }

        .tree li {
            position: relative;
            padding: 5px 0
        }

        .tree li::before {
            content: '';
            position: absolute;
            left: -40px;
            top: 28px;
            width: 40px;
            border-top: 2px dashed #ccc
        }

        .tree-node-box {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            text-align: center;
            min-width: 130px;
            border: 2px solid
        }

        .me-node {
            border-color: #0d6efd;
            background: #e7f1ff;
            color: #0d6efd
        }

        .refer-node {
            border-color: #198754;
            background: #d1e7dd;
            color: #146c43
        }

        .normal-node {
            border-color: #6c757d;
            background: #f8f9fa;
            color: #495057
        }
    </style>

    <script>
        $(document).ready(function () {
            // Init ALL tabs lazily on shown — same fix as wallet ledger
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                var target = $(e.target).attr('href');
                if (target === '#t1' && !$.fn.DataTable.isDataTable('#tbl_t1')) {
                    $('#tbl_t1').DataTable({ order: [[6, 'desc']], pageLength: 25 });
                }
                if (target === '#t2' && !$.fn.DataTable.isDataTable('#tbl_t2')) {
                    $('#tbl_t2').DataTable({ order: [[6, 'desc']], pageLength: 25 });
                }
                if (target === '#t3' && !$.fn.DataTable.isDataTable('#tbl_t3')) {
                    $('#tbl_t3').DataTable({ order: [[6, 'desc']], pageLength: 25 });
                }
            });

            // Trigger active tab manually so tbl_t1 inits properly
            $('a[data-bs-toggle="tab"].active').trigger('shown.bs.tab');
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.main-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Qubeta\svrs\resources\views/member/reports/referral_tree.blade.php ENDPATH**/ ?>