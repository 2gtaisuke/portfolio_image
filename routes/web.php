<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function(){
    return view('foo');
});

Route::get('/user/{user}', 'UserController@show')
    ->name('user.show');

// login
Route::get('login/{provider}', 'SocialAccountController@redirectToProvider')
    ->name('social.login');
Route::get('login/{provider}/callback', 'SocialAccountController@handleProviderCallback')
    ->name('social.callback');

Auth::routes(['verify' => true]);