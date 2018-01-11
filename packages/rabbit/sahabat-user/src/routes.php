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
	Route::get('/member/filldata/step/back/{i}', 'SahabatUserController@completeData')
				->name('SHB.complete_data');
	Route::post('/member/filldata/save', 'SahabatUserController@completionSave')
				->name('SHB.complete_data_save');

	Route::middleware('shbbackend')->prefix('usermgmt')->group(function () {

		Route::get('/', 'SahabatUserController@users')
				->name('SHB.dashboard');

		Route::get('/modules', 'SahabatUserController@users')
				->name('SHB.users');

		Route::get('/view/{id}', 'SahabatUserController@viewUser')
				->name('SHB.user__edit');

		Route::get('/view/{id}/detail', 'SahabatUserController@viewUserDetail')
				->name('SHB.user__edit__detail');

		Route::post('/view/{id}', 'SahabatUserController@updateUser')
				->name('SHB.user__update');

		Route::get('/delete/{id}', 'SahabatUserController@deleteUser')
				->name('SHB.user__delete');

		Route::post('/module/add', 'SahabatUserController@moduleSave')
				->name('SHB.module.save');

		Route::get('/module/{id}/edit', 'SahabatUserController@moduleEdit')
				->name('SHB.module.edit');


		Route::post('/module/{id}/update', 'SahabatUserController@moduleUpdate')
				->name('SHB.module.update');

		Route::get('/module/{id}/remove', 'SahabatUserController@moduleDelete')
				->name('SHB.module.remove');

		Route::get('/permissions', 'SahabatUserController@permissions')
				->name('SHB.permissions');

		Route::post('/permissions', 'SahabatUserController@permissionSave')
				->name('SHB.permissions.save');
		Route::post('ajax/permissions', 'SahabatUserController@permissionSaveAjax')
				->name('SHB.permissions.save.ajax');
	});
});