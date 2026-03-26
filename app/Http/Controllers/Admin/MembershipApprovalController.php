<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberMembership;
use App\Models\User;
use App\Services\ReferralRewardService;
use Illuminate\Http\Request;

class MembershipApprovalController extends Controller
{
    protected ReferralRewardService $rewardService;

    public function __construct(ReferralRewardService $rewardService)
    {
        $this->rewardService = $rewardService;
    }

    public function index()
    {
        $memberships = MemberMembership::with('user', 'plan')
            ->latest()
            ->get();

        return view('admin.membership.approval', compact('memberships'));
    }

    public function changeStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:member_memberships,id',
            'status' => 'required|in:0,1',
        ]);

        $membership = MemberMembership::with('user')->findOrFail($request->id);

        if ($membership->status != 2) {
            return response()->json([
                'success' => false,
                'message' => 'Already processed.',
            ]);
        }

        if ($request->status == 1) {
            $user = $membership->user;

            // member_code itself is the refer code — no REF prefix
            $referCode = $user->member_code;
            $referLink = url('/sign-up?ref=' . $referCode);

            $membership->status = 1;
            $membership->refer_code = $referCode;
            $membership->refer_link = $referLink;
            $membership->approved_at = now();
            $membership->save();

            $user->is_refer_member = 1;

            // Agar sponsor_id null hai → Admin ke under daal do directly
            if (empty($user->sponsor_id)) {
                $user->sponsor_id = 'SVRS000';
            }

            $user->save();

            // Fire reward chain if member is already activated
            if ($user->status == 1) {
                $this->rewardService->processRewards($user);
            }

            return response()->json([
                'success' => true,
                'message' => 'Membership approved. Refer link generated.',
            ]);
        }

        // Reject
        $membership->status = 0;
        $membership->save();

        return response()->json([
            'success' => true,
            'message' => 'Membership rejected.',
        ]);
    }

    public function rewardList()
    {
        $rewards = \App\Models\ReferralReward::with('earner', 'fromUser', 'coin')
            ->latest()
            ->get();

        return view('admin.membership.reward_list', compact('rewards'));
    }
}