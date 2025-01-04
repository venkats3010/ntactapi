<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Session;

class ModulesController extends Controller
{
    protected $signature = 'run:make-controller {name}';
    protected $description = 'Run the make:controller Artisan command';
    
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
        $user = Session::get('auth');
		if($user == null){
			\Redirect::to('/login')->send();
		}
        
        $data_post = array();
        $data_post['apikey'] = $this->api_key;
        $response = $this->api_call($data_post, 'modules/get/');
        $modules = json_decode($response->getBody()->getContents(), true);

       return view('modules.index', compact('modules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $response = array();
        return view('modules.create', compact('response'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data_post = array();
        $session= Session::all(); 
		$created_by = $session['auth'];
        $data_post['apikey'] = $this->api_key;
        $data_post['title'] = $title = $request->input('title');
        $data_post['slug'] = $request->input('slug');
        $data_post['content'] = $request->input('content');
        $data_post['meta_title'] = $request->input('meta_title');
        $data_post['meta_description'] = $request->input('meta_description');
        $data_post['status'] = $request->input('status');
        $data_post['loginuser'] = $created_by;
        
        $response = $this->api_call($data_post, 'modules/create');
        $response = json_decode($response->getBody()->getContents(), true);
        return $response; 
        
exit;
        $name = $title.'Controller';
        
        Route::group(['prefix' => $title], function () {
            Route::get('/', 'App\Http\Controllers\TestController@index')->name('test');            
        });

        $command = "make:controller $name --resource";
        // Run the command
        Artisan::call($command);
        //print_r(Artisan::output());exit;
               
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
        $data_post = array();
        $data_post['apikey'] = $this->api_key;
        $data_post['rowid'] = $request->input('rowid');
        $apiresponse = $this->api_call($data_post, 'modules/getbyid/');
        $response = json_decode($apiresponse->getBody()->getContents(), true);       

        return view('modules.edit', compact('response'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data_post = array();
        $session= Session::all(); 
		$created_by = $session['data'][0]['id'];
        $data_post['apikey'] = $this->api_key;
        $data_post['rowid'] = $rowid = $request->input('id');
        $data_post['title'] = $title = $request->input('title');
        $data_post['slug'] = $request->input('slug');
        $data_post['content'] = $request->input('content');
        $data_post['meta_title'] = $request->input('meta_title');
        $data_post['meta_description'] = $request->input('meta_description');
        $data_post['status'] = $request->input('status');
        $data_post['loginuser'] = $created_by;

        $response = $this->api_call($data_post, 'modules/update');
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
        $data_post['rowid'] = $id;  //$request->input('rowid');

        $response = $this->api_call($data_post, 'modules/delete/');
        $response = json_decode($response->getBody()->getContents(), true);
        return json_encode($response);
    }
}
