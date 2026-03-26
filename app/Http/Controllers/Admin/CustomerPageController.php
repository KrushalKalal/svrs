<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerPageController extends Controller
{
    public function welcome_letter()
    {
        $member = auth('admin')->user();
        return view('admin.customer_pages.welcome_letter', compact('member'));
    }

    public function receipt()
    {
        return view('admin.customer_pages.receipt');
    }

    public function customer_support()
    {
        $user = auth('admin')->user();

        // FIX: was 'super admin' — system only has 'admin'
        if ($user->role === 'admin') {
            $supports = CustomerSupport::with('member')->latest()->get();
            return view('admin.customer_pages.customer_support_admin', compact('supports'));
        }

        if ($user->role === 'member') {
            $supports = CustomerSupport::where('user_id', $user->id)->latest()->get();
            return view('admin.customer_pages.customer_support_member', compact('supports'));
        }

        abort(403, 'Unauthorized');
    }

    public function customer_support_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $filePath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = 'uploads/support/';
            $file->move(public_path($path), $filename);
            $filePath = $path . $filename;
        }

        CustomerSupport::create([
            'user_id' => auth('admin')->id(),
            'message' => $request->message,
            'attachment' => $filePath,
        ]);

        return response()->json(['status' => true, 'message' => 'Support request submitted successfully.']);
    }

    public function customer_support_reply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'support_id' => 'required|exists:customer_support,id',
            'reply' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        CustomerSupport::where('id', $request->support_id)->update([
            'reply' => $request->reply,
            'replied_at' => now(),
        ]);

        return response()->json(['status' => true, 'message' => 'Reply sent successfully.']);
    }
}