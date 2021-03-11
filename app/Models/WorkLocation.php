<?php

namespace App\Models;

use App\Models\Traits\Active;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkLocation extends Model
{
    use HasFactory, Active;

    protected $table='work_locations';

    protected $fillable=['name','isactive'];
}
