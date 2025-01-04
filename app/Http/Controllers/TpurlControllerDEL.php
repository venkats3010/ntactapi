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

class TpurlController extends Controller
{

    public function __construct(){
        $this->middleware(function ($request, $next) {
            $user = Session::get('mpin');
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
        $user = Session::get('auth');
		if($user == null){
			\Redirect::to('/login')->send();
		}
		$session= Session::all(); 
		$created_by = $session['auth']['data']['id'];
        
        $data_post = array();
        $data_post['apikey'] = $this->api_key;
        $data_post['userid'] = $created_by;
        $response = $this->api_call($data_post, 'tasks/get/');
        $tasks = json_decode($response->getBody()->getContents(), true);

       return view('tpurl.index', compact('tasks'));
    }

    public function getuser(Request $request)
    {
        $response = array();
        $session= Session::all(); 
		$created_by = $session['auth']['data']['id'];
		$response['userid'] = $created_by;
		
		$data_post = array();
        $data_post['apikey'] = $this->api_key;
        $data_post['name'] = $request->input('name');
        $res = $this->api_call($data_post, 'users/get/');
        $users = json_decode($res->getBody()->getContents(), true);
		
        return $users;
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        $data_post = $response = array();
        $data_post['apikey'] = $this->api_key;
        
        $res = $this->api_call($data_post, 'users/get/');
        $users = json_decode($res->getBody()->getContents(), true);
        
        $data_post['rowid'] = $request->input('rowid');
        $apiresponse = $this->api_call($data_post, 'tasks/getbyid/');
        $response = json_decode($apiresponse->getBody()->getContents(), true);

        $data_post['rowid'] = $request->input('rowid');
        $apiresponse = $this->api_call($data_post, 'tasks/getbyid/');
        $response = json_decode($apiresponse->getBody()->getContents(), true);

        $cres = $this->api_call($data_post, 'category/get/');
        $category = json_decode($cres->getBody()->getContents(), true);
        
        $pres = $this->api_call($data_post, 'priorities/get/');
        $priorities = json_decode($pres->getBody()->getContents(), true);  
        
        $sres = $this->api_call($data_post, 'statustypes/get/');
        $statustypes = json_decode($sres->getBody()->getContents(), true);
        
        return view('tpurl.edit', compact('response', 'users', 'category', 'priorities', 'statustypes'));
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
        $data_post['apikey'] = $this->api_key;
        $data_post['ticket_id'] = $request->input('tid');
        $data_post['rowid'] = $rowid = $request->input('rowid');
        $data_post['orgid'] = $request->input('orgid');
        //$data_post['subject'] = $request->input('subject');
        //$data_post['category'] = $request->input('category');
        $data_post['details'] = $request->input('details');
        $data_post['severity'] = $request->input('severity');
        $data_post['assignto'] = $request->input('assignto');
        $data_post['status'] = $request->input('status') ?? 'A';
        $data_post['userid'] = $created_by;

        // $attachment = $_FILES['files']['tmp_name'];
        // $filename = $_FILES['files']['name'];
        // $filetype = $_FILES['files']['type'];
        // $filesize = $_FILES['files']['size'];
        // $cfile = new \CURLFile($attachment, $filetype, $filename);
        // $data_post['files'] = $cfile;
        
        /*  foreach ($_FILES['files']['name'] as $key => $name) {
      
            $attachment = $_FILES['files']['tmp_name'][$key];
            $filetype = $_FILES['files']['type'][$key];
            $cfile = new \CURLFile($attachment, $filetype, $name);
      
            $data_post['files[' . $key . ']'] = new \CURLFile($attachment, $filetype, $name);
        } */
        
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
            
                $filename = $_POST['documents']['filename'][$key];
                $comment = $_POST['documents']['filecomment'][$key];
            
                $data_post['files[' . $key . ']'] = $cfile;
                $data_post['filenames[' . $key . ']'] = $filename;
                $data_post['comments[' . $key . ']'] = $comment;
            }
        }
        
        $response = $this->post_file_curl_api($data_post, 'tasks/update/');
        //$response = json_decode($response->getBody()->getContents(), true);
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
}
