<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth'])->name('dashboard');


Route::group(['middleware'=>['auth', 'acl'], 'is'=>'admin'], function() {

    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

    Route::get('/dashboard', 'SuperAdmin\DashboardController@index')->name('home');


    Route::group(['prefix'=>'shoppr'], function(){
        Route::get('/','SuperAdmin\ShopprController@index')->name('shoppr.list');
        Route::get('create','SuperAdmin\ShopprController@create')->name('shoppr.create');
        Route::post('store','SuperAdmin\ShopprController@store')->name('shoppr.store');
        Route::get('edit/{id}','SuperAdmin\ShopprController@edit')->name('shoppr.edit');
        Route::post('update/{id}','SuperAdmin\ShopprController@update')->name('shoppr.update');

        Route::post('add-money/{id}','SuperAdmin\ShopprController@addMoney')->name('shoppr.wallet.add');
        Route::get('history-list/{id}','SuperAdmin\ShopprController@transaction')->name('shoppr.tranaction.list');
        Route::get('details/{id}','SuperAdmin\ShopprController@details')->name('shoppr.details');

    });

    Route::group(['prefix'=>'store'], function(){
        Route::get('/','SuperAdmin\StoreController@index')->name('store.list');
        Route::get('create','SuperAdmin\StoreController@create')->name('store.create');
        Route::post('store','SuperAdmin\StoreController@store')->name('store.store');
        Route::get('edit/{id}','SuperAdmin\StoreController@edit')->name('store.edit');
        Route::post('update/{id}','SuperAdmin\StoreController@update')->name('store.update');
        Route::post('upload-images/{id}','SuperAdmin\StoreController@images')->name('store.images.uploads');
        Route::get('image-delete/{id}','SuperAdmin\StoreController@deleteimage')->name('store.image.delete');

    });

    Route::group(['prefix' => 'setting'], function () {

        Route::get('/','SuperAdmin\SettingController@index')->name('setting.list');
        Route::get('edit/{id}','SuperAdmin\SettingController@edit')->name('setting.edit');
        Route::post('update/{id}', 'SuperAdmin\SettingController@update')->name('setting.update');
    });

    Route::group(['prefix'=>'commission'], function() {
        Route::get('/', 'SuperAdmin\CommissionController@index')->name('commission.list');
    });
    //endshoppradmin

    Route::group(['prefix' => 'banners'], function () {
        Route::get('/', 'SuperAdmin\BannerController@index')->name('banners.list');
        Route::get('create', 'SuperAdmin\BannerController@create')->name('banners.create');
        Route::post('store', 'SuperAdmin\BannerController@store')->name('banners.store');
        Route::get('edit/{id}', 'SuperAdmin\BannerController@edit')->name('banners.edit');
        Route::post('update/{id}', 'SuperAdmin\BannerController@update')->name('banners.update');
        Route::get('delete/{id}', 'SuperAdmin\BannerController@delete')->name('banners.delete');
    });

    Route::group(['prefix' => 'customer'], function (){
        Route::get('/','SuperAdmin\CustomerController@index')->name('customer.list');
        Route::get('edit/{id}','SuperAdmin\CustomerController@edit')->name('customer.edit');
        Route::post('update/{id}','SuperAdmin\CustomerController@update')->name('customer.update');
    });

    Route::group(['prefix'=>'news'], function(){
        Route::get('/','SuperAdmin\NewsUpdateController@index')->name('news.list');
        Route::get('create','SuperAdmin\NewsUpdateController@create')->name('news.create');
        Route::post('store','SuperAdmin\NewsUpdateController@store')->name('news.store');
        Route::get('edit/{id}','SuperAdmin\NewsUpdateController@edit')->name('news.edit');
        Route::post('update/{id}','SuperAdmin\NewsUpdateController@update')->name('news.update');

    });

    Route::group(['prefix'=>'story'], function(){
        Route::get('/','SuperAdmin\StoryController@index')->name('story.list');
        Route::get('create','SuperAdmin\StoryController@create')->name('story.create');
        Route::post('store','SuperAdmin\StoryController@store')->name('story.store');
        Route::get('edit/{id}','SuperAdmin\StoryController@edit')->name('story.edit');
        Route::post('update/{id}','SuperAdmin\StoryController@update')->name('story.update');

    });

    Route::group(['prefix'=>'order'], function(){
        Route::get('/','SuperAdmin\OrderController@index')->name('order.list');
        Route::get('details/{id}','SuperAdmin\OrderController@details')->name('order.details');
        Route::post('changeRider/{id}','SuperAdmin\OrderController@changeRider')->name('rider.change');
        Route::get('change-payment-status/{id}','SuperAdmin\OrderController@changePaymentStatus')->name('payment.status.change');
        Route::get('change-status/{id}','SuperAdmin\OrderController@changeStatus')->name('order.status.change');

    });

    Route::group(['prefix'=>'checkin'], function(){
        Route::get('/','SuperAdmin\CheckinController@index')->name('checkin.list');

    });

    Route::group(['prefix'=>'category'], function(){
        Route::get('/','SuperAdmin\CategoryController@index')->name('category.list');
        Route::get('create','SuperAdmin\CategoryController@create')->name('category.create');
        Route::post('store','SuperAdmin\CategoryController@store')->name('category.store');
        Route::get('edit/{id}','SuperAdmin\CategoryController@edit')->name('category.edit');
        Route::post('update/{id}','SuperAdmin\CategoryController@update')->name('category.update');

    });

});

require __DIR__.'/auth.php';
