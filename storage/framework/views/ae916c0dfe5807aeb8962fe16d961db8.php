
<?php $__env->startSection('title', config('app.name') . ' || Reward Claims'); ?>

<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Reward Claims</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a></li>
                        <li class="breadcrumb-item active">Reward Claims</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">G-Coin Claim Requests</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="claimsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Member</th>
                                <th>Member Code</th>
                                <th>Reward Tier</th>
                                <th>G-Coins</th>
                                <th>INR Value</th>
                                <th>Referrals at Claim</th>
                                <th>Status</th>
                                <th>Claimed On</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $claims; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $claim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td><?php echo e($claim->user->full_name ?? '-'); ?></td>
                                    <td><span class="badge bg-primary"><?php echo e($claim->user->member_code ?? '-'); ?></span></td>
                                    <td><span class="badge bg-info"><?php echo e($claim->tier->name ?? '-'); ?></span></td>
                                    <td><strong><?php echo e(number_format($claim->g_coins_claimed)); ?></strong></td>
                                    <td>&#8377;<?php echo e(number_format($claim->g_coins_claimed / 10, 2)); ?></td>
                                    <td><?php echo e($claim->referral_count_at_claim); ?></td>
                                    <td>
                                        <?php if($claim->status == 1): ?>
                                            <span class="badge bg-success">Approved</span>
                                        <?php elseif($claim->status == 0): ?>
                                            <span class="badge bg-danger">Rejected</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($claim->created_at->format('d M Y')); ?></td>
                                    <td>
                                        <?php if($claim->status == 2): ?>
                                            <button class="btn btn-sm btn-success approve-claim" data-id="<?php echo e($claim->id); ?>"
                                                data-status="1">
                                                <i class="ti ti-check"></i> Approve
                                            </button>
                                            <button class="btn btn-sm btn-danger reject-claim" data-id="<?php echo e($claim->id); ?>"
                                                data-status="0">
                                                <i class="ti ti-x"></i> Reject
                                            </button>
                                        <?php else: ?>
                                            <span class="text-muted">Processed</span>
                                        <?php endif; ?>
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
            $('#claimsTable').DataTable({ order: [[8, 'desc']] });

            $(document).on('click', '.approve-claim, .reject-claim', function () {
                var id = $(this).data('id');
                var status = $(this).data('status');
                var label = status == 1 ? 'Approve' : 'Reject';

                Swal.fire({
                    title: label + ' this reward claim?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, ' + label,
                    confirmButtonColor: status == 1 ? '#28a745' : '#dc3545',
                }).then(function (result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "<?php echo e(route('admin.reward.claim.status')); ?>",
                            type: 'POST',
                            data: { id: id, status: status, _token: "<?php echo e(csrf_token()); ?>" },
                            success: function (res) {
                                if (res.success) {
                                    toastr.success(res.message);
                                    setTimeout(function () { location.reload(); }, 1000);
                                } else {
                                    toastr.error(res.message);
                                }
                            },
                            error: function () { toastr.error('Something went wrong'); }
                        });
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.main-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Qubeta\svrs\resources\views/admin/rewards/claims.blade.php ENDPATH**/ ?>