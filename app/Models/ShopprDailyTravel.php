<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopprDailyTravel extends Model
{
    use HasFactory;

    protected $table='shoppr_daily_travel';

    protected $fillable=['shoppr_id', 'date', 'km'];
}
