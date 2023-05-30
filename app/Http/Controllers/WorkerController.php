<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Models\Worker;
use App\Models\ModelBoot;
use App\Http\Helper;

class WorkerController extends Controller
{
    
    public function get_worker_info($worker_id){
        $worker = Worker::find($worker_id);
        
        return response()->json([
            'worker' => $worker
        ]);
    }
    
    public function new_worker(Request $request){
        $params = Helper::get_params($request);
        $params_array = (array) $params;
        
        $manager_id = auth()->user()->id;
        $new_worker = Worker::create([
           'name' => $params->name,
           'creator' => $manager_id
        ]);
        
        return response()->json([
            'worker' => $new_worker
        ],200);
    }
    
    
    public function worker_list(){
        
        /*        
        $workers = Worker::with(['creator' => function ($query){
            $query->select('id','name');
        }])->get();
        */
        
        $workers = Worker::with('creator:id,name')->get();
        return response()->json([
            'workers' => $workers
        ]);
    }
}
