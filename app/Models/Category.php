<?php

namespace App\Models;

use App\Models\Traits\Active;
use App\Models\Traits\DocumentUploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory,Active, DocumentUploadTrait;

    protected $table='categories';

    protected $fillable=['name','isactive'];

    protected $hidden = ['created_at','deleted_at','updated_at'];

}
