<?php

namespace App\Services\SendBird;

use App\Models\Customer;
use App\Models\Shoppr;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;

class SendBird
{

    public function __construct(Client $client){
        $this->base_url="https://api-".env('SENDBIRD_APP_ID').".sendbird.com/v3";
        $this->token=env('SENDBIRD_API_TOKEN');
        $this->client=$client;
    }

    public function createUser($user){

        //die('hello');

        if($user instanceof Shoppr){
            $user_id='Shoppr-'.$user->id;
            $img_url=$user->image;
            $nick_name=$user->name;
        }else if($user instanceof Customer){
            $user_id='Customer-'.$user->id;
            $img_url=$user->image;
            $nick_name=$user->name;
        }else{
            //die('sds');
            return;
        }


//        Content-Type: application/json; charset=utf8
//Api-Token: {master_api_token or secondary_api_token}

        try{
            $response=$this->client->request('POST', $this->base_url.'/users', [
                'headers'        => ['Content-Type' => 'application/json; charset=utf8', 'Api-Token'=>env('SENDBIRD_API_TOKEN')],
                //'decode_content' => false,
                'json' => [
                    'user_id' => $user_id,
                    'profile_url' => $img_url,
                    'nickname' => $nick_name,
                    'issue_access_token' => true,
                ]
            ]);
            //echo 'step1';
        }catch(TransferException $e){
            //echo 'step2';
            $response=$e->getResponse();
        }

        $response=$response->getBody()->getContents();
        //echo $response; die;
        $response=json_decode($response, true);

        return $response;
    }
}
