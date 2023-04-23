<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;
    
    protected $table = 'sizes';
    
    protected $primaryKey = 'id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'number',
        'model_boot_id'
    ];
    
    public function model_boot(){
        return $this->belongsTo('App\Models\ModelBoot', 'model_boot_id');
    }
    
    public function size_order(){
        return $this->hasMany('App\Models\Size_Order', 'size_id');
    }
    
    public function size_worker(){
        return $this->hasMany('App\Models\Size_worker', 'size_id');
    }
}
