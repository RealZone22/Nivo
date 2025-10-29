<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Masmerise\Toaster\Toaster;

class CheckIfUserIsDisabled
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->disabled) {
            auth()->logout();

            Toaster::error(__('auth::login.user_disabled'));

            return redirect()->route('auth.login');
        }

        return $next($request);
    }
}
