<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\Shoppr;
use App\Services\Notification\FCMNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNewOrderNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chat_id,$location;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($chat_id,$location)
    {
        $this->chat_id=$chat_id;
        $this->location=$location;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $shopprs=Shoppr::whereHas('locations', function($query) {
            $query->where('name', $this->location->name);
        })
        ->select('id', 'notification_token')->get();

        foreach($shopprs as $shoppr){
            if($shoppr->notification_token){
                Notification::create([
                    'user_id'=>$shoppr->id,
                    'type'=>'individual',
                    'title'=>'New Order',
                    'description'=>'New Order',
                    'user_type'=>'SHOPPR'
                ]);

                $shoppr->notify(new FCMNotification('New Order', 'New Order', array_merge(['message'=>'New Order'], ['type'=>'pending_order', 'chat_id'=>''.$this->chat_id]),'pending_order'));
            }
        }
    }
}
