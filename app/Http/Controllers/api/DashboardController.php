<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\SurvayQuestionnaire;
use App\Models\Company;
use App\Models\Roles;
use DB;
use Validator;

class DashboardController extends Controller
{
    public function index()
    {
        //
    }

    public function get(Request $request)
    {	
		//
    }

   public function store(Request $request)
    {
		///
    }

    public function update(Request $request, $id)
    {
		//
    }

    public function destroy(string $id)
    {
		//
    }
	
	public function getCompany(Request $request)
    {	//	\DB::enableQueryLog(); 
        if ($request->has('id')) {
			$res = Company::where('id', $request->get('id'))->first();
		}else if ($request->has('name')) {
			$res = Company::where('name', 'like', '%' . $request->get('name') . '%')->get();
		}else{
            $res = DB::table('company')->where('status', 'A')->get();
        }
		// $qry = \DB::getQueryLog();
		return response(['status' => 200, 'result' => "true", 'message' => "Success", 'data'=>$res]);
    }
	
	public function getSurvayQuestionnaire(Request $request)
    {
		if ($request->has('id')) {
			$res = SurvayQuestionnaire::where('id', $request->get('id'))->first();
		}else if ($request->has('survay_type')) {
			$res = SurvayQuestionnaire::where('survay_type', $request->get('survay_type'))->get();
		}else{
            $res = DB::table('survay_questionnaire')->where('status', 'A')->get();
        }
        //$response = DB::table('survay_questionnaire')->get();
        return response(['status' => 200, 'result' => "true", 'message' => "Success", 'data'=>$res]);
    }
	
	public function getRoles(Request $request)
    {
		if ($request->has('id')) {
			$res = Roles::where('id', $request->get('id'))->first();
		}else{
            $res = DB::table('roles')->where('status', 'A')->get();
        }
        //$response = DB::table('roles')->get();
        return response(['status' => 200, 'result' => "true", 'message' => "Success", 'data'=>$res]);
    }
	
}
