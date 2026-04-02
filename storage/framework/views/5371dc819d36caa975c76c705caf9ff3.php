<?php $__env->startSection('title', 'Membership'); ?><?php $__env->startSection('nav-title', 'Membership'); ?><?php $__env->startSection('nav-back'); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('nav-back-url', route('member.profile')); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $user = auth()->user();
    ?>

    
    <?php if($membership && $membership->status == 1): ?>

            
            <div style="margin:16px 20px 0;">
                <div style="background:linear-gradient(135deg,rgba(0,212,170,0.12),rgba(0,212,170,0.04));border:1px solid rgba(0,212,170,0.25);border-radius:16px;padding:24px 20px;text-align:center;">
                    <div style="width:60px;height:60px;border-radius:50%;background:rgba(0,212,170,0.15);display:flex;align-items:center;justify-content:center;font-size:26px;margin:0 auto 12px;">
                        <i class="fa fa-circle-check" style="color:var(--accent);"></i>
                    </div>
                    <h3 style="font-size:18px;font-weight:800;color:var(--accent);margin-bottom:6px;">Refer &amp; Earn Active</h3>
                    <p style="font-size:13px;color:var(--muted);">Active since <?php echo e($membership->approved_at?->format('d M Y')); ?></p>
                </div>
            </div>

            
            <div class="section-label">Your Referral</div>
            <div style="margin:0 20px;" class="app-card">
                <div class="list-row" style="padding:14px 16px;">
                    <div class="list-body">
                        <div class="sub" style="font-size:11px;margin-bottom:4px;">Refer Code</div>
                        <div style="font-size:22px;font-weight:800;color:var(--gold);font-family:'Space Mono',monospace;letter-spacing:3px;"><?php echo e($membership->refer_code); ?></div>
                    </div>
                    <button onclick="copyText('<?php echo e($membership->refer_code); ?>')"
                        style="width:44px;height:44px;border-radius:12px;background:rgba(240,165,0,0.12);border:1px solid rgba(240,165,0,0.25);color:var(--gold);cursor:pointer;font-size:16px;display:flex;align-items:center;justify-content:center;">
                        <i class="fa fa-copy"></i>
                    </button>
                </div>
                <?php if($membership->refer_link): ?>
                    <div style="padding:0 16px 14px;">
                        <div style="display:flex;align-items:center;gap:8px;padding:10px 14px;background:rgba(255,255,255,0.04);border-radius:10px;border:1px solid rgba(255,255,255,0.06);">
                            <span style="flex:1;font-size:12px;color:var(--muted);word-break:break-all;"><?php echo e($membership->refer_link); ?></span>
                            <button onclick="copyText_val('<?php echo e($membership->refer_link); ?>')"
                                style="background:none;border:none;color:var(--muted);cursor:pointer;padding:4px;font-size:13px;">
                                <i class="fa fa-copy"></i>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div style="height:8px;"></div>

        
    <?php elseif($membership && $membership->status == 2): ?>

            <div style="margin:16px 20px 0;">
                <div style="background:rgba(240,165,0,0.06);border:1px solid rgba(240,165,0,0.2);border-radius:16px;padding:28px 20px;text-align:center;">
                    <div style="width:60px;height:60px;border-radius:50%;background:rgba(240,165,0,0.12);display:flex;align-items:center;justify-content:center;font-size:26px;margin:0 auto 12px;">
                        <i class="fa fa-clock" style="color:var(--gold);"></i>
                    </div>
                    <h3 style="font-size:18px;font-weight:800;margin-bottom:6px;">Under Review</h3>
                    <p style="font-size:13px;color:var(--muted);margin-bottom:14px;">Your membership payment of ₹<?php echo e(number_format($membership->amount, 2)); ?> is pending admin approval.</p>
                    <span class="badge-app badge-gold" style="font-size:12px;padding:6px 16px;">Pending Approval</span>
                </div>
            </div>
            <div style="height:8px;"></div>

        
    <?php elseif($membership && $membership->status == 0): ?>

            <div style="margin:16px 20px 0;">
                <div style="background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.2);border-radius:16px;padding:28px 20px;text-align:center;">
                    <div style="width:60px;height:60px;border-radius:50%;background:rgba(239,68,68,0.12);display:flex;align-items:center;justify-content:center;font-size:26px;margin:0 auto 12px;">
                        <i class="fa fa-xmark" style="color:#ef4444;"></i>
                    </div>
                    <h3 style="font-size:18px;font-weight:800;color:#ef4444;margin-bottom:6px;">Payment Rejected</h3>
                    <p style="font-size:13px;color:var(--muted);">Your membership payment was rejected. Please contact admin.</p>
                </div>
            </div>
            <div style="height:8px;"></div>

        
    <?php else: ?>

        
        <div style="margin:16px 20px 0;">
            <div style="background:linear-gradient(135deg,#0d1b2a,#0a2240);border:1px solid rgba(59,130,246,0.2);border-radius:20px;padding:28px 24px;text-align:center;">
                <div style="width:64px;height:64px;border-radius:50%;background:rgba(59,130,246,0.15);display:flex;align-items:center;justify-content:center;font-size:28px;margin:0 auto 14px;border:2px solid rgba(59,130,246,0.25);">
                    <i class="fa fa-medal" style="color:#60a5fa;"></i>
                </div>
                <h3 style="font-size:20px;font-weight:800;margin-bottom:6px;">Upgrade to Refer &amp; Earn</h3>
                <p style="font-size:13px;color:var(--muted);margin-bottom:16px;line-height:1.6;">Unlock referral network, gold rewards<br>&amp; milestone bonuses</p>
                <div style="display:inline-block;padding:10px 28px;background:var(--gold);border-radius:12px;font-size:22px;font-weight:800;color:#000;">
                    ₹<?php echo e($plan->price ?? 519); ?>.00
                </div>
                <p style="font-size:11px;color:var(--muted);margin-top:8px;">Referral Membership</p>
            </div>
        </div>

        
        <div class="section-label">Benefits Included</div>
        <div style="margin:0 20px;" class="app-card">
            <?php $__currentLoopData = [
                    ['users', 'teal', 'Earn coins on every referral (L1, L2, L3)'],
                    ['star', 'gold', 'Unlock Gold Coin wallet &amp; rewards'],
                    ['trophy', 'gold', 'Milestone bonuses — claim Gold Coins'],
                    ['share-nodes', 'accent-blue', 'Share your referral code &amp; grow network'],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$icon, $color, $text]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="list-row">
                    <div class="list-icon <?php echo e($color); ?>" style="width:36px;height:36px;border-radius:10px;font-size:14px;">
                        <i class="fa fa-<?php echo e($icon); ?>"></i>
                    </div>
                    <div class="list-body">
                        <div class="title" style="font-size:13px;"><?php echo $text; ?></div>
                    </div>
                    <i class="fa fa-circle-check" style="color:var(--accent);font-size:14px;"></i>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <div class="section-label">Payment Details</div>
        <div style="margin:0 20px;" class="app-card">
            <?php if($contact): ?>
                <?php $__currentLoopData = [
                        ['Bank', $contact->bank ?? '-'],
                        ['Account Name', $contact->account_name ?? '-'],
                        ['Account No.', $contact->account_number ?? '-'],
                        ['IFSC', $contact->ifsc_code ?? '-'],
                        ['Branch', $contact->branch ?? '-'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $val]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="display:flex;justify-content:space-between;padding:10px 16px;border-bottom:1px solid rgba(255,255,255,0.05);">
                        <span style="font-size:13px;color:var(--muted);"><?php echo e($label); ?></span>
                        <span style="font-size:13px;font-weight:600;"><?php echo e($val); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($contact->qr_image): ?>
                    <div style="padding:16px;text-align:center;">
                        <img src="<?php echo e(asset($contact->qr_image)); ?>" style="width:140px;height:140px;object-fit:contain;border-radius:12px;">
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        
        <div class="section-label">Upload Payment Proof</div>
        <div style="margin:0 20px;">
            <form id="membershipForm" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div id="uploadArea"
                    onclick="document.getElementById('screenshotInput').click()"
                    style="display:flex;align-items:center;justify-content:space-between;padding:16px;background:var(--card);border-radius:14px;border:1.5px dashed rgba(255,255,255,0.12);cursor:pointer;margin-bottom:16px;">
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="width:40px;height:40px;border-radius:10px;background:rgba(255,255,255,0.06);display:flex;align-items:center;justify-content:center;font-size:16px;color:var(--muted);">
                            <i class="fa fa-arrow-up-from-bracket"></i>
                        </div>
                        <div>
                            <div id="uploadLabel" style="font-size:14px;font-weight:600;">Upload Payment Screenshot</div>
                            <div style="font-size:12px;color:var(--muted);">Tap to select from gallery</div>
                        </div>
                    </div>
                    <i class="fa fa-chevron-right" style="color:var(--muted);font-size:13px;"></i>
                </div>
                <input type="file" id="screenshotInput" name="screenshot" style="display:none;" accept="image/*">

                
                <div id="previewWrap" style="display:none;margin-bottom:16px;position:relative;">
                    <img id="previewImg" src="" style="width:100%;border-radius:12px;max-height:200px;object-fit:cover;">
                    <button type="button" onclick="clearPreview()"
                        style="position:absolute;top:8px;right:8px;width:28px;height:28px;border-radius:50%;background:rgba(0,0,0,0.6);border:none;color:#fff;cursor:pointer;font-size:12px;">
                        <i class="fa fa-xmark"></i>
                    </button>
                </div>

                <button type="submit" class="btn-app btn-gold" id="payBtn">
                    <i class="fa fa-shield-halved"></i> Submit for Approval
                </button>
            </form>
        </div>

        <div style="height:24px;"></div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
    function copyText_val(val) {
        navigator.clipboard.writeText(val);
        if (typeof toastr !== 'undefined') toastr.success('Copied!');
    }

    // Screenshot preview
    document.getElementById('screenshotInput')?.addEventListener('change', function() {
        if (!this.files[0]) return;
        var r = new FileReader();
        r.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('previewWrap').style.display = '';
            document.getElementById('uploadLabel').textContent = 'Screenshot selected ✓';
        };
        r.readAsDataURL(this.files[0]);
    });

    function clearPreview() {
        document.getElementById('screenshotInput').value = '';
        document.getElementById('previewWrap').style.display = 'none';
        document.getElementById('uploadLabel').textContent = 'Upload Payment Screenshot';
    }

    // Submit
    $('#membershipForm')?.on('submit', function(e) {
        e.preventDefault();
        var btn = document.getElementById('payBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spin"></span> Submitting...';
        $.ajax({
            url: "<?php echo e(route('member.membership.pay')); ?>",
            type: 'POST',
            data: new FormData(this),
            contentType: false,
            processData: false,
            success: function(res) {
                if (res.status) {
                    toastr.success(res.message);
                    setTimeout(function() { location.reload(); }, 1500);
                } else {
                    toastr.error(res.message);
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fa fa-shield-halved"></i> Submit for Approval';
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    $.each(xhr.responseJSON.errors, function(k, v){ toastr.error(v[0]); });
                } else {
                    toastr.error('Something went wrong');
                }
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-shield-halved"></i> Submit for Approval';
            }
        });
    });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('member.layout.app-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Qubeta\svrs\resources\views/member/membership/index.blade.php ENDPATH**/ ?>