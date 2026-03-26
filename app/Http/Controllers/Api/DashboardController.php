<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CoinChart;
use App\Models\CoinTrade;
use App\Models\GoldCoinWallet;
use App\Models\MemberMembership;
use App\Models\ReferralReward;
use App\Models\RewardTier;
use App\Models\UserRewardClaim;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Wallet
        $wallet = Wallet::where('user_id', $user->id)->first();
        $walletBalance = $wallet?->balance ?? 0;

        // Coin
        $bought = CoinTrade::where('user_id', $user->id)->whereIn('type', ['buy', 'reward'])->sum('quantity');
        $sold = CoinTrade::where('user_id', $user->id)->where('type', 'sell')->sum('quantity');
        $coinBalance = $bought - $sold;
        $currentPrice = CoinChart::latest()->value('close_price') ?? 0;
        $coinValue = $coinBalance * $currentPrice;

        // Referrals
        $totalReferrals = $user->allDirectReferrals()->count();
        $activeReferrals = $user->active_refer_member_count;
        $referralEarnings = ReferralReward::where('earner_id', $user->id)->sum('reward_quantity');

        // Gold Coin Wallet
        $goldWallet = GoldCoinWallet::where('user_id', $user->id)->first();

        // Membership
        $membership = MemberMembership::where('user_id', $user->id)->first();

        // Milestone Progress
        // Auto-detect FK column: 'tier_id' or 'reward_tier_id' — whichever exists in DB
        $milestones = [];
        if ($user->is_refer_member) {
            try {
                $tiers = RewardTier::orderBy('required_referrals')->get();
                $claimsTable = (new UserRewardClaim)->getTable();
                $columns = Schema::getColumnListing($claimsTable);
                $tierFkColumn = in_array('reward_tier_id', $columns) ? 'reward_tier_id' : 'tier_id';

                foreach ($tiers as $tier) {
                    $pct = $tier->required_referrals > 0
                        ? min(100, round(($activeReferrals / $tier->required_referrals) * 100))
                        : 0;

                    $claimed = UserRewardClaim::where('user_id', $user->id)
                        ->where($tierFkColumn, $tier->id)
                        ->where('status', 1)
                        ->exists();

                    $milestones[] = [
                        'tier_id' => $tier->id,
                        'name' => $tier->name,
                        'g_coins' => $tier->g_coins,
                        'required' => $tier->required_referrals,
                        'current' => $activeReferrals,
                        'progress' => $pct,
                        'achieved' => $activeReferrals >= $tier->required_referrals,
                        'claimed' => $claimed,
                    ];
                }
            } catch (\Exception $e) {
                $milestones = []; // dashboard still loads even if milestones fail
            }
        }

        return response()->json([
            'status' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->full_name,
                'member_code' => $user->member_code,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'profile_image' => $user->profile_image ? asset($user->profile_image) : null,
                'status' => $user->status,
                'is_refer_member' => (bool) $user->is_refer_member,
            ],
            'wallet' => [
                'balance' => $walletBalance,
            ],
            'coin' => [
                'balance' => round($coinBalance, 8),
                'current_price' => $currentPrice,
                'inr_value' => round($coinValue, 2),
            ],
            'referrals' => [
                'total' => $totalReferrals,
                'active' => $activeReferrals,
                'earnings_coins' => round($referralEarnings, 8),
            ],
            'gold_wallet' => $user->is_refer_member ? [
                'balance' => $goldWallet?->balance ?? 0,
                'inr_value' => round(($goldWallet?->balance ?? 0) / 10, 2),
            ] : null,
            'membership' => $membership ? [
                'status' => $membership->status,
                'status_label' => $membership->status_label,
                'refer_code' => $membership->refer_code,
                'refer_link' => $membership->refer_link,
            ] : null,
            'milestones' => $milestones,
            'features' => [
                'can_add_member' => (bool) $user->is_refer_member,
                'can_refer' => (bool) $user->is_refer_member,
                'can_view_rewards' => (bool) $user->is_refer_member,
                'can_view_gold' => (bool) $user->is_refer_member,
                'can_view_income' => (bool) $user->is_refer_member,
                'show_membership_badge' => !$user->is_refer_member && $user->status == 1,
            ],
        ]);
    }
}