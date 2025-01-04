<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Costcodes;
use DB;
use Validator;

class CostcodesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
 * @OA\Get(
 *     path="/api/cost_codes/get",
 *     operationId="getCostCodes",
 *     tags={"CostCodes"},
 *     summary="Get a list of all cost codes",
 *     description="Retrieve all cost codes from the database.",
 *     @OA\Response(
 *         response=200,
 *         description="Cost codes retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Success"),
 *             @OA\Property(property="data", type="array", @OA\Items(
 *                 @OA\Property(property="cost_code", type="string", example="CC123"),
 *                 @OA\Property(property="description", type="string", example="Cost code description"),
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

    public function get()
    {
        $response = DB::table('cost_codes')->get();
        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $response,
        ], 200);
    }

    /**
 * @OA\Post(
 *     path="/api/cost_codes/store",
 *     operationId="storeCostCode",
 *     tags={"CostCodes"},
 *     summary="Create a new cost code",
 *     description="Store a new cost code in the database.",
 *     @OA\RequestBody(
 *         required=true,
 *         description="Cost code data to be created",
 *         @OA\JsonContent(
 *             required={"cost_code", "description", "status"},
 *             @OA\Property(property="cost_code", type="string", example="CC123"),
 *             @OA\Property(property="description", type="string", example="Cost code description"),
 *             @OA\Property(property="status", type="string", example="active"),
 *             @OA\Property(property="cost_code_uuid", type="string", example="uuid-12345")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Cost code created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Cost code created successfully."),
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request - Validation failed or unique constraint violated",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=400),
 *             @OA\Property(property="error", type="string", example="Cost code number must be unique. This cost code number is already taken."),
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
                'cost_code' => 'required',
                'description' => 'required',
                'status' => 'required',
            ]);

            $costcode = Costcodes::create([
                'cost_code_uuid' => $request->cost_code_uuid,
                'cost_code' => $validated['cost_code'],
                'description' => $validated['description'],
                'status' => $validated['status'],
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Cost code created successfully.',
                'data' => $costcode,
            ], 200);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json([
                    'status' => 400,
                    'error' => 'Cost code number must be unique. This cost code number is already taken.',
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
 *     path="/api/cost_codes/{id}/update",
 *     operationId="updateCostCode",
 *     tags={"CostCodes"},
 *     summary="Update a specific cost code",
 *     description="Update the cost code details in the database.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the cost code to update",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Cost code data to be updated",
 *         @OA\JsonContent(
 *             required={"cost_code", "description", "status"},
 *             @OA\Property(property="cost_code", type="string", example="CC123"),
 *             @OA\Property(property="description", type="string", example="Cost code description"),
 *             @OA\Property(property="status", type="string", example="active"),
 *             @OA\Property(property="cost_code_uuid", type="string", example="uuid-12345")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Cost code updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Cost code updated successfully.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Cost code not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=404),
 *             @OA\Property(property="message", type="string", example="Cost code not found!")
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
            'cost_code' => 'required',
            'description' => 'required',
            'status' => 'required|string|max:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $costcode = Costcodes::find($id);

        if ($costcode) {
            $costcode->cost_code_uuid = $request->cost_code_uuid;
            $costcode->cost_code = $request->cost_code;
            $costcode->description = $request->description;
            $costcode->status = $request->status;
            $costcode->save();

            return response()->json([
                'status' => 200,
                'message' => 'Cost code updated successfully.',
            ], 200);
        }

        return response()->json(['message' => 'Cost code not found!'], 404);
    }

}
