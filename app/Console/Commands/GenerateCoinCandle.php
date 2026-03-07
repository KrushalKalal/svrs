<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateCoinCandle extends Command
{
    
    protected $signature = 'generate:coin-candle';
    protected $description = 'Generate coin chart candle every 5 minutes';

    public function handle()
    {
        $coin = \App\Models\Coin::where('status', 1)->first();

        if (!$coin) {
            $this->info('No active coin found');
            return;
        }

        $lastCandle = \App\Models\CoinChart::where('coin_id', $coin->id)
            ->latest()
            ->first();

        $openPrice = $lastCandle ? $lastCandle->close_price : $coin->start_price;

        $move = rand(-5, 5); 
        $closePrice = $openPrice + $move;

        if ($closePrice < $coin->start_price) {
            $closePrice = $coin->start_price;
        }

        if ($closePrice > $coin->end_price) {
            $closePrice = $coin->end_price;
        }

        $highPrice = max($openPrice, $closePrice) + rand(0, 3);
        $lowPrice  = min($openPrice, $closePrice) - rand(0, 3);

        $highPrice = min($highPrice, $coin->end_price);
        $lowPrice  = max($lowPrice, $coin->start_price);

        \App\Models\CoinChart::create([
            'coin_id'     => $coin->id,
            'open_price'  => $openPrice,
            'close_price' => $closePrice,
            'high_price'  => $highPrice,
            'low_price'   => $lowPrice,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        $this->info('Candle generated: O=' . $openPrice . ' C=' . $closePrice);
    }
}
