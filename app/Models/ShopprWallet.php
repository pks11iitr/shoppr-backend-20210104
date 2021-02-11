<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ShopprWallet extends Model
{
    use HasFactory;

    protected $table='shopper_wallet';

    protected $fillable=['refid', 'user_id', 'description', 'type', 'amount', 'order_id'];

    protected $appends=['icon','date'];

    public function getIconAttribute($value){
        if($this->type=='Debit')
            return Storage::url('images/red.png');
        else
            return Storage::url('images/green.png');

    }

    public function getDateAttribute($value){
        return date('h:iA', strtotime($this->created_at));
    }

    public function shoppr()
    {
        return $this->belongsTo('App\Models\Shoppr', 'user_id');
    }

    public static function balance($userid){
        $wallet=ShopprWallet::where('user_id', $userid)
            ->select(DB::raw('sum(amount) as total'), 'type')
            ->groupBy('type')
            ->get();
        $balances=[];
        foreach($wallet as $w){
            $balances[$w->type]=$w->total;
        }
        return ($balances['Credit']??0)-($balances['Debit']??0);
    }

    public static function updatewallet($userid, $description, $type, $amount, $orderid=null){
        ShopprWallet::create([
            'user_id'=>$userid,
            'description'=>$description,
            'type'=>$type,
            'amount'=>$amount,
            'order_id'=>$orderid,
            'refid'=>date('YmdHis')
        ]);
    }

}
