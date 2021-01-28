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

    protected $fillable=['chat_id', 'type', 'message', 'file_path', 'direction', 'status', 'price', 'quantity', 'lat', 'lang', 'order_id'];

    public function getFilePathAttribute($value){
        if($value)
            return Storage::url($value);
        return '';
    }

    public function chat(){
        return $this->belongsTo('App\Models\Chat', 'chat_id');
    }

    public function getCreatedAtAttribute($value){
        return date('d/m/Y h:iA', strtotime($value));
    }

}
