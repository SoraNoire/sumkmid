<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['admin']], function () {

	Route::get('/', ['as'=>'home', 'uses'=>function () {
	    return json_encode(app()->SSO->Auth());
	}]);
    
});


Route::get('/cs', ['as'=>'home', 'uses'=>function () {
	    // Cookie::queue("encu", "asujsanjsjbsaasu", 10080);
	}]);
Route::get('/cr', ['as'=>'home', 'uses'=>function () {
	    Cookie::queue("encu", "", -10080);
	}]);

Route::get('/cg', ['as'=>'home', 'uses'=>function () {
		dd(\App\Helpers\SSOHelper::$Auth);
	    dd(Cookie::get());
	}]);

Route::get('login','PublicController@login');
Route::get('ssologin',['as'=>'ssologin','uses'=>'PublicController@ssoLogin']);



Route::get('/', ['as'=>'home', 'uses'=>function () {
    return view('welcome');
}]);

// Auth::routes();

Route::get('sample','SampleController@sample');
Route::get('loginadmin','SampleController@admin');
Route::get('am','SampleController@addmentor');

Route::get('appreg','SampleController@appReg');
Route::get('appupd','SampleController@appUpd');

Route::get('/logout', function(){
	\App\Helpers\SSOHelper::logout();
	return Redirect::to('/');
});
