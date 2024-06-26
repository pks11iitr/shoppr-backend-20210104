<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopprDailyTravel extends Model
{
    use HasFactory;

    protected $table='shoppr_daily_travel';

    protected $fillable=['shoppr_id', 'date', 'km','rider_commission'];

    public function shoppr(){
        return $this->belongsTo('App\Models\Shoppr', 'shoppr_id');
    }

}
