<?php

namespace App\Services;

use App\Models\GoldCoinTransaction;
use App\Models\GoldCoinWallet;
use App\Models\RewardTier;
use App\Models\User;
use App\Models\UserRewardClaim;

class GoldCoinService
{
    public function checkEligibility(User $user): array
    {
        $activeReferrals = User::where('sponsor_id', $user->member_code)
            ->where('role', 'member')
            ->where('status', 1)
            ->where('is_refer_member', 1)
            ->count();

        // FIX: order by tier_order (DB column name)
        $tiers = RewardTier::orderBy('tier_order')->get();
        $milestones = [];

        foreach ($tiers as $tier) {
            // FIX: where reward_tier_id not tier_id
            $claim = UserRewardClaim::where('user_id', $user->id)
                ->where('reward_tier_id', $tier->id)
                ->first();

            $eligible = $activeReferrals >= $tier->required_referrals;
            $canClaim = false;
            $reason = '';

            if ($claim && $claim->status == 1) {
                $reason = 'Already claimed and approved.';
            } elseif ($claim && $claim->status == 2) {
                $reason = 'Claim is pending admin approval.';
            } else {
                if (!$eligible) {
                    $reason = 'Need ' . ($tier->required_referrals - $activeReferrals) . ' more active refer members.';
                } elseif (!$this->isPreviousTierApproved($user, $tier)) {
                    $reason = 'Previous milestone must be approved first.';
                } else {
                    $canClaim = true;
                }
            }

            $milestones[] = [
                'tier' => $tier,
                'progress' => $activeReferrals,
                'eligible' => $eligible,
                'can_claim' => $canClaim,
                'reason' => $reason,
                'claim' => $claim,
                'claim_status' => $claim?->status,
            ];
        }

        return [
            'referral_count' => $activeReferrals,
            'milestones' => $milestones,
        ];
    }

    private function isPreviousTierApproved(User $user, RewardTier $tier): bool
    {
        // FIX: tier_order (DB column)
        if ($tier->tier_order == 1)
            return true;

        $prev = RewardTier::where('tier_order', $tier->tier_order - 1)->first();
        if (!$prev)
            return true;

        // FIX: reward_tier_id
        return UserRewardClaim::where('user_id', $user->id)
            ->where('reward_tier_id', $prev->id)
            ->where('status', 1)
            ->exists();
    }

    public function processClaim(User $user, int $tierId): array
    {
        $tier = RewardTier::find($tierId);
        if (!$tier)
            return ['success' => false, 'message' => 'Invalid reward tier.'];

        $eligibility = $this->checkEligibility($user);
        $milestone = collect($eligibility['milestones'])->first(fn($m) => $m['tier']->id == $tierId);

        if (!$milestone)
            return ['success' => false, 'message' => 'Milestone not found.'];
        if (!$milestone['can_claim']) {
            return ['success' => false, 'message' => $milestone['reason'] ?: 'Not eligible.'];
        }

        UserRewardClaim::create([
            'user_id' => $user->id,
            'reward_tier_id' => $tier->id,   // FIX
            'claim_number' => $tier->tier_order, // FIX
            'g_coins_claimed' => $tier->g_coins,
            'referral_count_at_claim' => $eligibility['referral_count'],
            'status' => 2,
        ]);

        return [
            'success' => true,
            'message' => 'Claim submitted! Admin will credit ' . number_format($tier->g_coins) . ' G-Coins after approval.',
        ];
    }

    public function approveClaim(UserRewardClaim $claim): array
    {
        if ($claim->status == 1)
            return ['success' => false, 'message' => 'Already approved.'];

        $coins = $claim->g_coins_claimed ?? $claim->tier->g_coins;
        $wallet = GoldCoinWallet::firstOrCreate(
            ['user_id' => $claim->user_id],
            ['balance' => 0, 'total_earned' => 0]
        );

        $wallet->balance += $coins;
        $wallet->total_earned += $coins;
        $wallet->save();

        // FIX: GoldCoinTransaction fillable does not have wallet_id — removed
        GoldCoinTransaction::create([
            'user_id' => $claim->user_id,
            'type' => 'credit',
            'amount' => $coins,
            'remark' => 'Reward Claim Approved: ' . ($claim->tier->name ?? ''),
            'status' => 1,
        ]);

        $claim->status = 1;
        $claim->approved_at = now();
        $claim->save();

        return ['success' => true, 'message' => number_format($coins) . ' G-Coins credited successfully.'];
    }

    public function rejectClaim(UserRewardClaim $claim): array
    {
        $claim->status = 0;
        $claim->save();
        return ['success' => true, 'message' => 'Claim rejected.'];
    }
}