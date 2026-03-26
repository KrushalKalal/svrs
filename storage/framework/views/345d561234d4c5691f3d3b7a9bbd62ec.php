
<?php $__env->startSection('title', config('app.name') . ' || Trade History'); ?>
<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="container-fluid">
            <div class="card mt-4">
                <div class="card-header">
                    <h4>Trade History</h4>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped" id="HistoryTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Coin</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $trades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $trade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>

                                    <td>
                                        <?php echo e($trade->created_at->format('d M Y h:i A')); ?>

                                    </td>

                                    <td>
                                        <?php echo e($trade->coin->name ?? 'N/A'); ?>

                                    </td>

                                    <td>
                                        <?php if($trade->type == 'buy'): ?>
                                            <span class="badge bg-success">BUY</span>
                                        <?php else: ?>
                                            <span class="badge bg-info">REWARD</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>₹ <?php echo e(number_format($trade->price, 2)); ?></td>
                                    <td><?php echo e(number_format($trade->quantity, 2)); ?></td>
                                    <td>₹ <?php echo e(number_format($trade->price * $trade->quantity, 2)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center">No Trade History Found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            let table = $('#HistoryTable').DataTable();
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.main-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Qubeta\svrs\resources\views/admin/coin_chart/history.blade.php ENDPATH**/ ?>