<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Crypt;
use Session;

class TasksController extends Controller
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

    public function index()
    {
        $data_post = array();
        $user = Session::get('auth');
		if($user == null){
			\Redirect::to('/login')->send();
		}
        $session= Session::all(); 
	    $data_post['userid'] = $created_by = $session['auth']['data']['id'];
		$data_post['role'] = $role = $session['auth']['data']['role'];
        $data_post['apikey'] = $this->api_key;
        $response = $this->api_call($data_post, 'tasks/get/');
        $tasks = json_decode($response->getBody()->getContents(), true);

       return view('tasks.index', compact('tasks'));
    }

    public function getuser(Request $request)
    {
        $response = array();
        $session= Session::all(); 
		$created_by = $session['auth']['data']['id'];
		$response['userid'] = $created_by;
		
		$data_post = array();
        $data_post['apikey'] = $this->api_key;
        //$data_post['name'] = $request->input('name');
        $res = $this->api_call($data_post, 'users/get/');
        $users = json_decode($res->getBody()->getContents(), true);
		
        return $users;
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $response = array();
        $session= Session::all(); 
		$created_by = $session['auth']['data']['id'];
		$response['userid'] = $created_by;
		
		$data_post = array();
        $data_post['apikey'] = $this->api_key;
        $res = $this->api_call($data_post, 'users/get/');
        $users = json_decode($res->getBody()->getContents(), true);
        
        $cres = $this->api_call($data_post, 'category/get/');
        $category = json_decode($cres->getBody()->getContents(), true);
        
        $pres = $this->api_call($data_post, 'priorities/get/');
        $priorities = json_decode($pres->getBody()->getContents(), true);  
        
        $sres = $this->api_call($data_post, 'statustypes/get/');
        $statustypes = json_decode($sres->getBody()->getContents(), true); 
        
        return view('tasks.create', compact('response', 'users', 'category', 'priorities', 'statustypes', 'created_by'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        ini_set('memory_limit', '2G');
        $data_post = array();
        $session= Session::all(); 
		$created_by = $session['auth']['data']['id'];
        $data_post['apikey'] = $this->api_key;
        $data_post['orgid'] = $request->input('orgid');
        $data_post['subject'] = $request->input('subject');
        $data_post['category'] = $request->input('category');
        $data_post['details'] = $request->input('details');
        $data_post['severity'] = $request->input('severity');
        $assignto = $request->input('assignto');
        $data_post['assignto'] = implode(",", $assignto); 
        //$data_post['assigntophone'] = $assigntophone = $request->input('assigntophone');
        //$data_post['assigncountrycode'] = $assigncountrycode = $request->input('assigncountrycode');
        $data_post['status'] = $request->input('status') ?? 1;
        $data_post['userid'] = $created_by;


       //print_r($_FILES['documents']['name']['files']);echo "<br>";
        //echo count($_FILES['documents']['name']['files']);echo "<br>";
        if(isset($_FILES['documents']['name']['files'])){
            foreach ($_FILES['documents']['name']['files'] as $key => $name) {
                
            $attachment = $_FILES['documents']['tmp_name']['files'][$key];
            $filetype = $_FILES['documents']['type']['files'][$key];
            $cfile = new \CURLFile($attachment, $filetype, $name);
            
               /* print_r($doc['name']['files'][0]);exit; 
                $attachment = $doc['tmp_name']['files'][$key];
                $filetype = $doc['type']['files'][$key];
                $namefiel = $doc['name']['files'][$key];*/
                
                $cfile = new \CURLFile($attachment, $filetype, $name);
            
                $filename = '';//$_POST['documents']['filename'][$key];
                $comment = isset($_POST['documents']['filecomment'][$key])?$_POST['documents']['filecomment'][$key]:"";
            
                $data_post['files[' . $key . ']'] = $cfile;
                $data_post['filenames[' . $key . ']'] = $filename;
                $data_post['comments[' . $key . ']'] = $comment;
            }
        }
        

        
        //$postdata = $request->all();
       
        $response = $this->post_file_curl_api($data_post, 'tasks/create/');
        //$res = json_decode($response->getBody()->getContents(), true);
        //print_r($data_post);exit; 
        if($response->status == 200){
    			//$assignedtoph = Crypt::encrypt($assigntophone);
				//$resetUrl = url('/').'/mpinlogin/'.$assignedtoph;
				//$postInput['mobilephone'] = "+".$assigncountrycode.$assigntophone;
				
				//$apiUrl = 'http://tinyurl.com/api-create.php?url=' . urlencode($resetUrl);
                //$shortUrl = file_get_contents($apiUrl);
            if(is_array($assignto) && count($assignto) > 0){    
                foreach($assignto as $assign) {
                    $datapost['apikey'] = $this->api_key;
                    $datapost['rowid'] = $assign;
                    $sres = $this->api_call($datapost, 'users/getuser/');
                    $user = json_decode($sres->getBody()->getContents(), true);
				    $postInput['mobilephone'] = "+".$user['user'][0]['countrycode'].$user['user'][0]['phone'];
    				$postInput['message']  = "A new task(".$response->TASKID.") has been created for ".$user['user'][0]['firstname'].", click following link to view - ".url('/');
    				
    				//print_r($postInput);
    				$resp = $this->post_curl_api($postInput, "notification/sms/");
    				//print_r($resp);
                }
            }
        }
        
        return $response; 

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
    public function edit(Request $request)
    {
        $session= Session::all(); 
		$created_by = $session['auth']['data']['id'];

        $data_post = $response = array();
        $data_post['apikey'] = $this->api_key;
        
        $res = $this->api_call($data_post, 'users/get/');
        $users = json_decode($res->getBody()->getContents(), true);
        
        $data_post['rowid'] = $request->input('rowid');
        $apiresponse = $this->api_call($data_post, 'tasks/getbyid/');
        $response = json_decode($apiresponse->getBody()->getContents(), true);

        $cres = $this->api_call($data_post, 'category/get/');
        $category = json_decode($cres->getBody()->getContents(), true);
        
        $pres = $this->api_call($data_post, 'priorities/get/');
        $priorities = json_decode($pres->getBody()->getContents(), true);  
        
        $sres = $this->api_call($data_post, 'statustypes/get/');
        $statustypes = json_decode($sres->getBody()->getContents(), true);
        
        return view('tasks.edit', compact('response', 'users', 'category', 'priorities', 'statustypes', 'created_by'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        ini_set('memory_limit', '2G');
        $data_post = array();
        $session= Session::all(); 
		$created_by = $session['auth']['data']['id'];
		$createdname = $session['auth']['data']['firstname'];
        $data_post['apikey'] = $this->api_key;
        $data_post['ticket_id'] = $tid = $request->input('tid');
        $data_post['rowid'] = $rowid = $request->input('rowid');
        $data_post['orgid'] = $request->input('orgid');
        //$data_post['subject'] = $request->input('subject');
        //$data_post['category'] = $request->input('category');
        $data_post['details'] = $request->input('details');
        $data_post['severity'] = $request->input('severity');
        $data_post['assignto'] = "";
        if($request->input('assignto')){
            $assignto = $request->input('assignto');
            $data_post['assignto'] = implode(",", $assignto);            
        }
        $data_post['status'] = $request->input('status') ?? '1';
        $data_post['createdby'] = $createdby = $request->input('createdby') ?? '';
        $data_post['userid'] = $created_by;


        if(isset($_FILES['documents']['name']['files'])){
            foreach ($_FILES['documents']['name']['files'] as $key => $name) {
                
                $attachment = $_FILES['documents']['tmp_name']['files'][$key];
                $filetype = $_FILES['documents']['type']['files'][$key];
                $cfile = new \CURLFile($attachment, $filetype, $name);
            
               /* print_r($doc['name']['files'][0]);exit; 
                $attachment = $doc['tmp_name']['files'][$key];
                $filetype = $doc['type']['files'][$key];
                $namefiel = $doc['name']['files'][$key];*/
                
                $cfile = new \CURLFile($attachment, $filetype, $name);
            
                $filename = '';//$_POST['documents']['filename'][$key];
                $comment = isset($_POST['documents']['filecomment'][$key])?$_POST['documents']['filecomment'][$key]:"";
            
                $data_post['files[' . $key . ']'] = $cfile;
                $data_post['filenames[' . $key . ']'] = $filename;
                $data_post['comments[' . $key . ']'] = $comment;
            }
        }
        
        $response = $this->post_file_curl_api($data_post, 'tasks/update/');
        //$response = json_decode($response->getBody()->getContents(), true);
        
            if($response->status == 200){
                $datapost['apikey'] = $this->api_key;
                $datapost['rowid'] = $createdby;
                /*$sres = $this->api_call($datapost, 'users/getuser/');
                $user = json_decode($sres->getBody()->getContents(), true);
				$postInput['mobilephone'] = "+".$user['user'][0]['countrycode'].$user['user'][0]['phone'];
				$postInput['message']  = $tid. " was updated (by".$createdname."), click following link to view or update. ".url('/');
				

				$resp = $this->post_curl_api($postInput, "notification/sms/");
                */
                $assign_to = [];
                $assigned_to_array = explode(',', $request->input('assigned_to'));
                $assigned_to_array = array_map('trim', $assigned_to_array);
                foreach ($assigned_to_array as $id) {
                    $assign_to[] = $id;
                }
                if (isset($created_by)) {
                    $assign_to[] = $created_by;
                }
                if (isset($createdby)) {
                    $assign_to[] = $createdby;
                }                
                //print_r($assign_to);exit;
                if(is_array($assign_to) && count($assign_to) > 0){    
                    foreach($assign_to as $assign) {
                        if($created_by != $assign){
                        $datapost['apikey'] = $this->api_key;
                        $datapost['rowid'] = $assign;
                        $sres = $this->api_call($datapost, 'users/getuser/');
                        $user = json_decode($sres->getBody()->getContents(), true);
    				    $postInput['mobilephone'] = "+".$user['user'][0]['countrycode'].$user['user'][0]['phone'];
        				$postInput['message']  = $tid. " was updated (by ".$createdname."), click following link to view or update. ".url('/');
        				
        				//print_r($postInput);
        				$resp = $this->post_curl_api($postInput, "notification/sms/");
        				//print_r($resp);
                        }
                    }
                }
                
            }
        
        return $response; 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data_post = array();
        $data_post['apikey'] = $this->api_key;
        $data_post['rowid'] = $id;  //$request->input('rowid');

        $response = $this->api_call($data_post, 'tasks/delete/');
        $response = json_decode($response->getBody()->getContents(), true);
        return json_encode($response);
    }
    
    public function archive(Request $request)
    {
        $data_post = array();
        $user = Session::get('auth');
		if($user == null){
			\Redirect::to('/login')->send();
		}
        $session= Session::all();
        $data_post['apikey'] = $this->api_key;
        $res = $this->api_call($data_post, 'users/get/');
        $users = json_decode($res->getBody()->getContents(), true);
        
	    $data_post['userid'] = $created_by = $session['auth']['data']['id'];
		$data_post['role'] = $role = $session['auth']['data']['role'];
        $data_post['isarchive'] = 1;
        $data_post['date_range'] = $date_range = $request->input('date_range');
        $data_post['createduser'] = $createduser = $request->input('createduser');
        $response = $this->api_call($data_post, 'tasks/get/');
        $tasks = json_decode($response->getBody()->getContents(), true);

       return view('tasks.archive', compact('tasks', 'date_range', 'createduser','users'));
    }
    
    public function statusupdate(Request $request)
    {
        $data_post = array();
        $data_post['apikey'] = $this->api_key;
        $data_post['rowid'] = $request->input('rowid');
        $data_post['status'] = $request->input('status');
        $response = $this->api_call($data_post, 'tasks/statusupdate/');
        $response = json_decode($response->getBody()->getContents(), true);
        return json_encode($response);
    }
    
    
}
