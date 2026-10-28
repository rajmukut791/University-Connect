<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class ExpiryMaintenanceService
{
    /** Keep visitor-driven fallback cleanup bounded when no scheduler is running. */
    public static function run(string $task, callable $callback): void
    {
        $key = 'expiry-maintenance:'.$task;
        if (!app()->runningUnitTests() && !Cache::add($key, true, 30)) {
            return;
        }
        try {
            $callback();
        } catch (\Throwable $error) {
            Cache::forget($key);
            throw $error;
        }
    }
}
