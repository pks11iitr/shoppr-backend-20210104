<?php


namespace App\Services\SMS;


class Nimbusit
{
    protected static $USERID;
    protected static $PASSWORD;
    protected static $SENDERID;
    protected static $DLT_TEMPLATE_ID;
    protected static $ENTITY_ID;

    public static function send($mobile, $message){

        self::$USERID=env('SMS_USER_ID');
        self::$PASSWORD=env('SMS_USER_PASSWORD');
        self::$SENDERID=env('SMS_SENDER_ID');
        self::$DLT_TEMPLATE_ID=env('DLT_TEMPLATE_ID');
        self::$ENTITY_ID=env('ENTITY_ID');

        $curl = curl_init();
//echo "http://nimbusit.biz/api/SmsApi/SendSingleApi?UserID=".self::$USERID."&Password=".self::$PASSWORD."&SenderID=".self::$SENDERID."&Phno=$mobile&Msg=$message";die;
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://nimbusit.biz/api/SmsApi/SendSingleApi?UserID=".self::$USERID."&Password=".self::$PASSWORD."&SenderID=".self::$SENDERID."&Phno=$mobile&Msg=".urlencode($message)."&EntityID=".self::$ENTITY_ID."&TemplateID=".self::$DLT_TEMPLATE_ID,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        //var_dump($response);die;
        if ($err) {
            return false;
        } else {
            return true;
        }
    }
}
