<?php

use Illuminate\Support\Facades\Auth;
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

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/threads', 'ThreadsController@index')->name('threads');
Route::post('/threads', 'ThreadsController@store')->middleware(['verified', 'auth'])->name('add_thread');
Route::get('/threads/create', 'ThreadsController@create')->name('create_thread');
Route::get('/threads/{channel:slug}', 'ThreadsController@index'); // index threads by channel
Route::get('/threads/{channel:slug}/{thread:slug}', 'ThreadsController@show')->name('thread');
Route::put('/threads/{channel:slug}/{thread:slug}', 'ThreadsController@update')->name('thread.update');
Route::delete('/threads/{channel:slug}/{thread}', 'ThreadsController@destroy');
// Route::ressource('threads', 'ThreadsController'); // en une route on peut déclarer le CRUD et donc  éliminer les autres routes. Je ne l'utilise pas car je souhaite exploiter l'attribut name sur les différents route

Route::put('/locked-threads/{thread}', 'LockedThreadsController@store')->name('locked-threads.store')->middleware('admin');
Route::put('/unlocked-threads/{thread}', 'LockedThreadsController@destroy')->name('locked-threads.destroy')->middleware('admin');

Route::post('/replies/{reply}/best', 'BestRepliesController@store')->name('best-replies.store'); // Meilleur réponse

Route::get('/threads/{channel:slug}/{thread}/replies', 'RepliesController@index'); // Sert pour la pagination avec VUEJS
Route::post('/threads/{channel:slug}/{thread}/replies', 'RepliesController@store')->name('add_reply'); // Add Reply

Route::post('/replies/{reply}/favorites', 'FavoritesController@store')->name('fav_reply'); // Fav Reply
Route::delete('/replies/{reply}/favorites', 'FavoritesController@destroy'); // Unfav Reply

Route::delete('/replies/{reply}', 'RepliesController@destroy')->name('delete_reply'); // Delete a Reply
Route::put('/replies/{reply}', 'RepliesController@update');

Route::post('/threads/{channel:slug}/{thread}/subscriptions', 'ThreadSubscriptionsController@store')->middleware('auth');
Route::delete('/threads/{channel:slug}/{thread}/subscriptions', 'ThreadSubscriptionsController@destroy')->middleware('auth');

Route::get('/profiles/{user:name}', 'ProfilesController@show')->name('profile'); // Go to profile page

Route::get('/profiles/{user:name}/notifications', 'UserNotificationsController@index'); // Liste notifications
Route::delete('/profiles/{user:name}/notifications/{notification}', 'UserNotificationsController@destroy'); // Delete a specific notification 

// API
Route::get('api/users', 'Api\UsersController@index'); // Pour accès name user - Autocompletion
Route::post('api/users/{user}/avatar', 'Api\UserAvatarController@store')->middleware('auth')->name('avatar_path'); // Upload un avatar