<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Transactions;
use DB;
use Validator;

class TransactionsController extends Controller
{

    public function index()
    {
        //
    }

/**
 * @OA\Get(
 *     path="/api/transactions/get",
 *     operationId="getTransactions",
 *     tags={"Transactions"},
 *     summary="Get a list of all transactions",
 *     description="Retrieve all transactions from the database.",
 *     @OA\Response(
 *         response=200,
 *         description="Transactions retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Success"),
 *             @OA\Property(property="data", type="array", @OA\Items(
 *                 @OA\Property(property="transaction_uuid", type="string", example="TXN12345"),
 *                 @OA\Property(property="clock_in", type="string", example="CIU12345"),
 *                 @OA\Property(property="clock_out", type="string", example="COU12345"),
 *                 @OA\Property(property="employee_id", type="integer", example=101),
 *                 @OA\Property(property="job_id", type="integer", example=202),
 *                 @OA\Property(property="cost_code_id", type="integer", example=303),
 *                 @OA\Property(property="transaction_hours", type="number", format="float", example=8.5),
 *                 @OA\Property(property="status", type="string", example="active")
 *             ))
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=500),
 *             @OA\Property(property="error", type="string", example="An error occurred while processing your request."),
 *             @OA\Property(property="details", type="string", example="Database connection failed")
 *         )
 *     )
 * )
 */    
 
     public function get(Request $request)
    {	//	\DB::enableQueryLog(); 
		if($request->has('id')){
			$res = Transactions::where('transaction_id', $request->get('id'))->first();
		}else{
            $res = DB::table('transactions')->get();
        }
		// $qry = \DB::getQueryLog();
		return response(['status' => 200, 'result' => "true", 'message' => "Success", 'data'=>$res]);
    }
 
    /* public function get()
    {
        $response = DB::table('transactions')->get();
        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $response,
        ], 200);
    } */

/**
 * @OA\Post(
 *     path="/api/transactions/store",
 *     operationId="storeTransaction",
 *     tags={"Transactions"},
 *     summary="Create a new transaction",
 *     description="Store a new transaction in the database.",
 *     @OA\RequestBody(
 *         required=true,
 *         description="Transaction data to be created",
 *         @OA\JsonContent(
 *             required={"transaction_uuid", "clock_in", "clock_out", "employee_id", "job_id", "cost_code_id", "transaction_hours", "status", "created_by"},
 *             @OA\Property(property="transaction_uuid", type="string", example="TXN12345"),
 *             @OA\Property(property="clock_in", type="string", example="CIU12345"),
 *             @OA\Property(property="clock_out", type="string", example="COU12345"),
 *             @OA\Property(property="employee_id", type="integer", example=101),
 *             @OA\Property(property="job_id", type="integer", example=202),
 *             @OA\Property(property="cost_code_id", type="integer", example=303),
 *             @OA\Property(property="transaction_hours", type="number", format="float", example=8.5),
 *             @OA\Property(property="status", type="string", example="active"),
 *             @OA\Property(property="created_by", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Transaction created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Transaction created successfully."),
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request - Validation failed or unique constraint violated",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=400),
 *             @OA\Property(property="error", type="string", example="Something went wrong, kindly check once."),
 *             @OA\Property(property="details", type="string", example="Integrity constraint violation")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=500),
 *             @OA\Property(property="error", type="string", example="An error occurred while processing your request."),
 *             @OA\Property(property="details", type="string", example="Database connection failed")
 *         )
 *     )
 * )
 */

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'transaction_uuid' => 'required',
                'clock_in' => 'required',
                'clock_out' => 'required',
                'employee_id' => 'required',
                'job_id' => 'required',
                'cost_code_id' => 'required',
                'transaction_hours' => 'required',                
                'status' => 'required',
                'created_by' => 'required',
            ]);

            $transaction = Transactions::create([
                'transaction_uuid' => $validated['transaction_uuid'],
                'clock_in' => $validated['clock_in'],
                'clock_out' => $validated['clock_out'],
                'employee_id' => $validated['employee_id'],
                'job_id' => $validated['job_id'],
                'transaction_hours' => $validated['transaction_hours'],                
                'status' => $validated['status'],
                'created_by' => $validated['created_by'],
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Transaction created successfully.',
                'data' => $transaction,
            ], 200);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json([
                    'status' => 400,
                    'error' => 'Something went wrong, kindly check once.',
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

/**
 * @OA\Post(
 *     path="/api/transactions/{id}/update",
 *     operationId="updateTransaction",
 *     tags={"Transactions"},
 *     summary="Update a specific transaction",
 *     description="Update the transaction details in the database.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the transaction to update",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Transaction data to be updated",
 *         @OA\JsonContent(
 *             required={"transaction_uuid", "clock_in", "clock_out", "employee_id", "job_id", "cost_code_id", "transaction_hours", "status", "updated_by"},
 *             @OA\Property(property="transaction_uuid", type="string", example="TXN12345"),
 *             @OA\Property(property="clock_in", type="string", example="CIU12345"),
 *             @OA\Property(property="clock_out", type="string", example="COU12345"),
 *             @OA\Property(property="employee_id", type="integer", example=101),
 *             @OA\Property(property="job_id", type="integer", example=202),
 *             @OA\Property(property="cost_code_id", type="integer", example=303),
 *             @OA\Property(property="transaction_hours", type="number", format="float", example=8.5),
 *             @OA\Property(property="status", type="string", example="active"),
 *             @OA\Property(property="updated_by", type="integer", example=2)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Transaction updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Transaction updated successfully.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Transaction not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=404),
 *             @OA\Property(property="message", type="string", example="Transaction not found!")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request - Validation failed",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=400),
 *             @OA\Property(property="errors", type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=500),
 *             @OA\Property(property="error", type="string", example="An error occurred while processing your request."),
 *             @OA\Property(property="details", type="string", example="Database connection failed")
 *         )
 *     )
 * )
 */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'transaction_uuid' => 'required',
            'clock_in' => 'required',
            'clock_out' => 'required',
            'employee_id' => 'required',
            'job_id' => 'required',
            'cost_code_id' => 'required',
            'transaction_hours' => 'required',                
            'updated_by' => 'required',
            'status' => 'required|string|max:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $transaction = Transactions::find($id);

        if ($transaction) {
            $transaction->transaction_uuid = $request->transaction_uuid;
            $transaction->clock_in = $request->clock_in;
            $transaction->clock_out = $request->clock_out;
            $transaction->employee_id = $request->employee_id;
            $transaction->job_id = $request->job_id;
            $transaction->cost_code_id = $request->cost_code_id;
            $transaction->transaction_hours = $request->transaction_hours;
            $transaction->status = $request->status;
            $transaction->updated_by = $request->updated_by;
            $transaction->save();

            return response()->json([
                'status' => 200,
                'message' => 'Transaction updated successfully.',
            ], 200);
        }

        return response()->json(['message' => 'Transaction not found!'], 404);
    }

}
