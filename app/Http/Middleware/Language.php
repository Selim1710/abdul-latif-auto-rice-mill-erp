<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Session::has('applocale') AND array_key_exists(Session::get('applocale'), Config::get('languages'))) {
            //dd(Session::get('applocale'));
            App::setLocale(Session::get('applocale'));
        }else {
            // This is optional as Laravel will automatically set the fallback language if there is none specified
            App::setLocale(Config::get('app.fallback_locale'));
        }
        //dd(App::setLocale(Session::get('applocale')));
        return $next($request);
    }
}
