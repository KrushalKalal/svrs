<?php $__env->startSection('title', 'Add New Member'); ?>
<?php $__env->startSection('nav-title', 'Add New Member'); ?>
<?php $__env->startSection('nav-back'); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('nav-back-url', route('member.dashboard')); ?>

<?php $__env->startSection('content'); ?>

    
    <div style="margin:16px 20px 0;">
        <div class="blue-card" style="padding:14px 16px;display:flex;align-items:flex-start;gap:10px;">
            <i class="fa fa-circle-info" style="color:var(--accent-blue);font-size:18px;margin-top:2px;flex-shrink:0;"></i>
            <div>
                <div style="font-size:14px;font-weight:700;margin-bottom:4px;">Registering under your account</div>
                <div style="font-size:13px;color:var(--muted);">
                    New member will be placed under Sponsor ID:
                    <span class="badge-app badge-blue"
                        style="font-family:'Space Mono',monospace;margin-left:4px;"><?php echo e(auth()->user()->member_code); ?></span>
                </div>
                <?php if(auth()->user()->is_refer_member): ?>
                    <div style="font-size:12px;color:var(--green);margin-top:4px;">
                        <i class="fa fa-coins" style="margin-right:4px;"></i>You will earn referral rewards when this member
                        activates and buys coins.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div style="padding:16px 20px 20px;">
        <form id="addMemberForm" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="sponsor_id" value="<?php echo e(auth()->user()->member_code); ?>">

            <div style="margin-bottom:12px;">
                <label class="input-label">Sponsor ID</label>
                <input type="text" class="input-app" value="<?php echo e(auth()->user()->member_code); ?>" readonly
                    style="color:var(--muted);font-family:'Space Mono',monospace;">
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                <div>
                    <label class="input-label">First Name <span style="color:var(--red);">*</span></label>
                    <input type="text" name="first_name" class="input-app" placeholder="First Name">
                    <small class="error-text first_name_error" style="color:var(--red);font-size:11px;"></small>
                </div>
                <div>
                    <label class="input-label">Last Name <span style="color:var(--red);">*</span></label>
                    <input type="text" name="last_name" class="input-app" placeholder="Last Name">
                    <small class="error-text last_name_error" style="color:var(--red);font-size:11px;"></small>
                </div>
            </div>

            <div style="margin-bottom:12px;">
                <label class="input-label">Mobile Number <span style="color:var(--red);">*</span></label>
                <input type="text" name="mobile" class="input-app" maxlength="10" placeholder="10-digit mobile">
                <small class="error-text mobile_error" style="color:var(--red);font-size:11px;"></small>
            </div>

            <div style="margin-bottom:12px;">
                <label class="input-label">Email Address <span style="color:var(--red);">*</span></label>
                <input type="email" name="email" class="input-app" placeholder="email@example.com">
                <small class="error-text email_error" style="color:var(--red);font-size:11px;"></small>
            </div>

            <div style="margin-bottom:12px;position:relative;">
                <label class="input-label">Password <span style="color:var(--red);">*</span></label>
                <input type="password" name="password" id="passField" class="input-app" placeholder="Min 6 characters">
                <button type="button" onclick="tgPw('passField',this)"
                    style="position:absolute;right:14px;bottom:14px;background:none;border:none;color:var(--muted);cursor:pointer;"><i
                        class="fa fa-eye-slash"></i></button>
                <small class="error-text password_error" style="color:var(--red);font-size:11px;"></small>
            </div>

            <div style="margin-bottom:12px;position:relative;">
                <label class="input-label">Confirm Password <span style="color:var(--red);">*</span></label>
                <input type="password" name="password_confirmation" id="confField" class="input-app"
                    placeholder="Repeat password">
                <button type="button" onclick="tgPw('confField',this)"
                    style="position:absolute;right:14px;bottom:14px;background:none;border:none;color:var(--muted);cursor:pointer;"><i
                        class="fa fa-eye-slash"></i></button>
                <small class="error-text password_confirmation_error" style="color:var(--red);font-size:11px;"></small>
            </div>

            <div style="margin-bottom:12px;">
                <label class="input-label">Deposit Amount (₹) <span style="color:var(--red);">*</span></label>
                <input type="number" name="amount" class="input-app" min="<?php echo e($deposit->min_amount ?? 200); ?>"
                    max="<?php echo e($deposit->max_amount ?? 2000); ?>"
                    placeholder="₹<?php echo e($deposit->min_amount ?? 200); ?> – ₹<?php echo e($deposit->max_amount ?? 2000); ?>">
                <small style="color:var(--muted);font-size:11px;">Min ₹<?php echo e($deposit->min_amount ?? 200); ?>, Max
                    ₹<?php echo e($deposit->max_amount ?? 2000); ?></small>
                <small class="error-text amount_error" style="color:var(--red);font-size:11px;display:block;"></small>
            </div>

            
            <div class="section-label" style="padding:0;margin:16px 0 12px;">Payment Details</div>
            <div class="app-card" style="margin-bottom:16px;">
                <?php if(isset($contact) && $contact->qr_image): ?>
                    <div style="padding:16px;text-align:center;border-bottom:1px solid var(--border2);">
                        <img src="<?php echo e(asset($contact->qr_image)); ?>"
                            style="width:130px;border-radius:10px;background:#fff;padding:6px;">
                        <p style="font-size:12px;color:var(--muted);margin-top:6px;">Scan to Pay</p>
                    </div>
                <?php endif; ?>
                <div style="padding:12px 16px;">
                    <div class="info-row"><span class="key">Bank</span><span class="val"><?php echo e($contact->bank ?? '—'); ?></span>
                    </div>
                    <div class="info-row"><span class="key">Account Name</span><span
                            class="val"><?php echo e($contact->account_name ?? '—'); ?></span></div>
                    <div class="info-row" onclick="copyText('<?php echo e($contact->account_number ?? ''); ?>')"
                        style="cursor:pointer;">
                        <span class="key">Account No.</span>
                        <span class="val" style="font-family:'Space Mono',monospace;"><?php echo e($contact->account_number ?? '—'); ?>

                            <i class="fa fa-copy" style="font-size:11px;color:var(--muted);"></i></span>
                    </div>
                    <div class="info-row" onclick="copyText('<?php echo e($contact->ifsc_code ?? ''); ?>')" style="cursor:pointer;">
                        <span class="key">IFSC</span>
                        <span class="val" style="font-family:'Space Mono',monospace;"><?php echo e($contact->ifsc_code ?? '—'); ?> <i
                                class="fa fa-copy" style="font-size:11px;color:var(--muted);"></i></span>
                    </div>
                    <div class="info-row" style="border:none;"><span class="key">Branch</span><span
                            class="val"><?php echo e($contact->branch ?? '—'); ?></span></div>
                </div>
            </div>

            <div style="margin-bottom:20px;">
                <label class="input-label">Payment Screenshot <span style="color:var(--red);">*</span></label>
                <div class="app-card list-row" style="cursor:pointer;" onclick="document.getElementById('ssInput').click()">
                    <div class="list-icon blue" style="width:36px;height:36px;border-radius:10px;font-size:15px;"><i
                            class="fa fa-upload"></i></div>
                    <div class="list-body">
                        <div class="title">Upload Payment Screenshot</div>
                        <div class="sub">Tap to select from gallery</div>
                    </div>
                    <i class="fa fa-chevron-right list-chevron"></i>
                </div>
                <input type="file" id="ssInput" name="attachment" accept="image/*" style="display:none;">
                <div id="ssPreview" style="display:none;margin-top:10px;text-align:center;">
                    <img style="max-width:100%;border-radius:10px;max-height:160px;">
                </div>
                <small class="error-text attachment_error" style="color:var(--red);font-size:11px;"></small>
            </div>

            <button type="submit" class="btn-app btn-gold" id="submitBtn">
                <i class="fa fa-user-plus"></i> Register Member
            </button>
            <a href="<?php echo e(route('member.dashboard')); ?>" class="btn-app btn-outline-gold"
                style="margin-top:10px;text-decoration:none;">Cancel</a>
        </form>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        function tgPw(id, btn) {
            var inp = document.getElementById(id);
            var show = inp.type === 'password';
            inp.type = show ? 'text' : 'password';
            btn.innerHTML = show ? '<i class="fa fa-eye"></i>' : '<i class="fa fa-eye-slash"></i>';
        }
        document.getElementById('ssInput').addEventListener('change', function () {
            if (!this.files[0]) return;
            var r = new FileReader();
            r.onload = function (e) {
                var p = document.getElementById('ssPreview');
                p.style.display = ''; p.querySelector('img').src = e.target.result;
            };
            r.readAsDataURL(this.files[0]);
        });
        $('#addMemberForm').on('submit', function (e) {
            e.preventDefault();
            $('.error-text').text('');
            var btn = document.getElementById('submitBtn');
            btn.disabled = true; btn.innerHTML = '<span class="spin"></span> Registering...';
            $.ajax({
                url: "<?php echo e(route('member.add.member.store')); ?>",
                type: 'POST', data: new FormData(this),
                contentType: false, processData: false,
                success: function (res) {
                    if (res.status) { toastr.success(res.message); setTimeout(function () { location.reload(); }, 1500); }
                    else toastr.error(res.message);
                },
                error: function (xhr) {
                    if (xhr.status === 422) $.each(xhr.responseJSON.errors, function (f, m) { $('.' + f + '_error').text(m[0]); });
                    else toastr.error('Server error. Try again.');
                },
                complete: function () { btn.disabled = false; btn.innerHTML = '<i class="fa fa-user-plus"></i> Register Member'; }
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('member.layout.app-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Qubeta\svrs\resources\views/member/add_member.blade.php ENDPATH**/ ?>