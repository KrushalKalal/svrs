<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactSetting;
use App\Models\DepositSetting;
use App\Models\Policy;

class ContentController extends Controller
{
    public function privacyPolicy()
    {
        $policy = Policy::where('type', 'privacy')->first();
        return response()->json([
            'status' => true,
            'content' => $policy?->content ?? '',
        ]);
    }

    public function terms()
    {
        $policy = Policy::where('type', 'terms')->first();
        return response()->json([
            'status' => true,
            'content' => $policy?->content ?? '',
        ]);
    }

    public function contactInfo()
    {
        $contact = ContactSetting::first();
        return response()->json([
            'status' => true,
            'data' => $contact ? [
                'bank' => $contact->bank,
                'account_name' => $contact->account_name,
                'account_number' => $contact->account_number,
                'ifsc_code' => $contact->ifsc_code,
                'branch' => $contact->branch,
                'upi_id' => $contact->upi_id ?? null,
                'phone' => $contact->phone ?? null,
                'email' => $contact->email ?? null,
                'qr_image' => $contact->qr_image ? asset($contact->qr_image) : null,
            ] : null,
        ]);
    }

    public function depositInfo()
    {
        $deposit = DepositSetting::first();
        return response()->json([
            'status' => true,
            'data' => $deposit ? [
                'min_amount' => $deposit->min_amount,
                'max_amount' => $deposit->max_amount,
            ] : null,
        ]);
    }
}