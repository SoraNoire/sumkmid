<?php

/**
 * routes
 *
 * @package RolePermissions
 * @author 
 **/


Route::namespace('\Rabbit\SahabatUser\Controllers')->group(function () {

	// Route::get('/member/filldata', 'SahabatUserController@completeData')
				// ->name('SHB.complete_data');
	Route::get('/member/filldata/step/{i}', 'SahabatUserController@completeData')
				->name('SHB.complete_data');
	Route::post('/member/filldata/save', 'SahabatUserController@completionSave')
				->name('SHB.complete_data_save');

	Route::middleware('shbbackend')->prefix('usermgmt')->group(function () {

		Route::get('/', 'SahabatUserController@users')
				->name('SHB.dashboard');

		Route::get('/users', 'SahabatUserController@users')
				->name('panel.user__index');

		Route::get('/view/{id}', 'SahabatUserController@viewUser')
				->name('panel.user__edit');

		Route::get('/view/{id}/detail', 'SahabatUserController@viewUserDetail')
				->name('panel.user__edit__detail');

		Route::post('/view/{id}', 'SahabatUserController@updateUser')
				->name('panel.user__update');

		Route::get('/delete/{id}', 'SahabatUserController@deleteUser')
				->name('panel.user__delete');

		
	});


});