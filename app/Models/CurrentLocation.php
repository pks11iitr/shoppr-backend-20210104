<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentLocation extends Model
{
    use HasFactory;

    protected $table='current_locations';

    protected $fillable=['lat','lang', 'shoppr_id'];

}
