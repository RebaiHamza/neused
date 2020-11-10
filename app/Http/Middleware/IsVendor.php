<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsVendor
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

        if (Auth::check()) {
            
            $auth = Auth::user();

            if ($auth->role_id == 'v' || $auth->role_id == 'a') {

                return $next($request);

            } else {

                return abort('401','Unauthorized action');

            }

        } else {

            return abort('401','Unauthorized action');

        }

    }
}
