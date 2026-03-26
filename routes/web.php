<?php

use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CoinController;
use App\Http\Controllers\Admin\ContactSettingController;
use App\Http\Controllers\Admin\CustomerPageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\MembershipApprovalController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\Admin\RewardClaimController;
use App\Http\Controllers\Admin\WalletController;
use App\Http\Controllers\AuthController as FrontAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Member\MemberDashboardController;
use App\Http\Controllers\Member\MemberMembershipController;
use App\Http\Controllers\Member\MemberReportController;
use App\Http\Controllers\Member\MemberRewardController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// ============================================================
// UTILITY
// ============================================================
Route::get('/clear', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return 'Cache cleared!';
});

// ============================================================
// PUBLIC FRONTEND
// ============================================================
Route::get('/', [HomeController::class, 'index'])->name('front.index');
Route::get('/privacy-policy', [HomeController::class, 'privacy_policy'])->name('front.privacy.policy');
Route::get('/terms-conditions', [HomeController::class, 'terms_conditions'])->name('front.terms.conditions');

Route::get('/sign-up', [FrontAuthController::class, 'signupForm'])->name('front.sign.up');
Route::post('/check-sponsor', [FrontAuthController::class, 'checkSponsor'])->name('front.check.sponsor');
Route::post('/register-user', [FrontAuthController::class, 'registerUser'])->name('front.register.user');

// ============================================================
// ADMIN PANEL — domain/admin
// ============================================================
Route::prefix('admin')->name('admin.')->group(function () {

    // Auth (no middleware)
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'loginSubmit'])->name('login.submit');
    Route::get('forgot-password', [AdminAuthController::class, 'show_forgot_password_Form'])->name('forgot.password');
    Route::post('forgot-password-check', [AdminAuthController::class, 'forgot_password_check'])->name('forgot.password.check');
    Route::get('otp-verification/{id}', [AdminAuthController::class, 'otp_Verification_Form'])->name('otp.verification');
    Route::post('verify-otp', [AdminAuthController::class, 'verifyOtp'])->name('verify.otp');
    Route::post('resend-otp', [AdminAuthController::class, 'resendOtp'])->name('resend.otp');
    Route::get('reset-password/{id}', [AdminAuthController::class, 'reset_password'])->name('reset.password');
    Route::post('reset-password-submit', [AdminAuthController::class, 'reset_password_submit'])->name('reset.password.submit');

    // Admin authenticated
    Route::middleware(['auth:admin', 'role:admin'])->group(function () {

        Route::get('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('profile', [AdminAuthController::class, 'profile'])->name('profile');
        Route::post('profile-update', [AdminAuthController::class, 'profile_update'])->name('profile.update');
        Route::post('password-update', [AdminAuthController::class, 'password_update'])->name('password.update');
        Route::post('bank-details', [AdminAuthController::class, 'bank_details'])->name('bank.details');

        Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

        // Members
        Route::get('id-activate', [MemberController::class, 'id_activate'])->name('id.activate');
        Route::get('member-list', [MemberController::class, 'member_list'])->name('member.list');
        Route::get('activate-member', [MemberController::class, 'activate_member'])->name('activate.member');
        Route::get('inactive-member', [MemberController::class, 'inactive_member'])->name('inactive.member');
        Route::post('member-update-status', [MemberController::class, 'member_update_status'])->name('member.update.status');
        Route::get('member-bankdetails/{id}', [MemberController::class, 'member_bankdetails'])->name('member.bankdetails');
        Route::get('add-member', [MemberController::class, 'add_member_form'])->name('add.member');
        Route::post('add-member-store', [MemberController::class, 'add_member_store'])->name('add.member.store');

        // Coin
        Route::get('coin', [CoinController::class, 'coin'])->name('coin');
        Route::get('coin-chart', [CoinController::class, 'coin_chart'])->name('coin.chart');
        Route::post('coin-trade', [CoinController::class, 'coin_trade'])->name('coin.trade');
        Route::get('coin-history', [CoinController::class, 'coin_history'])->name('coin.history');
        Route::get('coin-list', [CoinController::class, 'coin_list'])->name('coin.list');
        Route::get('create-coin', [CoinController::class, 'create_coin'])->name('create.coin');
        Route::post('coin-store', [CoinController::class, 'coin_store'])->name('coin.store');
        Route::get('coin-edit/{id}', [CoinController::class, 'coin_edit'])->name('coin.edit');
        Route::post('coin-update', [CoinController::class, 'coin_update'])->name('coin.update');

        // Wallet
        Route::get('my-wallet', [WalletController::class, 'my_wallet'])->name('my.wallet');
        Route::post('wallet-add-money', [WalletController::class, 'wallet_addMoney'])->name('wallet.add.money');
        Route::post('wallet-withdraw-request', [WalletController::class, 'withdrawRequest'])->name('wallet.withdraw.request');
        Route::get('deposit-approval', [WalletController::class, 'deposit_approval'])->name('deposit.approval');
        Route::post('deposit-change-status', [WalletController::class, 'deposit_change_status'])->name('deposit.change.status');
        Route::get('withdrawal-approval', [WalletController::class, 'withdrawal_approval'])->name('withdrawal.approval');
        Route::post('withdrawal-change-status', [WalletController::class, 'withdrawal_change_status'])->name('withdrawal.change.status');

        // Settings
        Route::get('contact-setting', [ContactSettingController::class, 'contact_setting'])->name('contact.setting');
        Route::post('contact-setting-update', [ContactSettingController::class, 'contact_setting_update'])->name('contact.setting.update');
        Route::get('deposit-setting', [ContactSettingController::class, 'deposit_setting'])->name('deposit.setting');
        Route::post('deposit-setting-update', [ContactSettingController::class, 'deposit_setting_update'])->name('deposit.setting.update');
        Route::get('withdrawal-setting', [ContactSettingController::class, 'withdrawal_setting'])->name('withdrawal.setting');
        Route::post('withdrawal-setting-update', [ContactSettingController::class, 'withdrawal_setting_update'])->name('withdrawal.setting.update');

        // Policy
        Route::get('privacy', [PolicyController::class, 'privacy'])->name('privacy');
        Route::post('update-privacy', [PolicyController::class, 'update_privacy'])->name('update.privacy');
        Route::get('term', [PolicyController::class, 'term'])->name('term');
        Route::post('update-term', [PolicyController::class, 'update_term'])->name('update.term');

        // Customer Support
        Route::get('customer-support', [CustomerPageController::class, 'customer_support'])->name('customer.support');
        Route::post('customer-support-store', [CustomerPageController::class, 'customer_support_store'])->name('customer.support.store');
        Route::post('customer-support-reply', [CustomerPageController::class, 'customer_support_reply'])->name('customer.support.reply');
        Route::get('welcome-letter', [CustomerPageController::class, 'welcome_letter'])->name('welcome.letter');

        // Phase 2 — Membership
        Route::get('membership-approval', [MembershipApprovalController::class, 'index'])->name('membership.approval');
        Route::post('membership-change-status', [MembershipApprovalController::class, 'changeStatus'])->name('membership.change.status');
        Route::get('referral-reward-list', [MembershipApprovalController::class, 'rewardList'])->name('referral.reward.list');

        // Phase 3 — Reward Claims
        Route::get('reward-claims', [RewardClaimController::class, 'index'])->name('reward.claims');
        Route::post('reward-claim-status', [RewardClaimController::class, 'changeStatus'])->name('reward.claim.status');

        // Reports
        Route::get('reports/financial', [AdminReportController::class, 'financialReport'])->name('reports.financial');
        Route::get('reports/wallet-ledger/{id}', [AdminReportController::class, 'memberWalletLedger'])->name('reports.wallet.ledger');
        Route::get('reports/referral-tree/{id}', [AdminReportController::class, 'referralTree'])->name('reports.referral.tree');
        Route::get('reports/income/{id}', [AdminReportController::class, 'memberIncomeReport'])->name('reports.income');
    });
});

// ============================================================
// MEMBER PANEL — domain/member
// ============================================================
Route::prefix('member')->name('member.')->group(function () {

    // Member login — shared with admin login page, same guard
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');

    Route::middleware(['auth:admin', 'role:member'])->group(function () {

        Route::get('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');

        // Add Member
        Route::get('add-member', [MemberController::class, 'add_member_form'])->name('add.member');
        Route::post('add-member-store', [MemberController::class, 'add_member_store'])->name('add.member.store');

        // Coin
        Route::get('coin', [CoinController::class, 'coin'])->name('coin');
        Route::get('coin-chart', [CoinController::class, 'coin_chart'])->name('coin.chart');
        Route::post('coin-trade', [CoinController::class, 'coin_trade'])->name('coin.trade');
        Route::get('coin-history', [CoinController::class, 'coin_history'])->name('coin.history');

        // Wallet
        Route::get('my-wallet', [WalletController::class, 'my_wallet'])->name('my.wallet');
        Route::post('wallet-add-money', [WalletController::class, 'wallet_addMoney'])->name('wallet.add.money');
        Route::post('wallet-withdraw-request', [WalletController::class, 'withdrawRequest'])->name('wallet.withdraw.request');

        // Profile
        Route::get('profile', [AdminAuthController::class, 'profile'])->name('profile');
        Route::post('profile-update', [AdminAuthController::class, 'profile_update'])->name('profile.update');
        Route::post('password-update', [AdminAuthController::class, 'password_update'])->name('password.update');
        Route::post('bank-details', [AdminAuthController::class, 'bank_details'])->name('bank.details');

        // Customer Support
        Route::get('customer-support', [CustomerPageController::class, 'customer_support'])->name('customer.support');
        Route::post('customer-support-store', [CustomerPageController::class, 'customer_support_store'])->name('customer.support.store');
        Route::get('welcome-letter', [CustomerPageController::class, 'welcome_letter'])->name('welcome.letter');

        // Phase 2
        Route::get('membership', [MemberMembershipController::class, 'index'])->name('membership');
        Route::post('membership-pay', [MemberMembershipController::class, 'pay'])->name('membership.pay');
        Route::get('my-referrals', [MemberMembershipController::class, 'referrals'])->name('my.referrals');

        // Phase 3
        Route::get('my-rewards', [MemberRewardController::class, 'index'])->name('my.rewards');
        Route::post('claim-reward', [MemberRewardController::class, 'claim'])->name('claim.reward');
        Route::get('gold-wallet', [MemberRewardController::class, 'goldWallet'])->name('gold.wallet');

        // Reports
        Route::get('reports/wallet-ledger', [MemberReportController::class, 'walletLedger'])->name('reports.wallet.ledger');
        Route::get('reports/income', [MemberReportController::class, 'incomeReport'])->name('reports.income');
        Route::get('reports/my-tree', [MemberReportController::class, 'referralTree'])->name('reports.my.tree');
    });
});