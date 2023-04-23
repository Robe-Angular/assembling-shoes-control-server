<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;
    protected $table = 'workers';
    
    protected $primaryKey = 'id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'creator'
    ];
    
    public function creator(){
        return $this->belongsTo('App\Models\User', 'creator');
    }
    
    public function model_boot_worker(){
        return $this->hasMany('App\Models\Model_worker', 'worker_id');
    }
    
    public function size_worker(){
        return $this->hasMany('App\Models\Size_worker', 'worker_id');
    }
}
