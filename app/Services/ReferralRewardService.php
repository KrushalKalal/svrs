<?php

namespace App\Services;

use App\Models\CoinTrade;
use App\Models\ReferralReward;
use App\Models\User;

class ReferralRewardService
{
    // Level => percentage
    const LEVELS = [
        1 => 0.5,
        2 => 0.05,
        3 => 0.01,
    ];

    /**
     * Process referral rewards for a newly eligible member.
     *
     * Called from:
     * 1. MemberController::member_update_status (on activation) — if is_refer_member=1
     * 2. MembershipApprovalController::changeStatus (on ₹519 approval) — if status=1
     * 3. CoinController::coin_trade (on every buy) — passing new qty
     *
     * @param User $member — the member who triggered the reward
     * @param float|null $quantity — if null, use total buy qty; if passed, use this qty (top-up)
     */
    public function processRewards(User $member, float $quantity = null): void
    {
        // Both conditions must be true
        if ($member->status != 1 || $member->is_refer_member != 1) {
            return;
        }

        // Get quantity
        if ($quantity === null) {
            $quantity = CoinTrade::where('user_id', $member->id)
                ->where('type', 'buy')
                ->sum('quantity');
        }

        if ($quantity <= 0) {
            return;
        }

        $coin = \App\Models\Coin::first();
        if (!$coin)
            return;

        // Walk up sponsor chain — max 3 levels
        $current = $member;

        for ($level = 1; $level <= 3; $level++) {

            // Find sponsor of current
            if (empty($current->sponsor_id)) {
                break; // No sponsor — stop chain
            }

            if ($current->sponsor_id === $current->member_code) {
                break;
            }

            $sponsor = User::where('member_code', $current->sponsor_id)->first();

            if (!$sponsor) {
                break; // Sponsor not found — stop
            }

            if (!$sponsor->isReferMember()) {
                break; // Sponsor not a refer member — BREAK chain entirely
            }

            $percentage = self::LEVELS[$level];
            $rewardQty = $quantity * ($percentage / 100);

            // Create ReferralReward record
            ReferralReward::create([
                'earner_id' => $sponsor->id,
                'from_user_id' => $member->id,
                'level' => $level,
                'coin_id' => $coin->id,
                'percentage' => $percentage,
                'base_quantity' => $quantity,
                'reward_quantity' => $rewardQty,
                'status' => 1,
            ]);

            // Credit reward coins via CoinTrade
            CoinTrade::create([
                'user_id' => $sponsor->id,
                'coin_id' => $coin->id,
                'type' => 'reward',
                'price' => $coin->end_price ?? 0,
                'quantity' => $rewardQty,
                'total' => 0,
            ]);

            // Move up the chain
            $current = $sponsor;
        }
    }
}