<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Session;

class ConsoleController extends Controller
{

    public function __construct(){
        $this->middleware(function ($request, $next) {
            $user = Session::get('auth');
            if($user == null){
                \Redirect::to('/login')->send();
            }          
            return $next($request);
        });
    }

    public function api_call($params, $end_point){
        return Http::withBody(
            json_encode($params),
            'application/json'
        )->post($this->api_url.'/'.$end_point.'/?'.$this->api_key);
    }
    
    public function index(Request $request){
        //echo 'Console view';
        $session= Session::all(); 
        $permissions = json_decode($session['auth']['data']['permissions'], true);
        $user = Session::get('auth');
		if($user == null ){
			\Redirect::to('/login')->send();
		}
        return view('console.index');
    }



    
}

