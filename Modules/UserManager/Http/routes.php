<?php

Route::group(['middleware' => 'web', 'prefix' => 'admin/usermanager', 'namespace' => 'Modules\UserManager\Http\Controllers'], function()
{
    Route::get('/', 'UserController@index');

    Route::resource('users', 'UserController');

	Route::resource('roles', 'RoleController');

	Route::resource('permissions', 'PermissionController');

	Route::get('401', [ 'as' => '401','uses' =>'UserManagerController@e401']);

});
