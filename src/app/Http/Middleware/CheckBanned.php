<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if(Auth::check()) {

            if(Auth::user()->banned) {
                Auth::user()->logout();

                $message = 'Your account has been banned. Please contact administrator.';

                return redirect()->route('logout');
            }
        }

        return $response;
    }
}
