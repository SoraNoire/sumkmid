<?php

Route::group(['middleware' => 'web', 'namespace' => 'Modules\Auth\Http\Controllers'], function()
{
	Route::get('/activation/user/{token}/{email}', 'AuthController@activation');
    Route::get('/reset_password/{token}/{email}', 'AuthController@resetPassword');

});
Route::group(['as'=> 'auth', 'middleware' => 'web', 'prefix' => 'auth', 'namespace' => 'Modules\Auth\Http\Controllers'], function()
{

    Route::post('/logout', ['as'=>'.logout','uses'=>'AuthController@logout']);
    Route::post('/login', 'AuthController@postLogin');
    Route::post('/password/lost', 'AuthController@postLostPassword');
    Route::post('/password/reset', 'AuthController@postResetPassword');
    Route::post('/register', 'AuthController@postRegister');

    Route::get('/', ['as'=>'.index', 'uses'=>'AuthController@index']);
    Route::get('/login', ['as'=>'.login','uses'=>'AuthController@login']);
    Route::get('/register', ['as'=>'.register', 'uses'=>'AuthController@register']);
    Route::get('/password/lost', ['as'=>'.password.request','uses'=> 'AuthController@lostPassword']);
    Route::get('/password/reset', ['as'=>'.reset', 'uses'=>'AuthController@resetPassword']);
    Route::get('/logout', function(){ return Redirect( route('auth.index') );});
    
    
});

Route::group(['as'=> 'auth.users', 'middleware' => 'web', 'prefix' => 'auth/users', 'namespace' => 'Modules\Auth\Http\Controllers'], function()
{

    Route::get('/add', ['as'=>'.add', 'uses'=>'AuthController@userAdd']);
    
    
});
