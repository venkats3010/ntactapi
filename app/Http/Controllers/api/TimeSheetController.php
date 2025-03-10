<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
			$formattedDate = \Carbon\Carbon::createFromFormat('m/d/Y', $request->get('searchdate'))->format('Y-m-d');
			$query->whereDate('clock_in', $formattedDate);
		}
		// sheet filter
		if ($request->filled('sheetFilter')) {
			if($request->get('sheetFilter') == "P"){
				$query->whereIn('status', ['A', 'S']);			
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
            $query->where('supervisorid', $request->get('supervisorid'));
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

        // Grouping by all columns with aggregate functions for non-grouped ones
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
			//$query->orderBy('status');
			$query->orderBy('emp_id', 'DESC');
			$query->orderByRaw("FIELD(status, 'A', 'S', 'P', 'V', 'I')");
			//$query->orderBy('clock_in', 'DESC');
			//$response = $query->orderBy('id', 'DESC')->get()->map(function ($item) {
		}

        // Execute query and map results
        $response = $query->get()->map(function ($item) {
            // Map the image paths and company names
            $item->clockin_image_path = url('storage/logs/pictures/' . $item->clockin_pic);
            $item->clockout_image_path = url('storage/logs/pictures/' . $item->clockout_pic);

            // Fetch company name
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
            // Map the image paths and company names
            $item->clockin_image_path = url('storage/logs/pictures/' . $item->clockin_pic);
            $item->clockout_image_path = url('storage/logs/pictures/' . $item->clockout_pic);

            // Fetch company name
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
	
    public function supervisor(Request $request)
    {
		try {	
			// $data_post = $request->all(); 
			// $parsedData = json_encode($data_post, true);
			
			// Log::info('This is an informational Supervisor Request ' . $parsedData);		
			/* $res = DB::table('timesheet as T')
				->join('company as C', 'T.companyid', '=', 'C.id')
				->where('T.clock_in', '>=', '2025-02-10')
				->where('T.supervisorid', '=', '1234567890')
				->select('T.supervisorid', 'T.companyid', 'C.name as companyName', DB::raw('count(T.companyid) as countEntries'), DB::raw('date(max(T.clock_in)) as clockin'))
				->groupBy('T.supervisorid', 'T.companyid', 'C.name', DB::raw('date(T.clock_in)'))
				->orderBy('clockin', 'DESC')
				->get(); */
				/* $res = DB::table('timesheet AS T')
					->join('company AS C', 'T.companyid', '=', 'C.id')
					->select('T.supervisorid', 'T.companyid', 'C.name as companyName', 'T.companyid as countEntries', DB::raw('DATE(T.clock_in) as clock_in_date'), 'T.status')
					->where('T.clock_in', '>=', '2023-02-10')
					->where('T.status', '!=', 'I')
					->orderBy('T.clock_in')
					->get(); */
			/* if($request->has('searchdate')){
				$lastDate = $request->get('searchdate');
			}else{
				$lastDate = date('Y-m-d', strtotime("-7 days"));
			} */
			
			$res = DB::table('timesheet as T')
				->join('company as C', 'T.companyid', '=', 'C.id')
				//->where('T.clock_in', '>=', $lastDate)
				->where('T.supervisorid', '=', $request->get('supervisorid'))
				//->where('T.companyid', $request->get('companyid')) // Uncomment if needed
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
				DB::raw('MAX(T.status) as maxStatus'),
				DB::raw('count(T.companyid) as countEntries'),
				DB::raw('date(MAX(T.clock_in)) as clockin'),
				DB::raw("COUNT(CASE WHEN T.status IN ('P', 'A', 'S') THEN 1 END) as statusPCount"),
				DB::raw("COUNT(CASE WHEN T.status = 'V' THEN 1 END) as statusVCount")
			)
			->groupBy('T.supervisorid',  'T.companyid', 'C.name', DB::raw('date(T.clock_in)'))
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
				'clockin_survay' => $resp->clockin_survay,
				'clockout_survay' => $resp->clockout_survay,
                'status' => 'S',
                'is_split' => 'Y',
				'created_by' => $resp->created_by,
				'uu_id' => $resp->uu_id,
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

            $res = TimeSheet::create([
				'companyid' => $request->companyid,
				'supervisorid' => $request->supervisorid,
                'emp_id' => $validated['emp_id'],
                'emp_name' => $request->emp_name,
				'clock_in' => $request->clock_in,
                'clock_out' => $request->clock_out,
                'clockin_pic' => $request->clockin_pic,
				'classification' => $request->classification,
                'status' => $request->status,
				'created_by' => $request->created_by,
				'uu_id' => $request->uu_id ?? '',
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
			// file_put_contents($img_path, $decodedImage);
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
			// file_put_contents($img_path, $decodedImage);
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
			$clockin = Carbon::parse($request->clockin)->format('Y-m-d H:i');
			$clockout = NULL;
			if(!empty($request->clock_out)){
				$clockout = Carbon::parse($request->clock_out)->format('Y-m-d H:i');
			}
			$hours = "00:00:00";
			if(!empty($request->clockin) && !empty($request->clock_out)){			
				$diff = $clockin->diff($clockout);
				$hours = $diff->format('%H:%I:%S');
			}
			$status = $request->status ?? 'P';
			/* $timesheet = TimeSheet::where('id', $request->id)->update(['clock_out' => $clockout, 'break_time' => $request->break_time, 'job_id' => $request->job_id, 'cost_code_id' => $request->cost_code_id, 'perdim' => $request->perdim, 'regular_rate' => $request->regular_rate, 'overtime_rate' => $request->overtime_rate, 'labor_rate' => $request->labor_rate, 'hours' => $request->hours, 'overtime' => $request->overtime, 'status' => $request->status]); */
			if(isset($request->page) && $request->page == "timesheet"){
				$timesheet = TimeSheet::where('id', $request->id)->update(['clock_out' => $clockout, 'break_time' => $request->break_time, 'job_id' => $request->job_id, 'cost_code_id' => $request->cost_code_id, 'perdim' => $request->perdim, 'hours' => $request->hours, 'jobDescription' => $request->jobname, 'costcodeDescription' => $request->costcodename, 'status' => $request->status, 'updated_by' => $request->updated_by]);
			}else if(isset($request->page) && $request->page == "payroll"){
				$timesheet = TimeSheet::where('id', $request->id)->update([ 'break_time' => $request->break_time, 'perdim' => $request->perdim, 'hours' => $request->hours, 'overtime' => $request->overtime,'status' => $status, 'updated_by' => $request->updated_by]);
			}else{
				$timesheet = TimeSheet::where('id', $request->id)->update(['clock_out' => $clockout, 'break_time' => $request->break_time, 'job_id' => $request->job_id, 'cost_code_id' => $request->cost_code_id, 'perdim' => $request->perdim, 'hours' => $request->hours, 'status' => $request->status, 'updated_by' => $request->updated_by]);
			}			

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

			//if (strpos($key, 'rowid_') !== false) {
				//$id = explode('_', $key)[1];

		/*         Log::info('Processing timesheet for ID: ' . $sheet->clockin);
				Log::info('Processing timesheet for clockin: ' .  $sheet['clockout']);

				$clock_in = $key['clock_in_' . $id] ?? null;
				$clock_out = $key['clock_out_' . $id] ?? null;
				$break_time = $key['break_time_' . $id] ?? null;
				$job_id = $key['job_id_' . $id] ?? null;
				$cost_code_id = $key['cost_code_' . $id] ?? null;
				Log::info('Processing timesheet for clockIn: ' . $clock_in);
				Log::info('Processing timesheet for clockout: ' . $clock_out);
				// If all required fields are present, update the TimeSheet entry
				if ($clock_in && $clock_out && $break_time && $job_id && $cost_code_id) {
					$timesheet = TimeSheet::where('id', $id)->update([
						'clock_in' => $clock_in,
						'clock_out' => $clock_out,
						'break_time' => $break_time,
						'job_id' => $job_id,
						'cost_code_id' => $cost_code_id,
					]);

					//Log::info('Timesheet updated for ID ' . $id);
				} else {
					Log::warning('Missing data for ID ' . $id);
				} */
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
			Log::info('Clockin-Out Payload' . json_encode($request->all()));
			$validator = Validator::make($request->all(), [
				'rowid' => 'required'
			]);
			if ($validator->fails()) {
				return response()->json(['errors' => $validator->errors()], 400);
			}
			$clockin = Carbon::parse($request->clock_in)->format('Y-m-d H:i');
			$clockout = Carbon::parse($request->clock_out)->format('Y-m-d H:i');
			$ids = explode(',', $request->rowid);
			
			/* $timesheet = DB::table('timesheet')->whereIn('id', $ids)->update(['clock_in' => $clockin, 'clock_out' => $clockout,  'job_id' => $request->job_id, 'cost_code_id' => $request->cost_code_id, 'break_time' => $request->break_time, 'jobDescription' => $request->jobname, 'costcodeDescription' => $request->costcodename, 'updated_by' => $request->updated_by]); */

			$updateData = [];			
			if ($request->has('clock_in') && $request->get('clock_in') != null) {
				$updateData['clock_in'] = $clockin;
				$updateData['hours'] = "";
			}
			if ($request->has('clock_out') && $request->get('clock_out') != null) {
				$updateData['clock_out'] = $clockout;
				$updateData['hours'] = "";
			}
			if ($request->has('job_id') && $request->get('job_id') !== null) {
				$updateData['job_id'] = $request->job_id;
				$updateData['jobDescription'] = $request->jobname;
			}
			if ($request->has('cost_code_id') && $request->get('cost_code_id') !== null) {
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
/* 	private function calculateEqualTime($acthrs, $count, $hours, $breaktime)
    {
	if (strpos($acthrs, ':') === false) {    
        throw new \Exception("Invalid time format. Expected format: H:M");
    }
        list($actHours, $actMinutes) = explode(":", $acthrs);
        $totalActMinutes = ($actHours * 60) + $actMinutes;
        $totalMinutes = $totalActMinutes;
        $equalMinutes = $totalMinutes / $count;
        $equalHours = floor($equalMinutes / 60);
        $equalRemainingMinutes = $equalMinutes % 60;
        return sprintf("%02d:%02d", $equalHours, $equalRemainingMinutes);
    } */	
	
	
	
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
                'clockin_survay' => $request->clockin_survay,
                'clockout_survay' => $request->clockout_survay,
                'injury_checkout' => $request->injury_checkout,
                'status' => $request->status,
                'created_by' => $request->created_by,
                'uu_id' => $request->uu_id,
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


   /*  public function saveClockinCheckout(Request $request)
    {
		ini_set('memory_limit', '512M');
		//print_r($request);exit;
        try {
            $validated = $request->validate([
				'uu_id' => 'required',
				'emp_id' => 'required',
				'supervisorid' => 'required',
				'companyid' => 'required'                
			]);

			$timeSheet = TimeSheet::where('uu_id', $request->uu_id)->first();
			$clockinpic = $clockoutpic = $clockinFileName = $clockoutFileName = '';
			$currentDateTime = now();
			if($request->clockin_pic){
				$imageClockin = $request->clockin_pic;
				$imageClockin = str_replace(' ', '+', $imageClockin);
				$decodedImgClockin = base64_decode($imageClockin);
				$clockinFileName = $request->emp_id.'_clockin_'.$currentDateTime->format('YmdHis').'.png';
				$clockinimg_path = storage_path('logs/pictures/' . $clockinFileName);
				if (file_put_contents($clockinimg_path, $decodedImgClockin) !== false) {
					$clockinpic = 'Clockin Pic updated successfully.';
				}
			}
			if($request->clockout_pic){			
				$imageClockout = $request->clockout_pic;
				$imageClockout = str_replace(' ', '+', $imageClockout);
				$decodedImgClockout = base64_decode($imageClockout);
				$clockoutFileName = $request->emp_id.'_clockout_'.$currentDateTime->format('YmdHis').'.png';
				$clockoutimg_path = storage_path('logs/pictures/' . $clockoutFileName);
				if (file_put_contents($clockoutimg_path, $decodedImgClockout) !== false) {
					$clockoutpic = 'Clockout Pic updated successfully.';
				}
			}
			if ($timeSheet) {
				$timeSheet->update([
					'companyid' => $request->companyid,
					'supervisorid' => $request->supervisorid,
					'emp_id' => $validated['emp_id'],
					'emp_name' => $request->emp_name,
					'clock_in' => $request->clock_in,
					'clock_out' => $request->clock_out,
					'clockin_pic' => $clockinFileName,
					'clockout_pic' => $clockoutFileName,
					'classification' => $request->classification,
					'clockin_survay' => $request->clockin_survay,
					'clockout_survay' => $request->clockout_survay,
					'injury_checkout' => $request->injury_checkout,
					'status' => $request->status,
					'created_by' => $request->created_by,
				]);
				
				$insertedId = $timeSheet->id;
				return response()->json([
					'status' => 200,
					'message' => 'Time Entry updated successfully.',					
					'data' => $insertedId,
					'clockinImage' => $clockinpic,
					'clockoutImage' => $clockoutpic,
				], 200);
			} else {
				$res = TimeSheet::create([
					'companyid' => $request->companyid,
					'supervisorid' => $request->supervisorid,
					'emp_id' => $validated['emp_id'],
					'emp_name' => $request->emp_name,
					'clock_in' => $request->clock_in,
					'clock_out' => $request->clock_out,
					'clockin_pic' => $clockinFileName,
					'clockout_pic' => $clockoutFileName,
					'classification' => $request->classification,
					'clockin_survay' => $request->clockin_survay,
					'clockout_survay' => $request->clockout_survay,
					'injury_checkout' => $request->injury_checkout,
					'status' => $request->status,
					'created_by' => $request->created_by,
					'uu_id' => $request->uu_id,
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
    } */

}
