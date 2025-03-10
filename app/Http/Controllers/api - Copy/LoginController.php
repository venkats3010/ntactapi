<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Validator;
use Config;
use Session;
use DB;

class LoginController extends Controller
{

    public function adminLogin(Request $request)
    { 
        $validator = Validator::make($request->all(), [ 
            'username' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) { 
             return response()->json(['status' => 422, 'error'=>$validator->errors()], 422);            
        }

		try {
			$getUser = DB::table('users')->where('username', $request->get('username'))->first();
			if ($getUser) {
				if ($getUser && Hash::check($request->get('password'), $getUser->password)) {
                $userModel = \App\Models\User::find($getUser->id);
                if ($userModel) {
                    $accessToken = $userModel->createToken('authToken')->plainTextToken;               
                    Log::channel('auth')->info('testAPI-- ' . json_encode($getUser));
                    if ($getUser) {
                        return response(['status' => 200, 'result' => "true", 'message' => "user authentication successful", 'auth' => "admin", 'access_token' => $accessToken, 'data'=>$getUser]);
                    }else{
                        return json_encode($getUser);          
                    }
                } else {
                    return response(['status' => 201, 'error' => 'User not found'], 201);
                }           
              } else {
                return response(['status' => 201,'error' => 'Enter correct UserName'], 201); 
              }         
		} else {
			return response(['status' => 201,'error' => 'Enter correct UserName'], 201); 
		}
		} catch (\Exception $e) {
			// Handle any other exceptions
			echo "An error occurred: " . $e->getMessage();
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


    public function fieldLogin(Request $request){
        if(session('auth') != null){
            \Redirect::to('/')->send();
        }
        return view('field.auth.login');
    }



}
