<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Gateway;
use Illuminate\Support\Facades\Log;

class ResetGatewayLimits extends Command
{
    protected $signature = 'gateway:reset-limits';
    protected $description = 'Reset daily transaction limits for all gateways';

    public function handle()
    {
        Gateway::query()->update(['current_amount' => 0]);
        Log::info('Gateway limits have been reset.');
        $this->info('Gateway limits have been reset.');
    }
}
