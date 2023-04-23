<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size_worker extends Model
{
    use HasFactory;
    
    protected $table = 'size_worker';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'quantity',
        'worker_id',
        'size_id'
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public function worker(){
        return $this->belongsTo('App\Models\Worker', 'worker_id');
    }
    
    
    
    public function size(){
        return $this->belongsTo('App\Models\Size', 'size_id');
    }
}
