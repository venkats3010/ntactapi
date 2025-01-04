<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Session;

class DashboardController extends Controller
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

    public function index(Request $request){
        //echo 'Dashboard view';
        $session= Session::all(); 
        $permissions = json_decode($session['auth']['data']['permissions'], true);
        $user = Session::get('auth');
		if($user == null ){
			\Redirect::to('/login')->send();
		}
		if(Session::get('mpin') == 1){
		    return view('tpurl.index');
		}else{
		    return view('dashboard.index'); 
		}
    }


    public function api_call($params, $end_point){
        return Http::withBody(
            json_encode($params),
            'application/json'
        )->post($this->api_url.'/'.$end_point.'/?'.$this->api_key);
    }

    public function getUserModules1(Request $request)
	{
        $session = Session::all();
        $data_post['userid'] = $session['auth'];
		
        $data_post = $modules = array();
        $modules['permissions'] = $session['auth']['data']['permissions'];
        $data_post['apikey'] = $this->api_key;
        $response = $this->api_call($data_post, 'modules/get/');
        $modules['data'] = json_decode($response->getBody()->getContents(), true);
        return $modules;
    }
    public function getUserModules(Request $request)
    {
        $session = session()->all(); 
        $user = $session['auth'] ?? null; 
  
        if (!$user) {
            $modules = [
                'status' => 401,
                'message' => "User not authenticated",
            ];
            return $modules;
        }
    
        $data_post = [
            'userid' => $user['data']['id'], 
            'apikey' => $this->api_key,
        ];
    
        try {
            $response = $this->api_call($data_post, 'modules/get/');
            $modules = [
                'permissions' => $user['data']['permissions'] ?? [],
                'data' => json_decode($response->getBody()->getContents(), true),
            ];
    
            if (json_last_error() !== JSON_ERROR_NONE) {
                $modules = [
                    'status' => 500,
                    'data' => json_decode($response->getBody()->getContents(), true),
                ];
            }
    
        } catch (\Exception $e) {
            $modules = [
                'status' => 500,
                'data' => json_decode($response->getBody()->getContents(), true),
            ];
        }
        return $modules;
    }
    
    
    
    
    public function getDashboardData(Request $request)
    {
        $data_post = $response = array();
        $data_post['apikey'] = $this->api_key;   
        
        $session= Session::all(); 
		$created_by = $session['auth']['data']['id'];
		$data_post['role'] = $role = $session['auth']['data']['role'];
        $data_post['loginuser'] = $created_by;
        $res_summary = $this->api_call($data_post, 'analytics/getSummary/');
        $summary = json_decode($res_summary->getBody()->getContents(), true); 

		return view('dashboard.analytics', compact('summary'));
    }
    
}

