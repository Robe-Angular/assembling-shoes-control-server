<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    
    protected $fillable = [
        'creator',
        'add_or_subtract',
        'model_boot_worker_id'
    ];
    
    public function model_boot_worker(){
        return $this->belongsTo('App\Models\Model_worker', 'model_boot_worker_id');
    }
    
    public function size_order(){
        return $this->hasMany('App\Models\Size_Order', 'order_id');
    }
    
    public function creator(){
        return $this->belongsTo('App\Models\User', 'creator');
    }
    
    
}
