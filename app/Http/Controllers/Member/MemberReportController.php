<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\CoinTrade;
use App\Models\GoldCoinTransaction;
use App\Models\MemberMembership;
use App\Models\ReferralReward;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\Wallet;

class MemberReportController extends Controller
{
    public function walletLedger()
    {
        $user = auth()->user();
        $wallet = Wallet::where('user_id', $user->id)->first();

        $walletTransactions = WalletTransaction::where('user_id', $user->id)
            ->latest()->get();

        $coinTrades = CoinTrade::with('coin')
            ->where('user_id', $user->id)
            ->latest()->get();

        $referralRewards = ReferralReward::with('fromUser')
            ->where('earner_id', $user->id)
            ->latest()->get();

        $goldTransactions = GoldCoinTransaction::where('user_id', $user->id)
            ->latest()->get();

        return view('member.reports.wallet_ledger', compact(
            'user',
            'wallet',
            'walletTransactions',
            'coinTrades',
            'referralRewards',
            'goldTransactions'
        ));
    }

    public function incomeReport()
    {
        $user = auth()->user();

        $level1Rewards = ReferralReward::with('fromUser', 'coin')
            ->where('earner_id', $user->id)->where('level', 1)
            ->latest()->get();

        $level2Rewards = ReferralReward::with('fromUser', 'coin')
            ->where('earner_id', $user->id)->where('level', 2)
            ->latest()->get();

        $level3Rewards = ReferralReward::with('fromUser', 'coin')
            ->where('earner_id', $user->id)->where('level', 3)
            ->latest()->get();

        $totalLevel1 = $level1Rewards->sum('reward_quantity');
        $totalLevel2 = $level2Rewards->sum('reward_quantity');
        $totalLevel3 = $level3Rewards->sum('reward_quantity');
        $totalCoins = $totalLevel1 + $totalLevel2 + $totalLevel3;

        return view('member.reports.income_report', compact(
            'user',
            'level1Rewards',
            'level2Rewards',
            'level3Rewards',
            'totalLevel1',
            'totalLevel2',
            'totalLevel3',
            'totalCoins'
        ));
    }

    public function referralTree()
    {
        $user = auth()->user();

        $level1 = User::where('sponsor_id', $user->member_code)
            ->with('membership')->get();

        $level1Codes = $level1->pluck('member_code');
        $level2 = User::whereIn('sponsor_id', $level1Codes)
            ->with('membership')->get();

        $level2Codes = $level2->pluck('member_code');
        $level3 = User::whereIn('sponsor_id', $level2Codes)
            ->with('membership')->get();

        return view('member.reports.referral_tree', compact(
            'user',
            'level1',
            'level2',
            'level3'
        ));
    }
}