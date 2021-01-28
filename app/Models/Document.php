<?php

namespace App\Models;

use App\Models\Traits\DocumentUploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory,DocumentUploadTrait;
    protected $table='documents';
    protected $hidden = ['deleted_at','updated_at','created_at'];

    protected $fillable = ['image', 'store_id'];
    public function getImageAttribute($value){
        if($value)
            return Storage::url($value);
        return null;
    }
}
