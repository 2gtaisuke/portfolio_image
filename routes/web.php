<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function(){
    return view('foo');
});

// user
Route::get('/user/{user}', 'UserController@show')
    ->name('user.show');

Route::middleware('can:update,user')->group(function() {
    Route::get('/user/{user}/edit', 'UserController@edit')
        ->name('user.edit');
    Route::post('/user/{user}', 'UserController@update')
        ->name('user.update');
});

Route::middleware('can:delete,user')->group(function() {
    Route::delete('/user/{user}', 'UserController@destroy')
        ->name('user.destroy');
});

// login
Route::get('login/{provider}', 'SocialAccountController@redirectToProvider')
    ->name('social.login');
Route::get('login/{provider}/callback', 'SocialAccountController@handleProviderCallback')
    ->name('social.callback');

Auth::routes(['verify' => true]);