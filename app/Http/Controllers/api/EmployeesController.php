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
 *     operationId="getEmployees",
 *     tags={"Employees"},
 *     summary="Get a list of all employees",
 *     description="Retrieve all employees from the database.",
 *     @OA\Response(
 *         response=200,
 *         description="Employees retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Success"),
 *             @OA\Property(property="data", type="array", @OA\Items(
 *                 @OA\Property(property="employee_number", type="string", example="E12345"),
 *                 @OA\Property(property="employee_name", type="string", example="John Doe"),
 *                 @OA\Property(property="classification", type="string", example="Manager"),
 *                 @OA\Property(property="per_diem_rate", type="number", format="float", example=150.5),
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
    {
        if ($request->has('id')) {
            $response = Employees::where('employee_number', $request->get('id'))->get();
        }else if($request->has('name')){
            $response = Employees::where('employee_name', 'like', '%' . $request->get('name') . '%')->get();
        }else{
            $response = DB::table('employees')->get();
        }        
        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $response,
        ], 200);
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
            $employee->status = $request->status;
            $employee->save();

            return response()->json([
                'status' => 200,
                'message' => 'Employee updated successfully.',
            ], 200);
        }

        return response()->json(['message' => 'Employee not found!'], 404);
    }

}