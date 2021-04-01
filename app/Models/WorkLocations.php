<?php

namespace App\Models;

use App\Models\Traits\Active;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkLocations extends Model
{
    use HasFactory,Active;

    protected $table='work_locations';

    protected $fillable=['name', 'isactive', 'city_id'];


    public static function extractlocationfromjson($json, $cityname){
        $json=json_decode($json, true);

        $city=City::where('name', $cityname)->first();
        if(!$city)
            return null;
        if($json && count($json)>=4){
            $json=array_reverse($json);
            $locality1=$json[3]['value']??'';
            $locality2=$json[4]['value']??'';

            $location=WorkLocations::active()->where(function($query)use($locality1,$locality2){
                $query->where('name', $locality1)
                    ->orWhere('name',$locality2);
            })
                ->where('city_id', $city->id)
                ->first();
            if($location){
                return $location;
            }
        }

        return null;
    }


    public function city(){
        return $this->belongsTo('App\Models\City', 'city_id');
    }

}
