<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CoinController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\MembershipController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ReferralController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\RewardController;
use App\Http\Controllers\Api\SupportController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\DashboardController;
use Illuminate\Support\Facades\Route;

// ============================================================
// PUBLIC
// ============================================================
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::get('/privacy-policy', [ContentController::class, 'privacyPolicy']);
Route::get('/terms', [ContentController::class, 'terms']);
Route::get('/contact-info', [ContentController::class, 'contactInfo']);
Route::get('/deposit-info', [ContentController::class, 'depositInfo']);

Route::get('/check-refer-code/{code}', [ReferralController::class, 'checkCode']);
Route::get('/check-sponsor/{code}', [AuthController::class, 'checkSponsor']);
Route::get('/register-info', [AuthController::class, 'registerInfo']);

// ============================================================
// AUTHENTICATED
// ============================================================
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
    Route::post('/password/update', [ProfileController::class, 'passwordUpdate']);
    Route::post('/bank-details', [ProfileController::class, 'bankDetails']);
    Route::get('/bank-details', [ProfileController::class, 'getBankDetails']);

    // Coin
    Route::get('/coin-chart', [CoinController::class, 'chart']);
    Route::get('/coin-info', [CoinController::class, 'info']);
    Route::post('/coin-trade', [CoinController::class, 'trade']);
    Route::get('/coin-history', [CoinController::class, 'history']);
    Route::get('/coin-balance', [CoinController::class, 'balance']);

    // Wallet
    Route::get('/wallet', [WalletController::class, 'index']);
    Route::post('/wallet/deposit', [WalletController::class, 'deposit']);
    Route::post('/wallet/withdraw', [WalletController::class, 'withdraw']);
    Route::get('/wallet/transactions', [WalletController::class, 'transactions']);

    // Support
    Route::get('/support', [SupportController::class, 'index']);
    Route::post('/support/create', [SupportController::class, 'create']);

    // Phase 2
    Route::get('/membership/status', [MembershipController::class, 'status']);
    Route::post('/membership/pay', [MembershipController::class, 'pay']);
    Route::get('/referral/info', [ReferralController::class, 'info']);
    Route::get('/referral/list', [ReferralController::class, 'list']);
    Route::get('/referral/rewards', [ReferralController::class, 'rewards']);

    // Phase 3
    Route::get('/rewards/status', [RewardController::class, 'status']);
    Route::post('/rewards/claim', [RewardController::class, 'claim']);
    Route::get('/rewards/history', [RewardController::class, 'history']);
    Route::get('/gold-wallet', [RewardController::class, 'goldWallet']);

    // Reports
    Route::get('/report/wallet-ledger', [ReportController::class, 'walletLedger']);
    Route::get('/report/income', [ReportController::class, 'incomeReport']);
    Route::get('/report/referral-tree', [ReportController::class, 'referralTree']);

    //add new member 
    Route::get('/add-member-info', [AuthController::class, 'addMemberInfo']);
    Route::post('/add-member', [AuthController::class, 'addMember']);
});