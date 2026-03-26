<?php

namespace App\Http\Controllers;

use App\Models\CoinChart;
use App\Models\ContactSetting;
use App\Models\DepositSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signupForm(Request $request)
    {
        $contact = ContactSetting::first();
        $depositsetting = DepositSetting::first();

        $refCode = $request->get('ref'); // member_code of sponsor
        $sponsor = null;

        if ($refCode) {
            $sponsor = User::where('member_code', $refCode)
                ->where('is_refer_member', 1)
                ->where('status', 1)
                ->first();
        }

        return view('front.signup', compact('contact', 'depositsetting', 'sponsor', 'refCode'));
    }

    public function checkSponsor(Request $request)
    {
        $code = $request->sponsor_id ?? $request->refer_code ?? null;

        if (!$code) {
            return response()->json(['status' => false, 'message' => 'Code required.']);
        }

        $sponsor = User::where('member_code', $code)
            ->where('is_refer_member', 1)
            ->where('status', 1)
            ->first();

        if ($sponsor) {
            return response()->json([
                'status' => true,
                'name' => $sponsor->first_name . ' ' . $sponsor->last_name,
            ]);
        }

        return response()->json(['status' => false, 'message' => 'Invalid or inactive sponsor code.']);
    }

    public function registerUser(Request $request)
    {
        $deposit = DepositSetting::first();

        if (!$deposit) {
            return response()->json([
                'message' => 'Deposit setting not configured. Please contact admin.'
            ], 500);
        }

        $isReferral = !empty($request->sponsor_id);

        $rules = [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|digits:10|unique:users,mobile',
            'password' => 'required|min:6|confirmed',
            'attachment' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'amount' => 'required|numeric|min:' . $deposit->min_amount . '|max:' . $deposit->max_amount,
        ];

        if ($isReferral) {
            $rules['sponsor_id'] = [
                'required',
                function ($attribute, $value, $fail) {
                    $exists = User::where('member_code', $value)
                        ->where('is_refer_member', 1)
                        ->where('status', 1)
                        ->exists();
                    if (!$exists) {
                        $fail('Invalid or inactive sponsor ID.');
                    }
                },
            ];
        }

        $validator = Validator::make($request->all(), $rules, [
            'amount.min' => 'Minimum deposit is ₹' . $deposit->min_amount,
            'amount.max' => 'Maximum deposit is ₹' . $deposit->max_amount,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $lastCandle = CoinChart::latest()->first();
        if (!$lastCandle) {
            return response()->json([
                'message' => 'Coin price not available. Try again later.'
            ], 500);
        }

        $filePath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = 'uploads/attachment/';
            $file->move(public_path($path), $filename);
            $filePath = $path . $filename;
        }

        // Generate unique SVRS member_code
        do {
            $memberCode = 'SVRS' . rand(100000, 999999);
        } while (User::where('member_code', $memberCode)->exists());

        User::create([
            'member_code' => $memberCode,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'sponsor_id' => $request->sponsor_id ?? null,
            'role' => 'member',
            'amount' => $request->amount,
            'coin_price' => $lastCandle->close_price,
            'attachment' => $filePath,
            'otp' => rand(111111, 999999),
            'status' => 2,
            'is_refer_member' => 0,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Registration Successful! Awaiting admin activation.',
        ]);
    }
}