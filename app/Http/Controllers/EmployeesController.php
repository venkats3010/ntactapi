<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Mail;
use Session;

class EmployeesController extends Controller
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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $session= Session::all(); 
	    $created_by = $session['auth']['data']['id'];
		$role = $session['auth']['data']['role'];
		
        $user = Session::get('auth');
        if($user == null || $role == 2){
            \Redirect::to('/logout')->send();
        }
        $data_post = array();
        $data_post['apikey'] = $this->api_key;
        $response = $this->api_call($data_post, 'users/get/');
        $users = json_decode($response->getBody()->getContents(), true);

        return view('employees.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $response = $data_post = $modules = array();
        $data_post['apikey'] = $this->api_key;
        $response = $this->api_call($data_post, 'modules/get/');
        $modules = json_decode($response->getBody()->getContents(), true);

        $rolesresponse = $this->api_call(array("apikey" => $this->api_key), 'roles/get/');
        $roles = json_decode($rolesresponse->getBody()->getContents(), true);

        return view('employees.create', compact('response', 'modules', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data_post = array();
       
        $data_post['apikey'] = $this->api_key;
        $data_post['firstname'] = $firstname = $request->input('firstname');
        $data_post['lastname'] = $lastname = $request->input('lastname');
        $data_post['email'] = $request->input('email');
        $data_post['countrycode'] = $countrycode = $request->input('countrycode');
        $data_post['phone'] = $phone = $request->input('phone');
        $data_post['gender'] = $request->input('gender');
        $data_post['status'] = $request->input('status');
        $data_post['permissions'] = $request->input('permissions');
        $data_post['role'] = $request->input('role');
        $data_post['orgid'] = $request->input('orgid');
        $token = Str::random(32);
        $email = $data_post['email'];
        $data_post['token'] = $token;

        $response = $this->api_call($data_post, 'users/create/');
        $response = json_decode($response->getBody()->getContents(), true);
        if ($response['result'] == "true") {
            //$emailParams = ['token' => $token, 'email' => $email, 'name' => $firstname, 'fromname' => "Encloud Team"];
            //$sendEmail = $this->sendEmail($emailParams);
            
            $postInput['mobilephone'] = "+".$countrycode.$phone;
    		$postInput['message']  = "Welcome to myDesk, click following link to login - ".url('/');
    		$resp = $this->post_curl_api($postInput, "notification/sms/");
            
        }
        return json_encode($response);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $response = $data_post = $modules = array();
        $data_post['apikey'] = $this->api_key;

        $response = $this->api_call($data_post, 'modules/get/');
        $modules = json_decode($response->getBody()->getContents(), true);

        $rolesresponse = $this->api_call($data_post, 'roles/get/');
        $roles = json_decode($rolesresponse->getBody()->getContents(), true);

        $data_post['rowid'] = $id;
        $userresponse = $this->api_call($data_post, 'users/getuserbyid/');
        $user = json_decode($userresponse->getBody()->getContents(), true);
        
        return view('employees.edit', compact('response', 'modules', 'roles', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $data_post = array();
       
        $data_post['apikey'] = $this->api_key;
        $data_post['rowid'] = $request->input('rowid');
        $data_post['firstname'] = $firstname = $request->input('firstname');
        $data_post['lastname'] = $lastname = $request->input('lastname');
        $data_post['email'] = $request->input('email');
        $data_post['countrycode'] = $request->input('countrycode');
        $data_post['phone'] = $request->input('phone');
        $data_post['gender'] = $request->input('gender');
        $data_post['status'] = $request->input('status');
        $data_post['permissions'] = $request->input('permissions');
        $data_post['role'] = $request->input('role');
        $data_post['orgid'] = $request->input('orgid');
        $token = Str::random(32);
        $email = $data_post['email'];
        $data_post['token'] = $token;

        $response = $this->api_call($data_post, 'users/update/');
        $response = json_decode($response->getBody()->getContents(), true);
        return json_encode($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data_post = array();
        $data_post['apikey'] = $this->api_key;
        $data_post['rowid'] = $id;

        $response = $this->api_call($data_post, 'users/delete/');
        $response = json_decode($response->getBody()->getContents(), true);
        return json_encode($response);
    }
    public function changepassword(Request $request)
    {

        $data_post = array();
        $data_post['apikey'] = $this->api_key;
        $data_post['rowid'] = $request->input('userid');
        $data_post['currentpassword'] = $request->input('currentpassword');
        $data_post['newpassword'] = $request->input('newpassword');
        $data_post['confirmpassword'] = $request->input('confirmpassword');

        $response = $this->api_call($data_post, 'auth/changepassword/');
        $response = json_decode($response->getBody()->getContents(), true);
        return json_encode($response);
    }

    public function profileedit($page)
    {
        $session = session()->all(); 
        $user = $session['auth'] ?? null; 
        
        $response = $data_post = $modules = array();
        $data_post['apikey'] = $this->api_key;

        $data_post['rowid'] = $user['data']['id'];
        $userresponse = $this->api_call($data_post, 'users/getuserbyid/');
        $user = json_decode($userresponse->getBody()->getContents(), true);
        
        return view('employees.profile', compact('response', 'user', 'page'));
    }

    public function getProfilePicture(Request $request)
    {
		$session= Session::all();
		
        $data_post = $response = array();
        $data_post['apikey'] = $this->api_key;
        $data_post['rowid'] = $rowid = $session['data'][0]['id'];
        $data_post['profilepicture'] = $profilepicture = $session['profilepicture'];
		

        if($profilepicture){
			$response['status'] = 200;
			$response['data'] = my_asset('assets/images/profilePictures/' . $profilepicture);
        }else{
			$response['status'] = 201;
			$response['data'] = '';
		}
		return $response;
    }
	public function upload_profile_pic(Request $request)
    {
		$session= Session::all();
		$response = array();
		$userrowid = $request->post('userrowid');      

        if ($request->hasFile('upload_pic')) {
            $folder_save_path = base_path("/public/assets/images/profilePictures");			
			//chmod ($folder_save_path, 0777);
			$filename = $userrowid . '.' . $request->file('upload_pic')->getClientOriginalExtension();
            $attachement_save_path = $folder_save_path . '/' . $filename;

            // Remove existing files based on the pattern

            $filePathPattern = $folder_save_path . '/' . $userrowid . '.*';
            $profiles = glob($filePathPattern);
            foreach ($profiles as $profile) {
                unlink($profile);
            }

            // Move the uploaded file to the specified path
            if ($request->file('upload_pic')->move($folder_save_path, $filename)) {
				
				$postInput = [
					'apikey' => $this->api_key,
					'rowid' => $request->post('userrowid'),
					'profilephoto' => $filename,
				];
				
				$res = $this->api_call($postInput, '/users/updateProfilePhoto/');
				$response = json_decode($res->getBody()->getContents(), true);
				//print_r($response);exit;
				if($response['status'] == 200){
					$response['status'] = 200;
					$response['message'] = "File is valid and was successfully uploaded.";
					$request->session()->put('profilepicture', $filename);
					
				}else{
					$response['status'] = 201;
					$response['message'] = "Error uploading file!.";		
				}
            } else {
				$response['status'] = 201;
                $response['message'] = "Error uploading file!";
            }
        } else {
			$response['status'] = 204;
            $response['message'] = "No file uploaded!";
        }
        
		//return json_encode($response);
        return response()->json($response);
        //return $response;
    }
}
