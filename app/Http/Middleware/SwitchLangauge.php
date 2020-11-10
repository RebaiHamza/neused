<?php

namespace App\Http\Middleware;

use Closure;
use App;
use Session;
use DB;
use App\Language;

class SwitchLangauge
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
    
        if (!Session::has('changed_language')) {

            $defLang = Language::where('def','=',1)->first();
            
            if(isset($defLang)){
                Session::put('changed_language', $defLang->lang_code);
            }
            else{
                Session::put('changed_language', 'en');
            }

        }

        App::setLocale(Session::get('changed_language'));

        return $next($request);
    }
}
