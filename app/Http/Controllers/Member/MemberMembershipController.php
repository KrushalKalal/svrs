<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\ContactSetting;
use App\Models\MemberMembership;
use App\Models\MembershipPlan;
use App\Models\ReferralReward;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MemberMembershipController extends Controller
{
    /**
     * Membership page — 4 states:
     * 1. Not paid yet           → show payment form
     * 2. Pending (status=2)     → show "under review"
     * 3. Rejected (status=0)    → show rejected message
     * 4. Approved (status=1)    → show refer_code + refer_link
     */
    public function index()
    {
        $user = auth()->user();
        $plan = MembershipPlan::where('status', 1)->first();
        $membership = MemberMembership::where('user_id', $user->id)->first();
        $contact = ContactSetting::first();

        return view('member.membership.index', compact('user', 'plan', 'membership', 'contact'));
    }

    /**
     * Submit Rs.519 membership payment screenshot.
     */
    public function pay(Request $request)
    {
        $user = auth()->user();

        // Block if already pending or approved
        $existing = MemberMembership::where('user_id', $user->id)->first();
        if ($existing && in_array($existing->status, [1, 2])) {
            return response()->json([
                'status' => false,
                'message' => 'You have already submitted a payment request.',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'screenshot' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $plan = MembershipPlan::where('status', 1)->first();

        // Save screenshot
        $file = $request->file('screenshot');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = 'uploads/membership/';

        if (!file_exists(public_path($path))) {
            mkdir(public_path($path), 0755, true);
        }

        $file->move(public_path($path), $filename);

        MemberMembership::updateOrCreate(
            ['user_id' => $user->id],
            [
                'plan_id' => $plan?->id,
                'amount' => $plan?->price ?? 519,
                'payment_screenshot' => $path . $filename,
                'status' => 2, // pending admin approval
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Payment submitted! Admin will review and approve shortly.',
        ]);
    }

    /**
     * My Referrals — 3-level tree + per-level earnings.
     */
    public function referrals()
    {
        $user = auth()->user();

        // Guard — only refer members can access
        if (!$user->is_refer_member) {
            return redirect()->route('member.membership')
                ->with('error', 'Please activate Refer & Earn membership first.');
        }

        // Level 1 — direct: sponsor_id = my member_code
        $level1 = User::where('sponsor_id', $user->member_code)
            ->where('role', 'member')
            ->get();

        // Level 2 — under level 1 members
        $l1Codes = $level1->pluck('member_code')->filter()->values()->toArray();
        $level2 = !empty($l1Codes)
            ? User::whereIn('sponsor_id', $l1Codes)->where('role', 'member')->get()
            : collect();

        // Level 3 — under level 2 members
        $l2Codes = $level2->pluck('member_code')->filter()->values()->toArray();
        $level3 = !empty($l2Codes)
            ? User::whereIn('sponsor_id', $l2Codes)->where('role', 'member')->get()
            : collect();

        // Coin earnings per level
        $earnings = [
            1 => ReferralReward::where('earner_id', $user->id)->where('level', 1)->sum('reward_quantity'),
            2 => ReferralReward::where('earner_id', $user->id)->where('level', 2)->sum('reward_quantity'),
            3 => ReferralReward::where('earner_id', $user->id)->where('level', 3)->sum('reward_quantity'),
        ];

        return view('member.membership.referrals', compact(
            'user',
            'level1',
            'level2',
            'level3',
            'earnings'
        ));
    }
}