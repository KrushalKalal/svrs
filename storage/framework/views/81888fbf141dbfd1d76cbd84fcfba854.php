
<?php $__env->startSection('title', config('app.name') . ' || Member Bank Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Member Bank Details</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('admin.dashboard')); ?>">
                                <i class="ti ti-smart-home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">Members</li>
                        <li class="breadcrumb-item active">Bank Details</li>
                    </ol>
                </nav>
            </div>

            <div>
                <a href="<?php echo e(route('admin.member.list')); ?>" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <!-- Member Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h4 class="card-title">Member Information</h4>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Member Code</label>
                        <p><?php echo e($bankDetail->user->member_code); ?></p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Full Name</label>
                        <p><?php echo e($bankDetail->user->first_name); ?> <?php echo e($bankDetail->user->last_name); ?></p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Email</label>
                        <p><?php echo e($bankDetail->user->email); ?></p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Mobile</label>
                        <p><?php echo e($bankDetail->user->mobile); ?></p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Deposit Amount</label>
                        <p>₹ <?php echo e(number_format($bankDetail->user->amount, 2)); ?></p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Coin Price</label>
                        <p>₹ <?php echo e(number_format($bankDetail->user->coin_price, 2)); ?></p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Status</label><br>
                        <?php if($bankDetail->user->status == 1): ?>
                            <span class="badge bg-success">Active</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Inactive</span>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Joined Date</label>
                        <p><?php echo e($bankDetail->user->created_at->format('d M Y h:i A')); ?></p>
                    </div>

                </div>
            </div>
        </div>

        <!-- Bank Details -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Bank Information</h4>
            </div>

            <div class="card-body">
                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Bank Name</label>
                        <p><?php echo e($bankDetail->bank_name); ?></p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Account Holder</label>
                        <p><?php echo e($bankDetail->account_holder_name); ?></p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Account Number</label>
                        <p><?php echo e($bankDetail->account_number); ?></p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">IFSC Code</label>
                        <p><?php echo e($bankDetail->ifsc_code); ?></p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Branch</label>
                        <p><?php echo e($bankDetail->branch_name); ?></p>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">UPI</label>
                        <p><?php echo e($bankDetail->upi ?? ''); ?></p>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Added On</label>
                        <p><?php echo e($bankDetail->created_at->format('d M Y')); ?></p>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.main-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\SVRS\resources\views/admin/members/bank_details.blade.php ENDPATH**/ ?>