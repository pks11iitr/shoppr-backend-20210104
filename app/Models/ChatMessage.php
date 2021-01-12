<?php

namespace App\Models;

use App\Models\Traits\DocumentUploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ChatMessage extends Model
{
    use HasFactory, DocumentUploadTrait;

    protected $table='chatmessages';

    protected $fillable=['chat_id', 'type', 'message', 'file_path', 'direction', 'status', 'price', 'quantity'];

    public function getFilePathAttribute($value){
        if($value)
            return Storage::url($value);
        return '';
    }

}
