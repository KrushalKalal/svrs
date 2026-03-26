<?php $__env->startSection('title', config('app.name') . ' || Membership'); ?>

<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
            <div class="my-auto mb-2">
                <h2 class="mb-1">Membership</h2>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);"><i class="ti ti-smart-home"></i></a></li>
                        <li class="breadcrumb-item active">Membership</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">

            
            <div class="col-md-6">

                <?php if(!$membership): ?>
                    
                    <div class="card">
                        <div class="card-header">
                            <h4>Pay Membership Fee</h4>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <strong>One-Time Fee: &#8377;<?php echo e($plan->price ?? 519); ?></strong><br>
                                Pay and upload screenshot to unlock Refer and Earn features.
                            </div>
                            <form id="membershipForm" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <div class="mb-3">
                                    <label class="form-label">Payment Screenshot <span class="text-danger">*</span></label>
                                    <input type="file" name="screenshot" class="form-control" accept="image/*">
                                    <small class="text-danger error-text screenshot_error"></small>
                                </div>
                                <button type="submit" class="btn btn-primary w-100" id="payBtn">
                                    <span class="btn-text"><i class="ti ti-send me-1"></i>Submit Payment</span>
                                    <span class="btn-loader d-none"><span class="spinner-border spinner-border-sm"></span>
                                        Submitting...</span>
                                </button>
                            </form>
                        </div>
                    </div>

                <?php elseif($membership->status == 2): ?>
                    
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="ti ti-clock fs-1 text-warning"></i>
                            <h4 class="mt-3">Under Review</h4>
                            <p class="text-muted">Your membership payment of &#8377;<?php echo e(number_format($membership->amount, 2)); ?>

                                is pending admin approval.</p>
                            <span class="badge bg-warning text-dark  px-3 py-2">Pending Approval</span>
                        </div>
                    </div>

                <?php elseif($membership->status == 0): ?>
                    
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="ti ti-x fs-1 text-danger"></i>
                            <h4 class="mt-3 text-danger">Payment Rejected</h4>
                            <p class="text-muted">Your membership payment was rejected. Please contact admin.</p>
                        </div>
                    </div>

                <?php elseif($membership->status == 1): ?>
                    
                    <div class="card border-success">
                        <div class="card-header bg-success text-white">
                            <h4 class="mb-0"><i class="ti ti-circle-check me-2"></i>Refer and Earn Active</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label class="form-label text-muted">Your Refer Code</label>
                                <div class="input-group">
                                    <input type="text" class="form-control fw-bold" value="<?php echo e($membership->refer_code); ?>"
                                        id="referCode" readonly>
                                    <button class="btn btn-outline-secondary" onclick="copyText('referCode')">
                                        <i class="ti ti-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Your Refer Link</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="<?php echo e($membership->refer_link); ?>" id="referLink"
                                        readonly>
                                    <button class="btn btn-outline-secondary" onclick="copyText('referLink')">
                                        <i class="ti ti-copy"></i> Copy
                                    </button>
                                </div>
                            </div>
                            <div class="alert alert-success mt-3 mb-0">
                                <strong>Active since:</strong> <?php echo e($membership->approved_at?->format('d M Y')); ?>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            
            <?php if(!$membership || $membership->status != 1): ?>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Payment Details</h4>
                        </div>
                        <div class="card-body">
                            <?php if($contact && $contact->qr_image): ?>
                                <div class="text-center mb-3">
                                    <img src="<?php echo e(asset($contact->qr_image)); ?>" alt="QR Code" style="max-width:180px;"
                                        class="img-fluid">
                                    <p class="text-muted mt-1">Scan to Pay</p>
                                </div>
                            <?php endif; ?>
                            <ul class="list-unstyled">
                                <li class="mb-2"><strong>Bank:</strong> <?php echo e($contact->bank ?? '-'); ?></li>
                                <li class="mb-2"><strong>Account Name:</strong> <?php echo e($contact->account_name ?? '-'); ?></li>
                                <li class="mb-2"><strong>Account Number:</strong> <?php echo e($contact->account_number ?? '-'); ?></li>
                                <li class="mb-2"><strong>IFSC Code:</strong> <?php echo e($contact->ifsc_code ?? '-'); ?></li>
                                <li class="mb-2"><strong>Branch:</strong> <?php echo e($contact->branch ?? '-'); ?></li>
                            </ul>
                            <div class="alert alert-primary mt-2 mb-0">
                                <strong>Amount to Pay: &#8377;<?php echo e($plan->price ?? 519); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <script>
        function copyText(id) {
            var el = document.getElementById(id);
            navigator.clipboard.writeText(el.value);
            toastr.success('Copied to clipboard!');
        }

        $('#membershipForm').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $('.error-text').text('');

            $('#payBtn .btn-text').addClass('d-none');
            $('#payBtn .btn-loader').removeClass('d-none');
            $('#payBtn').prop('disabled', true);

            $.ajax({
                url: "<?php echo e(route('member.membership.pay')); ?>",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (res) {
                    if (res.status) {
                        toastr.success(res.message);
                        setTimeout(function () { location.reload(); }, 1500);
                    } else {
                        toastr.error(res.message);
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        $.each(xhr.responseJSON.errors, function (key, val) {
                            $('.' + key + '_error').text(val[0]);
                        });
                    } else {
                        toastr.error('Something went wrong');
                    }
                },
                complete: function () {
                    $('#payBtn .btn-text').removeClass('d-none');
                    $('#payBtn .btn-loader').addClass('d-none');
                    $('#payBtn').prop('disabled', false);
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.main-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Qubeta\svrs\resources\views/member/membership/index.blade.php ENDPATH**/ ?>