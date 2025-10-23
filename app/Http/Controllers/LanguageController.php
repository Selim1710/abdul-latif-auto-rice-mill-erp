<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switchLanguage($lang)
    {
        if (array_key_exists($lang, Config::get('languages'))) {
            //dd($lang);
            Session::put('applocale', $lang);
        }
        //dd(Session::get('applocale'));
    	return redirect()->back();
    }
}
