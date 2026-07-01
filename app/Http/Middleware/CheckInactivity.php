<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class CheckInactivity
{
    public function handle(Request $request, Closure $next)
    {
        $timeout = Config::get('session.inactivity_timeout', 600); // 10 minutes default

        if ($timeout > 0 && Auth::check()) {
            $lastActivity = session('last_activity_time');
            $currentTime = time();

            if ($lastActivity && ($currentTime - $lastActivity > $timeout)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Session expired due to inactivity'], 401);
                }

                return redirect()->route('login')->with('status', 'Sesi Anda telah berakhir karena tidak ada aktivitas selama beberapa waktu.');
            }

            session(['last_activity_time' => $currentTime]);
        }

        return $next($request);
    }
}