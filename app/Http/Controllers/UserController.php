<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function index(Request $request){
        return response()->json([
            'manager' => auth()->user()
        ]);
    }
    
    public function verifies(Request $request){
        return response()->json([
            'manager' => auth()->user()
        ]);
    }
    
}
