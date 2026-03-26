<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Member Code</th>
                <th>Status</th>
                <th>Refer Member</th>
                <th>Joined</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($loop->iteration); ?></td>
                    <td><?php echo e($member->full_name); ?></td>
                    <td><span class="badge bg-primary"><?php echo e($member->member_code); ?></span></td>
                    <td>
                        <?php if($member->status == 1): ?>
                            <span class="badge bg-success">Active</span>
                        <?php elseif($member->status == 0): ?>
                            <span class="badge bg-danger">Inactive</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">Pending</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($member->is_refer_member): ?>
                            <span class="badge bg-success"><i class="ti ti-check"></i> Yes</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">No</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($member->created_at->format('d M Y')); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted">No Level <?php echo e($level); ?> referrals yet.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div><?php /**PATH D:\Qubeta\svrs\resources\views/member/membership/partials/referral_table.blade.php ENDPATH**/ ?>