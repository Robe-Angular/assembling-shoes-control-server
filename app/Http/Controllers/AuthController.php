<?php

namespace App\Http\Controllers;
use App\Http\Helper;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
      public function login(Request $request)
    {
        $params = Helper::get_params($request);
        $params_array = (array) $params;
        
        
        $credentials = $request->only('email', 'password');
        $user_role = '';
        if ($token = auth('user')->attempt($params_array)) {
            $user_role = 'user';
            return response()->json([
                'token' => $token,
                'user_role' => $user_role
            ]);
        }else{
            if ($token = auth('admin')->attempt($params_array)) {
                $user_role = 'admin';
                return response()->json([
                    'token' => $token,
                    'user_role' => $user_role
                ]);
            }   
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
