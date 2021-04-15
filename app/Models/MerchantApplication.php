<?php

namespace App\Models;

use App\Models\Traits\DocumentUploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MerchantApplication extends Model
{
    use HasFactory, DocumentUploadTrait;

    protected $table='merchant_applications';

    protected $fillable=[
        'store_name','image','store_type','image', 'lat','lang','email','mobile','address','about_store', 'customer_id','opening_time'
    ];

    public function getImageAttribute($value){
        if($value)
            return Storage::url($value);
        return Storage::url('customers/default.jpeg');
    }

    public function customer(){
        return $this->belongsTo('App\Models\Customer','customer_id');
    }

}
