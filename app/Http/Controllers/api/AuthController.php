<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Config;
use Session;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        echo "venkat";
    }



/**
 * @OA\Post(
 *     path="/api/auth/login",
 *     summary="User Login",
 *     description="Login to the application using username and password",
 *     operationId="login",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Login credentials",
 *         @OA\JsonContent(
 *             required={"username", "password"},
 *             @OA\Property(property="username", type="string", example="john_doe"),
 *             @OA\Property(property="password", type="string", format="password", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User authentication successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="result", type="string", example="true"),
 *             @OA\Property(property="message", type="string", example="user authentication successful"),
 *             @OA\Property(property="auth", type="integer", example=1),
 *             @OA\Property(property="access_token", type="string", example="your_access_token_here"),
 *             @OA\Property(property="data", type="object", additionalProperties=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=422),
 *             @OA\Property(property="error", type="object", additionalProperties=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Incorrect username or password",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=201),
 *             @OA\Property(property="error", type="string", example="Enter correct username/password")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="An error occurred")
 *         )
 *     )
 * )
 */

    public function login(Request $request)
    { 
        //echo $username = $request->input('username');
        //print_r($request->get('username'));exit;
        $validator = Validator::make($request->all(), [ 
            'username' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) { 
             return response()->json(['status' => 422, 'error'=>$validator->errors()], 422);            
        }

        $dataList = [];
        try {
       // DB::enableQueryLog();
        $getUser = DB::table('users')->where('username', $request->get('username'))->first();
        //$getUser = DB::select('select * from users where username = ?', [$request->get('username')]);
        // print_r($getUser[0]->password); exit;
        // dd(DB::getQueryLog());exit;
        if ($getUser && Hash::check($request->get('password'), $getUser->password)) {
            $userModel = \App\Models\User::find($getUser->id);
            if ($userModel) {
                $accessToken = $userModel->createToken('authToken')->plainTextToken;               
                Log::channel('auth')->info('testAPI-- ' . json_encode($getUser));
                if ($getUser) {
                    return response(['status' => 200, 'result' => "true", 'message' => "user authentication successful", 'auth' => 1, 'access_token' => $accessToken, 'data'=>$getUser]);
                }else{
                    return json_encode($getUser);          
                }
            } else {
                return response(['status' => 201, 'error' => 'User not found'], 201);
            }           
          } else {
            return response(['status' => 201,'error' => 'Enter correct username/password'], 201); 
          }
        } catch (\Exception $e) {
             // Handle any other exceptions
            echo "An error occurred: " . $e->getMessage();
        }
    }
	
	
    public function validatePhone(Request $request)
    { 
        $validator = Validator::make($request->all(), [ 
            'phonenumber' => 'required'
        ]);
        if ($validator->fails()) { 
             return response()->json(['status' => 422, 'error'=>$validator->errors()], 422);            
        }

        try {
             $getPhone = DB::table('users')->where('phone', $request->get('phonenumber'))->first();
             if ($getPhone) {
                return response(['status' => 200, 'result' => "true", 'message' => "Phone authentication successful"]);          
               } else {
                 return response(['status' => 201,'error' => 'Enter correct Phone Number'], 201); 
               }
             } catch (\Exception $e) {
                  // Handle any other exceptions
                 echo "An error occurred: " . $e->getMessage();
             }

    }

    public function validatePin(Request $request)
    { 
        $validator = Validator::make($request->all(), [ 
            'phonenumber' => 'required',
            'mpin' => 'required'
        ]);
        if ($validator->fails()) { 
             return response()->json(['status' => 422, 'error'=>$validator->errors()], 422);            
        }
        //echo $request->get('mpin');exit;
        try {
             $getUser = DB::table('users')->where('phone', $request->get('phonenumber'))->first();
             //print_r($getUser->mpin); exit;
            //$pinverify = password_verify($request->get('mpin'), $getUser->mpin);
            if ($getUser && Hash::check($request->get('mpin'), $getUser->mpin)) {
                $userModel = \App\Models\User::find($getUser->id);
                if ($userModel) {
                    $accessToken = $userModel->createToken('authToken')->plainTextToken;               
                    Log::channel('auth')->info('testAPI-- ' . json_encode($getUser));
                    if ($getUser) {
                        return response(['status' => 200, 'result' => "true", 'message' => "user authentication successful", 'auth' => "field", 'access_token' => $accessToken, 'data'=>$getUser]);
                    }else{
                        return json_encode($getUser);          
                    }
                } else {
                    return response(['status' => 201, 'error' => 'User not found'], 201);
                }           
              } else {
                return response(['status' => 201,'error' => 'Enter correct Phone Number/Pin'], 201); 
              }
             } catch (\Exception $e) {
                  // Handle any other exceptions
                 echo "An error occurred: " . $e->getMessage();
             }

    }
	
}
