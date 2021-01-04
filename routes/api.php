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


$api->get('configurations', 'MobileApps\ConfigurationController@getFilters');

$api->group(['middleware' => ['customer-api-auth']], function ($api) {

    $api->get('get-options', 'MobileApps\ProfileController@getOptions');
    $api->post('basic-info', 'MobileApps\ProfileController@updateBasicInfo');
    $api->post('work-education', 'MobileApps\ProfileController@updateWorkInfo');
    $api->post('personal-details', 'MobileApps\ProfileController@updatePersonalInfo');
    $api->post('about', 'MobileApps\ProfileController@updateAboutMe');

    $api->get('home', 'MobileApps\HomeController@home');
    $api->get('profile-details/{id}', 'MobileApps\ProfileController@details');
    $api->get('my-matches', 'MobileApps\ProfileController@findMatches');

    $api->get('chats', 'MobileApps\ChatCotroller@chatlist');
    $api->get('chats/{id}', 'MobileApps\ChatCotroller@chatDetails');
    $api->post('send/{id}', 'MobileApps\ChatCotroller@send');


    $api->group(['prefix' => 'requests'], function ($api) {
        $api->get('', 'MobileApps\MessageCotroller@index');
        $api->get('interest/{receiver_id}', 'MobileApps\MessageCotroller@sendInterest');
        $api->get('request-photo/{receiver_id}', 'MobileApps\MessageCotroller@sendPhotoRequest');
        $api->get('accept/{message_id}', 'MobileApps\MessageCotroller@accept');
        $api->get('decline/{message_id}', 'MobileApps\MessageCotroller@decline');
    });

});
