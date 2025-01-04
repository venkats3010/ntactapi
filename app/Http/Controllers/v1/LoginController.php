<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Validator;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
 
    public function login(Request $request)
    { dd($request->all()); 
        $validator = Validator::make($request->all(), [ 
            'username' => 'required',
            'pwd' => 'required',
        ]);
        if ($validator->fails()) { 
             return response()->json(['status' => 422, 'error'=>$validator->errors()], 422);            
        }
       
        $getUser = DB::table('user_master')->where('username', $request->get('username'))->first();

        if ($getUser && Hash::check($request->get('pwd'), $getUser->pwd)) {
            // here you know data is valid
            $userModel = \App\Models\User::find($getUser->id);

            // Check if the user model instance is found
            if ($userModel) {
                // Create a personal access token for the user
                $accessToken = $userModel->createToken('authToken')->plainTextToken;

                return response(['access_token' => $accessToken]);
                // Now you can use $accessToken as needed
            } else {
                return response(['status' => 201, 'error' => 'User not found'], 201);
            }
           
          } else {
            return response(['status' => 201,'error' => 'Enter correct username/password'], 201); 
          }
        
    }
}
