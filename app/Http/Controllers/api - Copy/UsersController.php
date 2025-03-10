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

    public function get(Request $request)
    {	//	\DB::enableQueryLog(); 
		if($request->has('id')){
			$res = User::where('id', $request->get('id'))->first();
		}else if($request->has('name')){
			$res = User::where('username', 'like', '%' . $request->get('name') . '%')->get();
        }else{
            $res = DB::table('users')->where('status', 'A')->get();
        }
		// $qry = \DB::getQueryLog();
		return response(['status' => 200, 'result' => "true", 'message' => "Success", 'data'=>$res]);
    }
	
   /*  public function get()
    {
        $response = DB::table('users')->get();
        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $response,
        ], 200);
    } */

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
			$mpin = !isset($request->mpin) || $request->mpin == "" ? '1234' : $request->mpin;
            $user = User::create([
                'firstname' => $validated['firstname'],
                'lastname' => $validated['lastname'],
                'username' => $validated['username'],
                'password' => password_hash($request->password, PASSWORD_BCRYPT),
                'mpin' => password_hash($mpin, PASSWORD_BCRYPT),
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
            //$user->username = $request->username;
			if($request->password){
				$user->password = password_hash($request->password, PASSWORD_BCRYPT);
			}
			if($request->mpin){
				$user->mpin = password_hash($request->mpin, PASSWORD_BCRYPT);
			}
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

 /**
     * @OA\Put(
     *     path="/users/{id}/changepassword",
     *     summary="Change the user's password",
     *     description="Allows a user to change their password.",
     *     operationId="changePassword",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the user to update the password for"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Password change request body",
     *         @OA\JsonContent(
     *                 type="object",
     *                 required={"currentpassword", "newpassword", "confirmpassword"},
     *                 @OA\Property(property="currentpassword", type="string", description="Current password of the user", example="oldPassword123"),
     *                 @OA\Property(property="newpassword", type="string", description="New password to be set", example="newPassword456"),
     *                 @OA\Property(property="confirmpassword", type="string", description="Confirmation of the new password", example="newPassword456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Password updated successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error - required fields not provided or mismatched",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object", example={
	 *					@OA\Property(property="currentpassword", type="string", example="The current password field is required."),
     *                 @OA\Property(property="newpassword", type="string", example="The new password field is required."),
     *                @OA\Property(property="confirmpassword", type="string", example="The confirm password field is required.")
     *             })
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="user not found!")
     *         )
     *     )
     * )
     */
    public function changepassword(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'currentpassword' => 'required',
            'newpassword' => 'required',
            'confirmpassword' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        if ($request->newpassword != $request->confirmpassword) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::find($id);

        if ($user) {
			$user->password = password_hash($request->newpassword, PASSWORD_BCRYPT);
            $user->save();

            return response()->json([
                'status' => 200,
                'message' => 'Password updated successfully.',
            ], 200);
        }

        return response()->json(['message' => 'user not found!'], 404);
    }
	
	public function destroy($id)
	{
		$res = User::find($id);
		if ($res) {
			$res->status = 'I';
			$res->save();

			return response()->json([
				'status' => 200,
				'message' => 'Delete successfully.',
			], 200);
		}

		return response()->json([
			'message' => 'Resource not found.',
		], 404);
	}
	
}
