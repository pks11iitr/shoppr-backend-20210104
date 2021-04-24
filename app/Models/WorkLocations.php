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
            $locality1=$json[2]['value']??'';
            $locality2=$json[3]['value']??'';
            $locality3=$json[4]['value']??'';
            $locality4=$json[5]['value']??'';
            $locality5=$json[6]['value']??'';

            $location=WorkLocations::active()->where(function($query)use($locality1,$locality2, $locality3,$locality4, $locality5){
                $query->where('name', $locality1)
                    ->orWhere('name',$locality2)
                    ->orWhere('name',$locality3)
                    ->orWhere('name',$locality4)
                    ->orWhere('name',$locality5);
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
