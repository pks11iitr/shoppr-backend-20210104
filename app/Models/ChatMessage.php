<?php

namespace App\Models;

use App\Models\Traits\DocumentUploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory, DocumentUploadTrait;

    protected $table='chatmessages';

    protected $fillable=['chat_id', 'type', 'message', 'file_path', 'direction', 'status'];

}