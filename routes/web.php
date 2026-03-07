<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CoinController;
use App\Http\Controllers\Admin\ContactSettingController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CustomerPageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FrontSettingController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\WalletController;
use App\Http\Controllers\AuthController as FrontAuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/clear', function () {
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return "Cache cleared!";
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'loginSubmit'])->name('login.submit');
    Route::get('forgot-password', [AdminAuthController::class, 'show_forgot_password_Form'])->name('forgot.password');
    Route::post('forgot-password-check', [AdminAuthController::class, 'forgot_password_check'])->name('forgot.password.check');
    Route::get('otp-verification/{id}', [AdminAuthController::class, 'otp_Verification_Form'])->name('otp.verification');
    Route::post('verify-otp', [AdminAuthController::class, 'verifyOtp'])->name('verify.otp');
    Route::post('resend-otp', [AdminAuthController::class, 'resendOtp'])->name('resend.otp');
    Route::get('reset-password/{id}', [AdminAuthController::class, 'reset_password'])->name('reset.password');
    Route::post('reset-password-submit', [AdminAuthController::class, 'reset_password_submit'])->name('reset.password.submit');

    Route::middleware(['auth:admin'])->group(function () {

        Route::get('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('profile', [AdminAuthController::class, 'profile'])->name('profile');
        Route::post('profile-update', [AdminAuthController::class, 'profile_update'])->name('profile.update');
        Route::post('password-update', [AdminAuthController::class, 'password_update'])->name('password.update');
        Route::post('bank-details', [AdminAuthController::class, 'bank_details'])->name('bank.details');

        Route::middleware(['permission:View dashboard'])->group(function () {
            Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
            Route::get('dashboard-leads-filter', [DashboardController::class, 'dashboard_leads_filter'])->name('dashboard.leads.filter');
        });

        Route::middleware(['permission:Member'])->group(function () {
            Route::get('id-activate', [MemberController::class, 'id_activate'])->name('id.activate');
            Route::get('member-list', [MemberController::class, 'member_list'])->name('member.list');
            Route::get('activate-member', [MemberController::class, 'activate_member'])->name('activate.member');
            Route::get('inactive-member', [MemberController::class, 'inactive_member'])->name('inactive.member');
            Route::post('member-update-status', [MemberController::class, 'member_update_status'])->name('member.update.status');
            Route::get('member-bankdetails/{id}', [MemberController::class, 'member_bankdetails'])->name('member.bankdetails');
        });

        Route::middleware(['permission:View Roles'])->group(function () {
            Route::get('/roles', [RoleController::class, 'role_list'])->name('roles.list');
        });
        Route::middleware(['permission:Edit Roles'])->group(function () {
            Route::get('/roles-edit/{id}', [RoleController::class, 'role_edit'])->name('roles.edit');
            Route::post('/roles-update', [RoleController::class, 'role_update'])->name('roles.update');
        });

        Route::middleware(['permission:View Permissions'])->group(function () {
            Route::get('/permissions', [PermissionController::class, 'permissions_list'])->name('permissions.list');
        });

        Route::middleware(['permission:Create Permissions'])->group(function () {
            Route::get('/create-permissions', [PermissionController::class, 'permissions_create'])->name('permissions.create');
            Route::post('/permissions-store', [PermissionController::class, 'permissions_store'])->name('permissions.store');
        });

        Route::middleware(['permission:Edit Permissions'])->group(function () {
            Route::get('/permissions-edit/{id}', [PermissionController::class, 'permissions_edit'])->name('permissions.edit');
            Route::post('/permissions-update', [PermissionController::class, 'permissions_update'])->name('permissions.update');
        });

        Route::middleware(['permission:Privacy Policy'])->group(function () {
            Route::get('/privacy', [PolicyController::class, 'privacy'])->name('privacy');
            Route::post('/update-privacy', [PolicyController::class, 'update_privacy'])->name('update.privacy');
        });

        Route::middleware(['permission:Terms Conditions'])->group(function () {
            Route::get('/term', [PolicyController::class, 'term'])->name('term');
            Route::post('/update-term', [PolicyController::class, 'update_term'])->name('update.term');
        });

        Route::middleware(['permission:Contact Setting'])->group(function () {
            Route::get('/contact-setting', [ContactSettingController::class, 'contact_setting'])->name('contact.setting');
            Route::post('/contact-setting-update', [ContactSettingController::class, 'contact_setting_update'])->name('contact.setting.update');
        });

        Route::middleware(['permission:Deposit Setting'])->group(function () {
            Route::get('/deposit-setting', [ContactSettingController::class, 'deposit_setting'])->name('deposit.setting');
            Route::post('/deposit-setting-update', [ContactSettingController::class, 'deposit_setting_update'])->name('deposit.setting.update');
        });

        Route::middleware(['permission:Withdrawal Setting'])->group(function () {
            Route::get('/withdrawal-setting', [ContactSettingController::class, 'withdrawal_setting'])->name('withdrawal.setting');
            Route::post('/withdrawal-setting-update', [ContactSettingController::class, 'withdrawal_setting_update'])->name('withdrawal.setting.update');
        });

        Route::middleware(['permission:Welcome Letter'])->group(function () {
            Route::get('/welcome-letter', [CustomerPageController::class, 'welcome_letter'])->name('welcome.letter');
        });

        Route::middleware(['permission:Coin'])->group(function () {
            Route::get('/coin', [CoinController::class, 'coin'])->name('coin');
            Route::get('/coin-chart', [CoinController::class, 'coin_chart'])->name('coin.chart');
            Route::post('/coin-trade', [CoinController::class, 'coin_trade'])->name('coin.trade');
        });

        Route::middleware(['permission:Coin History'])->group(function () {
            Route::get('/coin-history', [CoinController::class, 'coin_history'])->name('coin.history');
        });

        Route::middleware(['permission:My Wallet'])->group(function () {
            Route::get('/my-wallet', [WalletController::class, 'my_wallet'])->name('my.wallet');
            Route::post('/wallet-add-money', [WalletController::class, 'wallet_addMoney'])->name('wallet.add.money');
            Route::post('/wallet-withdraw-request', [WalletController::class, 'withdrawRequest'])->name('wallet.withdraw.request');
        });

        Route::middleware(['permission:Deposit Approval'])->group(function () {
            Route::get('/deposit-approval', [WalletController::class, 'deposit_approval'])->name('deposit.approval');
            Route::post('/deposit-change-status', [WalletController::class, 'deposit_change_status'])->name('deposit.change.status');
        });

        Route::middleware(['permission:Withdrawal Approval'])->group(function () {
            Route::get('/withdrawal-approval', [WalletController::class, 'withdrawal_approval'])->name('withdrawal.approval');
            Route::post('/withdrawal-change-status', [WalletController::class, 'withdrawal_change_status'])->name('withdrawal.change.status');
        });

        Route::middleware(['permission:Customer Support'])->group(function () {
            Route::get('/customer-support', [CustomerPageController::class, 'customer_support'])->name('customer.support');
            Route::post('/customer-support-store', [CustomerPageController::class, 'customer_support_store'])->name('customer.support.store');
            Route::post('/customer-support-reply', [CustomerPageController::class, 'customer_support_reply'])->name('customer.support.reply');
        });

        Route::middleware(['permission:Coin Master'])->group(function () {
            Route::get('/coin-list', [CoinController::class, 'coin_list'])->name('coin.list');
            Route::get('/create-coin', [CoinController::class, 'create_coin'])->name('create.coin');
            Route::post('/coin-store', [CoinController::class, 'coin_store'])->name('coin.store');
            Route::get('/coin-edit/{id}', [CoinController::class, 'coin_edit'])->name('coin.edit');
            Route::post('/coin-update', [CoinController::class, 'coin_update'])->name('coin.update');
        });
    });
});



Route::get('/sign-up', [FrontAuthController::class, 'signupForm'])->name('front.sign.up');
Route::post('/check-sponsor', [FrontAuthController::class, 'checkSponsor'])->name('front.check.sponsor');
Route::post('/register-user', [FrontAuthController::class, 'registerUser'])->name('front.register.user');

Route::get('/', [HomeController::class, 'index'])->name('front.index');;

Route::post('/contact-submit', [HomeController::class, 'contactSubmit'])->name('front.contact.submit');
Route::post('/contact-inquery', [HomeController::class, 'contact_inquery'])->name('front.contact.inquery');
Route::post('/newsletter-submit', [HomeController::class, 'newsletter_submit'])->name('front.newsletter.submit');

Route::get('/privacy-policy', [HomeController::class, 'privacy_policy'])->name('front.privacy.policy');
Route::get('/terms-conditions', [HomeController::class, 'terms_conditions'])->name('front.terms.conditions');
