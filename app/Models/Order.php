<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table='orders';

    protected $fillable=['user_id','shoppr_id', 'chat_id', 'refid', 'total', 'service_charge', 'status', 'payment_status', 'payment_mode'];

    public function details(){
        return $this->hasMany('App\Models\ChatMessage', 'order_id')
            ->where('type','product')
            ->where('status', 'accepted');
    }

    public function deliveryaddress(){
        return $this->hasMany('App\Models\ChatMessage', 'order_id')
            ->where('type','address');
    }

    public function reviews(){
        return $this->hasMany('App\Models\ChatMessage', 'order_id')
            ->where('type','rating')
            ->where('status', 'accepted');
    }



    public function grandTotal(){
        return $this->total+$this->service_charge;
    }

    public function grandTotalForPayment(){
        return $this->total+$this->service_charge-$this->balance_used;
    }

    public function customer(){
        return $this->belongsTo('App\Models\Customer', 'user_id');
    }

    public function shoppr(){
        return $this->belongsTo('App\Models\Shoppr', 'shoppr_id');
    }

    public function getCreatedAtAttribute($value){
        return date('d/m/Y h:iA', strtotime($value));
    }

}
