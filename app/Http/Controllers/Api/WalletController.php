<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DepositSetting;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\WithdrawalSetting;
use App\Models\ContactSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    /**
     * Get wallet balance.
     */
    public function index(Request $request)
    {
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $request->user()->id],
            ['balance' => 0]
        );

        $contact = ContactSetting::first();
        $deposit = DepositSetting::first();

        return response()->json([
            'status' => true,
            'balance' => $wallet->balance,
            'deposit_setting' => [
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
        ]);
    }


    /**
     * Submit deposit request — pending admin approval.
     * Wallet credited only after admin approves.
     * Accepts field name: screenshot OR attachment (both work)
     */
    public function deposit(Request $request)
    {
        $deposit = DepositSetting::first();
        $min = $deposit->min_amount ?? 200;
        $max = $deposit->max_amount ?? 2000;

        // Accept both 'screenshot' and 'attachment' field names from Flutter
        $file = $request->file('screenshot') ?? $request->file('attachment');

        $validator = Validator::make(
            array_merge($request->all(), ['_file' => $file]),
            [
                'amount' => "required|numeric|min:{$min}|max:{$max}",
                '_file' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            ],
            [
                'amount.min' => "Minimum deposit is ₹{$min}",
                'amount.max' => "Maximum deposit is ₹{$max}",
                '_file.required' => 'Payment screenshot is required.',
                '_file.mimes' => 'Screenshot must be jpg, jpeg, or png.',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        $wallet = Wallet::firstOrCreate(['user_id' => $user->id], ['balance' => 0]);

        $filePath = null;
        if ($file) {
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = 'uploads/deposits/';
            $file->move(public_path($path), $filename);
            $filePath = $path . $filename;
        }

        // Pending — admin approves, wallet credited on approval only (no auto coin buy)
        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'user_id' => $user->id,
            'amount' => $request->amount,
            'type' => 'credit',
            'remark' => 'Deposit Request',
            'invoice' => $filePath,
            'status' => 2, // pending admin approval
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Payment request submitted! Admin will approve shortly.',
        ]);
    }

    /**
     * Withdrawal request — pending admin approval.
     */
    public function withdraw(Request $request)
    {
        $setting = WithdrawalSetting::first();
        $min = $setting?->min_amount ?? 500;
        $max = $setting?->max_amount ?? 5000;

        $validator = Validator::make($request->all(), [
            'amount' => "required|numeric|min:{$min}|max:{$max}",
        ], [
            'amount.min' => "Minimum withdrawal is ₹{$min}",
            'amount.max' => "Maximum withdrawal is ₹{$max}",
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        $wallet = Wallet::where('user_id', $user->id)->first();

        if (!$wallet || $wallet->balance < $request->amount) {
            return response()->json([
                'status' => false,
                'message' => 'Insufficient wallet balance. Available: ₹' . number_format($wallet?->balance ?? 0, 2),
            ], 400);
        }

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'user_id' => $user->id,
            'amount' => $request->amount,
            'type' => 'debit',
            'remark' => 'Withdrawal Request',
            'status' => 2, // pending admin approval
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Withdrawal request submitted. Admin will process shortly.',
        ]);
    }

    /**
     * Get all wallet transactions.
     */
    public function transactions(Request $request)
    {
        $txns = WalletTransaction::where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->map(fn($t) => [
                'id' => $t->id,
                'type' => $t->type,
                'amount' => $t->amount,
                'remark' => $t->remark,
                'status' => $t->status,
                'status_label' => $t->status == 1 ? 'Approved' : ($t->status == 0 ? 'Rejected' : 'Pending'),
                'date' => $t->created_at->format('d M Y h:i A'),
            ]);

        return response()->json(['status' => true, 'transactions' => $txns]);
    }
}