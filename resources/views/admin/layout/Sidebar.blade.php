<div class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('member.dashboard') }}"
            class="logo logo-normal text-center">
            <img src="{{ asset('admin/img/logo.png') }}" alt="Logo" style="height:50px;width:auto;">
        </a>
        <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('member.dashboard') }}"
            class="logo-small">
            <img src="{{ asset('admin/img/logo-small.png') }}" alt="Logo">
        </a>
        <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('member.dashboard') }}"
            class="dark-logo">
            <img src="{{ asset('admin/img/logo-white.jpg') }}" alt="Logo">
        </a>
    </div>

    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>

                {{-- ================================================ --}}
                {{-- ADMIN SIDEBAR --}}
                {{-- ================================================ --}}
                @if(auth()->user()->role === 'admin')

                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="px-2">
                            <i class="ti ti-smart-home"></i><span> Dashboard</span>
                        </a>
                    </li>

                    <li class="submenu">
                        <a href="javascript:void(0);">
                            <i class="ti ti-users"></i><span>Members</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('admin.id.activate') }}">ID Activate</a></li>
                            <li><a href="{{ route('admin.add.member') }}">Add New Member</a></li>
                            {{-- FIX: admin.member.list (was: member.list) --}}
                            <li><a href="{{ route('admin.member.list') }}">Member List</a></li>
                            <li><a href="{{ route('admin.activate.member') }}">Active Members</a></li>
                            <li><a href="{{ route('admin.inactive.member') }}">Inactive Members</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{ route('admin.membership.approval') }}" class="px-2">
                            <i class="ti ti-id-badge"></i><span> Membership Approval</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.reward.claims') }}" class="px-2">
                            <i class="ti ti-trophy"></i><span> Reward Claims</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.deposit.approval') }}" class="px-2">
                            <i class="fe fe-download"></i><span> Deposit Approval</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.withdrawal.approval') }}" class="px-2">
                            <i class="fe fe-upload"></i><span> Withdrawal Approval</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.coin.list') }}" class="px-2">
                            <i class="ti ti-coins"></i><span> Coin Master</span>
                        </a>
                    </li>

                    <li class="submenu">
                        <a href="javascript:void(0);">
                            <i class="ti ti-chart-bar"></i><span>Reports</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('admin.reports.financial') }}">Financial Report</a></li>
                            <li><a href="{{ route('admin.referral.reward.list') }}">Referral Rewards</a></li>
                        </ul>
                    </li>

                    <li class="submenu">
                        <a href="javascript:void(0);">
                            <i class="ti ti-settings"></i><span>Master Settings</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('admin.contact.setting') }}">Contact Setting</a></li>
                            <li><a href="{{ route('admin.deposit.setting') }}">Deposit Setting</a></li>
                            <li><a href="{{ route('admin.withdrawal.setting') }}">Withdrawal Setting</a></li>
                            <li><a href="{{ route('admin.privacy') }}">Privacy Policy</a></li>
                            <li><a href="{{ route('admin.term') }}">Terms & Conditions</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{ route('admin.customer.support') }}" class="px-2">
                            <i class="fe fe-headphones"></i><span> Customer Support</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.profile') }}" class="px-2">
                            <i class="fe fe-user"></i><span> Profile</span>
                        </a>
                    </li>

                    {{-- ================================================ --}}
                    {{-- MEMBER SIDEBAR --}}
                    {{-- ================================================ --}}
                @elseif(auth()->user()->role === 'member')

                    <li>
                        <a href="{{ route('member.dashboard') }}" class="px-2">
                            <i class="ti ti-smart-home"></i><span> Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('member.coin') }}" class="px-2">
                            <i class="fe fe-dollar-sign"></i><span> Coin Chart</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('member.coin.history') }}" class="px-2">
                            <i class="fe fe-repeat"></i><span> Coin History</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('member.my.wallet') }}" class="px-2">
                            <i class="fe fe-credit-card"></i><span> My Wallet</span>
                        </a>
                    </li>

                    @if(auth()->user()->is_refer_member)
                        <li>
                            <a href="{{ route('member.add.member') }}" class="px-2">
                                <i class="ti ti-user-plus"></i><span> Add New Member</span>
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->status == 1)
                        <li>
                            <a href="{{ route('member.membership') }}" class="px-2">
                                <i class="ti ti-id-badge"></i><span> Membership</span>
                                @if(!auth()->user()->is_refer_member)
                                    <span class="badge bg-warning ms-1" style="font-size:9px;">Pay &#8377;519</span>
                                @endif
                            </a>
                        </li>

                        @if(auth()->user()->is_refer_member)
                            <li>
                                <a href="{{ route('member.my.referrals') }}" class="px-2">
                                    <i class="ti ti-share"></i><span> My Referrals</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('member.my.rewards') }}" class="px-2">
                                    <i class="ti ti-trophy"></i><span> My Rewards</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('member.gold.wallet') }}" class="px-2">
                                    <i class="ti ti-coin"></i><span> Gold Coin Wallet</span>
                                </a>
                            </li>
                        @endif
                    @endif

                    <li class="submenu">
                        <a href="javascript:void(0);">
                            <i class="ti ti-chart-bar"></i><span>Reports</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <ul>
                            <li><a href="{{ route('member.reports.wallet.ledger') }}">Wallet Ledger</a></li>
                            @if(auth()->user()->is_refer_member)
                                <li><a href="{{ route('member.reports.income') }}">Income Report</a></li>
                                <li><a href="{{ route('member.reports.my.tree') }}">My Referral Tree</a></li>
                            @endif
                        </ul>
                    </li>

                    <li>
                        <a href="{{ route('member.customer.support') }}" class="px-2">
                            <i class="fe fe-headphones"></i><span> Customer Support</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('member.profile') }}" class="px-2">
                            <i class="fe fe-user"></i><span> Profile</span>
                        </a>
                    </li>

                @endif
            </ul>
        </div>
    </div>
</div>