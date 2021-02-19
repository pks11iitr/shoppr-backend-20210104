<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    use HasFactory;

    protected $table='checkins';

    protected $fillable=['lat','lang','type','address', 'shoppr_id'];

    public function shoppr(){
        return $this->belongsTo('App\Models\Shoppr', 'shoppr_id');
    }
}
