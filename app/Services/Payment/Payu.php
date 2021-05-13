<?php


namespace App\Services\Payment;


use GuzzleHttp\Client;

class Payu
{
    public $key='';
    protected $api_salt='';

    public function __construct(Client $client){
        $this->key=env('PAYU_KEY');
        $this->api_salt=env('PAYU_SALT');
        $this->client=$client;
    }

    public function generateHash($data){
        //var_dump($data);die;
        $hashSequence = $this->key.'|'.$data['refid'].'|'.$data['amount'].'|'.$data['product'].'|'.$data['name'].'|'.$data['email'].'|||||||||||'.$this->api_salt;
        $hash = hash("sha512", $hashSequence);
        return $hash;
    }

    public function verifyhash($data){
        //var_dump($data);
        $hashSequence = $this->api_salt.'|'.$data['status'].'|||||||||||'.$data['email'].'|'.$data['name'].'|'.$data['product'].'|'.$data['amount'].'|'.$data['refid'].'|'.$this->key;
        $hash = hash("sha512", $hashSequence);
        return $hash;
    }
}
