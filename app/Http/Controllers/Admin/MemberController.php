<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankDetail;
use App\Models\CoinChart;
use App\Models\CoinTrade;
use App\Models\DepositSetting;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\ReferralRewardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    protected ReferralRewardService $rewardService;

    public function __construct(ReferralRewardService $rewardService)
    {
        $this->rewardService = $rewardService;
    }

    public function member_list()
    {
        $members = User::with('bankDetail')->where('role', 'member')->latest()->get();
        return view('admin.members.member_list', compact('members'));
    }

    public function id_activate()
    {
        $members = User::where('role', 'member')->where('status', 2)->latest()->get();
        return view('admin.members.id_activate', compact('members'));
    }

    public function activate_member()
    {
        $members = User::where('role', 'member')->where('status', 1)->latest()->get();
        return view('admin.members.activate_member', compact('members'));
    }

    public function inactive_member()
    {
        $members = User::where('role', 'member')->where('status', 0)->latest()->get();
        return view('admin.members.inactive_member', compact('members'));
    }

    public function member_bankdetails($id)
    {
        $bankDetail = BankDetail::with('user')->findOrFail($id);
        return view('admin.members.bank_details', compact('bankDetail'));
    }

    public function add_member_form()
    {
        $authUser = auth('admin')->user();
        $deposit = DepositSetting::first();
        $contact = \App\Models\ContactSetting::first();

        if ($authUser->role === 'admin') {
            return view('admin.member.add_member', compact('deposit', 'contact', 'authUser'));
        }

        if (!$authUser->is_refer_member) {
            return redirect()->route('member.membership')
                ->with('error', 'Please activate Refer & Earn membership first to add members.');
        }

        return view('member.add_member', compact('deposit', 'contact', 'authUser'));
    }

    public function add_member_store(Request $request)
    {
        $deposit = DepositSetting::first();

        if (!$deposit) {
            return response()->json(['status' => false, 'message' => 'Deposit setting not configured.'], 500);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|digits:10|unique:users,mobile',
            'password' => 'required|min:6|confirmed',
            'amount' => 'required|numeric|min:' . $deposit->min_amount . '|max:' . $deposit->max_amount,
            'attachment' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ], [
            'amount.min' => 'Minimum deposit is ₹' . $deposit->min_amount,
            'amount.max' => 'Maximum deposit is ₹' . $deposit->max_amount,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
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

        $authUser = auth('admin')->user();
        $sponsorId = $authUser->role === 'admin' ? 'SVRS000' : $authUser->member_code;

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
            'sponsor_id' => $sponsorId,
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

    public function member_update_status(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'status' => 'required|in:0,1',
        ]);

        try {
            $member = User::findOrFail($request->id);
            $member->status = $request->status;
            $member->save();

            if ($request->status == 1) {

                // Credit registration deposit to wallet on activation
                $alreadyCredited = WalletTransaction::where('user_id', $member->id)
                    ->where('remark', 'Registration Deposit')
                    ->where('status', 1)
                    ->exists();

                if (!$alreadyCredited && $member->amount > 0) {
                    $wallet = Wallet::firstOrCreate(
                        ['user_id' => $member->id],
                        ['balance' => 0]
                    );

                    $wallet->balance += $member->amount;
                    $wallet->save();

                    WalletTransaction::create([
                        'wallet_id' => $wallet->id,
                        'user_id' => $member->id,
                        'amount' => $member->amount,
                        'type' => 'credit',
                        'remark' => 'Registration Deposit',
                        'invoice' => $member->attachment,
                        'status' => 1,
                    ]);
                }

                // Phase 2: fire referral rewards if already a refer member
                if ($member->is_refer_member == 1) {
                    $this->rewardService->processRewards($member);
                }
            }

            return response()->json([
                'success' => true,
                'message' => $request->status == 1
                    ? 'Member Activated Successfully'
                    : 'Member Deactivated Successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }
}