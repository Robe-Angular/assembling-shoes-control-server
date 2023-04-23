<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Model_worker extends Model
{
    use HasFactory;
    
    protected $table = 'model_boot_worker';
    
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'model_boot_id',
        'worker_id'
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public function model_boot(){
        return $this->belongsTo('App\Models\ModelBoot', 'model_boot_id');
    }
    
    public function worker(){
        return $this->belongsTo('App\Models\Worker', 'worker_id');
    }
    
    public function orders(){
        return $this->hasMany('App\Models\Order', 'model_boot_worker_id');
    }
}
