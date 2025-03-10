<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Config;
use Session;

class LoginController extends Controller
{

    public function api_call($params, $end_point){
        return Http::withBody(
            json_encode($params),
            'application/json'
        )->post($this->api_url.'/'.$end_point);
    }
    public function index() {
        Cache::flush();
        cache()->flush(); 
        if(Session::get('auth') == null){
            return view('auth.login');
        }elseif(Session::get('mpin') == 1){
            return view('tpurl.index');
        } else {
            if(Session::get('mpin') == 1){
                return view('auth.login');
            }
            
            $session= Session::all(); 
            $permissions = json_decode($session['auth']['data']['permissions'], true);
            if(is_array($permissions) && !in_array('2', $permissions)){echo "AAA";
		       return view('auth.login'); 
		    }
            return view('dashboard.index');
        }
        
    }
    public function mpinIndex($uid) {
        Cache::flush();
        cache()->flush(); 
        if(Session::get('mpin') == null || Session::get('mpin') == 0){
            $cryptuid = $uid;
            $uid = Crypt::decrypt($uid);
            return view('auth.mpinlogin', compact('uid', 'cryptuid'));
        }elseif(Session::get('mpin') == 1){
            return view('tpurl.index');
        }
    }
    public function userLogin(Request $request){
        if(session('auth') != null){
            \Redirect::to('/')->send();
        }
        return view('auth.login');
    }

    public function checkLogin(Request $request){
        $username = $request->input('username');
        $password = $request->input('password');
        $mpin = $request->input('mpin');
        $response = $this->api_call(array("apikey"=>$this->api_key,"username"=>$username, "password"=>$password, "mpin"=>$mpin), "auth/signin/");
          
        $auth = json_decode($response->getBody()->getContents(), true);
        Log::channel('auth')->info('testD-- ' . json_encode($auth));

        if ($auth['result'] == "true") {
            if($mpin == 1){
                $request->session()->put('mpin', $mpin);
            }
            $request->session()->put('auth', $auth);
            Session::put('auth', $auth);
            $request->session()->put('username', $request->input('username'));
            $request->session()->put('profilepicture', $auth['data']['profilepicture']);    
            Log::channel('auth')->info('User logged in: ' . $username . ' & IP Address : '.$_SERVER['REMOTE_ADDR']);
            return json_encode($auth);                
        }else{
            return json_encode($auth);          
        }
    }

    public function logout(Request $request)
    {
        $data = $request->session()->all();
        if(!empty($request->session()->all()) && !empty($data['data'][0]['username'])){
            $username = $data['data'][0]['username'];
            Log::channel('auth')->info('User logged out: ' . $username. ' & IP Address : '.$_SERVER['REMOTE_ADDR']);
        }
        $request->session()->forget('auth');
        Session::flush('username'); 
        $request->session()->forget('mpin');
        Session::flush('profilepicture');        
        Auth::logout();
        Session::flush();
        $request->session()->flush();
        Cache::flush();
        cache()->flush();
        session_unset();

        return redirect('/login');
    }


    public function sendLoginotp(Request $request)
    {

        $request->validate([
            'phonenumber' => 'required',
        ]);

        $response = $this->api_call(array("phone" => $request->input('phonenumber'), "token" => $request->input('token'), "apikey" => $this->api_key), "users/checkUserPhone/");
        $phone = json_decode($response->getBody()->getContents(), true);

        if (isset($phone['result']) && $phone['result'] == "true") {
            $response = $this->api_call(array("phone" => $request->input('phonenumber'), "token" => $request->input('token'), "apikey" => $this->api_key), "auth/checkPhoneOtp/");
            $auth = json_decode($response->getBody()->getContents(), true);
    
            if (isset($auth['status']) && $auth['status'] == 200) {
                return $auth;
            }else if(isset($auth['status']) && $auth['status'] == 201){
                return back()->withInput()->with('error', 'Please enter valid Phone Number!');
            }else{
                return back()->withInput()->with('error', 'Invalid token!');
            }            
        }else{
            return $phone;
        }
    }




    public function fieldLogin(Request $request){
        if(session('auth') != null){
            \Redirect::to('/')->send();
        }
        return view('field.auth.login');
    }



}
