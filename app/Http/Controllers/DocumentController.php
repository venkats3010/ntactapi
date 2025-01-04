<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Session;

class DocumentController extends Controller
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

        $data_post = array();
        $user = Session::get('auth');
		if($user == null){
			\Redirect::to('/login')->send();
		}
        $session= Session::all(); 
	    $data_post['userid'] = $created_by = $session['auth']['data']['id'];
		$data_post['role'] = $role = $session['auth']['data']['role'];
        $data_post['apikey'] = $this->api_key;
        $response = $this->api_call($data_post, 'documents/get/');
        $documents = json_decode($response->getBody()->getContents(), true);

       return view('document.index', compact('documents'));
    }

    public function store(Request $request)
    {
        ini_set('memory_limit', '2G');
        $data_post = array();
        $session= Session::all(); 
		$created_by = $session['auth']['data']['id'];
        $data_post['apikey'] = $this->api_key;
        $data_post['docname'] = "Document";
        $data_post['status'] = $request->input('status') ?? '1';
        $data_post['userid'] = $created_by;
        
        foreach ($_FILES['files']['name'] as $key => $name) {
      
            $attachment = $_FILES['files']['tmp_name'][$key];
            $filetype = $_FILES['files']['type'][$key];
            $cfile = new \CURLFile($attachment, $filetype, $name);
      
            $data_post['files[' . $key . ']'] = new \CURLFile($attachment, $filetype, $name);
        } 

        $response = $this->post_file_curl_api($data_post, 'documents/create/');
        //$response = json_decode($response->getBody()->getContents(), true);
        return $response; 
    }

    public function destroy(string $id)
    {
        $data_post = array();
        $data_post['apikey'] = $this->api_key;
        $data_post['rowid'] = $id;  //$request->input('rowid');

        $response = $this->api_call($data_post, 'documents/delete/');
        $response = json_decode($response->getBody()->getContents(), true);
        return json_encode($response);
    }


    

    
}

