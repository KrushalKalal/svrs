<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoinChart;
use App\Models\CoinTrade;
use App\Models\GoldCoinTransaction;
use App\Models\GoldCoinWallet;
use App\Models\MemberMembership;
use App\Models\ReferralReward;
use App\Models\User;
use App\Models\UserRewardClaim;
use App\Models\Wallet;
use App\Models\WalletTransaction;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $admin = auth()->user();

        // ── Admin's own accounts ──────────────────────────────
        $adminWallet = Wallet::where('user_id', $admin->id)->first();
        $adminCoinBal = CoinTrade::where('user_id', $admin->id)
            ->whereIn('type', ['buy', 'reward'])->sum('quantity')
            - CoinTrade::where('user_id', $admin->id)
                ->where('type', 'sell')->sum('quantity');
        $adminGoldWal = GoldCoinWallet::where('user_id', $admin->id)->first();
        $adminReferEarn = ReferralReward::where('earner_id', $admin->id)->sum('reward_quantity');

        // ── Platform member stats ─────────────────────────────
        $totalMembers = User::where('role', 'member')->count();
        $activeMembers = User::where('role', 'member')->where('status', 1)->count();
        $pendingMembers = User::where('role', 'member')->where('status', 2)->count();
        $inactiveMembers = User::where('role', 'member')->where('status', 0)->count();
        $referMembers = User::where('role', 'member')->where('is_refer_member', 1)->count();

        // ── Financial summary ─────────────────────────────────
        $totalDeposits = WalletTransaction::where('type', 'credit')
            ->where('status', 1)->whereNotNull('invoice')->sum('amount');
        $totalWithdrawals = WalletTransaction::where('type', 'debit')
            ->where('status', 1)->sum('amount');
        $totalMembership = MemberMembership::where('status', 1)->sum('amount');

        // ── Coin summary ──────────────────────────────────────
        $currentPrice = CoinChart::latest()->first()?->close_price ?? 0;
        $totalSVRS = CoinTrade::whereIn('type', ['buy', 'reward'])->sum('quantity')
            - CoinTrade::where('type', 'sell')->sum('quantity');
        $totalReferralCoins = ReferralReward::where('status', 1)->sum('reward_quantity');
        $totalGoldCoins = GoldCoinTransaction::where('type', 'credit')->where('status', 1)->sum('amount');

        // ── Pending approvals (action required) ───────────────
        $pendingDeposits = WalletTransaction::where('type', 'credit')->where('status', 2)->count();
        $pendingWithdrawals = WalletTransaction::where('type', 'debit')->where('status', 2)->count();
        $pendingMembership = MemberMembership::where('status', 2)->count();
        $pendingRewardClaims = UserRewardClaim::where('status', 2)->count();

        // ── Recent activity ───────────────────────────────────
        $recentMembers = User::where('role', 'member')->latest()->take(5)->get();
        $recentDeposits = WalletTransaction::with('user')
            ->where('type', 'credit')->where('status', 2)
            ->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'admin',
            'adminWallet',
            'adminCoinBal',
            'adminGoldWal',
            'adminReferEarn',
            'totalMembers',
            'activeMembers',
            'pendingMembers',
            'inactiveMembers',
            'referMembers',
            'totalDeposits',
            'totalWithdrawals',
            'totalMembership',
            'currentPrice',
            'totalSVRS',
            'totalReferralCoins',
            'totalGoldCoins',
            'pendingDeposits',
            'pendingWithdrawals',
            'pendingMembership',
            'pendingRewardClaims',
            'recentMembers',
            'recentDeposits'
        ));
    }
}