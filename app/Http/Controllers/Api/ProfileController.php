<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BankDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user()->load('wallet', 'membership', 'goldCoinWallet');

        return response()->json([
            'status' => true,
            'user' => [
                'id' => $user->id,
                'member_code' => $user->member_code,
                'sponsor_id' => $user->sponsor_id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'profile_image' => $user->profile_image ? asset($user->profile_image) : null,
                'status' => $user->status,
                'is_refer_member' => $user->is_refer_member,
                'refer_code' => $user->membership?->refer_code,
                'refer_link' => $user->membership?->refer_link,
                'wallet_balance' => $user->wallet?->balance ?? 0,
                'gold_balance' => $user->goldCoinWallet?->balance ?? 0,
            ],
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'mobile' => 'required|digits:10|unique:users,mobile,' . $user->id,
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                @unlink(public_path($user->profile_image));
            }
            $file = $request->file('profile_image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = 'uploads/profile/';
            $file->move(public_path($path), $filename);
            $user->profile_image = $path . $filename;
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->mobile = $request->mobile;
        $user->save();

        return response()->json(['status' => true, 'message' => 'Profile updated successfully.']);
    }

    public function passwordUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Current password is incorrect.',
            ], 401);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['status' => true, 'message' => 'Password updated successfully.']);
    }

    public function bankDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required|string|max:100',
            'account_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:30',
            'ifsc_code' => 'required|string|max:20',
            'branch' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        BankDetail::updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'bank_name' => $request->bank_name,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'ifsc_code' => $request->ifsc_code,
                'branch' => $request->branch,
            ]
        );

        return response()->json(['status' => true, 'message' => 'Bank details saved successfully.']);
    }

    public function getBankDetails(Request $request)
    {
        $bank = BankDetail::where('user_id', $request->user()->id)->first();

        return response()->json([
            'status' => true,
            'data' => $bank ? [
                'bank_name' => $bank->bank_name,
                'account_name' => $bank->account_name,
                'account_number' => $bank->account_number,
                'ifsc_code' => $bank->ifsc_code,
                'branch' => $bank->branch,
            ] : null,
        ]);
    }
}