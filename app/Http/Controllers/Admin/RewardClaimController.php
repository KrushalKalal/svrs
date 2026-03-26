<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserRewardClaim;
use App\Services\GoldCoinService;
use Illuminate\Http\Request;

class RewardClaimController extends Controller
{
    protected GoldCoinService $goldCoinService;

    public function __construct(GoldCoinService $goldCoinService)
    {
        $this->goldCoinService = $goldCoinService;
    }

    public function index()
    {
        $claims = UserRewardClaim::with('user', 'tier')
            ->latest()
            ->get();

        return view('admin.rewards.claims', compact('claims'));
    }

    public function changeStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:user_reward_claims,id',
            'status' => 'required|in:0,1',
        ]);

        $claim = UserRewardClaim::with('tier')->findOrFail($request->id);

        if ($claim->status != 2) {
            return response()->json([
                'success' => false,
                'message' => 'Already processed.'
            ]);
        }

        if ($request->status == 1) {
            $this->goldCoinService->approveClaim($claim);

            return response()->json([
                'success' => true,
                'message' => 'Reward Claim Approved. ' . number_format($claim->g_coins_claimed) . ' G-Coins credited.',
            ]);
        }

        // Reject
        $claim->status = 0;
        $claim->save();

        return response()->json([
            'success' => true,
            'message' => 'Reward Claim Rejected.',
        ]);
    }
}