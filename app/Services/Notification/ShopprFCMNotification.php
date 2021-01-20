<?php


namespace App\Services\Notification;


class ShopprFCMNotification extends FCMNotification
{
    public function __construct($title, $body, $data){
        $this->title=$title;
        $this->body=$body;
        $this->data=$data;
    }


    // optional method when using kreait/laravel-firebase:^3.0, this method can be omitted, defaults to the default project
    public function fcmProject($notifiable, $message)
    {
        // $message is what is returned by `toFcm`
        return 'app'; // name of the firebase project to use
    }
}
