<?php

namespace App\Http\Middleware;

use Closure;

class IsActive
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
        $isactive = @file_get_contents(public_path() . '/config.txt');
        if ($isactive) {
            if ($isactive == 1) {
                return $next($request)
                    ->header('Access-Control-Allow-Origin', '*')
                    ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            } else {
                return Response(view('accessdenied'));

            }
        } else {
            return Response(view('accessdenied'));
        }
    }
}
