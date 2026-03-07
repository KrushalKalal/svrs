<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class FirebaseTest extends Command
{
    protected $signature = 'firebase:test';
    protected $description = 'Test Firebase connection';

    public function handle()
    {
        try {
            $factory = (new Factory)
                ->withServiceAccount(config('firebase.credentials'));

            $messaging = $factory->createMessaging();

            $this->info('✅ Firebase connected successfully');

        } catch (\Throwable $e) {
            $this->error('❌ Firebase connection failed');
            $this->error($e->getMessage());
        }
    }
}
