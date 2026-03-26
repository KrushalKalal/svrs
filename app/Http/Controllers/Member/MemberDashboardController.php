<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\CoinChart;
use App\Models\CoinTrade;
use App\Models\GoldCoinWallet;
use App\Models\ReferralReward;
use App\Models\Wallet;

class MemberDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $wallet = Wallet::where('user_id', $user->id)->first();

        $coinBalance = CoinTrade::where('user_id', $user->id)
            ->whereIn('type', ['buy', 'reward'])->sum('quantity')
            - CoinTrade::where('user_id', $user->id)->where('type', 'sell')->sum('quantity');

        $currentPrice = CoinChart::latest()->first()?->close_price ?? 0;
        $coinValue = $coinBalance * $currentPrice;

        $totalReferrals = $user->allDirectReferrals()->count();
        $activeReferrals = $user->active_refer_member_count;
        $referralEarnings = ReferralReward::where('earner_id', $user->id)->sum('reward_quantity');

        $goldWallet = GoldCoinWallet::where('user_id', $user->id)->first();

        return view('member.dashboard', compact(
            'user',
            'wallet',
            'coinBalance',
            'currentPrice',
            'coinValue',
            'totalReferrals',
            'activeReferrals',
            'referralEarnings',
            'goldWallet'
        ));
    }
}