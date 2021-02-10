<?php
namespace App\Services\Agora;
use App\Services\Agora\AccessToken;
use App\Services\Agora\Message;
//require_once "AccessToken.php";

class RtcTokenBuilder
{
    const RoleAttendee = 0;
    const RolePublisher = 1;
    const RoleSubscriber = 2;
    const RoleAdmin = 101;

//    public function __construct(){
//        $this->appID=env('AGORA_APP_ID');
//        $this->appCertificate=env('AGORA_APP_CERTIFICATE');
//    }

    # appID: The App ID issued to you by Agora. Apply for a new App ID from
    #        Agora Dashboard if it is missing from your kit. See Get an App ID.
    # appCertificate:	Certificate of the application that you registered in
    #                  the Agora Dashboard. See Get an App Certificate.
    # channelName:Unique channel name for the AgoraRTC session in the string format
    # uid: User ID. A 32-bit unsigned integer with a value ranging from
    #      1 to (232-1). optionalUid must be unique.
    # role: Role_Publisher = 1: A broadcaster (host) in a live-broadcast profile.
    #       Role_Subscriber = 2: (Default) A audience in a live-broadcast profile.
    # privilegeExpireTs: represented by the number of seconds elapsed since
    #                    1/1/1970. If, for example, you want to access the
    #                    Agora Service within 10 minutes after the token is
    #                    generated, set expireTimestamp as the current
    #                    timestamp + 600 (seconds)./
    public static function buildTokenWithUid($channelName, $uid, $role, $privilegeExpireTs){
        //die('sdsdk');
        $token= RtcTokenBuilder::buildTokenWithUserAccount($channelName, $uid, $role, $privilegeExpireTs);
        return $token;
    }

    # appID: The App ID issued to you by Agora. Apply for a new App ID from
    #        Agora Dashboard if it is missing from your kit. See Get an App ID.
    # appCertificate:	Certificate of the application that you registered in
    #                  the Agora Dashboard. See Get an App Certificate.
    # channelName:Unique channel name for the AgoraRTC session in the string format
    # userAccount: The user account.
    # role: Role_Publisher = 1: A broadcaster (host) in a live-broadcast profile.
    #       Role_Subscriber = 2: (Default) A audience in a live-broadcast profile.
    # privilegeExpireTs: represented by the number of seconds elapsed since
    #                    1/1/1970. If, for example, you want to access the
    #                    Agora Service within 10 minutes after the token is
    #                    generated, set expireTimestamp as the current
    public static function buildTokenWithUserAccount($channelName, $userAccount, $role, $privilegeExpireTs){
        //echo env('AGORA_APP_ID'), env('AGORA_CERTIFICATE');die;
        $token = AccessToken::init(env('AGORA_APP_ID'), env('AGORA_CERTIFICATE'), $channelName, $userAccount.'');
        $Privileges = AccessToken::Privileges;
        //var_dump($Privileges);
        $token->addPrivilege($Privileges["kJoinChannel"], $privilegeExpireTs);
        //echo $channelName, $userAccount, $role, $privilegeExpireTs;die;
        if(($role == RtcTokenBuilder::RoleAttendee) ||
            ($role == RtcTokenBuilder::RolePublisher) ||
            ($role == RtcTokenBuilder::RoleAdmin))
        {
            //die('sdsd');
            $token->addPrivilege($Privileges["kPublishVideoStream"], $privilegeExpireTs);
            $token->addPrivilege($Privileges["kPublishAudioStream"], $privilegeExpireTs);
            $token->addPrivilege($Privileges["kPublishDataStream"], $privilegeExpireTs);
        }
        return $token->build();
    }
}


?>
