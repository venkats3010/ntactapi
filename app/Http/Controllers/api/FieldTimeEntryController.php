<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Transactions;
use DB;
use Validator;

class FieldTimeEntryController extends Controller
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
			$response =[];
			if ($request->has('empid')) {
				$response = Transactions::where('employee_id', $request->get('empid'))->orderBy('transaction_id', 'DESC')->first();
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
	
    public function create()
    {
        //
    }

    public function clockin(Request $request)
    {
        try {
            $validated = $request->validate([           
                'employee_id' => 'required'                
            ]);

            $res = Transactions::create([
                'transaction_uuid' => $request->transaction_uuid,
                'clock_in' => $request->clock_in,
                'clock_out' => $request->clock_out,
                'employee_id' => $validated['employee_id'],
                'job_id' => $request->job_id,
                'cost_code_id' => $request->cost_code_id,
                'transaction_hours' => $request->transaction_hours,
                'classification' => $request->classification,
                'status' => $request->status,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Time Entry created successfully.',
                
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

    public function getEntry(Request $request)
    {
        try {
			$response =[];
			if ($request->has('empid')) {
				$response = DB::table('time_entries')->where('employee_id', $request->get('empid'))->orderBy('time_entry_id', 'DESC')->get();
			}else{
				$response = DB::table('time_entries')->orderBy('time_entry_id', 'DESC')->get();
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

    public function entry(Request $request)
    {
        try {
            $validated = $request->validate([           
                'employee_id' => 'required'                
            ]);

            $res = DB::table('time_entries')->insert([
                'time_entry_uuid' => $request->time_entry_uuid,
				'employee_id' => $validated['employee_id'],
                'device_id' => $request->device_id,
                'action_type' => $request->action_type,                
                'notes' => $request->notes,
                'status' => $request->status,
                'created_by' => $request->created_by,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Time Entry created successfully.',
                
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
        //
    }

    public function destroy(string $id)
    {
        //
    }

}
