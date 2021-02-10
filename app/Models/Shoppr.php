<?php

namespace App\Models;

use App\Models\Traits\DocumentUploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Shoppr extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable,DocumentUploadTrait;
    protected $table='shoppers';

    protected $fillable = ['mobile', 'name', 'status', 'lat','lang', 'isactive','location', 'notification_token','image','address','password','pan_card'.'sendbird_token','aadhaar_card','dl_no','account_no','ifsc_code','account_holder','bank_name','form_step'];

    protected $hidden = ['deleted_at','updated_at','created_at'];


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    /**
     * Specifies the user's FCM token
     *
     * @return string|array
     */
    public function routeNotificationForFcm()
    {
        return $this->notification_token;
    }

}
