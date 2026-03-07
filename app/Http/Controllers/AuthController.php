<?php

namespace App\Http\Controllers;

use App\Models\CoinChart;
use App\Models\ContactSetting;
use App\Models\DepositSetting;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signupForm()
    {
        $contact = ContactSetting::first();
        $depositsetting = DepositSetting::first();
        return view('front.signup',compact('contact', 'depositsetting'));
    }

    public function checkSponsor(Request $request)
    {
        $sponsor = User::where('member_code', $request->sponsor_id)->first();

        if ($sponsor) {
            return response()->json([
                'status' => true,
                'name'   => $sponsor->first_name . ' ' . $sponsor->last_name
            ]);
        }

        return response()->json([
            'status' => false
        ]);
    }

    public function registerUser(Request $request)
    {    
        $deposit = DepositSetting::first();

        if (!$deposit) {
            return response()->json([
                'message' => 'Deposit setting not configured. Please contact admin.'
            ], 500);
        }

        $rules = [
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email',
            'mobile'     => 'required|digits:10|unique:users,mobile',
            'password'   => 'required|min:6|confirmed',
            'sponsor_id' => 'required|exists:users,member_code',
            'attachment' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'amount'     => 'required|numeric|min:' . $deposit->min_amount,
        ];

        if (!empty($deposit->max_amount)) {
            $rules['amount'] .= '|max:' . $deposit->max_amount;
        }

        $validator = Validator::make($request->all(), $rules, [
            'amount.min' => 'Minimum deposit is ₹' . $deposit->min_amount,
            'amount.max' => 'Maximum deposit is ₹' . $deposit->max_amount,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $lastCandle = CoinChart::latest()->first();

        if (!$lastCandle) {
            return response()->json([
                'message' => 'Coin price not available. Try again later.'
            ], 500);
        }

        $latestPrice = $lastCandle->close_price;
        
        $filePath = null;       
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = 'uploads/attachment/';
            $file->move(public_path($path), $filename);
            $filePath = $path . $filename;
        }
        $user = User::create([
            'member_code' => 'MBR' . rand(100000, 999999),
            'first_name'  => $request->first_name,
            'last_name'   => $request->last_name,
            'email'       => $request->email,
            'mobile'      => $request->mobile,
            'password'    => Hash::make($request->password),
            'sponsor_id'  => $request->sponsor_id,
            'amount'      => $request->amount,
            'coin_price'  => $latestPrice,
            'attachment'  => $filePath,
            'otp'         => rand(111111, 999999),
            'status'      => 2,
        ]);

        $user->assignRole('member');

        return response()->json([
            'status' => true,
            'message' => 'Registration Successful'
        ]);
    }
}
