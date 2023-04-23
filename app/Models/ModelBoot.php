<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelBoot extends Model
{
    use HasFactory;
    
    protected $table = 'models_boot';
    
    protected $primaryKey = 'id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'features',
        'creator'
    ];
    
    public function creator(){
        return $this->belongsTo('App\Models\User', 'creator');
    }
    
    public function sizes(){
        return $this->hasMany('App\Models\Size', 'model_boot_id');
    }
    
    public function model_boot_worker(){
        return $this->hasMany('App\Models\Model_worker', 'model_boot_id');
    }
    
    public function size_worker(){
        return $this->hasManyThrough(
            'App\Models\Size_worker', 
            'App\Models\Size', 
            'model_boot_id', 
            'size_id'
        );
    }
    
}
