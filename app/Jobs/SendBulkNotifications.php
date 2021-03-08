<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Shoppr;
use App\Services\Notification\FCMNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBulkNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $title,$message,$type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($title,$message,$type)
    {
        $this->type=$type;
        $this->title=$title;
        $this->message=$message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //var_dump($this->stores);die;

        if($this->type=='SHOPPR'){
            $users=Shoppr::select('id', 'notification_token')->get();
        }else{
            $users=Customer::select('id', 'notification_token')->get();
        }

        $tokens_arr=[];
        foreach($users as $user){

            Notification::create([
                'user_id'=>$user->id,
                'user_type'=>$this->type,
                'title'=>$this->title,
                'description'=>$this->message,
                'data'=>null,
                'type'=>'individual'
            ]);

            if(in_array($user->notification_token, $tokens_arr))
                continue;

            $user->notify(new FCMNotification($this->title, $this->message, [],'notification_screen'));

            $tokens_arr[]=$user->notification_token;

        }
    }
}
