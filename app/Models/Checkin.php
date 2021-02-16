<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    use HasFactory;

    protected $table='checkins';

    protected $fillable=['lat','lang','type','address', 'shoppr_id'];
}
