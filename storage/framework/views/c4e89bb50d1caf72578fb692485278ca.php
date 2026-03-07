
<?php $__env->startSection('title', config('app.name') . ' || Members List'); ?>
<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Members List</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="<?php echo e(route('admin.dashboard')); ?>"><i class="ti ti-smart-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">Members</li>
                        <li class="breadcrumb-item active" aria-current="page">All Members</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">All Members</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="customersTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Joining Date</th>
                                        <th>Name</th>
                                        <th>Member ID</th>
                                        <th>Sponsor ID</th>
                                        <th>Amount</th>
                                        <th>Attachment</th>
                                        <th>Status</th>
                                        <th width="180">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr id="row_<?php echo e($items->id); ?>">
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e($items->created_at->format('d M Y')); ?></td>
                                            <td>
                                                <?php echo e($items->first_name); ?> <?php echo e($items->last_name); ?>

                                                <br>
                                                <?php echo e($items->mobile); ?>

                                            </td>
                                            <td><?php echo e($items->member_code); ?></td>
                                            <td><?php echo e($items->sponsor_id ?? '-'); ?></td>
                                            <td>
                                                ₹<?php echo e(number_format($items->amount ?? 0, 2)); ?>

                                            </td>
                                            <td>
                                                <?php if($items->attachment): ?>
                                                    <a href="<?php echo e(asset($items->attachment)); ?>" target="_blank"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fa fa-eye"></i> View
                                                    </a>
                                                    <a href="<?php echo e(asset($items->attachment)); ?>" download
                                                        class="btn btn-sm btn-secondary">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="text-muted">No File</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($items->status == 1): ?>
                                                    <span class="badge bg-success status-badge">Active</span>
                                                <?php elseif($items->status == 0): ?>
                                                    <span class="badge bg-danger status-badge">Inactive</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark status-badge">Pending</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-success change-status"
                                                    data-id="<?php echo e($items->id); ?>" data-status="1">
                                                    <i class="fa fa-check"></i>
                                                </button>

                                                <button class="btn btn-sm btn-danger change-status"
                                                    data-id="<?php echo e($items->id); ?>" data-status="0">
                                                    <i class="fa fa-ban"></i>
                                                </button>
                                                <?php if($items->bankDetail): ?>                                                    
                                                    <a href="<?php echo e(route('admin.member.bankdetails', $items->bankDetail->id)); ?>" class="btn btn-sm btn-info">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
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
        </div>
    </div>
    <script>
        $(document).on('click', '.change-status', function() {

            let button = $(this);
            let id = button.data('id');
            let status = button.data('status');
            let actionText = status == 1 ? 'Activate' : 'Inactivate';

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to " + actionText + " this member!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, ' + actionText + ' it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    button.prop('disabled', true).text('Updating...');
                    $.ajax({
                        url: "<?php echo e(route('admin.member.update.status')); ?>",
                        type: "POST",
                        data: {
                            _token: "<?php echo e(csrf_token()); ?>",
                            id: id,
                            status: status
                        },
                        success: function(res) {
                            if (res.success) {

                                let badge = $('#row_' + id).find('.status-badge');

                                if (status == 1) {
                                    badge.removeClass().addClass(
                                        'badge bg-success status-badge').text('Active');
                                } else {
                                    badge.removeClass().addClass('badge bg-danger status-badge')
                                        .text('Inactive');
                                }
                                Swal.fire({
                                    title: 'Updated!',
                                    text: res.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    location.reload();
                                });

                            } else {
                                Swal.fire('Error!', res.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'Something went wrong!', 'error');
                        },
                        complete: function() {
                            button.prop('disabled', false)
                                .text(status == 1 ? 'Activate' : 'Inactivate');
                        }
                    });

                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            let table = $('#customersTable').DataTable();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.main-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Qubeta\svrs\resources\views/admin/members/member_list.blade.php ENDPATH**/ ?>