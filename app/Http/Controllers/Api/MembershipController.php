<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactSetting;
use App\Models\MemberMembership;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MembershipController extends Controller
{
    public function status(Request $request)
    {
        $user = $request->user();
        $membership = MemberMembership::where('user_id', $user->id)->first();
        $plan = MembershipPlan::where('status', 1)->first();
        $contact = ContactSetting::first();

        return response()->json([
            'status' => true,
            'membership' => $membership ? [
                'status' => $membership->status,
                'status_label' => $membership->status_label,
                'refer_code' => $membership->refer_code,
                'refer_link' => $membership->refer_link,
                'amount' => $membership->amount,
                'approved_at' => $membership->approved_at,
            ] : null,
            'plan' => $plan ? [
                'name' => $plan->name,
                'price' => $plan->price,
            ] : null,
            'payment_info' => $contact ? [
                'bank' => $contact->bank,
                'account_name' => $contact->account_name,
                'account_number' => $contact->account_number,
                'ifsc_code' => $contact->ifsc_code,
                'branch' => $contact->branch,
                'upi_id' => $contact->upi_id ?? null,
                'phone' => $contact->phone ?? null,
                'qr_image' => $contact->qr_image ? asset($contact->qr_image) : null,
            ] : null,
        ]);
    }

    public function pay(Request $request)
    {
        $user = $request->user();

        $existing = MemberMembership::where('user_id', $user->id)->first();
        if ($existing) {
            return response()->json(['status' => false, 'message' => 'Already submitted.'], 400);
        }

        $validator = Validator::make($request->all(), [
            'screenshot' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $plan = MembershipPlan::where('status', 1)->first();
        $filePath = null;

        if ($request->hasFile('screenshot')) {
            $file = $request->file('screenshot');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = 'uploads/membership/';
            $file->move(public_path($path), $filename);
            $filePath = $path . $filename;
        }

        MemberMembership::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'payment_screenshot' => $filePath,
            'amount' => $plan->price,
            'status' => 2,
        ]);

        return response()->json(['status' => true, 'message' => 'Membership payment submitted. Awaiting approval.']);
    }
}