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
	Route::get('/test', ['as'=>'home', 'uses'=>function () {
	    return json_encode(app()->OAuthx->Auth());
	}]);
    Route::get('/admin', ['as'=>'admin_page', 'uses'=>function () {
	    return redirect('/admin/blog');
	}]);
});

// Route::get('login','PublicController@login')->name('login');
// Route::get('ssologin',['as'=>'ssologin','uses'=>'PublicController@ssoLogin']);


Route::get('/', 'PublicController@home')->name('public_home');
Route::get('/tentang', 'PublicController@tentang')->name('public_tentang');
Route::get('/mentor', 'PublicController@mentor')->name('public_mentor');
Route::get('/mentor/{mentorId}', 'PublicController@mentorSingle')->name('public_mentor_single');
Route::get('/kontak', 'PublicController@kontak')->name('public_kontak');
Route::get('/event', 'PublicController@event')->name('public_event');
Route::get('/galeri', 'PublicController@galeri')->name('public_galeri');
Route::get('/newsletter', 'PublicController@newsletter')->name('public_newsletter');
Route::get('/save-newsletter', 'PublicController@save_newsletter')->name('save_newsletter');

Route::get('/user-setting', 'memberController@userSetting')->name('user_setting');
Route::post('/user-setting/save','memberController@saveUserSetting')->name('user_setting_save');
Route::post('/user-setting/UpdateProfilePict','memberController@updateProfilePict')->name('user_update_profile_pict');

Route::post('/send-email', 'PublicController@messages_store_act')->name('sendemailcontact');

Route::get('/mentor/page/{page}', 'PublicController@mentor_archive');
Route::get('/event/page/{page}', 'PublicController@event_archive');

Route::get('/video/page/{page}', 'PublicController@video_archive');
Route::get('/search-galeri/', 'PublicController@searchGaleri')->name('search_galeri');
Route::get('/galeri/{slug}', 'PublicController@singleGaleri')->name('single_galeri');
Route::get('/video-category/{slug}', 'PublicController@videoCatArchive')->name('video_cat_archive');
Route::get('/video-tag/{slug}', 'PublicController@videoTagArchive')->name('video_tag_archive');


// Auth::routes();

Route::get('sample','SampleController@sample');
// Route::get('loginadmin','SampleController@admin');
Route::get('am','SampleController@addmentor');

Route::get('appreg','SampleController@appReg');
Route::get('appupd','SampleController@appUpd');
