<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GoldCoinTransaction;
use App\Models\GoldCoinWallet;
use App\Services\GoldCoinService;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    protected GoldCoinService $goldCoinService;

    public function __construct(GoldCoinService $goldCoinService)
    {
        $this->goldCoinService = $goldCoinService;
    }

    public function status(Request $request)
    {
        $user = $request->user();
        $eligibility = $this->goldCoinService->checkEligibility($user);
        $wallet = GoldCoinWallet::where('user_id', $user->id)->first();

        return response()->json([
            'status' => true,
            'referral_count' => $eligibility['referral_count'],
            'milestones' => collect($eligibility['milestones'])->map(function ($m) {
                return [
                    'tier_id' => $m['tier']->id,
                    'name' => $m['tier']->name,
                    'g_coins' => $m['tier']->g_coins,
                    'required' => $m['tier']->required_referrals,
                    'progress' => $m['progress'],
                    'eligible' => $m['eligible'],
                    'can_claim' => $m['can_claim'],
                    'claim_status' => $m['claim_status'],
                    'reason' => $m['reason'],
                ];
            }),
            'gold_wallet' => [
                'balance' => $wallet?->balance ?? 0,
                'total_earned' => $wallet?->total_earned ?? 0,
            ],
        ]);
    }

    public function claim(Request $request)
    {
        $request->validate(['tier_id' => 'required|exists:reward_tiers,id']);
        $user = $request->user();
        $result = $this->goldCoinService->processClaim($user, $request->tier_id);

        return response()->json($result, $result['status'] ? 200 : 400);
    }

    public function history(Request $request)
    {
        $user = $request->user();
        $claims = \App\Models\UserRewardClaim::with('tier')
            ->where('user_id', $user->id)->latest()->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'tier' => $c->tier->name,
                    'g_coins' => $c->g_coins_claimed,
                    'status' => $c->status,
                    'status_label' => $c->status_label,
                    'claimed_at' => $c->created_at->format('d M Y'),
                    'approved_at' => $c->approved_at?->format('d M Y'),
                ];
            });

        return response()->json(['status' => true, 'claims' => $claims]);
    }

    public function goldWallet(Request $request)
    {
        $user = $request->user();
        $wallet = GoldCoinWallet::where('user_id', $user->id)->first();
        $transactions = GoldCoinTransaction::where('user_id', $user->id)->latest()->get()
            ->map(function ($t) {
                return [
                    'id' => $t->id,
                    'type' => $t->type,
                    'amount' => $t->amount,
                    'remark' => $t->remark,
                    'date' => $t->created_at->format('d M Y h:i A'),
                ];
            });

        return response()->json([
            'status' => true,
            'wallet' => [
                'balance' => $wallet?->balance ?? 0,
                'total_earned' => $wallet?->total_earned ?? 0,
                'inr_value' => ($wallet?->balance ?? 0) / 10,
            ],
            'transactions' => $transactions,
        ]);
    }
}
