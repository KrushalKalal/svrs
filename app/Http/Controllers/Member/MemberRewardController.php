<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\GoldCoinTransaction;
use App\Models\GoldCoinWallet;
use App\Models\RewardTier;
use App\Models\UserRewardClaim;
use App\Services\GoldCoinService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MemberRewardController extends Controller
{
    protected GoldCoinService $goldCoinService;

    public function __construct(GoldCoinService $goldCoinService)
    {
        $this->goldCoinService = $goldCoinService;
    }

    /**
     * My Rewards page — shows 3 milestone cards with progress + claim buttons.
     */
    public function index()
    {
        $user = auth()->user();
        $eligibility = $this->goldCoinService->checkEligibility($user);

        return view('member.rewards.index', compact('user', 'eligibility'));
    }

    /**
     * Submit a reward claim (AJAX).
     * Validates eligibility then creates a pending UserRewardClaim.
     */
    public function claim(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tier_id' => 'required|integer|exists:reward_tiers,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user = auth()->user();
        $result = $this->goldCoinService->processClaim($user, (int) $request->tier_id);

        return response()->json([
            'status' => $result['success'],
            'message' => $result['message'],
        ]);
    }

    /**
     * Gold Coin Wallet page — balance + transaction history.
     */
    public function goldWallet()
    {
        $user = auth()->user();
        $wallet = GoldCoinWallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0, 'total_earned' => 0]
        );

        $transactions = GoldCoinTransaction::where('user_id', $user->id)
            ->latest()
            ->get();

        return view('member.rewards.gold_wallet', compact('user', 'wallet', 'transactions'));
    }
}