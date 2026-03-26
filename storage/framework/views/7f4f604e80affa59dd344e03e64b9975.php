<?php $__env->startSection('title', config('app.name') . ' || My Rewards'); ?>

<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">My Rewards</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a></li>
                        <li class="breadcrumb-item active">My Rewards</li>
                    </ol>
                </nav>
            </div>
        </div>

        
        <div class="alert alert-info d-flex align-items-center mb-4">
            <i class="ti ti-users fs-4 me-2"></i>
            <div>
                <strong>Your Active Refer Members (Level 1): <?php echo e($eligibility['referral_count']); ?></strong>
                <span class="text-muted ms-2">Only activated members who have paid &#8377;519 count toward
                    milestones.</span>
            </div>
        </div>

        <div class="row">
            <?php $__currentLoopData = $eligibility['milestones']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $milestone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-4 mb-4">
                    <div
                        class="card h-100 <?php echo e(($milestone['claim'] && $milestone['claim']->status == 1) ? 'border-success' : ''); ?>">
                        <div
                            class="card-header d-flex justify-content-between align-items-center
                                    <?php echo e(($milestone['claim'] && $milestone['claim']->status == 1) ? 'bg-success text-white' : ''); ?>">
                            <h5 class="mb-0"><?php echo e($milestone['tier']->name); ?></h5>
                            <?php if($milestone['claim'] && $milestone['claim']->status == 1): ?>
                                <span class="badge bg-white text-success">Claimed</span>
                            <?php elseif($milestone['claim'] && $milestone['claim']->status == 2): ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <span style="font-size:2.5rem;font-weight:700;color:#f0a500;">
                                    <?php echo e(number_format($milestone['tier']->g_coins)); ?>

                                </span>
                                <span class="text-muted fs-6"> G-Coins</span><br>
                                <small class="text-muted">= &#8377;<?php echo e(number_format($milestone['tier']->g_coins / 10, 2)); ?> INR
                                    value</small>
                            </div>

                            
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small>Progress</small>
                                    <small><?php echo e($milestone['progress']); ?> / <?php echo e($milestone['tier']->required_referrals); ?></small>
                                </div>
                                <div class="progress" style="height:10px;">
                                    <div class="progress-bar bg-success"
                                        style="width:<?php echo e(($milestone['progress'] / $milestone['tier']->required_referrals) * 100); ?>%">
                                    </div>
                                </div>
                                <small class="text-muted">Need <?php echo e($milestone['tier']->required_referrals); ?> active refer
                                    members</small>
                            </div>

                            
                            <?php if($milestone['claim'] && $milestone['claim']->status == 1): ?>
                                <div class="alert alert-success py-2 mb-0">
                                    <i class="ti ti-circle-check me-1"></i>
                                    Approved on <?php echo e($milestone['claim']->approved_at?->format('d M Y')); ?>

                                </div>
                            <?php elseif($milestone['claim'] && $milestone['claim']->status == 2): ?>
                                <div class="alert alert-warning py-2 mb-0">
                                    <i class="ti ti-clock me-1"></i> Claim under review
                                </div>
                            <?php elseif($milestone['can_claim']): ?>
                                <button class="btn btn-success w-100 claim-btn" data-tier="<?php echo e($milestone['tier']->id); ?>"
                                    data-name="<?php echo e($milestone['tier']->name); ?>"
                                    data-coins="<?php echo e(number_format($milestone['tier']->g_coins)); ?>">
                                    <i class="ti ti-gift me-1"></i> Claim <?php echo e(number_format($milestone['tier']->g_coins)); ?> G-Coins
                                </button>
                            <?php elseif($milestone['eligible'] && !$milestone['can_claim']): ?>
                                <div class="alert alert-secondary py-2 mb-0">
                                    <i class="ti ti-lock me-1"></i> <?php echo e($milestone['reason']); ?>

                                </div>
                            <?php else: ?>
                                <div class="alert alert-light py-2 mb-0 text-muted">
                                    <i class="ti ti-lock me-1"></i>
                                    Need <?php echo e($milestone['tier']->required_referrals - $milestone['progress']); ?> more referrals
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <div class="card mt-2">
            <div class="card-header">
                <h5 class="mb-0">How It Works</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <i class="ti ti-users fs-2 text-primary"></i>
                        <p class="mt-2"><strong>10 Referrals</strong><br>First Reward: 10,000 G-Coins</p>
                    </div>
                    <div class="col-md-4">
                        <i class="ti ti-trophy fs-2 text-success"></i>
                        <p class="mt-2"><strong>15 Referrals</strong><br>Option A: 5,000 G-Coins</p>
                    </div>
                    <div class="col-md-4">
                        <i class="ti ti-award fs-2 text-warning"></i>
                        <p class="mt-2"><strong>20 Referrals</strong><br>Option B: 10,000 G-Coins</p>
                    </div>
                </div>
                <div class="alert alert-info mt-2 mb-0">
                    <strong>10 G-Coins = &#8377;1 INR</strong> &nbsp;|&nbsp;
                    Lifetime max: 25,000 G-Coins = &#8377;2,500 &nbsp;|&nbsp;
                    Must claim in order: First Reward &rarr; Option A &rarr; Option B
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).on('click', '.claim-btn', function () {
            var tierId = $(this).data('tier');
            var name = $(this).data('name');
            var coins = $(this).data('coins');

            Swal.fire({
                title: 'Claim ' + coins + ' G-Coins?',
                text: name + ' — Your claim will be reviewed by admin.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Claim!',
                confirmButtonColor: '#28a745',
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "<?php echo e(route('member.claim.reward')); ?>",
                        type: 'POST',
                        data: { tier_id: tierId, _token: "<?php echo e(csrf_token()); ?>" },
                        success: function (res) {
                            if (res.status) {
                                toastr.success(res.message);
                                setTimeout(function () { location.reload(); }, 1500);
                            } else {
                                toastr.error(res.message);
                            }
                        },
                        error: function () { toastr.error('Something went wrong'); }
                    });
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.main-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Qubeta\svrs\resources\views/member/rewards/index.blade.php ENDPATH**/ ?>