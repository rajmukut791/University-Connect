<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class EnsureAccountAvailable
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $user?->releaseExpiredBlock();
        if ($user && (!$user->is_active || $user->is_blocked)) {
            if ($request->expectsJson()) return response()->json(['message'=>'Your account is unavailable.'],403);
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->withErrors(['login'=>'Your account is blocked or inactive.']);
        }
        return $next($request);
    }
}
