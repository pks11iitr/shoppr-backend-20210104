<?php
include("RtcTokenBuilder.php");

$appID = "b39ff59abf8e48728d42ac518e72c844";
$appCertificate = "9202fde4505b47ddbba3a89d9316fb8a";
$channelName = "randomchannel3";
$uid = 2882341273;
$uidStr = "2882341273";
$role = RtcTokenBuilder::RoleAttendee;
$expireTimeInSeconds = 3600;
$currentTimestamp = (new DateTime("now", new DateTimeZone('UTC')))->getTimestamp();
$privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

$token = RtcTokenBuilder::buildTokenWithUid($appID, $appCertificate, $channelName, $uid, $role, $privilegeExpiredTs);

$array=[
    'token'=>$token,
    'channel_name'=>$channelName
];

$response=[
    'staus'=>'success',
    'data'=>$array
];

echo json_encode($response);die;


//echo 'Token with int uid: ' . $token . PHP_EOL;

//$token = RtcTokenBuilder::buildTokenWithUserAccount($appID, $appCertificate, $channelName, $uidStr, $role, $privilegeExpiredTs);
//echo 'Token with user account: ' . $token . PHP_EOL;
?>
