<?php

namespace App\Jobs;

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

    protected $chat_id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($chat_id)
    {
        $this->chat_id=$chat_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $shopprs=Shoppr::get();
        foreach($shopprs as $shoppr){

            $shoppr->notify(new FCMNotification('New Order', $message->message??'', array_merge(['message'=>'New Order'], ['type'=>'pending-order', 'chat_id'=>''.$this->chat_id]),'pending-order'));
        }
    }
}
