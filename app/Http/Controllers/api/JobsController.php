<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Jobs;
use DB;
use Validator;

class JobsController extends Controller
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
 *     path="/api/jobs/get",
 *     operationId="getJobs",
 *     tags={"Jobs"},
 *     summary="Get a list of all jobs",
 *     description="Retrieve all jobs from the database.",
 *     @OA\Response(
 *         response=200,
 *         description="Jobs retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Success"),
 *             @OA\Property(property="data", type="array", @OA\Items(
 *                 @OA\Property(property="job_num", type="string", example="J12345"),
 *                 @OA\Property(property="job_name", type="string", example="Construction Project"),
 *                 @OA\Property(property="job_location", type="string", example="New York"),
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
        $response = DB::table('jobs')->get();
        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $response,
        ], 200);
    }

 /**
 * @OA\Post(
 *     path="/api/jobs/store",
 *     operationId="storeJob",
 *     tags={"Jobs"},
 *     summary="Create a new job",
 *     description="Store a new job in the database.",
 *     @OA\RequestBody(
 *         required=true,
 *         description="Job data to be created",
 *         @OA\JsonContent(
 *             required={"job_num", "job_name", "job_location", "status"},
 *             @OA\Property(property="job_num", type="string", example="J12345"),
 *             @OA\Property(property="job_name", type="string", example="Construction Project"),
 *             @OA\Property(property="job_location", type="string", example="New York"),
 *             @OA\Property(property="status", type="string", example="active")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Job created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Job created successfully."),
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request - Validation failed or unique constraint violated",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=400),
 *             @OA\Property(property="error", type="string", example="Job number must be unique. This job number is already taken."),
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
                'job_num' => 'required',
                'job_name' => 'required',
                'job_location' => 'required',
                'status' => 'required',
            ]);

            $job = Jobs::create([
                'job_num' => $validated['job_num'],
                'job_name' => $validated['job_name'],
                'job_location' => $validated['job_location'],
                'status' => $validated['status'],
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Job created successfully.',
                'data' => $job,
            ], 200);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json([
                    'status' => 400,
                    'error' => 'Jobs number must be unique. This job number is already taken.',
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
 *     path="/api/jobs/{id}/update",
 *     operationId="updateJob",
 *     tags={"Jobs"},
 *     summary="Update a specific job",
 *     description="Update the job details in the database.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the job to update",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Job data to be updated",
 *         @OA\JsonContent(
 *             required={"job_num", "job_name", "job_location", "status"},
 *             @OA\Property(property="job_num", type="string", example="J12345"),
 *             @OA\Property(property="job_name", type="string", example="Construction Project"),
 *             @OA\Property(property="job_location", type="string", example="New York"),
 *             @OA\Property(property="status", type="string", example="active")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Job updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Job updated successfully.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Job not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=404),
 *             @OA\Property(property="message", type="string", example="Job not found!")
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
            'job_num' => 'required',
            'job_name' => 'required',
            'job_location' => 'required',
            'status' => 'required|string|max:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $job = Jobs::find($id);

        if ($job) {
            $job->job_num = $request->job_num;
            $job->job_name = $request->job_name;
            $job->job_location = $request->job_location;
            $job->status = $request->status;
            $job->save();

            return response()->json([
                'status' => 200,
                'message' => 'Job updated successfully.',
            ], 200);
        }

        return response()->json(['message' => 'Job not found!'], 404);
    }

}
