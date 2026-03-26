<div class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <a href="<?php echo e(auth()->user()->role === 'admin' ? route('admin.dashboard') : route('member.dashboard')); ?>"
            class="logo logo-normal text-center">
            <img src="<?php echo e(asset('admin/img/logo.png')); ?>" alt="Logo" style="height:50px;width:auto;">
        </a>
        <a href="<?php echo e(auth()->user()->role === 'admin' ? route('admin.dashboard') : route('member.dashboard')); ?>"
            class="logo-small">
            <img src="<?php echo e(asset('admin/img/logo-small.png')); ?>" alt="Logo">
        </a>
        <a href="<?php echo e(auth()->user()->role === 'admin' ? route('admin.dashboard') : route('member.dashboard')); ?>"
            class="dark-logo">
            <img src="<?php echo e(asset('admin/img/logo-white.jpg')); ?>" alt="Logo">
        </a>
    </div>

    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>

                
                
                
                <?php if(auth()->user()->role === 'admin'): ?>

                    <li>
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="px-2">
                            <i class="ti ti-smart-home"></i><span> Dashboard</span>
                        </a>
                    </li>

                    <li class="submenu">
                        <a href="javascript:void(0);">
                            <i class="ti ti-users"></i><span>Members</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="<?php echo e(route('admin.id.activate')); ?>">ID Activate</a></li>
                            <li><a href="<?php echo e(route('admin.add.member')); ?>">Add New Member</a></li>
                            
                            <li><a href="<?php echo e(route('admin.member.list')); ?>">Member List</a></li>
                            <li><a href="<?php echo e(route('admin.activate.member')); ?>">Active Members</a></li>
                            <li><a href="<?php echo e(route('admin.inactive.member')); ?>">Inactive Members</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="<?php echo e(route('admin.membership.approval')); ?>" class="px-2">
                            <i class="ti ti-id-badge"></i><span> Membership Approval</span>
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo e(route('admin.reward.claims')); ?>" class="px-2">
                            <i class="ti ti-trophy"></i><span> Reward Claims</span>
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo e(route('admin.deposit.approval')); ?>" class="px-2">
                            <i class="fe fe-download"></i><span> Deposit Approval</span>
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo e(route('admin.withdrawal.approval')); ?>" class="px-2">
                            <i class="fe fe-upload"></i><span> Withdrawal Approval</span>
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo e(route('admin.coin.list')); ?>" class="px-2">
                            <i class="ti ti-coins"></i><span> Coin Master</span>
                        </a>
                    </li>

                    <li class="submenu">
                        <a href="javascript:void(0);">
                            <i class="ti ti-chart-bar"></i><span>Reports</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="<?php echo e(route('admin.reports.financial')); ?>">Financial Report</a></li>
                            <li><a href="<?php echo e(route('admin.referral.reward.list')); ?>">Referral Rewards</a></li>
                        </ul>
                    </li>

                    <li class="submenu">
                        <a href="javascript:void(0);">
                            <i class="ti ti-settings"></i><span>Master Settings</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="<?php echo e(route('admin.contact.setting')); ?>">Contact Setting</a></li>
                            <li><a href="<?php echo e(route('admin.deposit.setting')); ?>">Deposit Setting</a></li>
                            <li><a href="<?php echo e(route('admin.withdrawal.setting')); ?>">Withdrawal Setting</a></li>
                            <li><a href="<?php echo e(route('admin.privacy')); ?>">Privacy Policy</a></li>
                            <li><a href="<?php echo e(route('admin.term')); ?>">Terms & Conditions</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="<?php echo e(route('admin.customer.support')); ?>" class="px-2">
                            <i class="fe fe-headphones"></i><span> Customer Support</span>
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo e(route('admin.profile')); ?>" class="px-2">
                            <i class="fe fe-user"></i><span> Profile</span>
                        </a>
                    </li>

                    
                    
                    
                <?php elseif(auth()->user()->role === 'member'): ?>

                    <li>
                        <a href="<?php echo e(route('member.dashboard')); ?>" class="px-2">
                            <i class="ti ti-smart-home"></i><span> Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo e(route('member.coin')); ?>" class="px-2">
                            <i class="fe fe-dollar-sign"></i><span> Coin Chart</span>
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo e(route('member.coin.history')); ?>" class="px-2">
                            <i class="fe fe-repeat"></i><span> Coin History</span>
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo e(route('member.my.wallet')); ?>" class="px-2">
                            <i class="fe fe-credit-card"></i><span> My Wallet</span>
                        </a>
                    </li>

                    <?php if(auth()->user()->is_refer_member): ?>
                        <li>
                            <a href="<?php echo e(route('member.add.member')); ?>" class="px-2">
                                <i class="ti ti-user-plus"></i><span> Add New Member</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if(auth()->user()->status == 1): ?>
                        <li>
                            <a href="<?php echo e(route('member.membership')); ?>" class="px-2">
                                <i class="ti ti-id-badge"></i><span> Membership</span>
                                <?php if(!auth()->user()->is_refer_member): ?>
                                    <span class="badge bg-warning ms-1" style="font-size:9px;">Pay &#8377;519</span>
                                <?php endif; ?>
                            </a>
                        </li>

                        <?php if(auth()->user()->is_refer_member): ?>
                            <li>
                                <a href="<?php echo e(route('member.my.referrals')); ?>" class="px-2">
                                    <i class="ti ti-share"></i><span> My Referrals</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('member.my.rewards')); ?>" class="px-2">
                                    <i class="ti ti-trophy"></i><span> My Rewards</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('member.gold.wallet')); ?>" class="px-2">
                                    <i class="ti ti-coin"></i><span> Gold Coin Wallet</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <li class="submenu">
                        <a href="javascript:void(0);">
                            <i class="ti ti-chart-bar"></i><span>Reports</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="<?php echo e(route('member.reports.wallet.ledger')); ?>">Wallet Ledger</a></li>
                            <?php if(auth()->user()->is_refer_member): ?>
                                <li><a href="<?php echo e(route('member.reports.income')); ?>">Income Report</a></li>
                                <li><a href="<?php echo e(route('member.reports.my.tree')); ?>">My Referral Tree</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>

                    <li>
                        <a href="<?php echo e(route('member.customer.support')); ?>" class="px-2">
                            <i class="fe fe-headphones"></i><span> Customer Support</span>
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo e(route('member.profile')); ?>" class="px-2">
                            <i class="fe fe-user"></i><span> Profile</span>
                        </a>
                    </li>

                <?php endif; ?>
            </ul>
        </div>
    </div>
</div><?php /**PATH D:\Qubeta\svrs\resources\views/admin/layout/Sidebar.blade.php ENDPATH**/ ?>