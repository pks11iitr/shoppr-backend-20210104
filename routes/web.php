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




    //endshoppradmin

    Route::group(['prefix' => 'banners'], function () {
        Route::get('/', 'SuperAdmin\BannerController@index')->name('banners.list');
        Route::get('create', 'SuperAdmin\BannerController@create')->name('banners.create');
        Route::post('store', 'SuperAdmin\BannerController@store')->name('banners.store');
        Route::get('edit/{id}', 'SuperAdmin\BannerController@edit')->name('banners.edit');
        Route::post('update/{id}', 'SuperAdmin\BannerController@update')->name('banners.update');
        Route::get('delete/{id}', 'SuperAdmin\BannerController@delete')->name('banners.delete');
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

});

require __DIR__.'/auth.php';
