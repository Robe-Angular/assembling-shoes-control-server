<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request){
        return response()->json([
            'manager' => $request->user()
        ]);
    }
    
    public function verifies(Request $request){
        return response()->json([
            'manager' => $request->user()
        ]);
    }
    
}
