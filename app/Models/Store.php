<?php

namespace App\Models;

use App\Models\Traits\Active;
use App\Models\Traits\DocumentUploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Store extends Model
{
    use HasFactory,Active,DocumentUploadTrait;
    protected $table='stores';

    protected $fillable = ['location_id','store_name','store_type','email','lat','lang','isactive','mobile','opening_time','address','about_store','is_sale','image'];

    protected $hidden = ['deleted_at','updated_at','created_at'];

    public function getImageAttribute($value){
        if($value)
            return Storage::url($value);
        return null;
    }
    public function images(){
        return $this->hasMany('App\Models\Document', 'store_id');
    }

    public function categories(){
        return $this->belongsToMany('App\Models\Category', 'store_categories', 'store_id', 'category_id');
    }
}
