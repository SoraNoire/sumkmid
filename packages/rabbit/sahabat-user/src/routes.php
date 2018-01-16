<?php

/**
 * routes
 *
 * @package RolePermissions
 * @author 
 **/

Config::set('admin.domain',env('ADMIN_DOMAIN','manage.sahabatumkm.id'));
Route::namespace('\Rabbit\SahabatUser\Controllers')->group(function () {

	// Route::get('/member/filldata', 'SahabatUserController@completeData')
				// ->name('SHB.complete_data');
	Route::get('/member/filldata/step/{i}', 'SahabatUserController@completeData')
				->name('SHB.complete_data');
	Route::post('/member/filldata/save', 'SahabatUserController@completionSave')
				->name('SHB.complete_data_save');

	Route::middleware('shbbackend')->prefix('admin/usermgmt')->domain(config('admin.domain'))->group(function () {

		Route::get('/', 'SahabatUserController@users')
				->name('SHB.dashboard');

		Route::get('/user', 'SahabatUserController@users')
				->name('panel.user__index');

		Route::get('/user/view/{id}', 'SahabatUserController@viewUser')
				->name('panel.user__edit');

		Route::get('/user/view/{id}/detail', 'SahabatUserController@viewUserDetail')
				->name('panel.user__edit__detail');

		Route::post('/user/view/{id}', 'SahabatUserController@updateUser')
				->name('panel.user__update');


		Route::get('/delete/{id}', 'SahabatUserController@deleteUser')
				->name('SHB.user__delete');


		Route::get('/user/ktp', 'SahabatUserController@ktp')
				->name('panel.user__view__cek__ktp');

		// Route::post('/module/add', 'SahabatUserController@moduleSave')
		// 		->name('SHB.module.save');

		// Route::get('/module/{id}/edit', 'SahabatUserController@moduleEdit')
		// 		->name('SHB.module.edit');


		// Route::post('/module/{id}/update', 'SahabatUserController@moduleUpdate')
		// 		->name('SHB.module.update');

		// Route::get('/module/{id}/remove', 'SahabatUserController@moduleDelete')
		// 		->name('SHB.module.remove');

		// Route::get('/permissions', 'SahabatUserController@permissions')
		// 		->name('SHB.permissions');

		// Route::post('/permissions', 'SahabatUserController@permissionSave')
		// 		->name('SHB.permissions.save');
		// Route::post('ajax/permissions', 'SahabatUserController@permissionSaveAjax')
		// 		->name('SHB.permissions.save.ajax');

		Route::get('/user/delete/{id}', 'SahabatUserController@deleteUser')
				->name('panel.user__delete');

		Route::get('/export/user/xls', 'SahabatUserController@exportUsers')
				->name('panel.user__view__export');
		
	});
});