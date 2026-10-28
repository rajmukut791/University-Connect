<?php

namespace App\Http\Middleware;

use App\Services\PostLifecycleService;
use Closure;
use Illuminate\Http\Request;

class ArchiveExpiredPosts
{
    public function handle(Request $request, Closure $next)
    {
        \App\Services\ExpiryMaintenanceService::run('posts', fn () => PostLifecycleService::archiveExpired());
        return $next($request);
    }
}
