
<?php $__env->startSection('title', config('app.name') . ' || Deposit Approval'); ?>
<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="container-fluid">
            <div class="card mt-4">
                <div class="card-header">
                    <h4>Deposit Approval</h4>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered" id="DepositTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Member</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Remark</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $txn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>
                                    <td>
                                        <?php echo e($txn->user->first_name ?? ''); ?> <?php echo e($txn->user->last_name ?? ''); ?>

                                        <br>
                                        <?php echo e($txn->user->mobile ?? ''); ?>

                                        <br>
                                        <?php echo e($txn->user->member_code ?? ''); ?>

                                    </td>
                                    <td><?php echo e($txn->created_at->format('d M Y h:i A')); ?></td>
                                    <td>
                                        <?php if($txn->type == 'credit'): ?>
                                            <span class="badge bg-success">Credit</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Debit</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>₹<?php echo e(number_format($txn->amount, 2)); ?></td>
                                    <td>
                                        <?php if($txn->status === 1): ?>
                                            <span class="badge bg-success">Approved</span>
                                        <?php elseif($txn->status === 0): ?>
                                            <span class="badge bg-danger">Rejected</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($txn->remark); ?></td>
                                    <td>
                                        <?php if($txn->invoice): ?>
                                            <a href="<?php echo e(asset($txn->invoice)); ?>" target="_blank"
                                                class="btn btn-sm btn-info">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if($txn->status === 2): ?>
                                            <button class="btn btn-sm btn-success change-status"
                                                data-id="<?php echo e($txn->id); ?>" data-status="1">
                                                <i class="fa fa-check"></i>
                                            </button>

                                            <button class="btn btn-sm btn-danger change-status"
                                                data-id="<?php echo e($txn->id); ?>" data-status="0">
                                                <i class="fa fa-ban"></i>
                                            </button>
                                        <?php else: ?>
                                            <span class="text-muted">No Action</span>
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
        $(document).on('click', '.change-status', function() {

            let button = $(this);
            let id = button.data('id');
            let status = button.data('status');

            let actionText = status == 1 ? 'Approve' : 'Reject';

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to " + actionText + " this transaction!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: status == 1 ? '#28a745' : '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, ' + actionText + ' it!'
            }).then((result) => {

                if (result.isConfirmed) {

                    button.prop('disabled', true);

                    $.ajax({
                        url: "<?php echo e(route('admin.deposit.change.status')); ?>",
                        type: "POST",
                        data: {
                            _token: "<?php echo e(csrf_token()); ?>",
                            id: id,
                            status: status
                        },
                        success: function(res) {

                            if (res.success) {
                                Swal.fire('Success!', res.message, 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Error!', res.message, 'error');
                                button.prop('disabled', false);
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'Something went wrong!', 'error');
                            button.prop('disabled', false);
                        }
                    });
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            let table = $('#DepositTable').DataTable();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.main-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\SVRS\resources\views/admin/deposit_approval.blade.php ENDPATH**/ ?>