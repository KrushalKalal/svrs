
<?php $__env->startSection('title', 'Profile'); ?>
<?php $__env->startSection('nav-title', 'Profile'); ?>
<?php $__env->startSection('hide-member-code'); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('nav-actions'); ?>
    <a href="<?php echo e(route('member.profile')); ?>?tab=edit" class="nav-action-btn" title="Edit Profile">
        <i class="fa fa-pen"></i>
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php $user = auth()->user(); ?>

    
    <div style="padding:16px 20px 0;">
        <a href="<?php echo e(route('member.profile')); ?>?tab=account" class="gold-card"
            style="padding:16px;display:flex;align-items:center;gap:14px;text-decoration:none;">
            <div
                style="width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg,var(--gold),var(--gold-dark));display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:800;color:#000;flex-shrink:0;overflow:hidden;border:2px solid rgba(240,165,0,0.5);">
                <?php if($user->profile_image): ?>
                    <img src="<?php echo e(asset($user->profile_image)); ?>" style="width:100%;height:100%;object-fit:cover;">
                <?php else: ?>
                    <?php echo e(strtoupper(substr($user->first_name, 0, 1))); ?>

                <?php endif; ?>
            </div>
            <div style="flex:1;min-width:0;">
                <h3 style="font-size:17px;font-weight:800;margin-bottom:2px;"><?php echo e($user->full_name); ?></h3>
                <p style="font-size:13px;color:var(--muted);margin-bottom:8px;"><?php echo e($user->email); ?></p>
                <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
                    <div class="stat-pill gold" style="cursor:pointer;"
                        onclick="event.preventDefault();copyText('<?php echo e($user->member_code); ?>')">
                        <i class="fa fa-copy" style="font-size:10px;"></i>
                        <?php echo e($user->member_code); ?>

                    </div>
                    <?php if($user->is_refer_member): ?>
                        <div class="badge-app badge-teal">
                            <i class="fa fa-circle-check" style="margin-right:4px;font-size:10px;"></i>Refer Member
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <i class="fa fa-chevron-right" style="color:var(--muted);font-size:13px;"></i>
        </a>
    </div>

    
    <?php if(!$user->is_refer_member): ?>
        <div style="margin:12px 20px 0;">
            <a href="<?php echo e(route('member.membership')); ?>" class="upgrade-banner">
                <div class="ub-icon"><i class="fa fa-medal"></i></div>
                <div class="ub-body">
                    <div class="ub-title">Upgrade to Refer &amp; Earn</div>
                    <div class="ub-sub">Unlock referrals, gold rewards &amp; milestones</div>
                </div>
                <i class="fa fa-chevron-right ub-arrow"></i>
            </a>
        </div>
    <?php endif; ?>

    
    <?php if($user->is_refer_member): ?>
        <div class="section-label">Referral &amp; Rewards</div>
        <div style="margin:0 20px;" class="app-card">
            <a href="<?php echo e(route('member.my.referrals')); ?>" class="list-row">
                <div class="list-icon teal" style="width:40px;height:40px;border-radius:12px;font-size:16px;">
                    <i class="fa fa-users"></i>
                </div>
                <div class="list-body">
                    <div class="title">Referral Network</div>
                    <div class="sub">My team &amp; earnings</div>
                </div>
                <i class="fa fa-chevron-right list-chevron"></i>
            </a>
            <a href="<?php echo e(route('member.my.rewards')); ?>" class="list-row">
                <div class="list-icon gold" style="width:40px;height:40px;border-radius:12px;font-size:16px;">
                    <i class="fa fa-trophy"></i>
                </div>
                <div class="list-body">
                    <div class="title">Milestone Rewards</div>
                    <div class="sub">Claim Gold Coins</div>
                </div>
                <i class="fa fa-chevron-right list-chevron"></i>
            </a>
            
            <a href="<?php echo e(route('member.reports')); ?>" class="list-row">
                <div class="list-icon blue" style="width:40px;height:40px;border-radius:12px;font-size:16px;">
                    <i class="fa fa-chart-bar"></i>
                </div>
                <div class="list-body">
                    <div class="title">Reports</div>
                    <div class="sub">Income, ledger &amp; referral tree</div>
                </div>
                <i class="fa fa-chevron-right list-chevron"></i>
            </a>
            <a href="<?php echo e(route('member.add.member')); ?>" class="list-row">
                <div class="list-icon teal"
                    style="width:40px;height:40px;border-radius:12px;font-size:16px;background:rgba(0,212,170,0.12);">
                    <i class="fa fa-user-plus"></i>
                </div>
                <div class="list-body">
                    <div class="title">Add New Member</div>
                    <div class="sub">Register a member under you</div>
                </div>
                <i class="fa fa-chevron-right list-chevron"></i>
            </a>
        </div>
    <?php endif; ?>

    
    <div class="section-label">Account</div>
    <div style="margin:0 20px;" class="app-card">
        <a href="<?php echo e(route('member.profile')); ?>?tab=edit" class="list-row">
            <div class="list-icon blue" style="width:40px;height:40px;border-radius:12px;font-size:16px;">
                <i class="fa fa-user"></i>
            </div>
            <div class="list-body">
                <div class="title">My Profile</div>
                <div class="sub">View &amp; edit account details</div>
            </div>
            <i class="fa fa-chevron-right list-chevron"></i>
        </a>
        <a href="<?php echo e(route('member.profile')); ?>?tab=password" class="list-row">
            <div class="list-icon gold" style="width:40px;height:40px;border-radius:12px;font-size:16px;">
                <i class="fa fa-lock"></i>
            </div>
            <div class="list-body">
                <div class="title">Change Password</div>
                <div class="sub">Update your credentials</div>
            </div>
            <i class="fa fa-chevron-right list-chevron"></i>
        </a>
        
        <a href="<?php echo e(route('member.reports')); ?>" class="list-row">
            <div class="list-icon blue" style="width:40px;height:40px;border-radius:12px;font-size:16px;">
                <i class="fa fa-chart-bar"></i>
            </div>
            <div class="list-body">
                <div class="title">Reports</div>
                <div class="sub">Wallet ledger &amp; transactions</div>
            </div>
            <i class="fa fa-chevron-right list-chevron"></i>
        </a>
    </div>

    
    <div class="section-label">Information</div>
    <div style="margin:0 20px;" class="app-card">
        <a href="#" class="list-row">
            <div class="list-icon teal" style="width:40px;height:40px;border-radius:12px;font-size:16px;">
                <i class="fa fa-shield-halved"></i>
            </div>
            <div class="list-body">
                <div class="title">Privacy Policy</div>
            </div>
            <i class="fa fa-chevron-right list-chevron"></i>
        </a>
        <a href="#" class="list-row">
            <div class="list-icon blue" style="width:40px;height:40px;border-radius:12px;font-size:16px;">
                <i class="fa fa-scale-balanced"></i>
            </div>
            <div class="list-body">
                <div class="title">Terms &amp; Conditions</div>
            </div>
            <i class="fa fa-chevron-right list-chevron"></i>
        </a>
        <a href="<?php echo e(route('member.my.wallet')); ?>" class="list-row">
            <div class="list-icon green" style="width:40px;height:40px;border-radius:12px;font-size:16px;">
                <i class="fa fa-building-columns"></i>
            </div>
            <div class="list-body">
                <div class="title">Deposit Info</div>
                <div class="sub">Min &amp; max deposit limits</div>
            </div>
            <i class="fa fa-chevron-right list-chevron"></i>
        </a>
        <a href="<?php echo e(route('member.customer.support')); ?>" class="list-row">
            <div class="list-icon teal"
                style="width:40px;height:40px;border-radius:12px;font-size:16px;background:rgba(0,212,170,0.12);">
                <i class="fa fa-headset"></i>
            </div>
            <div class="list-body">
                <div class="title">Contact Us</div>
                <div class="sub">Get support</div>
            </div>
            <i class="fa fa-chevron-right list-chevron"></i>
        </a>
    </div>

    
    <div style="margin:16px 20px 0;">
        <div class="app-card app-card-inner" style="display:flex;align-items:center;gap:14px;">
            <div
                style="width:44px;height:44px;border-radius:14px;background:linear-gradient(135deg,var(--gold),var(--gold-dark));display:flex;align-items:center;justify-content:center;font-size:22px;color:#000;flex-shrink:0;">
                <i class="fa-solid fa-dollar-sign"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:14px;font-weight:700;">SVRS Coin</div>
                <div style="font-size:12px;color:var(--muted);">Smart Value Reward System • v1.0.0</div>
            </div>
            <div class="badge-app badge-teal" style="font-size:11px;">Up to date</div>
        </div>
    </div>

    <div style="height:8px;"></div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('member.layout.app-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Qubeta\svrs\resources\views/member/profile.blade.php ENDPATH**/ ?>