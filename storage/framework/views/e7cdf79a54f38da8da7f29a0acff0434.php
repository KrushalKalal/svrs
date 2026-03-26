
<?php $__env->startSection('title', config('app.name') . ' || My Referrals'); ?>

<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">My Referrals</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a></li>
                        <li class="breadcrumb-item active">My Referrals</li>
                    </ol>
                </nav>
            </div>
        </div>

        
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h6>Level 1 Earnings</h6>
                        <h3><?php echo e(number_format($earnings[1], 4)); ?> <small style="font-size:14px;">SVRS</small></h3>
                        <small>0.5% per referral buy</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h6>Level 2 Earnings</h6>
                        <h3><?php echo e(number_format($earnings[2], 4)); ?> <small style="font-size:14px;">SVRS</small></h3>
                        <small>0.05% per referral buy</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h6>Level 3 Earnings</h6>
                        <h3><?php echo e(number_format($earnings[3], 4)); ?> <small style="font-size:14px;">SVRS</small></h3>
                        <small>0.01% per referral buy</small>
                    </div>
                </div>
            </div>
        </div>

        
        <ul class="nav nav-tabs mb-3" id="referralTabs">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#treeView">Tree View</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#level1Tab">Level 1 (<?php echo e($level1->count()); ?>)</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#level2Tab">Level 2 (<?php echo e($level2->count()); ?>)</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#level3Tab">Level 3 (<?php echo e($level3->count()); ?>)</a>
            </li>
        </ul>

        <div class="tab-content">

            
            <div class="tab-pane fade show active" id="treeView">
                <div class="card">
                    <div class="card-body" style="overflow-x:auto;">
                        <ul class="tree">
                            <li>
                                <span class="tree-node bg-primary text-white">
                                    <i class="ti ti-user me-1"></i><?php echo e(auth()->user()->full_name); ?><br>
                                    <small><?php echo e(auth()->user()->member_code); ?></small>
                                </span>
                                <?php if($level1->count()): ?>
                                    <ul>
                                        <?php $__currentLoopData = $level1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li>
                                                <span
                                                    class="tree-node <?php echo e($l1->is_refer_member ? 'bg-success' : 'bg-secondary'); ?> text-white">
                                                    <i class="ti ti-user me-1"></i><?php echo e($l1->full_name); ?><br>
                                                    <small><?php echo e($l1->member_code); ?></small>
                                                </span>
                                                <?php $l1Children = $level2->where('sponsor_id', $l1->member_code); ?>
                                                <?php if($l1Children->count()): ?>
                                                    <ul>
                                                        <?php $__currentLoopData = $l1Children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <li>
                                                                <span
                                                                    class="tree-node <?php echo e($l2->is_refer_member ? 'bg-success' : 'bg-secondary'); ?> text-white">
                                                                    <i class="ti ti-user me-1"></i><?php echo e($l2->full_name); ?><br>
                                                                    <small><?php echo e($l2->member_code); ?></small>
                                                                </span>
                                                                <?php $l2Children = $level3->where('sponsor_id', $l2->member_code); ?>
                                                                <?php if($l2Children->count()): ?>
                                                                    <ul>
                                                                        <?php $__currentLoopData = $l2Children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l3): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <li>
                                                                                <span
                                                                                    class="tree-node <?php echo e($l3->is_refer_member ? 'bg-success' : 'bg-secondary'); ?> text-white">
                                                                                    <i class="ti ti-user me-1"></i><?php echo e($l3->full_name); ?><br>
                                                                                    <small><?php echo e($l3->member_code); ?></small>
                                                                                </span>
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
                            <span class="badge bg-primary me-2">You</span>
                            <span class="badge bg-success me-2">Refer Member</span>
                            <span class="badge bg-secondary">Normal Member</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="level1Tab">
                <div class="card">
                    <div class="card-body">
                        <?php echo $__env->make('member.membership.partials.referral_table', ['members' => $level1, 'level' => 1], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="level2Tab">
                <div class="card">
                    <div class="card-body">
                        <?php echo $__env->make('member.membership.partials.referral_table', ['members' => $level2, 'level' => 2], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="level3Tab">
                <div class="card">
                    <div class="card-body">
                        <?php echo $__env->make('member.membership.partials.referral_table', ['members' => $level3, 'level' => 3], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <style>
        .tree {
            list-style: none;
            padding: 0;
        }

        .tree ul {
            list-style: none;
            padding-left: 30px;
            margin-top: 8px;
            position: relative;
        }

        .tree ul::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            border-left: 2px dashed #ccc;
        }

        .tree li {
            position: relative;
            padding: 5px 0;
        }

        .tree li::before {
            content: '';
            position: absolute;
            left: -30px;
            top: 20px;
            width: 30px;
            border-top: 2px dashed #ccc;
        }

        .tree-node {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            text-align: center;
            min-width: 120px;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.main-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Qubeta\svrs\resources\views/member/membership/referrals.blade.php ENDPATH**/ ?>