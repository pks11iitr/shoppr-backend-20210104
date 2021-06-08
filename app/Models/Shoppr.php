<?php

namespace App\Models;

use App\Models\Traits\Active;
use App\Models\Traits\DocumentUploadTrait;
use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use NotificationChannels\Fcm\Exceptions\CouldNotSendNotification;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Shoppr extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable,DocumentUploadTrait, Active;

    protected $table='shoppers';

    protected $fillable = ['mobile', 'name', 'status', 'lat','lang', 'isactive','location', 'notification_token','image','address','password','pan_card'.'sendbird_token','front_aadhaar_card','front_dl_no','account_no','ifsc_code','account_holder','bank_name','form_step','state','city','back_aadhaar_card','back_dl_no','pay_per_km','pay_commission','pay_delivery','bike_front','bike_back', 'permanent_address', 'permanent_pin','permanent_city','permanent_state', 'secondary_mobile','emergency_mobile','work_type','city','state','email', 'is_available'];

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
        //return $this->notification_token;
        $tokens=$this->mytokens->map(function($element){
            return $element->notification_token;
        })->toArray();

        if($this->notification_token && !in_array($this->notification_token, $tokens))
            return array_merge($tokens, [$this->notification_token]);
        return $tokens;

    }

    public function mytokens(){
        return $this->morphMany('App\Models\NotificationToken', 'entity');
    }

    public function notify($instance)
    {
        try{
            app(Dispatcher::class)->send($this, $instance);

        }catch(CouldNotSendNotification $e){

        }

    }

    public function getImageAttribute($value){
        if($value)
            return Storage::url($value);
        return Storage::url('customers/default.jpeg');
    }
    public function getPanCardAttribute($value){
        if($value)
            return Storage::url($value);
        return Storage::url('customers/default.jpeg');
    }

    public function getFrontAadhaarCardAttribute($value){
        if($value)
            return Storage::url($value);
        return Storage::url('customers/default.jpeg');
    }

    public function getBackAadhaarCardAttribute($value){
        if($value)
            return Storage::url($value);
        return Storage::url('customers/default.jpeg');
    }

    public function getFrontDlNoAttribute($value){
        if($value)
            return Storage::url($value);
        return Storage::url('customers/default.jpeg');
    }
    public function getBackDlNoAttribute($value){
        if($value)
            return Storage::url($value);
        return Storage::url('customers/default.jpeg');
    }

    public function getBikeFrontAttribute($value){
        if($value)
            return Storage::url($value);
        return Storage::url('customers/default.jpeg');
    }

    public function getBikeBackAttribute($value){
        if($value)
            return Storage::url($value);
        return Storage::url('customers/default.jpeg');
    }

    public function order(){
        return $this->hasMany('App\Models\Order', 'shoppr_id');
    }

    public function rejectedchats(){
        return $this->hasMany('App\Models\Chat', 'shoppr_id');
    }

    public function locations(){
        return $this->belongsToMany('App\Models\WorkLocations', 'shoppr_work_locations', 'shoppr_id','location_id');
    }

    public function statename(){
        return $this->belongsTo('App\Models\State','permanent_state');
    }

    public function cityname(){
        return $this->belongsTo('App\Models\City','permanent_city');
    }

}
