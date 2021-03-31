<?php

namespace App\Models;

use App\Models\Traits\Active;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory,Active;

    protected $table='cities';

    protected $fillable=['name', 'isactive', 'state_id'];

    protected $hidden = ['created_at','deleted_at','updated_at'];

    public function state(){
        return $this->belongsTo('App\Models\State', 'state_id');
    }

}
