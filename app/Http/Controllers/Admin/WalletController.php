<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankDetail;
use App\Models\ContactSetting;
use App\Models\DepositSetting;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\WithdrawalSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    public function my_wallet()
    {
        $contact = ContactSetting::first();
        $depositsetting = DepositSetting::first();
        $withdrawal = WithdrawalSetting::first();
        $wallet = Wallet::where('user_id', auth()->id())->first();
        $bankDetail = BankDetail::where('user_id', auth()->id())->first();
        $hasBankDetail = $bankDetail ? true : false;
        $pendingDeposit = WalletTransaction::where('wallet_id', optional($wallet)->id)
            ->where('type', 'credit')
            ->where('status', 2)
            ->sum('amount');

        $pendingWithdraw = WalletTransaction::where('wallet_id', optional($wallet)->id)
            ->where('type', 'debit')
            ->where('status', 2)
            ->sum('amount');

        $transactions = WalletTransaction::where('wallet_id', $wallet->id ?? '')
            ->latest()
            ->get();
        return view('admin.my_wallet', compact(
            'contact',
            'depositsetting',
            'withdrawal',
            'wallet',
            'hasBankDetail',
            'pendingDeposit',
            'pendingWithdraw',
            'transactions'
        ));
    }
    public function wallet_addMoney(Request $request)
    {
        $deposit = DepositSetting::first();

        if (!$deposit) {
            return response()->json([
                'status' => false,
                'message' => 'Deposit settings not configured.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:' . $deposit->min_amount . '|max:' . $deposit->max_amount,
            'screenshot' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }


        $filePath = null;
        if ($request->hasFile('screenshot')) {
            $file = $request->file('screenshot');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = 'uploads/screenshots/';
            $file->move(public_path($path), $filename);
            $filePath = $path . $filename;
        }
        $wallet = Wallet::firstOrCreate(
            ['user_id' => auth()->id()],
            [
                'balance' => 0,
                'status'  => 1
            ]
        );
        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'user_id'   => auth()->id(),
            'amount'    => $request->amount,
            'type'      => 'credit',
            'remark'    => 'Add Money Request',
            'invoice'   => $filePath,
            'status'    => 2,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Payment Request Submitted Successfully!'
        ]);
    }
    public function withdrawRequest(Request $request)
    {
        $setting = WithdrawalSetting::first();

        $request->validate([
            'amount' => 'required|numeric|min:' . $setting->min_amount . '|max:' . $setting->max_amount,
        ]);

        // Get Wallet
        $wallet = Wallet::where('user_id', auth()->id())->first();

        if (!$wallet) {
            return response()->json([
                'status' => false,
                'message' => 'Wallet not found.'
            ]);
        }

        // Check Balance
        if ($request->amount > $wallet->balance) {
            return response()->json([
                'status' => false,
                'message' => 'Insufficient wallet balance.'
            ]);
        }

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'user_id'   => auth()->id(),
            'amount'    => $request->amount,
            'type'      => 'debit',
            'remark'    => 'Withdrawal Request',
            'status'    => 2 // Pending Admin Approval
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Withdrawal request submitted successfully.'
        ]);
    }
    public function deposit_approval()
    {
        $transactions = WalletTransaction::with('user')->where('type', 'credit')->latest()->get();
        return view('admin.deposit_approval', compact('transactions'));
    }

    public function deposit_change_status(Request $request)
    {
        $txn = WalletTransaction::with('wallet')->findOrFail($request->id);

        if ($txn->status != 2) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction already processed.'
            ]);
        }

        $wallet = $txn->wallet;

        if ($request->status == 1) {
            $txn->status = 1;
            $txn->remark = "Add Money Approved";
        }

        if ($request->status == 0) {
            $txn->status = 0;
            $txn->remark = "Add Money Rejected";
        }
        $txn->save();

        if ($request->status == 1) {
            $wallet = $txn->wallet;

            if (!$wallet) {
                $wallet = Wallet::new([
                    'user_id' => $txn->user_id,
                    'balance' => $txn->amount,
                    'status'  => '1',
                ]);
            } else {
                $wallet->balance += $txn->amount;
                $wallet->save();
            }
        }

        return response()->json([
            'success' => true,
            'message' => $request->status == 1
                ? 'Transaction Approved Successfully'
                : 'Transaction Rejected'
        ]);
    }

    public function withdrawal_approval()
    {
        $transactions = WalletTransaction::with('user')->where('type', 'debit')->latest()->get();
        return view('admin.withdrawal_approval', compact('transactions'));
    }

    public function withdrawal_change_status(Request $request)
    {
        $withdrawal = WithdrawalSetting::first();
        $txn = WalletTransaction::with('wallet')->findOrFail($request->id);

        if ($txn->status != 2) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction already processed.'
            ]);
        }

        if ($request->status == 1) {

            $approvedAmount = $request->amount;

            if ($approvedAmount <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid amount.'
                ]);
            }

            if ($approvedAmount < $withdrawal->min_amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Minimum withdrawal is ₹' . $withdrawal->min_amount
                ]);
            }

            if ($approvedAmount > $withdrawal->max_amount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maximum withdrawal is ₹' . $withdrawal->max_amount
                ]);
            }
            $wallet = $txn->wallet;

            if (!$wallet || $wallet->balance < $approvedAmount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance.'
                ]);
            }

            $wallet->balance -= $approvedAmount;
            $wallet->save();

            $txn->status = 1;
            $txn->amount = $approvedAmount;
            $txn->remark = "Withdrawal Approved";
        }

        if ($request->status == 0) {
            $txn->status = 0;
            $txn->remark = "Withdrawal Rejected";
        }

        $txn->save();

        return response()->json([
            'success' => true,
            'message' => $request->status == 1
                ? 'Withdrawal Approved Successfully'
                : 'Withdrawal Rejected'
        ]);
    }
}
