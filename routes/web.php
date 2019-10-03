<?php

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

use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/', 'ClientController@index')->name('client');
Route::resource('client-widget', 'Client\WidgetController')->only([
    'index', 'store', 'show', 'destroy'
]);
Route::resource('client-bot', 'Client\BotController')->only([
    'index', 'store', 'show', 'destroy'
]);

Route::post('client-bot/block/{id}', 'Client\BotController@storeBlock');
Route::post('client-bot/typing/{botId}', 'Client\BotController@storeTyping');
Route::delete('client-bot/block/{botId}/{id}', 'Client\BotController@destroyBlock');
Route::get('bot-users/{id}', 'Client\BotController@getUsers');

Route::post('client-bot/connection/{id}', 'Client\BotController@storeConnection');

Route::resource('client-chat', 'Client\ChatController')->only([
    'index', 'store', 'show', 'destroy'
]);
Route::get('client-chat/get/{token}', 'Client\ChatController@showByToken');

Route::get('image-upload', 'ImageUploadController@upload')->name('image.upload');
Route::post('image-upload', 'ImageUploadController@uploadPost')->name('image.upload.post');

Route::get('video-upload', 'VideoUploadController@upload')->name('video.upload');
Route::post('video-upload', 'VideoUploadController@uploadPost')->name('video.upload.post');


Route::post('update-avatar', 'UserController@updateAvatar')->name('user.update.avatar');
Route::post('create-user', 'UserController@createUser')->name('user.create');
Route::post('update-user', 'UserController@updateUser')->name('user.update');
Route::post('list-manager', 'UserController@listUsers')->name('user.list');

Route::post('list-manager', 'Client\ChatController@addUser')->name('chat.user.add');
Route::get('list-manager/{chatId}/{id}', 'Client\ChatController@deleteUser')->name('chat.user.delete');

Route::post('archives', 'Client\ArchiveController@store')->name('archives.store');
