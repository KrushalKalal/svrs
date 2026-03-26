<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoinTrade;
use App\Models\GoldCoinTransaction;
use App\Models\MemberMembership;
use App\Models\ReferralReward;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function financialReport(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to = $request->to ?? now()->toDateString();

        $totalDeposits = WalletTransaction::where('type', 'credit')
            ->where('status', 1)->whereNotNull('invoice')
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->sum('amount');

        $totalWithdrawals = WalletTransaction::where('type', 'debit')
            ->where('status', 1)->where('remark', 'Withdrawal Approved')
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->sum('amount');

        $totalMembershipFees = MemberMembership::where('status', 1)
            ->whereBetween('approved_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->sum('amount');

        $totalReferralRewardCoins = ReferralReward::where('status', 1)
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->sum('reward_quantity');

        $totalGoldCoinsApproved = GoldCoinTransaction::where('type', 'credit')
            ->where('status', 1)
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->sum('amount');

        $totalMembers = User::where('role', 'member')->count();
        $activeMembers = User::where('role', 'member')->where('status', 1)->count();
        $referMembers = User::where('role', 'member')->where('is_refer_member', 1)->count();
        $pendingActivations = User::where('role', 'member')->where('status', 2)->count();

        // Member list for report navigation
        $members = User::where('role', 'member')
            ->with('wallet')
            ->latest()
            ->get();

        return view('admin.reports.financial', compact(
            'from',
            'to',
            'totalDeposits',
            'totalWithdrawals',
            'totalMembershipFees',
            'totalReferralRewardCoins',
            'totalGoldCoinsApproved',
            'totalMembers',
            'activeMembers',
            'referMembers',
            'pendingActivations',
            'members'
        ));
    }

    public function memberWalletLedger(Request $request, $id)
    {
        $member = User::findOrFail($id);

        $walletTransactions = WalletTransaction::where('user_id', $member->id)->latest()->get();
        $coinTrades = CoinTrade::with('coin')->where('user_id', $member->id)->latest()->get();
        $referralRewards = ReferralReward::with('fromUser', 'coin')->where('earner_id', $member->id)->latest()->get();
        $goldTransactions = GoldCoinTransaction::where('user_id', $member->id)->latest()->get();
        $membership = MemberMembership::where('user_id', $member->id)->first();

        return view('admin.reports.wallet_ledger', compact(
            'member',
            'walletTransactions',
            'coinTrades',
            'referralRewards',
            'goldTransactions',
            'membership'
        ));
    }

    public function referralTree($id)
    {
        $member = User::findOrFail($id);

        $level1 = User::where('sponsor_id', $member->member_code)->with('membership')->get();
        $level1Codes = $level1->pluck('member_code');
        $level2 = User::whereIn('sponsor_id', $level1Codes)->with('membership')->get();
        $level2Codes = $level2->pluck('member_code');
        $level3 = User::whereIn('sponsor_id', $level2Codes)->with('membership')->get();

        return view('admin.reports.referral_tree', compact('member', 'level1', 'level2', 'level3'));
    }

    public function memberIncomeReport($id)
    {
        $member = User::findOrFail($id);

        $level1Rewards = ReferralReward::with('fromUser', 'coin')
            ->where('earner_id', $member->id)->where('level', 1)->latest()->get();
        $level2Rewards = ReferralReward::with('fromUser', 'coin')
            ->where('earner_id', $member->id)->where('level', 2)->latest()->get();
        $level3Rewards = ReferralReward::with('fromUser', 'coin')
            ->where('earner_id', $member->id)->where('level', 3)->latest()->get();

        $totalLevel1 = $level1Rewards->sum('reward_quantity');
        $totalLevel2 = $level2Rewards->sum('reward_quantity');
        $totalLevel3 = $level3Rewards->sum('reward_quantity');
        $totalCoins = $totalLevel1 + $totalLevel2 + $totalLevel3;

        $goldClaims = \App\Models\UserRewardClaim::with('tier')
            ->where('user_id', $member->id)->latest()->get();

        return view('admin.reports.income_report', compact(
            'member',
            'level1Rewards',
            'level2Rewards',
            'level3Rewards',
            'totalLevel1',
            'totalLevel2',
            'totalLevel3',
            'totalCoins',
            'goldClaims'
        ));
    }
}