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


$api->get('configurations', 'MobileApps\ConfigurationController@getFilters');

$api->group(['middleware' => ['customer-api-auth']], function ($api) {


//    $api->get('get-options', 'MobileApps\ProfileController@getOptions');
//    $api->post('basic-info', 'MobileApps\ProfileController@updateBasicInfo');
//    $api->post('work-education', 'MobileApps\ProfileController@updateWorkInfo');
//    $api->post('personal-details', 'MobileApps\ProfileController@updatePersonalInfo');
//    $api->post('about', 'MobileApps\ProfileController@updateAboutMe');

    $api->get('home', 'MobileApps\HomeController@home');
    $api->get('profile-details/{id}', 'MobileApps\ProfileController@details');
//    $api->get('my-matches', 'MobileApps\ProfileController@findMatches');

    $api->get('chats', 'MobileApps\Api\ChatController@chathistory');
    $api->get('start-chat', 'MobileApps\Api\ChatController@startChat');

    $api->get('chat-messages/{id}', 'MobileApps\Api\ChatMessageController@chatDetails');
    $api->post('send-message/{id}', 'MobileApps\Api\ChatMessageController@send');

    $api->get('accept/{id}', 'MobileApps\Api\ChatMessageController@acceptProduct');
    $api->get('reject/{id}', 'MobileApps\Api\ChatMessageController@rejectProduct');
    $api->get('cancel/{id}', 'MobileApps\Api\ChatMessageController@cancelProduct');
    $api->post('rate-service/{id}', 'MobileApps\Api\ChatMessageController@rateService');


    //home


    $api->group(['prefix' => 'requests'], function ($api) {
        $api->get('', 'MobileApps\MessageCotroller@index');
        $api->get('interest/{receiver_id}', 'MobileApps\MessageCotroller@sendInterest');
        $api->get('request-photo/{receiver_id}', 'MobileApps\MessageCotroller@sendPhotoRequest');
        $api->get('accept/{message_id}', 'MobileApps\MessageCotroller@accept');
        $api->get('decline/{message_id}', 'MobileApps\MessageCotroller@decline');
    });

    $api->get('cart/{chat_id}', 'MobileApps\MessageCotroller@index');
    $api->get('cart-cancel', 'MobileApps\MessageCotroller@cancelProduct');

});
$api->get('shoppr-list', 'MobileApps\Api\HomeController@index');
$api->get('stores-list', 'MobileApps\Api\StoreController@index');
$api->get('get-profile', 'MobileApps\Api\ProfileController@index');
$api->post('update-profile', 'MobileApps\Api\ProfileController@update');
$api->get('store-details/{id}', 'MobileApps\Api\StoreController@details');
$api->get('customer-balance', 'MobileApps\Api\WalletController@userbalance');
$api->get('wallet-history', 'MobileApps\Api\WalletController@index');
$api->post('recharge','MobileApps\Api\WalletController@addMoney');
$api->post('verify-recharge','MobileApps\Api\WalletController@verifyRecharge');


//shoppr APIs
$api->group(['prefix' => 'requests'], function ($api) {

    //$api->post('login', 'MobileApps\Auth\LoginController@login');
    $api->post('login-with-otp', 'MobileApps\ShopprApp\Auth\LoginController@loginWithOtp');
    $api->post('register', 'MobileApps\ShopprApp\Auth\RegisterController@register');
//$api->post('forgot', 'MobileApps\Auth\ForgotPasswordController@forgot');
    $api->post('verify-otp', 'MobileApps\ShopprApp\Auth\OtpController@verify');
    $api->post('resend-otp', 'MobileApps\ShopprApp\Auth\OtpController@resend');
//$api->post('update-password', 'MobileApps\Auth\ForgotPasswordController@updatePassword');
    $api->post('fb-login', 'MobileApps\ShopprApp\Auth\LoginController@facebookLogin');
    $api->post('gmail-login', 'MobileApps\ShopprApp\Auth\LoginController@gmailLogin');
//test comment again

});
