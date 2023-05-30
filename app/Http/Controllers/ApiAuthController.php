<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\Admin;
use App\Http\Helper;

class ApiAuthController extends Controller
{
    


    public function register(Request $request){
        
        $params = Helper::get_params($request);
        $params_array = (array) $params;
        
        $validator = Validator::make($params_array,[
            'name' => 'required|string|unique:users|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:5'
        ]);
        
        if($validator->fails()){
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        
        $admin_email = $params->admin_email;
        
        $admin=Admin::where('email',$admin_email)->first();
        if(!is_object($admin)){
            return response()->json([
                'message' => 'no admin'
            ], 400);
        }
        $allows_register = $admin->allow_register;
        
        if($validator->passes() && $allows_register){
            $user = User::create([
                'name' => $params->name,
                'email' => $params->email,
                'password' => Hash::make($params->password)
            ]);
            return response()->json([   
                'success' =>'success'
            ],200);
        }else{
            return response()->json([
                'message' => 'no admin'
            ], 400);
        }
    }
    
    
    /*
    public function login(Request $request){
        $params = Helper::get_params($request);
        $params_array = (array) $params;
        
        $attempt = $this->attempt($params_array);
        
        $user_role = 'user';
        $manager = User::where('email',$params_array['email'])->first();
        if(!is_object($manager)){
            $user_role = 'admin';
            $manager = Admin::where('email',$params_array['email'])->first();
            if(!is_object($manager)){
                return response()->json([
                    'message' => 'no user match',
                    'attempt' => $attempt,
                    'params' => $params,
                    'json' => $request->input('json',null)
                    
                ],401);
            }
        }
        //$token_hashed = $manager->createToken($manager->email)->plainTextToken;
        return response()->json([
            'message' =>'success',
            'user_role' => $user_role
        ]);
    }
    */
    public function logout(Request $request){
        auth()->logout();
        return response()->json([
            'message' =>'success'
        ]);
    }
}
