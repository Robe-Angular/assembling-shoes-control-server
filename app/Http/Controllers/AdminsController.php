<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Admin;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminsController extends Controller
{
    public function allow_register(Request $request,$allow_register){
        
        $admin = auth()->user();
        $admin_id = $admin->id;
        $int_allow_register = (int)($allow_register === 'true');
        
        $update_user = Admin::where('id', $admin_id)->first()->update(
            [
                'allow_register' => $int_allow_register
        ]);

        
        
        return response()->json([
            'update_user' => $update_user,
            'allow_register' => $allow_register,
            'int_allow_register' => $int_allow_register
                
        ]);
    }
    public function index(Request $request){
        return response()->json([
            'manager' => auth()->user()
        ]);
    }
    
    public function get_users_not_verified(){
        $users_not_verified = User::where('email_verified_at',NULL)->get();
        
        return response()->json([
            'users_not_verified' => $users_not_verified
        ]);
        
    }
    
    public function accept_email($user_id){
        $user_updated = User::where('id',$user_id)->update([
            'email_verified_at' => date('Y/m/d H:i:s',time())
        ]);
        
        return response()->json([
            'updated' => $user_updated
        ]);
    }
    
    public function deny_email($user_id){
        $user_deleted = User::where('id',$user_id)->delete();
        
        return response()->json([
            'deleted' => $user_deleted
        ]);
    }
}
