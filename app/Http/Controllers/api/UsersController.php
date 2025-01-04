<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\User;
use DB;
use Validator;

class UsersController extends Controller
{
    public function index()
    {
        //
    }

 /**
 * @OA\Get(
 *     path="/api/users/get",
 *     operationId="getUsers",
 *     tags={"Users"},
 *     summary="Get a list of all users",
 *     description="Retrieve all users from the database.",
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Users retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Success"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized - No or invalid token",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=401),
 *             @OA\Property(property="message", type="string", example="Unauthenticated.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=500),
 *             @OA\Property(property="message", type="string", example="An error occurred while processing your request."),
 *             @OA\Property(property="details", type="string", example="Database connection failed")
 *         )
 *     )
 * )
 */

    public function get()
    {
        $response = DB::table('users')->get();
        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $response,
        ], 200);
    }

/**
 * @OA\Post(
 *     path="/api/users/store",
 *     operationId="storeUser",
 *     tags={"Users"},
 *     summary="Create a new user",
 *     description="Store a new user in the database.",
 *     @OA\RequestBody(
 *         required=true,
 *         description="User data to be created",
 *         @OA\JsonContent(
 *             required={"firstname", "lastname", "username", "status"},
 *             @OA\Property(property="firstname", type="string", example="John"),
 *             @OA\Property(property="lastname", type="string", example="Doe"),
 *             @OA\Property(property="username", type="string", example="john_doe"),
 *             @OA\Property(property="gender", type="string", example="male"),
 *             @OA\Property(property="countrycode", type="string", example="+1"),
 *             @OA\Property(property="phone", type="string", example="1234567890"),
 *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *             @OA\Property(property="role", type="string", example="admin"),
 *             @OA\Property(property="permissions", type="array", items=@OA\Items(type="string", example="edit")),
 *             @OA\Property(property="status", type="string", example="active"),
 *             @OA\Property(property="created_by", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="User created successfully."),
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request - Validation failed or unique constraint violated",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=400),
 *             @OA\Property(property="error", type="string", example="Users number must be unique. This user number is already taken."),
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
                'firstname' => 'required',
                'lastname' => 'required',
                'username' => 'required',
                'status' => 'required',
            ]);

            $user = User::create([
                'firstname' => $validated['firstname'],
                'lastname' => $validated['lastname'],
                'username' => $validated['username'],
                'gender' => $request->gender,
                'countrycode' => $request->countrycode,
                'phone' => $request->phone,
                'email' => $request->email,
                'role' => $request->role,
                'permissions' => json_encode($request->permissions),
                'status' => $validated['status'],
                'created_by' => $request->created_by,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'User created successfully.',
                'data' => $user,
            ], 200);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json([
                    'status' => 400,
                    'error' => 'Users number must be unique. This user number is already taken.',
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
 *     path="/api/users/update/{id}",
 *     operationId="updateUser",
 *     tags={"Users"},
 *     summary="Update an existing user",
 *     description="Update the user details for the given user ID.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID to be updated",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="User data to be updated",
 *         @OA\JsonContent(
 *             required={"firstname", "lastname", "username", "status"},
 *             @OA\Property(property="firstname", type="string", example="John"),
 *             @OA\Property(property="lastname", type="string", example="Doe"),
 *             @OA\Property(property="username", type="string", example="john_doe"),
 *             @OA\Property(property="gender", type="string", example="male"),
 *             @OA\Property(property="countrycode", type="string", example="+1"),
 *             @OA\Property(property="phone", type="string", example="1234567890"),
 *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *             @OA\Property(property="role", type="string", example="admin"),
 *             @OA\Property(property="permissions", type="array", items=@OA\Items(type="string", example="edit")),
 *             @OA\Property(property="status", type="string", example="active"),
 *             @OA\Property(property="updated_by", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="User updated successfully.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request - Validation failed",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=400),
 *             @OA\Property(property="errors", type="object", example={"firstname": "The firstname field is required."})
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=404),
 *             @OA\Property(property="message", type="string", example="user not found!")
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
            'firstname' => 'required',
            'lastname' => 'required',
            'username' => 'required',
            'status' => 'required|string|max:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::find($id);

        if ($user) {
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->username = $request->username;
            $user->gender = $request->gender;
            $user->countrycode = $request->countrycode;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->role = $request->role;
            $user->permissions = $request->permissions;
            $user->status = $request->status;
            $user->updated_by = $request->updated_by;
            $user->save();

            return response()->json([
                'status' => 200,
                'message' => 'User updated successfully.',
            ], 200);
        }

        return response()->json(['message' => 'user not found!'], 404);
    }

}
