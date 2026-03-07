<?php

namespace App\Http\Controllers;

use App\Models\CoinChart;
use App\Models\Policy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {
        $type = $request->type ?? 'today';

        $query = CoinChart::query();
        if ($type == 'today') {
            $todayData = CoinChart::whereDate('created_at', Carbon::today())->get();
            if ($todayData->isEmpty()) {
                $yesterdayData = CoinChart::whereDate('created_at', Carbon::yesterday())->get();
                if ($yesterdayData->isEmpty()) {
                    $lastEntry = CoinChart::orderBy('created_at', 'DESC')->take(1)->get();
                    $charts = $lastEntry;
                } else {
                    $charts = $yesterdayData;
                }
            } else {
                $charts = $todayData;
            }
        }
        elseif ($type == 'month') {

            $charts = CoinChart::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->orderBy('created_at', 'ASC')
                ->get();
        }
        elseif ($type == '4month') {

            $charts = CoinChart::where('created_at', '>=', Carbon::now()->subMonths(4))
                ->orderBy('created_at', 'ASC')
                ->get();
        }
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
        return view('front.index',compact('data'));
    }
   
    public function privacy_policy()
    {
        $policy = Policy::where('type', 'privacy')->firstOrFail();

        return view('front.privacy', compact('policy'));
    }

    public function terms_conditions()
    {
        $terms = Policy::where('type', 'terms')->firstOrFail();

        return view('front.terms', compact('terms'));
    }
}
