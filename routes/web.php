<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function(){
    return view('foo');
});

Route::get('/user/{user}', 'UserController@show')
    ->middleware('can:view,user')->name('user.show');

Auth::routes();
