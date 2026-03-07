<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <!-- Logo -->
    <div class="sidebar-logo">
        <a href="{{ route('admin.dashboard') }}" class="logo logo-normal text-center">
            <img src="{{ asset('admin/img/logo.png') }}" alt="Logo" style="height:50px; width:auto;">
        </a>
        <a href="{{ route('admin.dashboard') }}" class="logo-small">
            <img src="{{ asset('admin/img/logo-small.png') }}" alt="Logo">
        </a>
        <a href="{{ route('admin.dashboard') }}" class="dark-logo">
            <img src="{{ asset('admin/img/logo-white.jpg') }}" alt="Logo">
        </a>
    </div>
    <!-- /Logo -->
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                @can('View dashboard')
                    <li>
                        <ul class="m-0">
                            <li>
                                <a href="{{ route('admin.dashboard') }}" class="px-2">
                                    <i class="ti ti-smart-home"></i><span> Dashboard</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @canany(['View Roles', 'View Permissions'])
                    <li>
                        <ul class="m-0">
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-shield"></i><span>Roles & Permissions</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    @can('View Roles')
                                        <li><a href="{{ route('admin.roles.list') }}">Role List</a></li>
                                    @endcan
                                    @can('View Permissions')
                                        <li><a href="{{ route('admin.permissions.list') }}">Permission List</a></li>
                                    @endcan
                                </ul>
                            </li>
                        </ul>
                    </li>
                @endcanany
                @can('Member')
                    <li>
                        <ul class="m-0">
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-users"></i><span>Member</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    <li>
                                        <a href="{{ route('admin.id.activate') }}">Id Activate</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.member.list') }}">Member Lists</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.inactive.member') }}">InActive Member</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.activate.member') }}">Active Member</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                @endcan
                @canany(['Privacy Policy', 'Terms Conditions', 'Contact Setting','Deposit Setting','Withdrawal Setting'])
                    <li>
                        <ul class="m-0">
                            <li class="submenu">
                                <a href="javascript:void(0);">
                                    <i class="ti ti-settings"></i><span>Master Settings</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <ul>
                                    @can('Contact Setting')
                                        <li><a href="{{ route('admin.contact.setting') }}">Contact Setting</a></li>
                                    @endcan
                                    @can('Deposit Setting')
                                        <li>
                                            <a href="{{ route('admin.deposit.setting') }}">Deposit Setting</a>
                                        </li>
                                    @endcan
                                    @can('Withdrawal Setting')
                                        <li>
                                            <a href="{{ route('admin.withdrawal.setting') }}">Withdrawal Setting</a>
                                        </li>
                                    @endcan
                                    @can('Privacy Policy')
                                        <li><a href="{{ route('admin.privacy') }}">Privacy & Policy</a></li>
                                    @endcan
                                    @can('Terms Conditions')
                                        <li><a href="{{ route('admin.term') }}">Terms & Conditions</a></li>
                                    @endcan
                                </ul>
                            </li>
                        </ul>
                    </li>
                @endcanany
                @can('Coin Master')
                    <li>
                        <ul class="m-0">
                            <li>
                                <a href="{{ route('admin.coin.list') }}" class="px-2">
                                    <i class="ti ti-coins"></i></i><span> Coin Master</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('Profile')
                    <li>
                        <ul class="m-0">
                            <li>
                                <a href="{{ route('admin.profile') }}" class="px-2">
                                    <i class="fe fe-user"></i><span> Profile</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('Welcome Letter')
                    <li>
                        <ul class="m-0">
                            <li>
                                <a href="{{ route('admin.welcome.letter') }}" class="px-2">
                                    <i class="fe fe-mail"></i><span> Welcome Letter</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('Coin')
                    <li>
                        <ul class="m-0">
                            <li>
                                <a href="{{ route('admin.coin') }}" class="px-2">
                                    <i class="fe fe-dollar-sign"></i><span> Coin</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('Coin History')
                    <li>
                        <ul class="m-0">
                            <li>
                                <a href="{{ route('admin.coin.history') }}" class="px-2">
                                    <i class="fe fe-repeat"></i><span> Coin History</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('My Wallet')
                    <li>
                        <ul class="m-0">
                            <li>
                                <a href="{{ route('admin.my.wallet') }}" class="px-2">
                                    <i class="fe fe-credit-card"></i><span> My Wallet</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('Deposit Approval')
                    <li>
                        <ul class="m-0">
                            <li>
                                <a href="{{ route('admin.deposit.approval') }}" class="px-2">
                                    <i class="fe fe-download"></i><span> Deposit Approval</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('Withdrawal Approval')
                    <li>
                        <ul class="m-0">
                            <li>
                                <a href="{{ route('admin.withdrawal.approval') }}" class="px-2">
                                    <i class="fe fe-upload"></i><span> Withdrawal Approval</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('Customer Support')
                    <li>
                        <ul class="m-0">
                            <li>
                                <a href="{{ route('admin.customer.support') }}" class="px-2">
                                    <i class="fe fe-headphones"></i><span> Customer Support</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->
