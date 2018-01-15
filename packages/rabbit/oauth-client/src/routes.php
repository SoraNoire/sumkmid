<?php

/**
 * routes
 *
 * @package RolePermissions
 * @author 
 **/

Config::set('admin.domain',env('ADMIN_DOMAIN','manage.sahabatumkm.id'));
Route::middleware('OAuthMiddleware')->namespace('\Rabbit\OAuthClient\Controllers')->group(function () {

	Route::get('/login_oauth', 'PController@OAuthLogin')
			->name('OA.login');
	Route::get('/login', 'PController@OAuthCallback')
			->name('OA.login.callback');

	Route::get('/register_oauth', 'PController@OAuthRegister')
			->name('OA.register');
	Route::get('/logout', 'PController@OAuthLogout')
			->name('OA.logout');
	
	Route::post('/challenge', 'PController@emailChallenge')
			->name('OA.admin.emailChallenge');

	Route::post('/resend_code', 'PController@resendEmailChallenge')
			->name('OA.admin.resendEmailChallenge');


	Route::middleware('backend')->prefix('oauthpanel')->domain(config('admin.domain'))->group(function () {

		Route::get('/', 'OAController@permissions')
				->name('OA.dashboard');

		Route::get('/modules', 'OAController@modules')
				->name('OA.modules');

		Route::get('/module/add', 'OAController@moduleAdd')
				->name('OA.module.add');

		Route::post('/module/add', 'OAController@moduleSave')
				->name('OA.module.save');

		Route::get('/module/{id}/edit', 'OAController@moduleEdit')
				->name('OA.module.edit');


		Route::post('/module/{id}/update', 'OAController@moduleUpdate')
				->name('OA.module.update');

		Route::get('/module/{id}/remove', 'OAController@moduleDelete')
				->name('OA.module.remove');

		Route::get('/permissions', 'OAController@permissions')
				->name('OA.permissions');

		Route::post('/permissions', 'OAController@permissionSave')
				->name('OA.permissions.save');
		Route::post('ajax/permissions', 'OAController@permissionSaveAjax')
				->name('OA.permissions.save.ajax');

	});


});