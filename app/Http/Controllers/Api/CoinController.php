<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coin;
use App\Models\CoinChart;
use App\Models\CoinTrade;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\ReferralRewardService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CoinController extends Controller
{
    protected ReferralRewardService $rewardService;

    public function __construct(ReferralRewardService $rewardService)
    {
        $this->rewardService = $rewardService;
    }

    /**
     * Get current coin info + price.
     */
    public function info()
    {
        $coin = Coin::where('status', 1)->first();
        $lastPrice = CoinChart::latest()->value('close_price') ?? 0;

        return response()->json([
            'status' => true,
            'current_price' => $lastPrice,
            'coin' => $coin ? [
                'id' => $coin->id,
                'name' => $coin->name,
                'image' => $coin->image ? asset($coin->image) : null,
                'start_price' => $coin->start_price,
                'end_price' => $coin->end_price,
            ] : null,
        ]);
    }

    /**
     * Get OHLC chart data.
     * Query param: type = today | week | month | all
     */
    public function chart(Request $request)
    {
        $type = $request->get('type', 'today');

        $query = CoinChart::orderBy('created_at');

        switch ($type) {
            case 'week':
                $query->where('created_at', '>=', Carbon::now()->subDays(7));
                break;
            case 'month':
                $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
                break;
            case 'all':
                break;
            default: // today
                $charts = CoinChart::whereDate('created_at', Carbon::today())->orderBy('created_at')->get();
                if ($charts->isEmpty()) {
                    $charts = CoinChart::whereDate('created_at', Carbon::yesterday())->orderBy('created_at')->get();
                }
                return response()->json([
                    'status' => true,
                    'data' => $this->formatChart($charts),
                ]);
        }

        return response()->json([
            'status' => true,
            'data' => $this->formatChart($query->get()),
        ]);
    }

    private function formatChart($charts): array
    {
        return $charts->map(fn($c) => [
            'x' => $c->created_at->timestamp * 1000,
            'y' => [
                (float) $c->open_price,
                (float) $c->high_price,
                (float) $c->low_price,
                (float) $c->close_price,
            ],
        ])->values()->toArray();
    }

    /**
     * Get coin balance of logged-in user.
     */
    public function balance(Request $request)
    {
        $userId = $request->user()->id;
        $bought = CoinTrade::where('user_id', $userId)->whereIn('type', ['buy', 'reward'])->sum('quantity');
        $sold = CoinTrade::where('user_id', $userId)->where('type', 'sell')->sum('quantity');
        $balance = $bought - $sold;
        $lastPrice = CoinChart::latest()->value('close_price') ?? 0;

        return response()->json([
            'status' => true,
            'coin_balance' => round($balance, 8),
            'current_price' => $lastPrice,
            'inr_value' => round($balance * $lastPrice, 2),
        ]);
    }

    /**
     * Execute a buy or sell trade.
     */
    public function trade(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:buy,sell',
            'price' => 'required|numeric|min:0.0001',
            'quantity' => 'required|numeric|min:0.0001',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        $coin = Coin::first();
        $total = round($request->price * $request->quantity, 2);
        $wallet = Wallet::firstOrCreate(['user_id' => $user->id], ['balance' => 0]);

        // ---- BUY ----
        if ($request->type === 'buy') {
            if ($wallet->balance < $total) {
                return response()->json([
                    'status' => false,
                    'message' => 'Insufficient wallet balance. Available: ₹' . number_format($wallet->balance, 2),
                ], 400);
            }
            $wallet->balance -= $total;
            $wallet->save();
        }

        // ---- SELL ----
        if ($request->type === 'sell') {
            $bought = CoinTrade::where('user_id', $user->id)->whereIn('type', ['buy', 'reward'])->sum('quantity');
            $sold = CoinTrade::where('user_id', $user->id)->where('type', 'sell')->sum('quantity');
            $available = $bought - $sold;

            if ($request->quantity > $available) {
                return response()->json([
                    'status' => false,
                    'message' => 'Insufficient coin balance. Available: ' . number_format($available, 8),
                ], 400);
            }

            $wallet->balance += $total;
            $wallet->save();

            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $user->id,
                'amount' => $total,
                'type' => 'credit',
                'remark' => 'Coin Sell — ' . number_format($request->quantity, 4) . ' SVRS @ ₹' . $request->price,
                'status' => 1,
            ]);
        }

        // Record trade
        CoinTrade::create([
            'coin_id' => $coin?->id,
            'user_id' => $user->id,
            'type' => $request->type,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'total' => $total,
        ]);

        // Phase 2: fire referral rewards on every buy
        if ($request->type === 'buy' && $user->status == 1 && $user->is_refer_member) {
            $this->rewardService->processRewards($user, (float) $request->quantity);
        }

        return response()->json([
            'status' => true,
            'message' => ucfirst($request->type) . ' order placed successfully.',
            'wallet_balance' => $wallet->balance,
        ]);
    }

    /**
     * Get coin trade history of logged-in user.
     */
    public function history(Request $request)
    {
        $trades = CoinTrade::with('coin')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->map(fn($t) => [
                'id' => $t->id,
                'type' => $t->type,
                'coin' => $t->coin?->name ?? 'SVRS',
                'price' => $t->price,
                'quantity' => $t->quantity,
                'total' => $t->total,
                'date' => $t->created_at->format('d M Y h:i A'),
            ]);

        return response()->json(['status' => true, 'trades' => $trades]);
    }
}