<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coin;
use App\Models\CoinChart;
use App\Models\CoinTrade;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CoinController extends Controller
{
    public function coin_list()
    {
        $coins = Coin::latest()->get();
        return view('admin.coin.list', compact('coins'));
    }

    public function create_coin()
    {
        return view('admin.coin.create');
    }
    public function coin_store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png',
            'start_price' => 'required|numeric',
            'end_price'   => 'required|numeric|gte:start_price',
            'status'      => 'required|in:0,1',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = 'uploads/coin/';
            $file->move(public_path($path), $filename);
            $imageName = $path . $filename;
        }

        Coin::create([
            'name'        => $request->name,
            'image'       => $imageName,
            'start_price' => $request->start_price,
            'end_price'   => $request->end_price,
            'status'      => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Coin Created Successfully'
        ]);
    }
    public function coin_edit($id)
    {
        $coin = Coin::findOrFail($id);
        return view('admin.coin.edit', compact('coin'));
    }
    public function coin_update(Request $request)
    {
        $coin = Coin::findOrFail($request->id);

        $request->validate([
            'name'        => 'required',
            'start_price' => 'required|numeric',
            'end_price'   => 'required|numeric|gte:start_price',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp'
        ]);

        if ($request->hasFile('image')) {

            if ($coin->image && file_exists(public_path($coin->image))) {
                unlink(public_path($coin->image));
            }

            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $path = 'uploads/coin/';
            $file->move(public_path($path), $filename);
            $coin->image = $path . $filename;
        }

        $coin->update([
            'name'        => $request->name,
            'start_price' => $request->start_price,
            'end_price'   => $request->end_price,
            'status'      => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Coin Updated Successfully'
        ]);
    }
    public function coin()
    {
        return view('admin.coin_chart.chart');
    }
    public function coin_chart(Request $request)
    {
        $type = $request->type ?? 'today';

        $query = CoinChart::query();

        // ===== TODAY FILTER WITH FALLBACK =====
        if ($type == 'today') {

            // Try Today Data
            $todayData = CoinChart::whereDate('created_at', Carbon::today())->get();

            if ($todayData->isEmpty()) {

                // Try Yesterday
                $yesterdayData = CoinChart::whereDate('created_at', Carbon::yesterday())->get();

                if ($yesterdayData->isEmpty()) {

                    // Get Last Available Entry (latest record)
                    $lastEntry = CoinChart::orderBy('created_at', 'DESC')->take(1)->get();
                    $charts = $lastEntry;
                } else {
                    $charts = $yesterdayData;
                }
            } else {
                $charts = $todayData;
            }
        }

        // ===== MONTH FILTER =====
        elseif ($type == 'month') {

            $charts = CoinChart::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->orderBy('created_at', 'ASC')
                ->get();
        }

        // ===== LAST 4 MONTH FILTER =====
        elseif ($type == '4month') {

            $charts = CoinChart::where('created_at', '>=', Carbon::now()->subMonths(4))
                ->orderBy('created_at', 'ASC')
                ->get();
        }

        // ===== FORMAT FOR CANDLE CHART =====
        $data = $charts->map(function ($item) {
            return [
                'x' => $item->created_at->timestamp * 1000,
                'y' => [
                    (float) $item->open_price,
                    (float) $item->high_price,
                    (float) $item->low_price,
                    (float) $item->close_price,
                ]
            ];
        });

        return response()->json([
            'status' => true,
            'data'   => $data
        ]);
    }

    public function coin_trade(Request $request)
    {
        $request->validate([
            'type' => 'required|in:buy,sell',
            'price' => 'required|numeric|min:0.0001',
            'quantity' => 'required|numeric|min:0.0001',
        ]);
        $coin = Coin::first();

        if (!$coin) {
            return response()->json([
                'status' => false,
                'message' => 'Coin not configured by admin'
            ]);
        }

        $userId = auth()->id();

        $wallet = Wallet::where('user_id', $userId)->lockForUpdate()->first();

        if (!$wallet) {
            $wallet = Wallet::create([
                'user_id' => $userId,
                'balance' => 0,
            ]);

            $wallet = Wallet::where('id', $wallet->id)
                ->lockForUpdate()
                ->first();
        }

        $totalAmount = $request->price * $request->quantity;

        if ($totalAmount <= 0) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid trade amount'
            ]);
        }

        if ($request->type === 'buy') {
            if ($wallet->balance < $totalAmount) {
                return response()->json([
                    'status' => false,
                    'message' => 'Insufficient Balance'
                ]);
            }

            $wallet->balance -= $totalAmount;
            $wallet->save();
        }

        if ($request->type === 'sell') {
            $totalBuy = CoinTrade::where('user_id', $userId)
                ->where('coin_id', $coin->id)
                ->where('type', 'buy')
                ->sum('quantity');

            $totalSell = CoinTrade::where('user_id', $userId)
                ->where('coin_id', $coin->id)
                ->where('type', 'sell')
                ->sum('quantity');

            $availableCoin = $totalBuy - $totalSell;

            if ($request->quantity > $availableCoin) {
                return response()->json([
                    'status' => false,
                    'message' => 'Not enough coin balance. Available: ' . number_format($availableCoin, 4)
                ]);
            }

            $wallet->balance += $totalAmount;
            $wallet->save();

            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id'   => auth()->id(),
                'amount'    => $totalAmount,
                'type'      => 'credit',
                'remark'    => 'Coin Sell Amount',
                'invoice'   => null,
                'status'    => 1,
            ]);
        }

        CoinTrade::create([
            'coin_id' => $coin->id,
            'user_id' => $userId,
            'type' => $request->type,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'total' => $totalAmount,
        ]);

        return response()->json([
            'status' => true,
            'message' => ucfirst($request->type) . ' Order Executed Successfully',
            'balance' => number_format($wallet->balance, 2)
        ]);
    }

    public function coin_history()
    {
        $userId = auth()->id();
        $trades = CoinTrade::with('coin')->where('user_id', $userId)->latest()->get();

        return view('admin.coin_chart.history', compact('trades'));
    }
}
