<?php

use Illuminate\Http\Request;
$api = app('Dingo\Api\Routing\Router');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api->get('chat', function(){
    return view('chat');
});

//$api->post('login', 'MobileApps\Auth\LoginController@login');
$api->post('login-with-otp', 'MobileApps\Auth\LoginController@loginWithOtp');
$api->post('register', 'MobileApps\Auth\RegisterController@register');
//$api->post('forgot', 'MobileApps\Auth\ForgotPasswordController@forgot');
$api->post('verify-otp', 'MobileApps\Auth\OtpController@verify');
$api->post('resend-otp', 'MobileApps\Auth\OtpController@resend');
//$api->post('update-password', 'MobileApps\Auth\ForgotPasswordController@updatePassword');
$api->post('fb-login', 'MobileApps\Auth\LoginController@facebookLogin');
$api->post('gmail-login', 'MobileApps\Auth\LoginController@gmailLogin');
//test comment again

$api->group(['middleware' => ['customer-api-auth']], function ($api) {

      $api->get('logout', 'MobileApps\Auth\LoginController@logout');

//    $api->get('get-options', 'MobileApps\ProfileController@getOptions');
//    $api->post('basic-info', 'MobileApps\ProfileController@updateBasicInfo');
//    $api->post('work-education', 'MobileApps\ProfileController@updateWorkInfo');
//    $api->post('personal-details', 'MobileApps\ProfileController@updatePersonalInfo');
//    $api->post('about', 'MobileApps\ProfileController@updateAboutMe');

    //$api->get('home', 'MobileApps\HomeController@home');
    //$api->get('profile-details/{id}', 'MobileApps\ProfileController@details');
//    $api->get('my-matches', 'MobileApps\ProfileController@findMatches');

    $api->get('chats', 'MobileApps\Api\ChatController@chathistory');
    $api->get('start-chat/{store_id?}', 'MobileApps\Api\ChatController@startChat');

    $api->get('chat-messages/{id}', 'MobileApps\Api\ChatMessageController@chatDetails');
    $api->post('send-message/{id}', 'MobileApps\Api\ChatMessageController@send');

    $api->get('accept/{id}', 'MobileApps\Api\ChatMessageController@acceptProduct');
    $api->get('reject/{id}', 'MobileApps\Api\ChatMessageController@rejectProduct');
    $api->get('cancel/{id}', 'MobileApps\Api\ChatMessageController@cancelProduct');
    $api->post('rate-service/{id}', 'MobileApps\Api\ChatMessageController@rateService');

    $api->get('cart/{chat_id}', 'MobileApps\Api\CartController@index');
    $api->get('cart-cancel/{message_id}', 'MobileApps\Api\CartController@cancelProduct');

    $api->get('initiate-order/{chat_id}', 'MobileApps\Api\OrderController@initiateOrder');
    $api->get('order-details/{order_id}', 'MobileApps\Api\OrderController@details');
    $api->get('orders', 'MobileApps\Api\OrderController@index');

    $api->post('initiate-payment/{order_id}', 'MobileApps\Api\PaymentController@initiatePayment');

    $api->get('track-location/{chat_id}', 'MobileApps\Api\ShopperTrackController@track');

    $api->get('initiate-video-call/{chat_id}', 'MobileApps\Api\CallController@initiateVideocall');

    $api->post('register-as-merchant','MobileApps\Api\PartnerController@register');
    $api->get('view-application','MobileApps\Api\PartnerController@view');

    $api->get('auto-assign/{chat_id}', 'MobileApps\Api\ChatController@autoassign');

});

$api->get('download-invoice/{order_refid}', ['as'=>'download.invoice', 'uses'=>'MobileApps\Api\OrderController@downloadInvoice']);


$api->post('verify-payment', 'MobileApps\Api\PaymentController@verifyPayment');

$api->get('shoppr-list', 'MobileApps\Api\HomeController@index');
$api->get('stores-list', 'MobileApps\Api\StoreController@index');
$api->get('get-profile', 'MobileApps\Api\ProfileController@index');
$api->post('update-profile', 'MobileApps\Api\ProfileController@update');
$api->get('store-details/{id}', 'MobileApps\Api\StoreController@details');
$api->get('customer-balance', 'MobileApps\Api\WalletController@userbalance');
$api->get('wallet-history', 'MobileApps\Api\WalletController@index');
$api->post('recharge','MobileApps\Api\WalletController@addMoney');
$api->post('verify-recharge','MobileApps\Api\WalletController@verifyRecharge');
$api->get('notifications', 'MobileApps\Api\NotificationController@index');
$api->post('check-availability', 'MobileApps\Api\AvailableLocationController@checkServiceAvailability');

//shoppr APIs
$api->group(['prefix' => 'shoppr'], function ($api) {

    //$api->post('login', 'MobileApps\Auth\LoginController@login');

    $api->post('login-with-otp', 'MobileApps\ShopprApp\Auth\LoginController@loginWithOtp');
    $api->post('register', 'MobileApps\ShopprApp\Auth\RegisterController@register');
//$api->post('forgot', 'MobileApps\Auth\ForgotPasswordController@forgot');
    $api->post('verify-otp', 'MobileApps\ShopprApp\Auth\OtpController@verify');
    $api->post('resend-otp', 'MobileApps\ShopprApp\Auth\OtpController@resend');
//$api->post('update-password', 'MobileApps\Auth\ForgotPasswordController@updatePassword');
    $api->post('fb-login', 'MobileApps\ShopprApp\Auth\LoginController@facebookLogin');
    $api->post('gmail-login', 'MobileApps\ShopprApp\Auth\LoginController@gmailLogin');
    $api->get('state-list', 'MobileApps\ShopprApp\StateController@state');
    $api->get('work-locations', 'MobileApps\ShopprApp\StateController@worklocations');
//test comment again

    $api->group(['middleware' => ['shoppr-api-auth']], function ($api) {

        $api->get('logout', 'MobileApps\ShopprApp\Auth\LoginController@logout');
        $api->get('profile', 'MobileApps\ShopprApp\ProfileController@getProfile');


        $api->get('chats', 'MobileApps\ShopprApp\ChatController@chathistory');
        //$api->get('start-chat', 'MobileApps\ShopprApp\ChatController@startChat');

        $api->get('chat-messages/{id}', 'MobileApps\ShopprApp\ChatMessageController@chatDetails');
        $api->post('send-message/{id}', 'MobileApps\ShopprApp\ChatMessageController@send');

        $api->post('update-location', 'MobileApps\ShopprApp\ShopperLocationController@update');

        $api->get('initiate-video-call/{chat_id}', 'MobileApps\ShopprApp\CallController@initiateVideocall');

        $api->get('order-details/{order_id}', 'MobileApps\ShopprApp\OrderController@details');
        $api->get('orders', 'MobileApps\ShopprApp\OrderController@index');
        $api->post('upload-document', 'MobileApps\ShopprApp\ProfileController@uploaddocument');
        $api->post('update-details', 'MobileApps\ShopprApp\ProfileController@bankdetails');
        $api->post('update-work-details', 'MobileApps\ShopprApp\ProfileController@updateworklocation');
        $api->post('update-personal-details', 'MobileApps\ShopprApp\ProfileController@updatePersonalDetails');
        $api->get('get-documents', 'MobileApps\ShopprApp\ProfileController@getDocuments');
        $api->get('get-work-info', 'MobileApps\ShopprApp\ProfileController@getWorkInfo');
        $api->get('get-personal-info', 'MobileApps\ShopprApp\ProfileController@getPersonalInfo');
        $api->get('get-bank-info', 'MobileApps\ShopprApp\ProfileController@getBankInfo');
        $api->post('update-documents', 'MobileApps\ShopprApp\ProfileController@updateDocument');

        $api->get('wallet-history', 'MobileApps\ShopprApp\WalletController@index');
        $api->get('wallet-balance', 'MobileApps\ShopprApp\WalletController@getWalletBalance');

        $api->get('deliver-order/{order_id}', 'MobileApps\ShopprApp\OrderController@deliverOrder');

        $api->post('commission-history', 'MobileApps\ShopprApp\WalletController@commissions');
        $api->post('time-history', 'MobileApps\ShopprApp\WalletController@kmCommissions');

        $api->get('profile-status', 'MobileApps\ShopprApp\ProfileController@getProfileCompletionStatus');

        $api->get('available-chats', 'MobileApps\ShopprApp\ChatController@availableChats');
        $api->get('accept-chat/{chat_id}', 'MobileApps\ShopprApp\ChatController@acceptChat');
        $api->get('reject-chat/{chat_id}', 'MobileApps\ShopprApp\ChatController@rejectChat');


        $api->get('checkin-status', 'MobileApps\ShopprApp\ProfileController@checkinstatus');
        $api->post('check-in', 'MobileApps\ShopprApp\ProfileController@checkin');
        $api->post('check-out', 'MobileApps\ShopprApp\ProfileController@checkout');
        $api->get('attendences', 'MobileApps\ShopprApp\ProfileController@attendencelist');

        $api->get('reviews', 'MobileApps\ShopprApp\ReviewController@index');




    });

    $api->get('notifications', 'MobileApps\ShopprApp\NotificationController@index');
    $api->get('available-locations', 'MobileApps\Api\AvailableLocationController@locations');

});

$api->get('available-locations', 'MobileApps\Api\AvailableLocationController@locations');
