<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use App\Models\TimeSheet;
use App\Models\Company;
use Carbon\Carbon;
use DB;
use Validator;

class TimeSheetController extends Controller
{
	public function api_call($params, $end_point){
        return Http::withBody(
            json_encode($params),
            'application/json'
        )->post($this->ntact_api_url.'/'.$end_point.'/?'.$this->ntact_api_key);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    public function get(Request $request)
    {
	
	try {
    $response = [];
    if ($request->has('qsearch')) {
        //$query = TimeSheet::query();
        $query = DB::table('timesheet');
		
        /* if ($request->has('stdate') && $request->has('eddate')) {
			$query->whereBetween('clock_in', [
				$request->get('stdate'),
				$request->get('eddate').' 23:59:59'
			]);
        } */
		if ($request->has('searchdate')) {
			if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $request->get('searchdate'))) {
				$formattedDate = $request->get('searchdate');
			} else {
				$formattedDate = \Carbon\Carbon::createFromFormat('m/d/Y', $request->get('searchdate'))->format('Y-m-d');
			}			
			$query->whereDate('clock_in', $formattedDate);
		}else if ($request->has('stdate') && $request->has('eddate')) {
			$query->whereBetween('clock_in', [
				$request->get('stdate'),
				$request->get('eddate').' 23:59:59'
			]);
        }
		// sheet filter
		if ($request->filled('sheetFilter')) {
			if($request->get('sheetFilter') == "P"){
				$query->whereIn('status', ['A', 'S', 'P']);			
			}else if($request->get('sheetFilter') == "V"){
				$query->where('status', 'V');				
			}else if($request->get('sheetFilter') == "I"){
				$query->where('status', 'I');				
			}else{
				//$query->whereNotIn('status', ['D', 'I']);
				$query->where('status', '!=', 'I');
			}
		}else{
			//$query->whereNotIn('status', ['D', 'I']);
			$query->where('status', '!=', 'I');
		}
		if ($request->has('supervisorid')) {
			if ($request->has('role') && $request->has('role') != 1) {
				$query->where('supervisorid', $request->get('supervisorid'));
			}            
        }
		if ($request->has('companyid')) {
            $query->where('companyid', $request->get('companyid'));
        }
		if ($request->has('status') && !$request->filled('sheetFilter')) {
			if ($request->get('status') == 'V') {
				$query->where('status', $request->get('status'));
			}

			if ($request->get('status') == 'P') {
				//$query->whereIn('status', ['P', 'A', 'S','V']);
				$query->where('status', '!=', 'I');
			}
		}
        // Apply filters
        if ($request->has('empid')) {
            $query->where('emp_id', $request->get('empid'));
        }
        if ($request->has('tid')) {
            $query->where('id', $request->get('tid'));
        }
        if ($request->has('jobcode')) {
            $query->where('job_id', $request->get('jobcode'));
        }
        if ($request->has('classification')) {
            $query->where('classification', $request->get('classification'));
        }

        if ($request->has('groupByField')) {
            $groupByField = $request->get('groupByField');

            if ($groupByField == "classification") {
				$query->orderBy('classification', 'DESC');
            }
            if ($groupByField == "job_id") {
				$query->orderBy('job_id', 'DESC');
            }
            if ($groupByField == "cost_code_id") {
				$query->orderBy('cost_code_id', 'DESC');
            }
            if ($groupByField == "emp_id") {
				$query->orderBy('status');
				$query->orderBy('emp_id', 'DESC');
            }
        }else{
			$query->orderBy('emp_id', 'DESC');
			$query->orderByRaw("FIELD(is_split, 'N', 'Y')");
			$query->orderByRaw("FIELD(status, 'A', 'S', 'P', 'V', 'I')");
		}

        $response = $query->get()->map(function ($item) {
            $item->clockin_image_path = url('storage/logs/pictures/' . $item->clockin_pic);
            $item->clockout_image_path = url('storage/logs/pictures/' . $item->clockout_pic);

            $companyName = Company::where('id', $item->companyid)->first()->name ?? '';
            $item->companyName = $companyName;

            return $item;
        });

    } else if ($request->has('empid')) {
        $response = DB::table('timesheet')->where('emp_id', $request->get('empid'))
                             ->orderBy('id', 'DESC')
                             ->first();
    } else if ($request->has('tid')) {
        $response = DB::table('timesheet')->where('id', $request->get('tid'))->first();
    } else if ($request->has('supervisorid')) {
		$data_post = $request->all(); 
		$parsedData = json_encode($data_post, true);
		Log::info('This is an informational GET  Request ' . $parsedData);
        $query = DB::table('timesheet')->where('supervisorid', $request->get('supervisorid'));		
			if ($request->has('companyid')) {
				$query->where('companyid', $request->get('companyid'));
			}
			if ($request->has('clockinDate')) {
				$query->whereDate('clock_in', $request->get('clockinDate'));
			}
			if ($request->has('status')) {
				//$query->where('status', $request->get('status'));
				if ($request->get('status') == 'V') {
					$query->where('status', $request->get('status'));
				}

				if ($request->get('status') == 'P') {
					$query->whereIn('status', ['P', 'A', 'S']);
				}
			}			
		$response = $query->orderBy('id', 'DESC')->get()->map(function ($item) {
				 
				 $item->clockin_image_path = url('storage/logs/pictures/' . $item->clockin_pic);
				 $item->clockout_image_path = url('storage/logs/pictures/' . $item->clockout_pic);
			   
				 $companyName = Company::where('id', $item->companyid)->first()->name ?? '';
				 $item->companyName = $companyName;
				 
				 return $item;
			 });
    } else {
        $response = DB::table('timesheet')->orderBy('id', 'DESC')->get()->map(function ($item) {
            $item->clockin_image_path = url('storage/logs/pictures/' . $item->clockin_pic);
            $item->clockout_image_path = url('storage/logs/pictures/' . $item->clockout_pic);

            $companyName = Company::where('id', $item->companyid)->first()->name ?? '';
            $item->companyName = $companyName;

            return $item;
        });
    }

    return response()->json([
        'status' => 200,
        'result' => "true",
        'message' => 'Success',
        'data' => $response,
    ], 200);

} catch (QueryException $e) {
    return response()->json([
        'status' => 500,
        'error' => 'An error occurred while processing your request.',
        'details' => $e->getMessage(),
    ], 500);
}


    }
    public function timesheetCounts(Request $request)
    {
		try {
			$result = DB::table('timesheet')
				->select('status', DB::raw('count(status) as status_count'));
			//$result->where('supervisorid', '=', $request->get('supervisorid'));
			$result->where('companyid', '=', $request->get('companyid'));
			if ($request->has('searchdate')) {
				$formattedDate = Carbon::createFromFormat('m/d/Y', $request->get('searchdate'))->format('Y-m-d');
				$result->whereDate('clock_in', $formattedDate);
			}
			$result = $result->groupBy('status')->get();
			
			return response()->json([
				'status' => 200,
				'result' => "true",
				'message' => 'Success',
				'data' => $result,
			], 200);

		} catch (QueryException $e) {
			return response()->json([
				'status' => 500,
				'error' => 'An error occurred while processing your request.',
				'details' => $e->getMessage(),
			], 500);
		}
    }
	public function getlast(Request $request)
    {
        try {
			$response =[];
			if ($request->has('empid')) {
				$response = DB::table('timesheet')->where('emp_id', $request->get('empid'))
                             ->orderBy('id', 'DESC')
                             ->first();
			}
            return response()->json([
                'status' => 200,
				'result' => "true",
                'message' => 'Success',
                'data' => $response,
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'error' => 'An error occurred while processing your request.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
    public function supervisor(Request $request)
    {
		try {	
			$res = DB::table('timesheet as T')
				->join('company as C', 'T.companyid', '=', 'C.id')
				->where('T.supervisorid', '=', $request->get('supervisorid'))
				->where('T.status', '!=', 'I');
			if ($request->has('startdate') && $request->has('enddate')) {
				$res->whereBetween('T.clock_in', [
					$request->get('startdate'). ' 00:00:00',
					$request->get('enddate') . ' 23:59:59'
				]);
			} else {
				$startOfWeek = date('Y-m-d', strtotime('monday this week'));
				$endOfWeek = date('Y-m-d', strtotime('sunday this week'));    
				$res->whereBetween('T.clock_in', [
					$startOfWeek . ' 00:00:00',
					$endOfWeek . ' 23:59:59'
				]);
			}
			if ($request->has('status')) {
				if ($request->get('status') == 'V') {
					$res->where('T.status', $request->get('status'));
				}

				if ($request->get('status') == 'P') {
					$res->whereIn('T.status', ['P', 'A', 'S']);
				}
			}

			$res = $res->select(
				'T.supervisorid',
				'T.companyid',
				'C.name as companyName',
				DB::raw('MAX(T.status) as maxStatus'),
				DB::raw('count(T.companyid) as countEntries'),
				DB::raw('date(MAX(T.clock_in)) as clockin'),
				DB::raw("COUNT(CASE WHEN T.status IN ('P', 'A', 'S') THEN 1 END) as statusPCount"),
				DB::raw("COUNT(CASE WHEN T.status = 'V' THEN 1 END) as statusVCount")
			)
			->groupBy('T.supervisorid',  'T.companyid', 'C.name', DB::raw('date(T.clock_in)'))
			->orderBy(DB::raw('maxStatus'))
			//->orderBy('T.status', 'ASC')
			//->orderByRaw("FIELD(T.status, 'A', 'S', 'P', 'V', 'I')")
			->get();

		//DB::raw("CASE WHEN T.status IN ('P', 'A','S', 'D') THEN 'P' ELSE T.status END"),
			return response(['status' => 200, 'result' => "true", 'message' => "Success", 'data'=>$res]);
		}catch (QueryException $e) {
			return response()->json([
				'status' => 500,
				'error' => 'Something went wrong.',
				'details' => $e->getMessage(),
			], 500);
		}
	}		

	public function adminDashboard(Request $request)
    {
		try {	
			$res = DB::table('timesheet as T')
				->join('company as C', 'T.companyid', '=', 'C.id')				
				->join('users as U', DB::raw('T.supervisorid COLLATE utf8mb4_unicode_ci'), '=', DB::raw('U.phone COLLATE utf8mb4_unicode_ci'))				
				->where('T.status', '!=', 'I');
			if ($request->has('startdate') && $request->has('enddate')) {
				$res->whereBetween('T.clock_in', [
					$request->get('startdate'). ' 00:00:00',
					$request->get('enddate') . ' 23:59:59'
				]);
			} else {
				$startOfWeek = date('Y-m-d', strtotime('monday this week'));
				$endOfWeek = date('Y-m-d', strtotime('sunday this week'));    
				$res->whereBetween('T.clock_in', [
					$startOfWeek . ' 00:00:00',
					$endOfWeek . ' 23:59:59'
				]);
			}
			if ($request->has('status')) {
				if ($request->get('status') == 'V') {
					$res->where('T.status', $request->get('status'));
				}

				if ($request->get('status') == 'P') {
					$res->whereIn('T.status', ['P', 'A', 'S']);
				}
			}
			if ($request->has('supervisorid')) {
				$res->where('T.supervisorid', '=', $request->get('supervisorid'));
			}
			
			$res = $res->select(
				'T.supervisorid',
				'T.companyid',
				'C.name as companyName',
				'U.firstname as firstName',
				'U.lastname as lastName',
				DB::raw('MAX(T.status) as maxStatus'),
				DB::raw('count(T.companyid) as countEntries'),
				DB::raw('date(MAX(T.clock_in)) as clockin'),
				DB::raw("COUNT(CASE WHEN T.status IN ('P', 'A', 'S') THEN 1 END) as statusPCount"),
				DB::raw("COUNT(CASE WHEN T.status = 'V' THEN 1 END) as statusVCount")
			)
			->groupBy('T.supervisorid',  'T.companyid', 'C.name', 'U.firstname', 'U.lastname', DB::raw('date(T.clock_in)'))
			->orderBy('T.supervisorid')
			->orderBy('clockin')
			->orderBy(DB::raw('maxStatus'))
			->get();
			
			return response(['status' => 200, 'result' => "true", 'message' => "Success", 'data'=>$res]);
		}catch (QueryException $e) {
			return response()->json([
				'status' => 500,
				'error' => 'Something went wrong.',
				'details' => $e->getMessage(),
			], 500);
		}
	}
	
    public function addNewRow(Request $request, $id)
    {
        try {
           if (!is_numeric($id) || !is_int((int)$id)) {
				return response()->json(['message' => 'Invalid ID provided.'], 400);
			}

		$resp = TimeSheet::find($id);

		$companyid = $resp->companyid;
		$empid = $resp->emp_id;
        $postData = array("apikey" => $this->ntact_api_key, "companyid"=>$companyid, "empid" => $empid);
        $response = $this->api_call($postData, "getemp/");		
        $res_data = json_decode($response->getBody()->getContents(), true);
		$payrate = '';
		if(isset($res_data['data'][0]['payrate'])){
			$payrate = $res_data['data'][0]['payrate'];
		}

            $res = TimeSheet::create([
				'companyid' => $resp->companyid,
				'supervisorid' => $resp->supervisorid,
                'emp_id' => $resp->emp_id,
                'emp_name' => $resp->emp_name,
				'clock_in' => $resp->clock_in,
                'clock_out' => $resp->clock_out,
                'clockin_pic' => $resp->clockin_pic,
                'clockout_pic' => $resp->clockout_pic,
				'classification' => $resp->classification,
				'payrate' => $payrate,
				'clockin_survay' => $resp->clockin_survay,
				'clockout_survay' => $resp->clockout_survay,
                'status' => 'S',
                'is_split' => 'Y',
				'created_by' => $resp->created_by,
				'uu_id' => $resp->uu_id,
				'audit_logs' => json_encode([
					[
						'action' => 'Splitting',
						'updated_by' => $resp->created_by,
						'updated_at' => now(),
					]
				]),
            ]);
			$insertedId = $res->id;
			
			//----------------Calculating hours----------------
			$formattedDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $resp->clock_in)->format('Y-m-d');
			$timeSheetids = TimeSheet::where('emp_id', $resp->emp_id)->where('status', '!=', 'I')->whereDate('clock_in', $formattedDate)->pluck('id');
			$count = $timeSheetids->count();
			//print_r($timeSheetids);exit; 
			$resp->is_split = 'Y';
			$acthrs = $resp->hours;
			$hours = "00:00";
			$breaktime = "0";
			$equalTime = $this->calculateEqualTime($acthrs, $count, $hours, $breaktime);
			//$timesheet = DB::table('timesheet')->whereIn('id', $timeSheetids)->update(['hours' => $equalTime]);
			//----------------Calculating hours----------------
            return response()->json([
                'status' => 200,
                'message' => 'Time Entry created successfully.',
                'rowid' => $insertedId,
                'data' => $resp,
				'equalTime' => $equalTime,
				'empids' => json_encode($timeSheetids)
            ], 200);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json([
                    'status' => 400,
                    'error' => 'Something went wrong.',
                    'details' => $e->getMessage(),
                ], 400);
            }

            return response()->json([
                'status' => 500,
                'error' => 'An error occurred while processing your request.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function clockin(Request $request)
    {
        try {
            $validated = $request->validate([           
                'emp_id' => 'required',
				'supervisorid' => 'required',
                'companyid' => 'required'                
            ]);

		$companyid = $request->companyid;
		$empid = $validated['emp_id'];
        $postData = array("apikey" => $this->ntact_api_key, "companyid"=>$companyid, "empid" => $empid);
        $response = $this->api_call($postData, "getemp/");		
        $res_data = json_decode($response->getBody()->getContents(), true);
		$payrate = '';
		if(isset($res_data['data'][0]['payrate'])){
			$payrate = $res_data['data'][0]['payrate'];
		}

            $res = TimeSheet::create([
				'companyid' => $request->companyid,
				'supervisorid' => $request->supervisorid,
                'emp_id' => $validated['emp_id'],
                'emp_name' => $request->emp_name,
				'clock_in' => $request->clock_in,
                'clock_out' => $request->clock_out,
                'clockin_pic' => $request->clockin_pic,
				'classification' => $request->classification,
				'payrate' => $payrate,
                'status' => $request->status,
				'created_by' => $request->created_by,
				'uu_id' => $request->uu_id ?? '',
				'audit_logs' => json_encode([
					[
						'action' => 'Clockin',
						'updated_by' => $request->created_by,
						'updated_at' => now(),
					]
				]),
            ]);
			$insertedId = $res->id;
            return response()->json([
                'status' => 200,
                'message' => 'Time Entry created successfully.',
				'clock_in' =>$request->clock_in,
                'data' => $insertedId,
            ], 200);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json([
                    'status' => 400,
                    'error' => 'Something went wrong.',
                    'details' => $e->getMessage(),
                ], 400);
            }

            return response()->json([
                'status' => 500,
                'error' => 'An error occurred while processing your request.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
	
    public function clockout(Request $request)
    {
        try {
			$validator = Validator::make($request->all(), [
				'id' => 'required_without:uu_id',
				'uu_id' => 'required_without:id|uu_id',
				'emp_id' => 'required'
			]);
			if ($validator->fails()) {
				return response()->json(['errors' => $validator->errors()], 400);
			}
			
			$currentDateTime = now();
			$imageData = $request->image;
			$imageData = str_replace(' ', '+', $imageData);
			$decodedImage = base64_decode($imageData);
			$fileName = $request->emp_id.'_clockout_'.$request->actiontype.'_'.$currentDateTime->format('YmdHis').'.png';
			$img_path = storage_path('logs/pictures/' . $fileName);

			if($request->actiontype == 'clockout'){
				if ($request->image != '' && file_put_contents($img_path, $decodedImage) !== false) {
					if($request->has('id') && $request->id){
						$timesheet = DB::table('timesheet')->where('emp_id', $request->emp_id)->where('id', $request->id)->update(['clock_out' => $request->clock_out, 'clockout_survay' => $request->clockout_survay, 'clockout_pic' => $fileName]);
					}
					if($request->has('uu_id') && $request->uu_id){
						$timesheet = DB::table('timesheet')->where('emp_id', $request->emp_id)->where('uu_id', $request->uu_id)->update(['clock_out' => $request->clock_out, 'clockout_survay' => $request->clockout_survay, 'clockout_pic' => $fileName]);
					}			
					if ($timesheet) {
						return response()->json([
							'status' => 200,
							'message' => 'TimeSheet updated successfully.',
						], 200);
					}				
				}
			}else if($request->actiontype == "injuryclockout"){
				$timesheet = DB::table('timesheet')->where('emp_id', $request->emp_id)->where('id', $request->id)->update(['clockout_survay' => $request->clockout_survay, 'injury_checkout' => $request->injury_checkout]);
				if ($timesheet) {
					return response()->json([
						'status' => 200,
						'message' => 'TimeSheet updated successfully.',
					], 200);
				}
			}else{
				return response()->json([
					'status' => 500,
					'error' => 'An error occurred while processing your request.',
					'details' => $e->getMessage(),
				], 500);
			}
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'error' => 'An error occurred while processing your request.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
    public function update_survay(Request $request)
    {
        try {
			$validator = Validator::make($request->all(), [
			    'id' => 'required_without:uu_id',
				'uu_id' => 'required_without:id|uu_id',
				'emp_id' => 'required',
				'type' => 'required'
			]);
			if ($validator->fails()) {
				return response()->json(['errors' => $validator->errors()], 400);
			}
			if($request->type == "clockin" ){
				if($request->has('id') && $request->id){
					$timesheet = TimeSheet::where('emp_id', $request->emp_id)->where('id', $request->id)->update(['clockin_survay' => $request->clockin_survay]);
				}
				if($request->has('uu_id') && $request->uu_id){
					$timesheet = TimeSheet::where('emp_id', $request->emp_id)->where('uu_id', $request->uu_id)->update(['clockin_survay' => $request->clockin_survay]);
				}
			}
			if($request->type == "clockout"){
				if($request->has('id') && $request->id){
					$timesheet = TimeSheet::where('emp_id', $request->emp_id)->where('id', $request->id)->update(['clockout_survay' => $request->clockout_survay]);
				}
				if($request->has('uu_id') && $request->uu_id){
					$timesheet = TimeSheet::where('emp_id', $request->emp_id)->where('uu_id', $request->uu_id)->update(['clockout_survay' => $request->clockout_survay]);
				}
			}
			if ($timesheet) {
				return response()->json([
					'status' => 200,
					'message' => 'Updated successfully.',
				], 200);
			}

        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'error' => 'An error occurred while processing your request.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
	public function update_image(Request $request)
    {
        try {
			$validator = Validator::make($request->all(), [
				'id' => 'required_without:uu_id',
				'uu_id' => 'required_without:id|uu_id',
				'emp_id' => 'required',
				'image' => 'required',
				'actiontype' => 'required',
			]);
			if ($validator->fails()) {
				return response()->json(['errors' => $validator->errors()], 400);
			}
			$currentDateTime = now();
			$imageData = $request->image;
			$imageData = str_replace(' ', '+', $imageData);
			$decodedImage = base64_decode($imageData);
			$fileName = $request->emp_id.'_'.$request->actiontype.'_'.$currentDateTime->format('YmdHis').'.png';
			$img_path = storage_path('logs/pictures/' . $fileName);

			if (file_put_contents($img_path, $decodedImage) !== false) {
				if($request->actiontype == "clockin"){
					if($request->has('id') && $request->id){
						$timesheet = TimeSheet::where('emp_id', $request->emp_id)->where('id', $request->id)->update(['clockin_pic' => $fileName]);
					}
					if($request->has('uu_id') && $request->uu_id){
						$timesheet = TimeSheet::where('emp_id', $request->emp_id)->where('uu_id', $request->uu_id)->update(['clockin_pic' => $fileName]);
					}
				}
				if($request->actiontype == "clockout"){
					if($request->has('id') && $request->id){
						$timesheet = TimeSheet::where('emp_id', $request->emp_id)->where('id', $request->id)->update(['clockout_pic' => $fileName]);
					}
					if($request->has('uu_id') && $request->uu_id){
						$timesheet = TimeSheet::where('emp_id', $request->emp_id)->where('uu_id', $request->uu_id)->update(['clockout_pic' => $fileName]);
					}
				}			
				if ($timesheet) {
					return response()->json([
						'status' => 200,
						'message' => 'Time Entry created successfully.'
					], 200);
				}
			}else{
				return response()->json([
					'status' => 500,
					'error' => 'An error occurred while processing your request.',
					'details' => $e->getMessage(),
				], 500);
			}
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'error' => 'An error occurred while processing your request.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }


    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {			
        try {
			$validator = Validator::make($request->all(), [
				'employeeid' => 'required'
			]);
			if ($validator->fails()) {
				return response()->json(['errors' => $validator->errors()], 400);
			}

			if(!empty($request->clock_in)){
				if (strpos($request->clockindate, '/') !== false) {
					$clockin = Carbon::createFromFormat('m/d/Y', $request->clockindate . ' ' . $request->clock_in);
				} else {
					$clockin = Carbon::parse($request->clockindate . ' ' . $request->clock_in);
				}
				//$clockin = Carbon::parse($request->clock_in);
			}
			$clockout = NULL;
			if(!empty($request->clock_out)){
				if (strpos($request->clockindate, '/') !== false) {
					$clockout = Carbon::createFromFormat('m/d/Y', $request->clockindate . ' ' . $request->clock_out);
				} else {
					$clockout = Carbon::parse($request->clockindate . ' ' . $request->clock_out);
				}
				//$clockout = Carbon::parse($request->clock_out);
			}

			$status = $request->status ?? 'P';
			$timesheet = "";

				$updateData = [];
				if ($request->has('clock_in') && $request->get('clock_in') != null) {
					$updateData['clock_in'] = Carbon::parse($clockin)->format('Y-m-d H:i');					
				}
				if ($request->has('clock_out') && $request->get('clock_out') != null) {
					$updateData['clock_out'] = Carbon::parse($clockout)->format('Y-m-d H:i');					
				}
				if ($request->has('job_id') && $request->get('job_id') !== null && $request->has('jobname')) {
					$updateData['job_id'] = $request->job_id;
					$updateData['jobDescription'] = $request->jobname;
				}
				if ($request->has('cost_code_id') && $request->get('cost_code_id') !== null && $request->has('costcodename')) {
					$updateData['cost_code_id'] = $request->cost_code_id;
					$updateData['costcodeDescription'] = $request->costcodename;
				}
				if ($request->has('break_time') && $request->get('break_time') !== null) {
					$updateData['break_time'] = $request->break_time;				
				}
				if ($request->has('perdim') && $request->get('perdim') !== null) {
					$updateData['perdim'] = $request->perdim;
				}
				if ($request->has('hours') && $request->get('hours') !== null) {
					$updateData['hours'] = $request->hours;
				}
				if ($request->has('status') && $request->get('status') !== null) {
					$updateData['status'] = $request->status;
				}
				if ($request->has('updated_by')) {
					$updateData['updated_by'] = $request->updated_by;
				}
				//------------Create logs------------------//
				$timesheet = TimeSheet::find($request->id);
				$currentAuditLogs = json_decode($timesheet->audit_logs, true);
				$newAuditLog = [
					'action' => 'Updated',
					'updated_by' => $request->updated_by,
					'updated_at' => now(),
					'data' => json_encode($updateData),
				];
				$currentAuditLogs[] = $newAuditLog;
				$updateData['audit_logs'] = json_encode($currentAuditLogs);
				//$updateData['audit_logs'] = DB::raw('JSON_ARRAY_APPEND(audit_logs, "$", ?)', [json_encode($newAuditLog)]);
				if (count($updateData) > 0) {
					$timesheet = DB::table('timesheet')->where('id', $request->id)->update($updateData);
				}

			//if ($timesheet) {
				return response()->json([
					'status' => 200,
					'message' => 'Updated successfully.'
				], 200);
			/* }else{
				return response()->json([
					'status' => 400,
					'message' => 'Failed to update.'
				], 400);
			} */
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'error' => 'An error occurred while processing your request.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

	public function updatebulk(Request $request)
	{
	try {
		    $data_post = $request->all();    
			//$timesheetData = json_encode($data_post['timesheetData']);    
			$timesheetData = $data_post['timesheetData'];    
			$page = $data_post['page'];
			//parse_str($timesheetData, $parsedData);
			$parsedData = json_encode($timesheetData, true);
			$timesheetData = json_decode($timesheetData, true);
			Log::info('This is an informational message Request ' . $parsedData);
		
		$timesheetData = array_filter($timesheetData, function ($value) {
			return $value !== null;  // Keep non-null values
		});
		$timesheetData = array_values($timesheetData);
		foreach ($timesheetData as $sheet) {
		if($sheet){
			Log::info('This is an informational message Request sshee' . json_encode($sheet));

					Log::info('This is an informational message Request dd ' . $sheet['rowid']);
				if (isset($sheet['rowid']) && $sheet['rowid'] != '' && $sheet['rowstatus'] != 'V' && $sheet['rowstatus'] != 'C') {
					Log::info('This is an informational message Requestii ' . $sheet['rowid']);
					$clockin = Carbon::parse($sheet['clockindate'].' '.$sheet['clockin'])->format('Y-m-d H:i');
					$clockout = NULL;
					if(!empty($sheet['clockout'])){
						$clockout = Carbon::parse($sheet['clockindate'].' '.$sheet['clockout'])->format('Y-m-d H:i');
					}
					$jobid = 0;$jobname = '';
					if(!empty($sheet['jobid'])){
						$jobid = $sheet['jobid'];
					}					
					if(!empty($sheet['jobname'])){
						$jobname = $sheet['jobname'];
					}
					$costcodeid = 0;$costcodename = '';
					if(!empty($sheet['costcodeid'])){
						$costcodeid = $sheet['costcodeid'];
					}					
					if(!empty($sheet['costcodename'])){
						$costcodename = $sheet['costcodename'];
					}					
					$breaktime = $sheet['breaktime'] ?? 0;
					$overtime = $sheet['overtime'] ?? '';
					$perdim = $sheet['perdim'] ?? '';
					$hours = $sheet['hours'] ?? '';
					$updated_by = $data_post['updated_by'] ?? '';
					if(isset($page) && $page == "timesheet"){
						$timesheet = DB::table('timesheet')->where('id', $sheet['rowid'])->update([
							'clock_in' => $clockin, 'clock_out' => $clockout, 'break_time' => $breaktime, 'job_id' => $jobid, 'cost_code_id' => $costcodeid, 'break_time' => $breaktime, 'perdim' => $perdim, 'hours' => $hours, 'jobDescription' => $jobname, 'costcodeDescription' => $costcodename, 'status' => 'V', 'updated_by' => $updated_by,
						]);	
					}else if(isset($page) && $page == "payroll"){
						$timesheet = DB::table('timesheet')->where('id', $sheet['rowid'])->update([
							 'break_time' => $breaktime, 'overtime'=>$overtime,'perdim' => $perdim, 'hours' => $hours, 'status' => 'V', 'updated_by' => $updated_by,
						]);	
					}else{
						$timesheet = DB::table('timesheet')->where('id', $sheet['rowid'])->update([
							'clock_in' => $clockin, 'clock_out' => $clockout, 'break_time' => $breaktime, 'job_id' => $jobid, 'cost_code_id' => $costcodeid, 'break_time' => $breaktime, 'perdim' => $perdim, 'hours' => $hours, 'status' => 'V', 'updated_by' => $updated_by,
						]);	
					}					

					Log::info('Timesheet updated for QRY ' . $timesheet);
				}

			}
		}


			return response()->json(['status' => '200', 'result' => 'true', 'message' => 'Timesheets saved successfully!']);
			} catch (QueryException $e) {
				return response()->json([
					'status' => 500,
					'error' => 'An error occurred while processing your request.',
					'details' => $e->getMessage(),
				], 500);
			}

	}



	public function destroy($id)
	{
		$res = TimeSheet::find($id);
		if ($res) {
			$res->status = 'I';
			$res->save();
			
			$formattedDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $res->clock_in)->format('Y-m-d');
			$timeSheetids = TimeSheet::where('emp_id', $res->emp_id)->whereDate('clock_in', $formattedDate)->pluck('id');
			$count = $timeSheetids->count();
			$hours = "00:00";
			$breaktime = "0";
			$timesheet = DB::table('timesheet')->whereIn('id', $timeSheetids)->update(['hours' => $hours]);
			if($res->is_split == "N"){
				$timesheet = DB::table('timesheet')->whereIn('id', $timeSheetids)->update(['status' => 'I']);
			}

			return response()->json([
				'status' => 200,
				'message' => 'Delete successfully.',
			], 200);
		}

		return response()->json([
			'message' => 'Resource not found.',
		], 404);
	}

	public function restore($id)
	{
		$res = TimeSheet::find($id);
		if ($res) {
			$res->status = 'A';
			$res->save();

			return response()->json([
				'status' => 200,
				'message' => 'Updated successfully.',
			], 200);
		}

		return response()->json([
			'message' => 'Resource not found.',
		], 404);
	}

	public function updateInjuryClockout(Request $request, $id)
	{
		try {
			$validator = Validator::make($request->all(), [
				'clockout' => 'required'
			]);
			if ($validator->fails()) {
				return response()->json(['errors' => $validator->errors()], 400);
			}
			$res = TimeSheet::find($id);
			if ($res) {
				$res->clock_out = $request->clockout;
				$res->save();

				return response()->json([
					'status' => 200,
					'message' => 'Updated successfully.',
				], 200);
			}

			return response()->json([
				'message' => 'Resource not found.',
			], 404);
		} catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'error' => 'An error occurred while processing your request.',
                'details' => $e->getMessage(),
            ], 500);
        }
	}

    public function updateMultiple(Request $request)
    {
        try {			
			Log::info('updateMultiple Payload' . json_encode($request->all()));
			$validator = Validator::make($request->all(), [
				'rowid' => 'required'
			]);
			if ($validator->fails()) {
				return response()->json(['errors' => $validator->errors()], 400);
			}
			$clockin = Carbon::parse($request->clock_in)->format('Y-m-d H:i');
			$clockout = Carbon::parse($request->clock_out)->format('Y-m-d H:i');
			$ids = explode(',', $request->rowid);

			$updateData = [];			
			if ($request->has('clock_in') && $request->get('clock_in') != null) {
				$updateData['clock_in'] = $clockin;
				$updateData['hours'] = "";
			}
			if ($request->has('clock_out') && $request->get('clock_out') != null) {
				$updateData['clock_out'] = $clockout;
				$updateData['hours'] = "";
			}
			if ($request->has('job_id') && $request->get('job_id') !== null && $request->has('jobname')) {
				$updateData['job_id'] = $request->job_id;
				$updateData['jobDescription'] = $request->jobname;
			}
			if ($request->has('cost_code_id') && $request->get('cost_code_id') !== null && $request->has('costcodename')) {
				$updateData['cost_code_id'] = $request->cost_code_id;
				$updateData['costcodeDescription'] = $request->costcodename;
			}
			if ($request->has('break_time') && $request->get('break_time') !== null) {
				$updateData['break_time'] = $request->break_time;				
			}
			if ($request->has('perdim') && $request->get('perdim') !== null) {
				$updateData['perdim'] = $request->perdim;
			}
			// if ($request->has('updated_by')) {
				$updateData['updated_by'] = $request->updated_by;
			//}
			
			$timesheet = DB::table('timesheet')->whereIn('id', $ids)->update($updateData);

			if (isset($request->break_time) && $request->filled('break_time')) {
				//$updateDataForBreak = $updateData;
				$updateDataForBreak['break_time'] = 0;
				$sheetBreaktime = DB::table('timesheet')
					->whereIn('id', $ids)
					->where('is_split', 'Y')
					->update($updateDataForBreak);
			}

			
			//------------Create logs------------------//
			$timesheets = DB::table('timesheet')->whereIn('id', $ids)->get();
			foreach ($timesheets as $timesheet) {
				$currentAuditLogs = json_decode($timesheet->audit_logs, true) ?? [];
				$newAuditLog = [
					'action' => 'Updated',
					'updated_by' => $request->updated_by,
					'updated_at' => now(),
					'data' => json_encode($updateData),
				];
				$currentAuditLogs[] = $newAuditLog;
				DB::table('timesheet')->where('id', $timesheet->id)->update([
					'audit_logs' => json_encode($currentAuditLogs),
				]);
			}			
			
			//if ($timesheet) {
				return response()->json([
					'status' => 200,
					'message' => 'Updated successfully.'
				], 200);
			/* }else{
				return response()->json([
					'status' => 400,
					'message' => 'Failed to update.'
				], 400);
			} */
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'error' => 'An error occurred while processing your request.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function getRecapData(Request $request)
    {
		try {
			$res = DB::table('timesheet')							
				->where('status', '!=', 'I');
			if ($request->has('companyid') && !empty($request->input('companyid'))) {
				$res->where('companyid', $request->get('companyid'));
			}
			if ($request->has('empid') && !empty($request->input('empid'))) {
				$res->where('emp_id', $request->get('empid'));
				//$res->where('emp_id', $request->get('empid'))
				//	->orWhere('classification', $request->get('empid'));
			}
			if ($request->has('classificationid') && !empty($request->input('classificationid'))) {
				$res->where('classification', $request->get('classificationid'));
			}
			if ($request->has('status') && !empty($request->input('status'))) {
				//$res->where('status', $request->get('status'));
				if($request->get('status') == "P"){
					$res->whereIn('status', ['A', 'S', 'P']);			
				}else if($request->get('status') == "V"){
					$res->where('status', $request->get('status'));				
				}
			}				
			$dates = [];			
			if ($request->has('startdate') && $request->has('enddate')) {
				$res->whereBetween('clock_in', [
					$request->get('startdate'). ' 00:00:00',
					$request->get('enddate') . ' 23:59:59'
				]);
				
				$startOfWeek = $request->has('startdate') ? Carbon::parse($request->get('startdate')) : '';
				$endOfWeek = $request->has('enddate') ? Carbon::parse($request->get('enddate')) : '';
				if ($startOfWeek instanceof Carbon && $endOfWeek instanceof Carbon) {
				while ($startOfWeek <= $endOfWeek) {
					$dates[] = $startOfWeek->format('Y-m-d');
					$startOfWeek->addDay();
				}
				}
			} else {
				$startOfWeek = date('Y-m-d', strtotime('monday this week'));
				$endOfWeek = date('Y-m-d', strtotime('sunday this week'));    
				$res->whereBetween('clock_in', [
					$startOfWeek . ' 00:00:00',
					$endOfWeek . ' 23:59:59'
				]);
				if ($startOfWeek instanceof Carbon && $endOfWeek instanceof Carbon) {
				while ($startOfWeek <= $endOfWeek) {
					$dates[] = $startOfWeek->format('Y-m-d');
					$startOfWeek->addDay();
				}
				}
			}
			
			$res = $res->select(
					'emp_id',				
					DB::raw('MAX(emp_name) AS emp_name'),
					//DB::raw('MAX(clock_in) AS clock_in'),
					DB::raw('MAX(companyid) AS companyid'),
					DB::raw('MAX(supervisorid) AS supervisorid'),
					DB::raw('MAX(classification) AS classification'),
					DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(hours))) AS total_hours'),
					DB::raw('SUM(perdim) AS total_perdiem'),
					DB::raw('MAX(DATE_FORMAT(clock_in, "%W, %M %d, %Y")) AS report_date'),        
					DB::raw('SEC_TO_TIME(SUM(CASE WHEN DAYOFWEEK(clock_in) = 1 THEN TIME_TO_SEC(hours) ELSE 0 END)) AS sunday'),
					DB::raw('SEC_TO_TIME(SUM(CASE WHEN DAYOFWEEK(clock_in) = 2 THEN TIME_TO_SEC(hours) ELSE 0 END)) AS monday'),
					DB::raw('SEC_TO_TIME(SUM(CASE WHEN DAYOFWEEK(clock_in) = 3 THEN TIME_TO_SEC(hours) ELSE 0 END)) AS tuesday'),
					DB::raw('SEC_TO_TIME(SUM(CASE WHEN DAYOFWEEK(clock_in) = 4 THEN TIME_TO_SEC(hours) ELSE 0 END)) AS wednesday'),
					DB::raw('SEC_TO_TIME(SUM(CASE WHEN DAYOFWEEK(clock_in) = 5 THEN TIME_TO_SEC(hours) ELSE 0 END)) AS thursday'),
					DB::raw('SEC_TO_TIME(SUM(CASE WHEN DAYOFWEEK(clock_in) = 6 THEN TIME_TO_SEC(hours) ELSE 0 END)) AS friday'),
					DB::raw('SEC_TO_TIME(SUM(CASE WHEN DAYOFWEEK(clock_in) = 7 THEN TIME_TO_SEC(hours) ELSE 0 END)) AS saturday')
				)
			->groupBy('emp_id')
			->orderBy('emp_id')
			->get();
			if($res){
				return response(['status' => 200, 'result' => "true", 'message' => "Success", 'data'=>$res, 'dates'=>$dates]);
			}else{
				return response()->json([
				'status' => 500,
				'result' => 'false',
				'message' => "No data found",
				'data' => array(),
			], 500);
			}
		}catch (QueryException $e) {
			return response()->json([
				'status' => 500,
				'error' => 'Something went wrong.',
				'details' => $e->getMessage(),
			], 500);
		}
    }
	public function getSageData(Request $request)
	{
		try {
			$res = DB::table('timesheet')
				->where('status', '!=', 'I');

			if ($request->has('companyid') && !empty($request->input('companyid'))) {
				$res->where('companyid', $request->get('companyid'));
			}

			if ($request->has('empid') && !empty($request->input('empid'))) {
				$res->where(function ($query) use ($request) {
					$query->where('emp_id', $request->get('empid'))
						->orWhere('classification', $request->get('empid'));
				});
			}

			if ($request->has('status') && !empty($request->input('status'))) {
				$res->where('status', $request->get('status'));
			}

			$dates = [];
			if ($request->has('startdate') && $request->has('enddate')) {
				$res->whereBetween('clock_in', [
					$request->get('startdate') . ' 00:00:00',
					$request->get('enddate') . ' 23:59:59'
				]);
				$startOfWeek = Carbon::parse($request->get('startdate'));
				$endOfWeek = Carbon::parse($request->get('enddate'));            
				if ($startOfWeek instanceof Carbon && $endOfWeek instanceof Carbon) {
					while ($startOfWeek <= $endOfWeek) {
						$dates[] = $startOfWeek->format('Y-m-d');
						$startOfWeek->addDay();
					}
				}
			} else {
				$startOfWeek = Carbon::parse('monday this week');
				$endOfWeek = Carbon::parse('sunday this week');
				$res->whereBetween('clock_in', [
					$startOfWeek->format('Y-m-d') . ' 00:00:00',
					$endOfWeek->format('Y-m-d') . ' 23:59:59'
				]);
				while ($startOfWeek <= $endOfWeek) {
					$dates[] = $startOfWeek->format('Y-m-d');
					$startOfWeek->addDay();
				}
			}
			$res = $res->orderBy('emp_id')->get();

			return response()->json([
				'status' => 200,
				'result' => "true",
				'message' => "Success",
				'data' => $res,
				'dates' => $dates
			]);
			
		} catch (QueryException $e) {
			return response()->json([
				'status' => 500,
				'error' => 'Something went wrong.',
				'details' => $e->getMessage(),
			], 500);
		}
	}	
    public function updateMultipleRecap(Request $request)
    {
        try {
			$validator = Validator::make($request->all(), [
				'rowid' => 'required'
			]);
			if ($validator->fails()) {
				return response()->json(['errors' => $validator->errors()], 400);
			}
			$clockin = Carbon::parse($request->clock_in)->format('Y-m-d H:i');
			$clockout = Carbon::parse($request->clock_out)->format('Y-m-d H:i');
			$ids = explode(',', $request->rowid);

			$timesheet = DB::table('timesheet')->whereIn('id', $ids)->update([
				 'break_time' => $request->break_time, 'overtime'=>$request->overtime,'perdim' => $request->perdiem, 'hours' => $request->totalhours, 'status' => 'C', 'updated_by' => $request->updated_by,
			]);		
			
			if ($timesheet) {
				return response()->json([
					'status' => 200,
					'message' => 'Updated successfully.'
				], 200);
			}else{
				return response()->json([
					'status' => 400,
					'message' => 'Failed to update.'
				], 400);
			}
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'error' => 'An error occurred while processing your request.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
    public function calculateHours(Request $request)
    {
        try {
			Log::info('calculateHours Payload' . json_encode($request->all()));
			$validator = Validator::make($request->all(), [
				'empid' => 'required'
			]);
			if ($validator->fails()) {
				return response()->json(['errors' => $validator->errors()], 400);
			}
			
			$formattedDate = \Carbon\Carbon::createFromFormat('m/d/Y', $request->get('searchdate'))->format('Y-m-d');
			$hours = $request->hours;
			$breaktime = $request->breaktime;

			$timeSheet = TimeSheet::where('emp_id', $request->get('empid'))->where('status', '!=', 'I')->whereDate('clock_in', $formattedDate)->pluck('id');
			$count = $timeSheet->count();
			//print_r($timeSheet);exit; 
			$acthrs = $request->get('actualhours');
			
			$equalTime = $this->calculateEqualTime($acthrs, $count, $hours, $breaktime);
			
			if ($equalTime) {
				//$timesheet = DB::table('timesheet')->whereIn('id', $timeSheet)->update(['hours' => $equalTime]);
				return response()->json([
					'status' => 200,
					'message' => 'Updated successfully.',
					'equalTime' => $equalTime,
					'rowid' => json_encode($timeSheet)
				], 200);
			}else{
				return response()->json([
					'status' => 400,
					'message' => 'Failed to update.',
					'equalTime' => $equalTime
				], 400);
			}
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'error' => 'An error occurred while processing your request.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
private function calculateEqualTime($acthrs, $count, $hours, $breaktime)
{
    $hours = (int)$this->convertTimeToMinutes($hours);
    $acthrs = (int)$this->convertTimeToMinutes($acthrs);
	if($breaktime > 0){
		$acthrs = (int)$acthrs - $breaktime;
	}	
    if ($acthrs < $hours) {
        //throw new \Exception("Actual hours should be greater than or equal to hours.");
		return 0;
    }
    $totalMinutesAfterSubtraction = $acthrs - $hours;
    if ($count <= 1) {
        //throw new \Exception("Count must be greater than 1.");
		return 0;
    }

    $equalMinutes = $totalMinutesAfterSubtraction / ($count - 1);

    $equalHours = floor($equalMinutes / 60);
    $equalRemainingMinutes = $equalMinutes % 60;

    return sprintf("%02d:%02d", $equalHours, $equalRemainingMinutes);
}
private function convertTimeToMinutes($time)
{
	try {
		list($hours, $minutes) = explode(":", $time);
		if (!is_numeric($hours) || !is_numeric($minutes)) {
			return 0;
		}
		return (int)$hours * 60 + (int)$minutes;
	} catch (\Exception $e) {        
        return 0;
    }
}

	
	
// Sync api

public function saveClockinCheckout(Request $request)
{
    ini_set('memory_limit', '512M');
	$payloaddata = $request->all();
	Log::info('Clockin-Out Payload' . json_encode($payloaddata));
    try {
        $validated = $request->validate([
            'uu_id' => 'required',
			'emp_id' => 'required'
        ]);

        $timeSheet = TimeSheet::where('uu_id', $request->uu_id)->first();
		//$timeSheet = DB::table('timesheet')->where('uu_id', $request->uu_id)->first();

        $clockinpic = $clockoutpic = $clockinFileName = $clockoutFileName = '';
        $currentDateTime = now();

        if ($request->clockin_pic) {
            $clockinFileName = $this->saveImage($request->clockin_pic, $request->emp_id, 'clockin', $currentDateTime);
            if ($clockinFileName) {
                $clockinpic = 'Clockin Pic updated successfully.';
            }
        }

        if ($request->clockout_pic) {
            $clockoutFileName = $this->saveImage($request->clockout_pic, $request->emp_id, 'clockout', $currentDateTime);
            if ($clockoutFileName) {
                $clockoutpic = 'Clockout Pic updated successfully.';
            }
        }

        if ($timeSheet) {
            $result = DB::table('timesheet')->where('uu_id', $request->uu_id)->update([               
                'clock_out' => $request->clock_out,               
                'clockout_pic' => $clockoutFileName,                
                'clockout_survay' => $request->clockout_survay,
                'injury_checkout' => $request->injury_checkout,                
            ]);

            //$insertedId = $timeSheet->id;
            return response()->json([
                'status' => 200,
                'message' => 'Time Entry updated successfully.',
               // 'data' => $insertedId,
                'clockinImage' => $clockinpic,
                'clockoutImage' => $clockoutpic,
            ], 200);
        } else {
			
		$companyid = $request->companyid;
		$empid = $request->emp_id;
        $postData = array("apikey" => $this->ntact_api_key, "companyid"=>$companyid, "empid" => $empid);
        $response = $this->api_call($postData, "getemp/");		
        $res_data = json_decode($response->getBody()->getContents(), true);
		$payrate = '';
		if(isset($res_data['data'][0]['payrate'])){
			$payrate = $res_data['data'][0]['payrate'];
		}
			
            $res = TimeSheet::create([
                'companyid' => $request->companyid,
                'supervisorid' => $request->supervisorid,
                'emp_id' => $request->emp_id,
                'emp_name' => $request->emp_name,
                'clock_in' => $request->clock_in,
                'clock_out' => $request->clock_out,
                'clockin_pic' => $clockinFileName,
                'clockout_pic' => $clockoutFileName,
                'classification' => $request->classification,
                'payrate' => $payrate,
                'clockin_survay' => $request->clockin_survay,
                'clockout_survay' => $request->clockout_survay,
                'injury_checkout' => $request->injury_checkout,
                'status' => $request->status,
                'created_by' => $request->created_by,
                'uu_id' => $request->uu_id,
				'audit_logs' => json_encode([
					[
						'action' => 'ClockinSync',
						'updated_by' => $request->created_by,
						'updated_at' => now(),
					]
				]),
            ]);

            $insertedId = $res->id;
            return response()->json([
                'status' => 200,
                'message' => 'Time Entry created successfully.',
                'data' => $insertedId,
                'clockinImage' => $clockinpic,
                'clockoutImage' => $clockoutpic,
            ], 200);
        }

    } catch (QueryException $e) {
        return $this->handleQueryException($e);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 500,
            'error' => 'An error occurred while processing your request.',
            'details' => $e->getMessage(),
        ], 500);
    }
}

private function saveImage($base64Image, $empId, $type, $currentDateTime)
{
    $image = str_replace(' ', '+', $base64Image);
    $decodedImage = base64_decode($image);
    $fileName = $empId . "_{$type}_" . $currentDateTime->format('YmdHis') . '.png';
    $imagePath = storage_path('logs/pictures/' . $fileName);

    if (file_put_contents($imagePath, $decodedImage) !== false) {
        return $fileName;
    }

    return null;
}

private function handleQueryException(QueryException $e)
{
    if ($e->getCode() === '23000') {
        return response()->json([
            'status' => 400,
            'error' => 'Duplicate entry or constraint violation.',
            'details' => $e->getMessage(),
        ], 400);
    }

    return response()->json([
        'status' => 500,
        'error' => 'An error occurred while processing your request.',
        'details' => $e->getMessage(),
    ], 500);
}


}
