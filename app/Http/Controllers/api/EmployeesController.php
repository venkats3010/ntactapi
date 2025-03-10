<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Employees;
use DB;
use Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class EmployeesController extends Controller
{

    public function index()
    {
        return view('employees.index');
    }

     /**
     * @OA\Get(
     *     path="/api/employees/get",
     *     tags={"Employees"},
     *     summary="Get employees by name or id",
     *     description="Retrieve a list of employees filtered by name or id. Either parameter can be provided.",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="Filter employees by name",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="Filter employees by id",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of employees",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */

    public function get(Request $request)
    {	//	\DB::enableQueryLog(); 
        if ($request->has('param')) {
			if(is_numeric($request->get('param'))){
				$res = Employees::where('employee_number', $request->get('param'))->get();
			}else{
				$res = Employees::where('employee_name', 'like', '%' . $request->get('param') . '%')->get();
			}            
        }else if ($request->has('id')) {
			$res = Employees::where('employee_id', $request->get('id'))->first();
		}else{
            $res = DB::table('employees')->where('status', 'A')->get();
        }
		// $qry = \DB::getQueryLog();
		return response(['status' => 200, 'result' => "true", 'message' => "Success", 'data'=>$res]);
    }

    /**
 * @OA\Post(
 *     path="/api/employees/store",
 *     operationId="storeEmployee",
 *     tags={"Employees"},
 *     summary="Create a new employee",
 *     description="Store a new employee in the database.",
 *     @OA\RequestBody(
 *         required=true,
 *         description="Employee data to be created",
 *         @OA\JsonContent(
 *             required={"employee_number", "employee_name", "classification", "per_diem_rate", "status"},
 *             @OA\Property(property="employee_number", type="string", example="E12345"),
 *             @OA\Property(property="employee_name", type="string", example="John Doe"),
 *             @OA\Property(property="classification", type="string", example="Manager"),
 *             @OA\Property(property="per_diem_rate", type="number", format="float", example=150.5),
 *             @OA\Property(property="status", type="string", example="active")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Employee created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Employee created successfully."),
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request - Validation failed or unique constraint violated",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=400),
 *             @OA\Property(property="error", type="string", example="Employee number must be unique. This employee number is already taken."),
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
                'employee_number' => 'required',
                'employee_name' => 'required',
                'classification' => 'required',
                'per_diem_rate' => 'required',				
                'status' => 'required',
            ]);

            $employee = Employees::create([
                'employee_number' => $validated['employee_number'],
                'employee_name' => $validated['employee_name'],
                'classification' => $validated['classification'],
                'per_diem_rate' => $validated['per_diem_rate'],
				'created_by' => $request->created_by,
                'status' => $validated['status'],
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Employee created successfully.',
                'data' => $employee,
            ], 200);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json([
                    'status' => 400,
                    'error' => 'Employee number must be unique. This employee number is already taken.',
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
 * @OA\Put(
 *     path="/api/employees/{id}/update",
 *     operationId="updateEmployee",
 *     tags={"Employees"},
 *     summary="Update a specific employee",
 *     description="Update the employee details in the database.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the employee to update",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Employee data to be updated",
 *         @OA\JsonContent(
 *             required={"employee_number", "employee_name", "classification", "per_diem_rate", "status"},
 *             @OA\Property(property="employee_number", type="string", example="E12345"),
 *             @OA\Property(property="employee_name", type="string", example="John Doe"),
 *             @OA\Property(property="classification", type="string", example="Manager"),
 *             @OA\Property(property="per_diem_rate", type="number", format="float", example=150.5),
 *             @OA\Property(property="status", type="string", example="active")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Employee updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Employee updated successfully.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Employee not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=404),
 *             @OA\Property(property="message", type="string", example="Employee not found!")
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
            'employee_number' => 'required',
            'employee_name' => 'required',
            'classification' => 'required',
            'per_diem_rate' => 'required',
            'status' => 'required|string|max:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $employee = Employees::find($id);

        if ($employee) {
            $employee->employee_number = $request->employee_number;
            $employee->employee_name = $request->employee_name;
            $employee->classification = $request->classification;
            $employee->per_diem_rate = $request->per_diem_rate;
			$employee->updated_by = $request->updated_by;
            $employee->status = $request->status;
            $employee->save();

            return response()->json([
                'status' => 200,
                'message' => 'Employee updated successfully.',
            ], 200);
        }

        return response()->json(['message' => 'Employee not found!'], 404);
    }


	/**
	 * @OA\Delete(
	 *     path="/api/employees/{id}",
	 *     summary="Delete an employee",
	 *     description="Marks an employee as inactive by setting their status to 'I'.",
	 *     operationId="destroyEmployee",
	 *     tags={"Employees"},
	 *     @OA\Parameter(
	 *         name="id",
	 *         in="path",
	 *         required=true,
	 *         description="ID of the employee to be deleted",
	 *         @OA\Schema(type="string")
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Employee marked as inactive successfully",
	 *         @OA\JsonContent(
	 *             @OA\Property(
	 *                 property="status",
	 *                 type="integer",
	 *                 example=200
	 *             ),
	 *             @OA\Property(
	 *                 property="message",
	 *                 type="string",
	 *                 example="Employee Deleted successfully."
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=404,
	 *         description="Employee not found",
	 *         @OA\JsonContent(
	 *             @OA\Property(
	 *                 property="message",
	 *                 type="string",
	 *                 example="Employee not found!"
	 *             )
	 *         )
	 *     )
	 * )
	 */

    public function destroy(string $id)
    {
        $employee = Employees::find($id);

        if ($employee) {
            $employee->status = "I";
            $employee->save();

            return response()->json([
                'status' => 200,
                'message' => 'Employee Deleted successfully.',
            ], 200);
        }

        return response()->json(['message' => 'Employee not found!'], 404);
    }

    public function getEmpList(Request $request)
    {	//	\DB::enableQueryLog(); 
		$dbname = "NTACT Constructors";
		$sqlQuery = "SELECT recnum as empid, fstnme as firstname, lstnme as lastname, midini as middleini, phnnum as phone, ctynme as city, state_ as state, zipcde as zipcode, status FROM [$dbname].[dbo].[employ]";

		$employees = DB::connection('sqlsrv')->select($sqlQuery);
		// $qry = \DB::getQueryLog();
		return response(['status' => 200, 'result' => "true", 'message' => "Success", 'data'=>$employees]);
    }
	
}