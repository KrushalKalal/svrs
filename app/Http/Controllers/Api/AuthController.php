<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CoinChart;
use App\Models\ContactSetting;
use App\Models\DepositSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->where('role', 'member')->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => false, 'message' => 'Invalid email or password'], 401);
        }

        if ($user->status == 0) {
            return response()->json(['status' => false, 'message' => 'Account is inactive.'], 403);
        }

        if ($user->status == 2) {
            return response()->json(['status' => false, 'message' => 'Account pending admin activation.'], 403);
        }

        if ($request->fcm_token) {
            $user->fcm_token = $request->fcm_token;
            $user->save();
        }

        $token = $user->createToken('member-token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'member_code' => $user->member_code,
                'name' => $user->full_name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'profile_image' => $user->profile_image ? asset($user->profile_image) : null,
                'is_refer_member' => (bool) $user->is_refer_member,
                'status' => $user->status,
            ],
        ]);
    }

    /**
     * Get registration page info — payment details + QR code
     * App hits this BEFORE showing register screen
     * GET /api/register-info?ref=SPONSOR_CODE (optional)
     */
    public function registerInfo(Request $request)
    {
        $contact = ContactSetting::first();
        $deposit = DepositSetting::first();

        // Check sponsor from ref param
        $sponsor = null;
        $refCode = $request->get('ref');
        if ($refCode) {
            $sponsorUser = User::where('member_code', $refCode)
                ->where('is_refer_member', 1)
                ->where('status', 1)
                ->first();
            if ($sponsorUser) {
                $sponsor = [
                    'name' => $sponsorUser->full_name,
                    'member_code' => $sponsorUser->member_code,
                ];
            }
        }

        return response()->json([
            'status' => true,
            'deposit' => [
                'min_amount' => $deposit->min_amount ?? 200,
                'max_amount' => $deposit->max_amount ?? 2000,
            ],
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
            'sponsor' => $sponsor, // null if no ref code or invalid
        ]);
    }

    /**
     * Register — self signup (with or without referral)
     * POST /api/register
     * FormData: first_name, last_name, email, mobile, password, password_confirmation,
     *           amount, attachment (screenshot), sponsor_id (optional)
     */
    public function register(Request $request)
    {
        $deposit = DepositSetting::first();

        $rules = [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|digits:10|unique:users,mobile',
            'password' => 'required|min:6|confirmed',
            'attachment' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'amount' => 'required|numeric|min:' . ($deposit->min_amount ?? 200) . '|max:' . ($deposit->max_amount ?? 2000),
        ];

        // Validate sponsor only if provided
        if (!empty($request->sponsor_id)) {
            $rules['sponsor_id'] = [
                'required',
                function ($attribute, $value, $fail) {
                    $exists = User::where('member_code', $value)
                        ->where('is_refer_member', 1)
                        ->where('status', 1)
                        ->exists();
                    if (!$exists) {
                        $fail('Invalid or inactive sponsor code.');
                    }
                },
            ];
        }

        $validator = Validator::make($request->all(), $rules, [
            'amount.min' => 'Minimum deposit is ₹' . ($deposit->min_amount ?? 200),
            'amount.max' => 'Maximum deposit is ₹' . ($deposit->max_amount ?? 2000),
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $lastCandle = CoinChart::latest()->first();
        if (!$lastCandle) {
            return response()->json(['status' => false, 'message' => 'Coin price not available.'], 500);
        }

        $filePath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = 'uploads/attachment/';
            $file->move(public_path($path), $filename);
            $filePath = $path . $filename;
        }

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
            'status' => 2,
            'is_refer_member' => 0,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Registration successful. Awaiting admin activation.',
        ]);
    }

    /**
     * Add New Member — by logged-in refer member
     * POST /api/v1/add-member  (auth:sanctum)
     * FormData: same as register + no sponsor_id needed (auto = auth user's member_code)
     */
    public function addMember(Request $request)
    {
        $authUser = $request->user();

        // Only refer members can add
        if (!$authUser->is_refer_member) {
            return response()->json([
                'status' => false,
                'message' => 'Please activate Refer & Earn membership first to add members.',
            ], 403);
        }

        $deposit = DepositSetting::first();

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|digits:10|unique:users,mobile',
            'password' => 'required|min:6|confirmed',
            'amount' => 'required|numeric|min:' . ($deposit->min_amount ?? 200) . '|max:' . ($deposit->max_amount ?? 2000),
            'attachment' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ], [
            'amount.min' => 'Minimum deposit is ₹' . ($deposit->min_amount ?? 200),
            'amount.max' => 'Maximum deposit is ₹' . ($deposit->max_amount ?? 2000),
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $lastCandle = CoinChart::latest()->first();
        if (!$lastCandle) {
            return response()->json(['status' => false, 'message' => 'Coin price not available.'], 500);
        }

        $filePath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = 'uploads/attachment/';
            $file->move(public_path($path), $filename);
            $filePath = $path . $filename;
        }

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
            'sponsor_id' => $authUser->member_code, // auto set to logged-in member
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
            'message' => 'Member added successfully! Pending admin activation.',
        ]);
    }

    /**
     * Get payment info for Add Member screen
     * GET /api/v1/add-member-info  (auth:sanctum)
     */
    public function addMemberInfo(Request $request)
    {
        $authUser = $request->user();

        if (!$authUser->is_refer_member) {
            return response()->json([
                'status' => false,
                'message' => 'Refer & Earn membership required.',
            ], 403);
        }

        $contact = ContactSetting::first();
        $deposit = DepositSetting::first();

        return response()->json([
            'status' => true,
            'deposit' => [
                'min_amount' => $deposit->min_amount ?? 200,
                'max_amount' => $deposit->max_amount ?? 2000,
            ],
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
            'sponsor' => [
                'name' => $authUser->full_name,
                'member_code' => $authUser->member_code,
                'refer_link' => url('/sign-up?ref=' . $authUser->member_code),
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['status' => true, 'message' => 'Logged out successfully.']);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();
        $otp = rand(100000, 999999);
        $user->update(['otp' => $otp, 'is_verified' => 0]);

        // Mail::to($user->email)->send(new SendOtpMail($otp));

        return response()->json(['status' => true, 'message' => 'OTP sent to your email.']);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if ($user->otp != $request->otp) {
            return response()->json(['status' => false, 'message' => 'Invalid OTP.'], 401);
        }

        $user->update(['otp' => null, 'is_verified' => 1]);

        return response()->json(['status' => true, 'message' => 'OTP verified.']);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->where('is_verified', 1)->first();
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Please verify OTP first.'], 403);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['status' => true, 'message' => 'Password reset successfully.']);
    }

    public function checkSponsor(Request $request, $code)
    {
        $sponsor = User::where('member_code', $code)
            ->where('is_refer_member', 1)
            ->where('status', 1)
            ->first();

        if ($sponsor) {
            return response()->json([
                'status' => true,
                'name' => $sponsor->full_name,
                'member_code' => $sponsor->member_code,
            ]);
        }

        return response()->json(['status' => false, 'message' => 'Invalid referral code.']);
    }
}