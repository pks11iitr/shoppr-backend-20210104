<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejectedChat extends Model
{
    use HasFactory;

    protected $table='rejected_chats';

    protected $fillable=['shoppr_id','chat_id'];
}
