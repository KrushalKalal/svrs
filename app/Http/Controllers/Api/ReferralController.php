<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MemberMembership;
use App\Models\ReferralReward;
use App\Models\User;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function info(Request $request)
    {
        $user = $request->user();
        $membership = MemberMembership::where('user_id', $user->id)->where('status', 1)->first();

        return response()->json([
            'status' => true,
            'is_refer_member' => $user->is_refer_member,
            'refer_code' => $membership?->refer_code,
            'refer_link' => $membership?->refer_link,
            'total_referrals' => $user->allDirectReferrals()->count(),
            'active_refer_members' => $user->active_refer_member_count,
        ]);
    }

    public function list(Request $request)
    {
        $user = $request->user();

        $level1 = User::where('sponsor_id', $user->member_code)
            ->select('id', 'first_name', 'last_name', 'member_code', 'status', 'is_refer_member', 'created_at')
            ->get()->map(function ($u) {
                return [
                    'id' => $u->id,
                    'name' => $u->full_name,
                    'member_code' => $u->member_code,
                    'status' => $u->status,
                    'is_refer_member' => $u->is_refer_member,
                    'joined' => $u->created_at->format('d M Y'),
                ];
            });

        $level1Codes = User::where('sponsor_id', $user->member_code)->pluck('member_code');

        $level2 = User::whereIn('sponsor_id', $level1Codes)
            ->select('id', 'first_name', 'last_name', 'member_code', 'status', 'is_refer_member', 'sponsor_id', 'created_at')
            ->get()->map(function ($u) {
                return [
                    'id' => $u->id,
                    'name' => $u->full_name,
                    'member_code' => $u->member_code,
                    'sponsor_id' => $u->sponsor_id,
                    'status' => $u->status,
                    'is_refer_member' => $u->is_refer_member,
                    'joined' => $u->created_at->format('d M Y'),
                ];
            });

        $level2Codes = User::whereIn('sponsor_id', $level1Codes)->pluck('member_code');

        $level3 = User::whereIn('sponsor_id', $level2Codes)
            ->select('id', 'first_name', 'last_name', 'member_code', 'status', 'is_refer_member', 'sponsor_id', 'created_at')
            ->get()->map(function ($u) {
                return [
                    'id' => $u->id,
                    'name' => $u->full_name,
                    'member_code' => $u->member_code,
                    'sponsor_id' => $u->sponsor_id,
                    'status' => $u->status,
                    'is_refer_member' => $u->is_refer_member,
                    'joined' => $u->created_at->format('d M Y'),
                ];
            });

        return response()->json([
            'status' => true,
            'data' => [
                'level_1' => $level1,
                'level_2' => $level2,
                'level_3' => $level3,
            ],
        ]);
    }

    public function rewards(Request $request)
    {
        $user = $request->user();

        $rewards = ReferralReward::with('fromUser', 'coin')
            ->where('earner_id', $user->id)
            ->latest()->get()
            ->map(function ($r) {
                return [
                    'id' => $r->id,
                    'from' => $r->fromUser->full_name,
                    'level' => $r->level,
                    'percentage' => $r->percentage,
                    'base_quantity' => $r->base_quantity,
                    'reward_quantity' => $r->reward_quantity,
                    'coin' => $r->coin->name ?? 'SVRS',
                    'date' => $r->created_at->format('d M Y h:i A'),
                ];
            });

        $totals = [
            'level_1' => ReferralReward::where('earner_id', $user->id)->where('level', 1)->sum('reward_quantity'),
            'level_2' => ReferralReward::where('earner_id', $user->id)->where('level', 2)->sum('reward_quantity'),
            'level_3' => ReferralReward::where('earner_id', $user->id)->where('level', 3)->sum('reward_quantity'),
        ];

        return response()->json(['status' => true, 'rewards' => $rewards, 'totals' => $totals]);
    }

    public function checkCode($code)
    {
        $membership = MemberMembership::with('user')
            ->where('refer_code', $code)->where('status', 1)->first();

        if ($membership) {
            return response()->json([
                'status' => true,
                'name' => $membership->user->full_name,
                'member_code' => $membership->user->member_code,
            ]);
        }

        return response()->json(['status' => false, 'message' => 'Invalid referral code.']);
    }
}
