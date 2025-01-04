<?php

use Illuminate\Support\Facades\Http;
// use Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

if (! function_exists('my_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param  string  $path
     * @param  bool|null  $secure
     * @return string
     */
    function my_asset($path, $secure = null)
    {
		$date = new DateTime( "NOW" );
		$version = '?id='.$date->format( "YmdH" );
        return app('url')->asset($path, $secure).$version;
        //return app('url')->asset("public/".$path, $secure).$version;
    }
}

