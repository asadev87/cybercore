<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InactivityLogout
{
    public function handle(Request $request, Closure $next)
    {
        $timeout = (int) config('session.inactivity_timeout_minutes', (int) env('INACTIVITY_LOGOUT_MINUTES', 30));
        if ($timeout > 0 && Auth::check()) {
            $last = (int) $request->session()->get('last_activity_ts', 0);
            $now  = time();

            if ($last && ($now - $last) > ($timeout * 60)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->with('status', 'session-expired');
            }
            $request->session()->put('last_activity_ts', $now);
        }
        return $next($request);
    }
}
