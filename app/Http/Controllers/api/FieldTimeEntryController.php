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
                'clock_in_uuid' => $request->clock_in_uuid,
                'clock_out_uuid' => $request->clock_out_uuid,
                'employee_id' => $validated['employee_id'],
                'job_id' => $request->job_id,
                'cost_code_id' => $request->cost_code_id,
                'transaction_hours' => $request->transaction_hours,
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
