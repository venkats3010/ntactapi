<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Session;

class ForgotPasswordController extends Controller
{
    public function api_call($params, $end_point){
        return Http::withBody(
            json_encode($params),
            'application/json'
        )->post($this->api_url.'/'.$end_point.'/?'.$this->api_key);
    }


    public function index(Request $request)
    {
        return view('auth.forgotPassword');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function submitForgotPasswordForm(Request $request, $flag=null)
    {
        $useremail = $request->input('email');
        if($useremail == ""){
            return back()->with('message', 'please enter your Valid Email!')->withInput();
            return false;
        }
        //return back()->with('message', 'We have e-mailed your password reset link!')->withInput();
        //echo "ewqrewqrqwr";exit;
        $request->validate([
            'email' => 'required|email|regex:/(.+)@(.+)\.(.+)/i',
        ]);

        $token = Str::random(32);
        $useremail = $request->input('email');
        $rowId = $request->input('row_id');

        $response = $this->api_call(array("useremail" => $useremail, "token" => $token, "apikey" => $this->api_key), "auth/forgotpwd/");
        $auth = json_decode($response->getBody()->getContents(), true);

        if ($auth['status'] == 200) {

            $emailParams = ['token' => $token, 'email' => $useremail, 'fromname' => "Mydesk Team", 'type' => "EmailAlert"];
            $sendEmail = $this->sendEmail($emailParams);

            if(!empty($rowId)){
                return response(json_encode($auth), 200);
            }
            return back()->with('message', 'We have e-mailed your password reset link!')->withInput();

        } else {
            return back()->with('error', 'Email Address Does Not Exist')->withInput();
        }
    }

    public function showResetPasswordForm($token)
    {
        $response = $this->api_call(array("token" => $token, "apikey" => $this->api_key), "auth/tokencheck/");
        $auth = json_decode($response->getBody()->getContents(), true);
        if ($auth['status'] == 200) {
            return view('resetPassword', ['token' => $token]);
        } else {
            Session::flush('username');
            Session::flush();
            return redirect('/forgotPassword')->with('error', 'Your token has Expired!');
        }
    }


    /* Create new Password */
    public function showCreatePasswordForm($token)
    {
        $response = $this->api_call(array("token" => $token, "apikey" => $this->api_key), "auth/tokencheck/");
        $auth = json_decode($response->getBody()->getContents(), true);
        if ($auth['status'] == 200) {
            return view('auth.createPassword', ['token' => $token]);
        } else {
            return redirect('/forgotPassword')->with('error', 'Your token has Expired!');
        }
    }

    public function submitResetPasswordForm(Request $request)
    {

        /*echo "<pre>";

        print_r($request->all());
        exit;*/

        $request->validate([
            'newPassword' => 'required',
            'confirmNewPassword' => 'required'
        ]);

        $response = $this->api_call(array("password" => $request->input('newPassword'), "token" => $request->input('token'), "flag_type" => $request->input('flag_type'), "apikey" => $this->api_key), "auth/resetpwd/");
        $auth = json_decode($response->getBody()->getContents(), true);

        if (isset($auth['status']) && $auth['status'] == 200) {

            $request->session()->flush();
            return redirect('/login')->with('message', $auth['message']);

        }else if(isset($auth['status']) && $auth['status'] == 201){
            return back()->withInput()->with('error', 'Your New Password and Old Password Should not be Same!');
           
        }else{
            return back()->withInput()->with('error', 'Invalid token!');
        }
       
    }

 
    public function createMpin($uid)
    {
        $cryptuid = $uid;
        $uid = Crypt::decrypt($uid);
        return view('auth.creatempin', compact('uid', 'cryptuid'));
    }
    public function submitCreateMpin(Request $request)
    {

        /*echo "<pre>";

        print_r($request->all());
        exit;*/

        $request->validate([
            'phone' => 'required',
            'mpin' => 'required',
            'confirmmpin' => 'required'
        ]);

        $response = $this->api_call(array("phone" => $request->input('phonenumber'), "token" => $request->input('token'), "mpin" => $request->input('mpin'), "confirmmpin" => $request->input('confirmmpin'), "apikey" => $this->api_key), "auth/resetmpin/");
        $auth = json_decode($response->getBody()->getContents(), true);

        if (isset($auth['status']) && $auth['status'] == 200) {

            $request->session()->flush();
            return redirect('/login')->with('message', $auth['message']);

        }else if(isset($auth['status']) && $auth['status'] == 201){
            return back()->withInput()->with('error', 'Your New Pin and Old Pin Should not be Same!');
           
        }else{
            return back()->withInput()->with('error', 'Invalid token!');
        }
       
    }

    public function sendotp(Request $request)
    {

        $request->validate([
            'phonenumber' => 'required',
        ]);

        $response = $this->api_call(array("phone" => $request->input('phonenumber'), "token" => $request->input('token'), "apikey" => $this->api_key), "users/checkUserPhone/");
        $phone = json_decode($response->getBody()->getContents(), true);

        if (isset($phone['result']) && $phone['result'] == "true") {
            $response = $this->api_call(array("phone" => $request->input('phonenumber'), "token" => $request->input('token'), "apikey" => $this->api_key), "auth/checkphone/");
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
    
    public function saveUpdateMpin(Request $request)
    {
        /* echo "<pre>";
        print_r($request->all());
        exit; */

        $request->validate([
            'phonenumber' => 'required',
            'mpin' => 'required',
            'confirmmpin' => 'required'
        ]);

        $response = $this->api_call(array("phone" => $request->input('phonenumber'), "token" => $request->input('token'), "mpin" => $request->input('mpin'), "confirmmpin" => $request->input('confirmmpin'), "apikey" => $this->api_key), "auth/resetmpin/");
        $auth = json_decode($response->getBody()->getContents(), true);

        if (isset($auth['status']) && $auth['status'] == 200) {
            $auth['uid']  = Crypt::encrypt($request->input('phonenumber'));
            return $auth;
        }else if(isset($auth['status']) && $auth['status'] == 201){
            return back()->withInput()->with('error', 'Your New Pin and Old Pin Should not be Same!');
           
        }else{
            return back()->withInput()->with('error', 'Invalid token!');
        }
    }

    public function forgotPasswordOtp()
    {
        return view('auth.forgotPasswordOtp');
    }
    public function saveUpdatePassword(Request $request)
    {
        /* echo "<pre>";
        print_r($request->all());
        exit; */

        $request->validate([
            'phonenumber' => 'required',
            'newpassword' => 'required',
            'confirmpassword' => 'required'
        ]);

        $response = $this->api_call(array("phone" => $request->input('phonenumber'), "token" => $request->input('token'), "newpassword" => $request->input('newpassword'), "confirmpassword" => $request->input('confirmpassword'), "apikey" => $this->api_key), "auth/updatePasswordPhone/");
        $auth = json_decode($response->getBody()->getContents(), true);

        if (isset($auth['status']) && $auth['status'] == 200) {
            return $auth;
        }else if(isset($auth['status']) && $auth['status'] == 201){
            return back()->withInput()->with('error', 'Your New Password and Old Password Should not be Same!');
           
        }else{
            return back()->withInput()->with('error', 'Invalid token!');
        }
    }
    
}
