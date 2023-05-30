<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModelBoot;
use App\Models\Size;
use App\Models\Model_worker;
use App\Models\Size_worker;
use App\Models\Size_Order;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Order;
use App\Http\Helper;

class ModelController extends Controller
{
    /*
     * Methods:
     * -Get model_boot info
     * -Make model_boot
     * -Model_boot list
     * -Get sizes by model_boot
     * -Make Order
     * -Model_boot from worker at least 0ne size
     * -Size_worker from model satisfy
     * -Complete order
     * -Model_boot from worker at least One order
     * -Get Order by worker
     * -Get Order by model_boot_worker
     * -Get all sizes_worker of worker
     * 
     */
    public function get_model_info($model_boot_id){
        $model_boot = ModeBoot::find($model_boot_id)->get();
        
        return response()->json([
            'model_boot' => $model_boot
        ]);
    }
    
    public function make_model(Request $request){
        $params = Helper::get_params($request);
        $params_array = (array) $params;
        
        $manager_id = auth()->user()->id;
        $new_model = ModelBoot::create([
            'title' => $params->title,
            'features' => $params->features,
            'creator' => $manager_id
        ]);
        
        $min_size = $params->minSize;
        $max_size = $params->maxSize;
        if($max_size >= $min_size && is_object($new_model)){
            
            for($i = $min_size; $i <= $max_size; $i++ ){
                $new_size = Size::create([
                    'number' => $i,
                    'model_boot_id' => $new_model->id
                ]);
            }
            return response()->json([
                'worker' => $new_model
            ],200);
            
        }else{
            ModelBoot::destroy($new_model->id);
            return response()->json([
                'message' => 'Error creating sizes'
            ],500);
                    
        }
        
        
        
        
        
    }
    
    public function model_list(){
        $models = ModelBoot::with('creator:id,name')->get();
        return response()->json([
            'models' => $models
        ]);
    }
    
    public function get_sizes_by_model_boot($model_boot_id){
        $sizes = Size::where('model_boot_id',$model_boot_id)->get();
        
        return response()->json([
            'sizes' => $sizes
        ],200);
    }
    
    public function make_order(Request $request, $worker_id){
        $sizes_input = Helper::get_params($request);
        $params_array = (array) $sizes_input;
        $models_boot_id = array_unique(array_column($sizes_input,'modelId'));
        $manager_id = auth()->user()->id;
        
        //Check already model_worker
        foreach ($models_boot_id as $model_boot_id){
            if(!Model_worker::where('model_boot_id', $model_boot_id)
                ->where('worker_id',$worker_id)
            ->exists()){
                $new_model_worker = Model_worker::create([
                    'model_boot_id' => $model_boot_id,
                    'worker_id' =>  $worker_id
                ]);
            }
        }
        
        //Check already size_worker
        foreach ($sizes_input as $size_input){
            if(!Size_worker::where('size_id',$size_input->sizeId)
                ->where('worker_id',$worker_id)
            ->exists()){
                $new_model_worker = Size_worker::create([
                    'size_id' => $size_input->sizeId,
                    'worker_id' =>  $worker_id,
                    'quantity' => 0
                ]);
            }
        }
        $result_array_sizes_worker = [];
        
        //Save Orders
        foreach($models_boot_id as $model_boot_id){
            //first get $model_boot_worker_id
            $model_boot_worker_id = Model_worker::where('model_boot_id',$model_boot_id)
                ->where('worker_id',$worker_id)
            ->first()->id;
            
            $new_order = Order::create([
                'creator' => $manager_id,
                'model_boot_worker_id' => $model_boot_worker_id,
                'add_or_subtract' => false
            ]);
            $new_order_id = $new_order->id;
            
            
            //first filter by model_boot
            $sizes_model_filter = array_filter($sizes_input, function($size_input) use ($model_boot_id) {
                return $size_input->modelId == $model_boot_id;
            });
            
            
            
            foreach($sizes_model_filter as $size_model_filter){
                //Save sizes_order
                $new_size_order = Size_Order::create([
                    'size_id' => $size_model_filter->sizeId,
                    'order_id' => $new_order_id,
                    'quantity' => $size_model_filter->quantity
                ]);
                
                //Update size_worker
                $update_size_worker = Size_worker::where('size_id',$size_model_filter->sizeId)
                    ->where('worker_id',$worker_id)->first()
                ->increment('quantity', $size_model_filter->quantity);
                array_push($result_array_sizes_worker, $update_size_worker);
                        
            }   
        }    
         
        /*
        {"sizes":
         * [
            * {
               * "sizeId":1,
               * "modelId":7,
               * "number":22,
               * "quantity":"10",
               * "modelTitle":"403",
               * "modelFeatures":"sin cierre, celaste, ranil"
            * },{"sizeId":2,"modelId":7,"number":23,"quantity":"12","modelTitle":"403","modelFeatures":"sin cierre, celaste, ranil"},{"sizeId":3,"modelId":7,"number":24,"quantity":"13","modelTitle":"403","modelFeatures":"sin cierre, celaste, ranil"},{"sizeId":4,"modelId":7,"number":25,"quantity":"14","modelTitle":"403","modelFeatures":"sin cierre, celaste, ranil"},{"sizeId":18,"modelId":8,"number":28,"quantity":"15","modelTitle":"403","modelFeatures":"con cierre, ranil, celaste"},{"sizeId":19,"modelId":8,"number":29,"quantity":"16","modelTitle":"403","modelFeatures":"con cierre, ranil, celaste"},{"sizeId":20,"modelId":8,"number":30,"quantity":"17","modelTitle":"403","modelFeatures":"con cierre, ranil, celaste"}]}
         *          */
        
            return response()->json([
                'result' => $result_array_sizes_worker
            ]);
    }
    
    public function model_from_worker_at_least_one_size($worker_id){
        
        $models_conditioned = ModelBoot::whereHas('size_worker', function ($query) use ($worker_id){
            $query->where('quantity','>',0)->where('worker_id',$worker_id);
        })->get();
        
        return response()->json([
            'models_satisfy' => $models_conditioned
        ]);
    }
    
    public function size_worker_from_model_satisfy($worker_id,$model_id){
        
    $size_worker = Size_worker::whereHas('size.model_boot',function ($query) use ($model_id){
            $query->where('id',$model_id);
        })
        ->where('quantity','>', 0)
        ->where('worker_id',$worker_id)
        ->with('size')        
    ->get();
        
        
        return response()->json([
            'sizes_worker_satisfy' => $size_worker
        ]);
    }
    
    public function complete_order(Request $request){
        //sizeId field is id of size_worker table, no size table
        
        $sizes = Helper::get_params($request);
        $models_boot = array_unique(array_column($sizes,'modelId'));
        $manager_id = request()->user()->id;
        $some_size_id = $sizes[0]->sizeId;
        $worker_id = Size_worker::find($some_size_id)->worker_id;                
        
        
        foreach($models_boot as $model){
            
            $model_boot_worker_id = Model_worker::where('worker_id',$worker_id)
                ->where('model_boot_id',$model)
            ->first()->id;
            
            $new_order = Order::create([
                'creator' => $manager_id,
                'model_boot_worker_id' => $model_boot_worker_id,
                'add_or_subtract' => true
            ]);
            
            $new_order_id = $new_order->id;
            
            $sizes_filter_model_boot = array_filter($sizes,function($size) use ($model){
                return $size->modelId == $model;
            });
            
            foreach($sizes_filter_model_boot as $size_element){
                //get Size.id from size_worker
                $size_id = Size_worker::find($size_element->sizeId)->size_id;
                Size_Order::create([
                    'quantity' => $size_element->quantity,
                    'size_id'=> $size_id,
                    'order_id' => $new_order_id
                ]);
            }
        }
        $decrement_sizes = [];
        foreach($sizes as $size){
            $decrement_size = Size_worker::find($size->sizeId)->decrement('quantity',$size->quantity);
            array_push($decrement_sizes, $decrement_size);
        }
        
        return response()->json([
           'decremented' => $decrement_sizes
        ]);
    }
    
    public function models_boot_worker_at_least_one_order($worker_id){
        $model_boot_worker = Model_worker::where('worker_id',$worker_id)
            ->whereHas('orders')
            ->with('model_boot')
        ->get();
        return response()->json([
            'models_boot_satisfy' => $model_boot_worker
        ]);
    }
    
    public function orders_by_worker($worker_id){
        $orders_by_worker = Order::whereHas('model_boot_worker', function ($query) use ($worker_id){
                $query->where('worker_id',$worker_id);
            })->with([
                'creator:id,name',
                'size_order.size'=>function ($query){
                    $query->orderBy('number','asc');
                },
                'model_boot_worker.model_boot'
                ])
        ->get();
        return response()->json([
           'orders_by_worker' => $orders_by_worker
        ]); 
                
    }
    
    public function orders_by_model_boot_worker($model_boot_worker_id){
        $orders_by_model_boot_worker = Order::where('model_boot_worker_id',$model_boot_worker_id)
                ->with([
                    'creator:id,name',
                    'size_order.size'=>function ($query){
                        $query->orderBy('number','asc');
                    },
                    'model_boot_worker.model_boot'
                ])
        ->get();
        return response()->json([
            'orders_by_model_boot_worker' => $orders_by_model_boot_worker
        ]);
    }
    
    public function models_boot_with_at_least_one_size_worker($worker_id){
        
        $models_boot_satisfy = ModelBoot::whereHas('sizes',function($query) use ($worker_id){
            $query->whereHas('size_worker', function ($query) use ($worker_id){
                $query->where('worker_id',$worker_id)->where('quantity','>',0);
            });
        })->get();
          
        return response()->json([
            'models_boot_satisfy' => $models_boot_satisfy
        ]);
    }
    
    public function size_worker_by_worker($worker_id){
        
        $models_boot_satisfy = ModelBoot::whereHas('sizes',function($query) use ($worker_id){
            $query->whereHas('size_worker', function ($query) use ($worker_id){
                $query->where('worker_id',$worker_id)->where('quantity','>',0);
            });
        })->with('sizes', function ($query) use ($worker_id){
            $query->whereHas('size_worker', function($query) use ($worker_id){
                $query->where('quantity','>',0)->where('worker_id',$worker_id);
            })->with('size_worker',function ($query) use ($worker_id){
                $query->where('quantity','>',0)->where('worker_id',$worker_id);
            });
        })->get();
                
        return response()->json([
            'models_with_size_worker' => $models_boot_satisfy
        ]);
    }
    
    public function size_worker_by_worker_and_model($worker_id,$model_id){
        $model_boot_satisfy = ModelBoot::whereHas('sizes',function($query) use ($worker_id){
            $query->whereHas('size_worker', function ($query) use ($worker_id){
                $query->where('worker_id',$worker_id)->where('quantity','>',0);
            });
        })->with('sizes', function ($query) use ($worker_id){
            $query->whereHas('size_worker', function($query) use ($worker_id){
                $query->where('quantity','>',0)->where('worker_id',$worker_id);
            })->with('size_worker',function ($query) use ($worker_id){
                $query->where('quantity','>',0)->where('worker_id',$worker_id);
            });
        })->find($model_id);
                
        return response()->json([
            'model_with_size_worker' => $model_boot_satisfy
        ]);
    }

}
