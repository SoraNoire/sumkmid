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


Route::get('/cs', ['uses'=>function () {
	    // Cookie::queue("encu", "asujsanjsjbsaasu", 10080);
	}]);
Route::get('/cr', ['uses'=>function () {
	    Cookie::queue("encu", "", -10080);
	}]);

Route::get('/cg', ['uses'=>function () {
		dd(\App\Helpers\SSOHelper::$Auth);
	    dd(Cookie::get());
	}]);

Route::get('login','PublicController@login')->name('login');
Route::get('ssologin',['as'=>'ssologin','uses'=>'PublicController@ssoLogin']);


Route::get('/', 'PublicController@home')->name('public_home');
Route::get('/mentor', 'PublicController@mentor')->name('public_mentor');
Route::get('/kontak', 'PublicController@kontak')->name('public_kontak');
Route::get('/event', 'PublicController@event')->name('public_event');
Route::get('/video', 'PublicController@video')->name('public_video');
Route::get('/user-setting', 'PublicController@userSetting')->name('user_setting');
Route::get('/video/search', 'PublicController@searchVideo')->name('search_video');
Route::get('/video/{slug}', 'PublicController@singleVideo')->name('single_video');
Route::post('/send-email', 'PublicController@messages_store_act')->name('sendemailcontact');

Route::get('/mentor/page/{page}', 'PublicController@mentor_archive');
Route::get('/event/page/{page}', 'PublicController@event_archive');
Route::get('/video/page/{page}', 'PublicController@video_archive');


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

Route::get('ssotestusers',function(){
	$user = new App\Helpers\SSOHelper;
	$u = $user->users('2');
	return response(json_encode($u));
});

Route::get('ssotestmentors',function(){
	$user = new App\Helpers\SSOHelper;
	$u = $user->mentors();
	return response(json_encode($u));
});
