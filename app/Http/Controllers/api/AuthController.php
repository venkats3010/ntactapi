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
                        return response(['status' => 200, 'result' => "true", 'message' => "user authentication successful", 'auth' => 1, 'access_token' => $accessToken, 'data'=>$getUser]);
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

    /**
     * @OA\POST(
     *     path="/api/auth/login",
     *     tags={"Auth"},
     *     summary="User Login",
     *     description="Enter the username and password",
     *     operationId="login",
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         description="username",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *           
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="password",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid status value"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
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

//         try {
//             $getUser = DB::select('select * from users where username = ?', [$request->get('username')]);
        
//             if ($getUser) {
//                 // Query was successful and returned data
//                 echo "User found!";
//             } else {
//                 // No user found
//                 echo "User not found!";
//             }
//         } catch (\Illuminate\Database\QueryException $e) {
//             // Handle query exceptions (e.g., invalid query, connection errors)
//             echo "Database query failed: " . $e->getMessage();
//         } catch (\Exception $e) {
//             // Handle any other exceptions
//             echo "An error occurred: " . $e->getMessage();
//         }
// exit;        
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
}
