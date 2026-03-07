<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSetting;
use App\Models\DepositSetting;
use App\Models\WithdrawalSetting;
use Illuminate\Http\Request;

class ContactSettingController extends Controller
{
    public function contact_setting()
    {
        $contact = ContactSetting::first();
        return view('admin.contact_setting', compact('contact'));
    }
    public function contact_setting_update(Request $request)
    {
        $request->validate([
            'bank'            => 'required|string|max:255',
            'account_name'    => 'required|string|max:255',
            'account_number'  => 'required|string|max:50',
            'ifsc_code'       => 'required|string|max:20',
            'branch'          => 'required|string|max:255',
            'qr_image'        => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $setting = ContactSetting::first() ?? new ContactSetting();
        if ($request->hasFile('qr_image')) {
            $file = $request->file('qr_image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = 'uploads/qr_image/';
            $file->move(public_path($path), $filename);

            if ($setting->qr_image && file_exists(public_path($setting->qr_image))) {
                unlink(public_path($setting->qr_image));
            }
            $setting->qr_image = $path . $filename;
        }

        $setting->bank           = $request->bank;
        $setting->account_name   = $request->account_name;
        $setting->account_number = $request->account_number;
        $setting->ifsc_code      = $request->ifsc_code;
        $setting->branch         = $request->branch;

        $setting->save();

        return response()->json([
            'status'  => true,
            'message' => 'Contact Settings Updated Successfully!'
        ]);
    }

    public function deposit_setting()
    {
        $setting = DepositSetting::first();
        return view('admin.settings.deposit_setting', compact('setting'));
    }

    public function deposit_setting_update(Request $request)
    {
        $request->validate([
            'min_amount' => 'required|numeric|min:1',
            'max_amount' => 'required|numeric|gt:min_amount',
        ], [
            'min_amount.required' => 'Minimum amount is required',
            'min_amount.min'      => 'Minimum amount must be greater than 0',

            'max_amount.required' => 'Maximum amount is required',
            'max_amount.gt'       => 'Maximum amount must be greater than Minimum amount',
        ]);

        $setting = DepositSetting::updateOrCreate(
            ['id' => 1],
            [
                'min_amount' => $request->min_amount,
                'max_amount' => $request->max_amount,
            ]
        );

        return response()->json([
            'status'  => true,
            'message' => 'Deposit setting saved successfully.',
            'data'    => $setting
        ]);
    }

    public function withdrawal_setting()
    {
        $setting = WithdrawalSetting::first();
        return view('admin.settings.withdrawal_setting', compact('setting'));
    }

    public function withdrawal_setting_update(Request $request)
    {
        $request->validate([
            'min_amount' => 'required|numeric|min:1',
            'max_amount' => 'required|numeric|gt:min_amount',
        ], [
            'min_amount.required' => 'Minimum amount is required',
            'min_amount.min'      => 'Minimum amount must be greater than 0',

            'max_amount.required' => 'Maximum amount is required',
            'max_amount.gt'       => 'Maximum amount must be greater than Minimum amount',
        ]);

        $setting = WithdrawalSetting::updateOrCreate(
            ['id' => 1],
            [
                'min_amount' => $request->min_amount,
                'max_amount' => $request->max_amount,
            ]
        );

        return response()->json([
            'status'  => true,
            'message' => 'Withdrawal setting saved successfully.',
            'data'    => $setting
        ]);
    }
}
