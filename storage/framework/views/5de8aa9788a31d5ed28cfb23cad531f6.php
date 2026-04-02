


<?php $__env->startSection('title', 'My Profile'); ?>
<?php $__env->startSection('nav-back'); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('nav-back-url', route('member.profile')); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $user      = auth()->user();
        $activeTab = request('tab', 'account');
        if ($activeTab === 'password') $activeTab = 'edit';
    ?>

    
    <div style="padding:16px 20px 0;">
        <div class="segment-ctrl" id="profileTabs">
            <button class="<?php echo e($activeTab === 'account' ? 'active' : ''); ?>" onclick="switchTab('account')">Account</button>
            <button class="<?php echo e($activeTab === 'edit'    ? 'active' : ''); ?>" onclick="switchTab('edit')">Edit</button>
            <button class="<?php echo e($activeTab === 'bank'    ? 'active' : ''); ?>" onclick="switchTab('bank')">Bank</button>
        </div>
    </div>

    
    <div id="tabAccount" style="<?php echo e($activeTab === 'account' ? '' : 'display:none;'); ?>">
        <div style="padding:24px 20px 0;text-align:center;">
            <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--gold),var(--gold-dark));display:flex;align-items:center;justify-content:center;font-size:32px;font-weight:800;color:#000;margin:0 auto 12px;overflow:hidden;border:3px solid rgba(240,165,0,0.45);">
                <?php if($user->profile_image): ?>
                    <img src="<?php echo e(asset($user->profile_image)); ?>" style="width:100%;height:100%;object-fit:cover;">
                <?php else: ?>
                    <?php echo e(strtoupper(substr($user->first_name, 0, 1))); ?>

                <?php endif; ?>
            </div>
            <h3 style="font-size:20px;font-weight:800;margin-bottom:3px;"><?php echo e($user->full_name); ?></h3>
            <p style="font-size:13px;color:var(--muted);margin-bottom:12px;"><?php echo e($user->email); ?></p>
            <div style="display:flex;gap:8px;justify-content:center;flex-wrap:wrap;">
                <div class="stat-pill gold" onclick="copyText('<?php echo e($user->member_code); ?>')" style="cursor:pointer;">
                    <i class="fa fa-copy" style="font-size:10px;"></i> <?php echo e($user->member_code); ?>

                </div>
                <?php if($user->is_refer_member): ?>
                    <div class="badge-app badge-teal">
                        <i class="fa fa-circle-check" style="margin-right:4px;font-size:10px;"></i>Refer Member
                    </div>
                <?php endif; ?>
                <div class="stat-pill <?php echo e($user->status == 1 ? 'green' : 'gold'); ?>">
                    <i class="fa fa-circle" style="font-size:7px;"></i><?php echo e($user->status == 1 ? 'Active' : 'Pending'); ?>

                </div>
            </div>
        </div>

        <div class="section-label">Account Details</div>
        <div style="margin:0 20px;" class="app-card">
            <div class="list-row">
                <div class="list-icon blue" style="width:36px;height:36px;border-radius:10px;font-size:14px;"><i class="fa fa-user"></i></div>
                <div class="list-body"><div class="sub" style="font-size:11px;">Full Name</div><div class="title"><?php echo e($user->full_name); ?></div></div>
            </div>
            <div class="list-row">
                <div class="list-icon teal" style="width:36px;height:36px;border-radius:10px;font-size:14px;"><i class="fa fa-envelope"></i></div>
                <div class="list-body"><div class="sub" style="font-size:11px;">Email</div><div class="title"><?php echo e($user->email); ?></div></div>
            </div>
            <div class="list-row">
                <div class="list-icon green" style="width:36px;height:36px;border-radius:10px;font-size:14px;"><i class="fa fa-phone"></i></div>
                <div class="list-body"><div class="sub" style="font-size:11px;">Mobile</div><div class="title"><?php echo e($user->mobile); ?></div></div>
            </div>
            <div class="list-row">
                <div class="list-icon gold" style="width:36px;height:36px;border-radius:10px;font-size:14px;"><i class="fa fa-id-card"></i></div>
                <div class="list-body">
                    <div class="sub" style="font-size:11px;">Member Code</div>
                    <div class="title" style="font-family:'Space Mono',monospace;"><?php echo e($user->member_code); ?></div>
                </div>
                <button onclick="copyText('<?php echo e($user->member_code); ?>')"
                    style="background:none;border:none;color:var(--muted);cursor:pointer;padding:4px;">
                    <i class="fa fa-copy"></i>
                </button>
            </div>
            <?php if($user->sponsor_id): ?>
            <div class="list-row">
                <div class="list-icon blue" style="width:36px;height:36px;border-radius:10px;font-size:14px;"><i class="fa fa-users"></i></div>
                <div class="list-body">
                    <div class="sub" style="font-size:11px;">Sponsor</div>
                    <div class="title" style="font-family:'Space Mono',monospace;"><?php echo e($user->sponsor_id); ?></div>
                </div>
            </div>
            <?php endif; ?>
            <div class="list-row">
                <div class="list-icon blue" style="width:36px;height:36px;border-radius:10px;font-size:14px;"><i class="fa fa-calendar"></i></div>
                <div class="list-body">
                    <div class="sub" style="font-size:11px;">Joining Date</div>
                    <div class="title"><?php echo e($user->created_at->format('d M Y')); ?></div>
                </div>
            </div>
        </div>

        
        <?php if($user->is_refer_member): ?>
            <?php $membership = \App\Models\MemberMembership::where('user_id', $user->id)->where('status', 1)->first(); ?>
            <?php if($membership): ?>
            <div class="section-label">Referral</div>
            <div style="margin:0 20px;" class="app-card">
                <div class="list-row" style="padding:14px 16px;">
                    <div class="list-body">
                        <div class="sub" style="font-size:11px;margin-bottom:4px;">Refer Code</div>
                        <div style="font-size:22px;font-weight:800;color:var(--gold);font-family:'Space Mono',monospace;letter-spacing:2px;">
                            <?php echo e($membership->refer_code ?? $user->member_code); ?>

                        </div>
                    </div>
                    <button onclick="copyText('<?php echo e($membership->refer_code ?? $user->member_code); ?>')"
                        style="width:40px;height:40px;border-radius:10px;background:rgba(240,165,0,0.15);border:1px solid rgba(240,165,0,0.3);color:var(--gold);cursor:pointer;font-size:15px;display:flex;align-items:center;justify-content:center;">
                        <i class="fa fa-copy"></i>
                    </button>
                </div>
            </div>
            <?php endif; ?>
        <?php endif; ?>

        <div style="height:8px;"></div>
    </div>

    
    <div id="tabEdit" style="<?php echo e($activeTab === 'edit' ? '' : 'display:none;'); ?>">
        <div style="padding:24px 20px 0;text-align:center;">
            <div style="position:relative;width:80px;margin:0 auto 6px;">
                <div id="avatarWrap" style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--gold),var(--gold-dark));display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:800;color:#000;overflow:hidden;border:3px solid rgba(240,165,0,0.45);">
                    <?php if($user->profile_image): ?>
                        <img src="<?php echo e(asset($user->profile_image)); ?>" style="width:100%;height:100%;object-fit:cover;">
                    <?php else: ?>
                        <span id="avatarInitial"><?php echo e(strtoupper(substr($user->first_name, 0, 1))); ?></span>
                    <?php endif; ?>
                </div>
                <button type="button" onclick="document.getElementById('photoInput').click()"
                    style="position:absolute;bottom:0;right:0;width:28px;height:28px;border-radius:50%;background:var(--gold);border:none;cursor:pointer;color:#000;font-size:12px;display:flex;align-items:center;justify-content:center;">
                    <i class="fa fa-camera"></i>
                </button>
            </div>
            <p style="font-size:12px;color:var(--muted);">Tap to change photo</p>
        </div>

        <div style="padding:0 20px 20px;">
            <div class="section-label" style="padding:0;margin:16px 0 12px;">Edit Profile</div>
            <form id="editProfileForm" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type="file" id="photoInput" name="photo" style="display:none;" accept="image/*">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                    <div>
                        <label class="input-label">First Name</label>
                        <input type="text" name="first_name" class="input-app" value="<?php echo e($user->first_name); ?>">
                    </div>
                    <div>
                        <label class="input-label">Last Name</label>
                        <input type="text" name="last_name" class="input-app" value="<?php echo e($user->last_name); ?>">
                    </div>
                </div>
                <div style="margin-bottom:16px;">
                    <label class="input-label">Mobile</label>
                    <input type="text" class="input-app" value="<?php echo e($user->mobile); ?>" readonly style="color:var(--muted);">
                </div>
                <button type="submit" class="btn-app btn-gold" id="saveProfileBtn">Save Profile</button>
            </form>

            <div class="section-label" style="padding:0;margin:24px 0 12px;">Change Password</div>
            <form id="changePassForm">
                <?php echo csrf_field(); ?>
                <div style="margin-bottom:12px;position:relative;">
                    <label class="input-label">Current Password</label>
                    <input type="password" name="current_password" class="input-app" placeholder="Current Password" id="cp1">
                    <button type="button" onclick="togglePw('cp1',this)"
                        style="position:absolute;right:14px;bottom:14px;background:none;border:none;color:var(--muted);cursor:pointer;">
                        <i class="fa fa-eye-slash"></i>
                    </button>
                </div>
                <div style="margin-bottom:12px;position:relative;">
                    <label class="input-label">New Password</label>
                    <input type="password" name="new_password" class="input-app" placeholder="New Password" id="cp2">
                    <button type="button" onclick="togglePw('cp2',this)"
                        style="position:absolute;right:14px;bottom:14px;background:none;border:none;color:var(--muted);cursor:pointer;">
                        <i class="fa fa-eye-slash"></i>
                    </button>
                </div>
                <div style="margin-bottom:16px;position:relative;">
                    <label class="input-label">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" class="input-app" placeholder="Confirm New Password" id="cp3">
                    <button type="button" onclick="togglePw('cp3',this)"
                        style="position:absolute;right:14px;bottom:14px;background:none;border:none;color:var(--muted);cursor:pointer;">
                        <i class="fa fa-eye-slash"></i>
                    </button>
                </div>
                <button type="submit" class="btn-app btn-gold" id="savePassBtn">
                    <i class="fa fa-lock"></i> Change Password
                </button>
            </form>
        </div>
    </div>

    
    <div id="tabBank" style="<?php echo e($activeTab === 'bank' ? '' : 'display:none;'); ?>padding:20px;">
        <div style="margin:16px 20px 0;padding:12px 16px;background:rgba(59,130,246,0.08);border:1px solid rgba(59,130,246,0.2);border-radius:12px;display:flex;align-items:flex-start;gap:10px;">
            <i class="fa fa-circle-info" style="color:var(--accent-blue);margin-top:2px;flex-shrink:0;"></i>
            <p style="font-size:13px;color:var(--muted);line-height:1.5;margin:0;">Bank details are used for withdrawal processing by admin.</p>
        </div>

        <div style="padding:12px 20px 20px;">
            <form id="bankForm">
                <?php echo csrf_field(); ?>
                <div style="margin-bottom:12px;">
                    <label class="input-label">Bank Name</label>
                    <input type="text" name="bank_name" class="input-app"
                        value="<?php echo e($bankdetail->bank_name ?? ''); ?>" placeholder="Bank Name">
                </div>
                <div style="margin-bottom:12px;">
                    <label class="input-label">Account Holder Name</label>
                    <input type="text" name="account_name" class="input-app"
                        value="<?php echo e($bankdetail->account_holder_name ?? ''); ?>" placeholder="Account Holder Name">
                </div>
                <div style="margin-bottom:12px;">
                    <label class="input-label">Account Number</label>
                    <input type="text" name="account_number" class="input-app"
                        value="<?php echo e($bankdetail->account_number ?? ''); ?>" placeholder="Account Number">
                </div>
                <div style="margin-bottom:12px;">
                    <label class="input-label">IFSC Code</label>
                    <input type="text" name="ifsc_code" class="input-app"
                        value="<?php echo e($bankdetail->ifsc_code ?? ''); ?>" placeholder="IFSC Code">
                </div>
                <div style="margin-bottom:12px;">
                    <label class="input-label">Branch (Optional)</label>
                    <input type="text" name="branch_name" class="input-app"
                        value="<?php echo e($bankdetail->branch_name ?? ''); ?>" placeholder="Branch">
                </div>
                <div style="margin-bottom:20px;">
                    <label class="input-label">UPI ID (optional)</label>
                    <input type="text" name="upi" class="input-app"
                        value="<?php echo e($bankdetail->upi ?? ''); ?>" placeholder="yourname@upi">
                </div>
                <button type="submit" class="btn-app btn-gold" id="saveBankBtn">
                    <i class="fa fa-building-columns"></i> Save Bank Details
                </button>
            </form>
        </div>
    </div>

    <div style="height:8px;"></div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
var _activeTab = '<?php echo e($activeTab); ?>';

function switchTab(tab) {
    ['account', 'edit', 'bank'].forEach(function(t) {
        var el = document.getElementById('tab' + t.charAt(0).toUpperCase() + t.slice(1));
        if (el) el.style.display = (t === tab) ? '' : 'none';
    });
    document.querySelectorAll('#profileTabs button').forEach(function(b, i) {
        b.classList.toggle('active', ['account', 'edit', 'bank'][i] === tab);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    switchTab(_activeTab);
});

function togglePw(id, btn) {
    var inp = document.getElementById(id);
    var show = inp.type === 'password';
    inp.type = show ? 'text' : 'password';
    btn.innerHTML = show ? '<i class="fa fa-eye"></i>' : '<i class="fa fa-eye-slash"></i>';
}

document.getElementById('photoInput').addEventListener('change', function() {
    if (!this.files[0]) return;
    var r = new FileReader();
    r.onload = function(e) {
        document.getElementById('avatarWrap').innerHTML =
            '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">';
    };
    r.readAsDataURL(this.files[0]);
});

$('#editProfileForm').on('submit', function(e) {
    e.preventDefault();
    var btn = document.getElementById('saveProfileBtn');
    btn.disabled = true; btn.innerHTML = '<span class="spin"></span>';
    $.ajax({
        url: "<?php echo e(route('member.profile.update')); ?>",
        type: 'POST', data: new FormData(this),
        contentType: false, processData: false,
        success: function(r) { r.status ? toastr.success(r.message) : toastr.error(r.message); },
        error: function(xhr) {
            if (xhr.status === 422) $.each(xhr.responseJSON.errors, function(k, v) { toastr.error(v[0]); });
            else toastr.error('Something went wrong.');
        },
        complete: function() { btn.disabled = false; btn.innerHTML = 'Save Profile'; }
    });
});

$('#changePassForm').on('submit', function(e) {
    e.preventDefault();
    var btn = document.getElementById('savePassBtn');
    btn.disabled = true; btn.innerHTML = '<span class="spin" style="border-top-color:var(--gold);"></span>';
    $.ajax({
        url: "<?php echo e(route('member.password.update')); ?>",
        type: 'POST', data: $(this).serialize(),
        success: function(r) {
            if (r.status) { toastr.success(r.message); document.getElementById('changePassForm').reset(); }
            else toastr.error(r.message);
        },
        error: function(xhr) {
            if (xhr.status === 422) $.each(xhr.responseJSON.errors, function(k, v) { toastr.error(v[0]); });
            else toastr.error('Something went wrong.');
        },
        complete: function() { btn.disabled = false; btn.innerHTML = '<i class="fa fa-lock"></i> Change Password'; }
    });
});

$('#bankForm').on('submit', function(e) {
    e.preventDefault();
    var btn = document.getElementById('saveBankBtn');
    btn.disabled = true; btn.innerHTML = '<span class="spin"></span>';
    $.ajax({
        url: "<?php echo e(route('member.bank.details')); ?>",
        type: 'POST', data: $(this).serialize(),
        success: function(r) { r.status ? toastr.success(r.message) : toastr.error(r.message); },
        error: function(xhr) {
            if (xhr.status === 422) $.each(xhr.responseJSON.errors, function(k, v) { toastr.error(v[0]); });
            else toastr.error('Something went wrong.');
        },
        complete: function() { btn.disabled = false; btn.innerHTML = '<i class="fa fa-building-columns"></i> Save Bank Details'; }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('member.layout.app-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Qubeta\svrs\resources\views/member/profile_tabs.blade.php ENDPATH**/ ?>