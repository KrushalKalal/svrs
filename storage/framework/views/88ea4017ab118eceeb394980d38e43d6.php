<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <!-- Logo -->
    <div class="sidebar-logo">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="logo logo-normal text-center">
            <img src="<?php echo e(asset('admin/img/logo.png')); ?>" alt="Logo" style="height:50px; width:auto;">
        </a>
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="logo-small">
            <img src="<?php echo e(asset('admin/img/logo-small.png')); ?>" alt="Logo">
        </a>
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="dark-logo">
            <img src="<?php echo e(asset('admin/img/logo-white.jpg')); ?>" alt="Logo">
        </a>
    </div>
    <!-- /Logo -->
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('View dashboard')): ?>
                    <li>
                        <ul class="m-0">
                            <li>
                                <a href="<?php echo e(route('admin.dashboard')); ?>" class="px-2">
                                    <i class="ti ti-smart-home"></i><span> Dashboard</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['View Roles', 'View Permissions'])): ?>
                    <li>
                        <ul class="m-0">
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-shield"></i><span>Roles & Permissions</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('View Roles')): ?>
                                        <li><a href="<?php echo e(route('admin.roles.list')); ?>">Role List</a></li>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('View Permissions')): ?>
                                        <li><a href="<?php echo e(route('admin.permissions.list')); ?>">Permission List</a></li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Member')): ?>
                    <li>
                        <ul class="m-0">
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-users"></i><span>Member</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li>
                                        <a href="<?php echo e(route('admin.id.activate')); ?>">Id Activate</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo e(route('admin.member.list')); ?>">Member Lists</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo e(route('admin.inactive.member')); ?>">InActive Member</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo e(route('admin.activate.member')); ?>">Active Member</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['Privacy Policy', 'Terms Conditions', 'Contact Setting','Deposit Setting','Withdrawal Setting'])): ?>
                    <li>
                        <ul class="m-0">
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-settings"></i><span>Master Settings</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Contact Setting')): ?>
                                        <li><a href="<?php echo e(route('admin.contact.setting')); ?>">Contact Setting</a></li>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Deposit Setting')): ?>
                                        <li>
                                            <a href="<?php echo e(route('admin.deposit.setting')); ?>">Deposit Setting</a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Withdrawal Setting')): ?>
                                        <li>
                                            <a href="<?php echo e(route('admin.withdrawal.setting')); ?>">Withdrawal Setting</a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Privacy Policy')): ?>
                                        <li><a href="<?php echo e(route('admin.privacy')); ?>">Privacy & Policy</a></li>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Terms Conditions')): ?>
                                        <li><a href="<?php echo e(route('admin.term')); ?>">Terms & Conditions</a></li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Coin Master')): ?>
                    <li>
                        <ul class="m-0">
                            <li>
                                <a href="<?php echo e(route('admin.coin.list')); ?>" class="px-2">
                                    <i class="ti ti-coins"></i></i><span> Coin Master</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Profile')): ?>
                    <li>
                        <ul class="m-0">
                            <li>
                                <a href="<?php echo e(route('admin.profile')); ?>" class="px-2">
                                    <i class="fe fe-user"></i><span> Profile</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Welcome Letter')): ?>
                    <li>
                        <ul class="m-0">
                            <li>
                                <a href="<?php echo e(route('admin.welcome.letter')); ?>" class="px-2">
                                    <i class="fe fe-mail"></i><span> Welcome Letter</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Coin')): ?>
                    <li>
                        <ul class="m-0">
                            <li>
                                <a href="<?php echo e(route('admin.coin')); ?>" class="px-2">
                                    <i class="fe fe-dollar-sign"></i><span> Coin</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Coin History')): ?>
                    <li>
                        <ul class="m-0">
                            <li>
                                <a href="<?php echo e(route('admin.coin.history')); ?>" class="px-2">
                                    <i class="fe fe-repeat"></i><span> Coin History</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('My Wallet')): ?>
                    <li>
                        <ul class="m-0">
                            <li>
                                <a href="<?php echo e(route('admin.my.wallet')); ?>" class="px-2">
                                    <i class="fe fe-credit-card"></i><span> My Wallet</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Deposit Approval')): ?>
                    <li>
                        <ul class="m-0">
                            <li>
                                <a href="<?php echo e(route('admin.deposit.approval')); ?>" class="px-2">
                                    <i class="fe fe-download"></i><span> Deposit Approval</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Withdrawal Approval')): ?>
                    <li>
                        <ul class="m-0">
                            <li>
                                <a href="<?php echo e(route('admin.withdrawal.approval')); ?>" class="px-2">
                                    <i class="fe fe-upload"></i><span> Withdrawal Approval</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Customer Support')): ?>
                    <li>
                        <ul class="m-0">
                            <li>
                                <a href="<?php echo e(route('admin.customer.support')); ?>" class="px-2">
                                    <i class="fe fe-headphones"></i><span> Customer Support</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
<?php /**PATH D:\xampp\htdocs\SVRS\resources\views/admin/layout/Sidebar.blade.php ENDPATH**/ ?>