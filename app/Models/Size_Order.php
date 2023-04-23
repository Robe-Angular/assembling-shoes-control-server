<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size_Order extends Model
{
    use HasFactory;
    
    protected $table = 'size_order';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'quantity',
        'size_id',
        'order_id'
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public function size(){
        return $this->belongsTo('App\Models\Size', 'size_id');
    }
    
    public function order(){
        return $this->belongsTo('App\Models\Order', 'order_id');
    }
}
