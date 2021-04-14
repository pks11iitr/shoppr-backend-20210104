<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $table='chats';

    protected $fillable=['customer_id', 'shoppr_id', 'lat','lang','location_id', 'is_terminated'];


    public function customer(){
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    public function shoppr(){
        return $this->belongsTo('App\Models\Shoppr', 'shoppr_id');
    }

    public function messages(){
        return $this->hasMany('App\Models\ChatMessage', 'chat_id');
    }

    public function getCreatedAtAttribute($value){
        return date('d/m/Y h:iA', strtotime($value));
    }

    public function rejectedby(){
        return $this->belongsToMany('App\Models\Shoppr', 'rejected_chats', 'chat_id', 'shoppr_id');
    }
}
