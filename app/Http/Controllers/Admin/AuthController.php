<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Mail\SendOtpMail;
use App\Models\BankDetail;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }
    public function loginSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid email or password'
            ]);
        }
        if ($user->status == 0) {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Please contact admin.'
            ]);
        }
        
        if ($user->status == 2) {
            return response()->json([
                'status' => false,
                'message' => 'Your account will be activated after 24 hours of admin approval.'
            ]);
        }

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::guard('admin')->attempt($credentials, $request->remember)) {          
            return response()->json([
                'status' => true,
                'message' => 'Login Successful'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid email or password'
        ], 401);
    }
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }
    
    public function show_forgot_password_Form()
    {
        return view('admin.auth.forgot_password');
    }
    public function forgot_password_check(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $otp = rand(100000, 999999);
            $user->update([
                'otp' => $otp,
                'is_verified' => 0,
            ]);
            // Mail::to($request->email)->send(new SendOtpMail($otp));
            return response()->json([
                'exists' => true,
                'user_id' => Crypt::encryptString($user->id),
            ]);
        }

        return response()->json([
            'exists' => false,
        ]);
    }
    public function otp_Verification_Form($id)
    {
        $userId = Crypt::decryptString($id);
        $user = User::findOrFail($userId);

        return view('admin.auth.email_verification', compact('user'));
    }
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp'   => 'required|string|min:4|max:6',
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user->otp === $request->otp) {
            $user->otp = null;
            $user->is_verified = 1;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully!',
                'redirect_url' => route('admin.reset.password', Crypt::encryptString($user->id))
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid or expired OTP.'
        ]);
    }

    // Resend OTP
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        $otp = rand(100000, 999999);
        $user->otp = $otp;
        $user->is_verified = 0;
        $user->save();
        try {
            // Mail::to($user->email)->send(new SendOtpMail($otp));
            return response()->json([
                'success' => true,
                'message' => 'OTP has been resent successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Try again later.'
            ]);
        }
    }
    public function reset_password($id)
    {
        $userId = Crypt::decryptString($id);
        $user = User::findOrFail($userId);
        return view('admin.auth.reset_password', compact('user'));
    }
    public function reset_password_submit(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ]);

        $user = User::where('email', $request->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully!',
            'redirect_url' => route('admin.login')
        ]);
    }
    public function profile()
    {
        $bankdetail = BankDetail::where('user_id', auth()->user()->id)->first();
        return view('admin.auth.profile', compact('bankdetail'));
    }
    public function profile_update(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update profile photo
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = 'uploads/profile/'; 
            $file->move(public_path($path), $filename);

            if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                unlink(public_path($user->profile_image));
            }
            $user->profile_image = $path . $filename;
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully'
        ]);
    }
    public function password_update(Request $request){
        $user = User::findOrFail(auth()->user()->id);
        $request->validate([           
            'current_password' => 'required|required_with:new_password|string',
            'new_password' => 'required|min:6|confirmed'
        ]);
        
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Current password is incorrect.'
            ]);
        }
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Password updated successfully.'
        ]);
    }
    public function bank_details(Request $request)
    {
        $request->validate([
            'account_name'   => 'required|string|max:255',
            'account_number' => 'required|numeric',
            'ifsc_code'      => 'required|string|max:20',
            'bank_name'      => 'required|string|max:255',
            'branch_name'    => 'required|string|max:255',
            'upi'            => 'nullable|string|max:255',
        ]);

        BankDetail::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'account_holder_name'   => $request->account_name,
                'account_number' => $request->account_number,
                'ifsc_code'      => strtoupper($request->ifsc_code),
                'bank_name'      => $request->bank_name,
                'branch_name'    => $request->branch_name,
                'upi'            => $request->upi,
            ]
        );

        return response()->json([
            'status' => true,
            'message' => 'Bank details updated successfully!'
        ]);
    }
}
