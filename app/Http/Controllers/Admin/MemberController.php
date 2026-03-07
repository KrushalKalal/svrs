<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankDetail;
use App\Models\CoinChart;
use App\Models\CoinTrade;
use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function member_list()
    {
        $members = User::with('bankDetail')->where('role', 'member')->latest()->get();
        return view('admin.members.member_list', compact('members'));
    }
    public function id_activate()
    {
        $members = User::where('role', 'member')->where('status', '2')->get();
        return view('admin.members.id_activate', compact('members'));
    }
    public function activate_member()
    {
        $members = User::where('role', 'member')->where('status', '1')->get();
        return view('admin.members.activate_member', compact('members'));
    }
    public function inactive_member()
    {
        $members = User::where('role', 'member')->where('status', '0')->get();
        return view('admin.members.inactive_member', compact('members'));
    }
    public function member_bankdetails($id)
    {
        $bankDetail = BankDetail::with('user')->findOrFail($id);
        return view('admin.members.bank_details', compact('bankDetail'));
    }
    public function member_update_status(Request $request)
    {
        $request->validate([
            'id'     => 'required|exists:users,id',
            'status' => 'required|in:0,1'
        ]);

        try {
            $member = User::findOrFail($request->id);
            $member->status = $request->status;
            $member->save();
            if ($request->status == 1) {

                $alreadyAllocated = CoinTrade::where('user_id', $member->id)
                    ->where('type', 'buy')
                    ->exists();

                if (!$alreadyAllocated) {

                    if (empty($member->coin_price) || $member->coin_price <= 0) {

                        $lastCandle = CoinChart::latest()->first();

                        if (!$lastCandle || $lastCandle->close_price <= 0) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Invalid coin price. Cannot activate member.'
                            ], 422);
                        }

                        $member->coin_price = $lastCandle->close_price;
                        $member->save();
                    }

                    $coinQty = $member->amount / $member->coin_price;

                    CoinTrade::create([
                        'user_id'  => $member->id,
                        'coin_id'  => 1,
                        'type'     => 'buy',
                        'price'    => $member->coin_price,
                        'quantity' => $coinQty,
                    ]);
                }
            }
            return response()->json([
                'success' => true,
                'message' => $request->status == 1
                    ? 'Member Activated Successfully'
                    : 'Member Inactivated Successfully'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }
}
