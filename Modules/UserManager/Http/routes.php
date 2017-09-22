<?php

/**
 * Unauthorized route
 *
 * @return void
 * @author 
 **/
Route::group(['middleware' => 'web', 'namespace' => 'Modules\UserManager\Http\Controllers'], function()
{
	Route::get('401.shtml', [ 'as' => '401','uses' =>'UserManagerController@e401']);
});


Route::group(['middleware' => 'web', 'prefix' => 'admin/usermanager', 'namespace' => 'Modules\UserManager\Http\Controllers'], function()
{
    Route::get('/', 'UserController@index');

    Route::resource('users', 'UserController');

	Route::resource('roles', 'RoleController');

	Route::resource('permissions', 'PermissionController');

});
