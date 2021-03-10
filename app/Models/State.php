<?php

namespace App\Models;

use App\Models\Traits\Active;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory ,Active;
    protected $table='states';
    protected $hidden = ['created_at','deleted_at','updated_at'];

    public function cities(){
        return $this->hasMany('App\Models\City', 'state_id');
    }
}
