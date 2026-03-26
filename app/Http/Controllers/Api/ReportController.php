<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CoinTrade;
use App\Models\GoldCoinTransaction;
use App\Models\ReferralReward;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function walletLedger(Request $request)
    {
        $user = $request->user();

        $walletTxns = WalletTransaction::where('user_id', $user->id)->latest()->get()
            ->map(fn($t) => [
                'type' => $t->type,
                'amount' => $t->amount,
                'remark' => $t->remark,
                'status' => $t->status,
                'date' => $t->created_at->format('d M Y h:i A'),
            ]);

        $coinTrades = CoinTrade::where('user_id', $user->id)->latest()->get()
            ->map(fn($t) => [
                'type' => $t->type,
                'price' => $t->price,
                'quantity' => $t->quantity,
                'total' => $t->total,
                'date' => $t->created_at->format('d M Y h:i A'),
            ]);

        $referralRewards = ReferralReward::where('earner_id', $user->id)->latest()->get()
            ->map(fn($r) => [
                'from' => $r->fromUser->full_name ?? '',
                'level' => $r->level,
                'quantity' => $r->reward_quantity,
                'date' => $r->created_at->format('d M Y h:i A'),
            ]);

        return response()->json([
            'status' => true,
            'wallet_txns' => $walletTxns,
            'coin_trades' => $coinTrades,
            'referral_rewards' => $referralRewards,
        ]);
    }

    public function incomeReport(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'status' => true,
            'income' => [
                'level_1_coins' => ReferralReward::where('earner_id', $user->id)->where('level', 1)->sum('reward_quantity'),
                'level_2_coins' => ReferralReward::where('earner_id', $user->id)->where('level', 2)->sum('reward_quantity'),
                'level_3_coins' => ReferralReward::where('earner_id', $user->id)->where('level', 3)->sum('reward_quantity'),
                'total_coins' => ReferralReward::where('earner_id', $user->id)->sum('reward_quantity'),
            ],
        ]);
    }

    public function referralTree(Request $request)
    {
        $user = $request->user();
        $level1Codes = User::where('sponsor_id', $user->member_code)->pluck('member_code');
        $level2Codes = User::whereIn('sponsor_id', $level1Codes)->pluck('member_code');

        $mapUser = fn($u) => [
            'id' => $u->id,
            'name' => $u->full_name,
            'member_code' => $u->member_code,
            'status' => $u->status,
            'is_refer_member' => $u->is_refer_member,
            'joined' => $u->created_at->format('d M Y'),
        ];

        return response()->json([
            'status' => true,
            'level_1' => User::where('sponsor_id', $user->member_code)->get()->map($mapUser),
            'level_2' => User::whereIn('sponsor_id', $level1Codes)->get()->map($mapUser),
            'level_3' => User::whereIn('sponsor_id', $level2Codes)->get()->map($mapUser),
        ]);
    }
}
