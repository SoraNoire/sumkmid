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
// inject setting
Config::set('admin.domain',env('ADMIN_DOMAIN','manage.sahabatumkm.id'));
Config::set('frontend.domain',env('FRONTEND_DOMAIN','sahabatumkm.id'));

// break backend activity that should on frontend
function THIS_IS_FRONTEND()
{
	if ( config('admin.domain') === Request::server("HTTP_HOST") )
	{
		// frontend, redirect.
		if ( 'admin' !== substr( Request::path(),0,5 ) ){
			if ( 'oauthpanel' !== substr( Request::path(),0,10 ) ){
				return redirect ( URL::to("http://".config('frontend.domain')) )->send();
			}
		}
	}	
}

THIS_IS_FRONTEND();
// Route::group(['middleware' => ['web', 'backend']], function () {
	// Route::get('/test', ['as'=>'home', 'uses'=>function () {
	    // return json_encode(app()->OAuthx->Auth());
	// }]);
    Route::get('/admin', ['as'=>'admin_page', 'uses'=>function () {
    	if (app()->OAuth->Auth()) {
			return redirect ( URL::to("http://".config('admin.domain')).'/admin/blog' )->send();
    	} else {
			return view('errors.404');
    	}
	}]);
// });

// Route::get('login','PublicController@login')->name('login');
// Route::get('ssologin',['as'=>'ssologin','uses'=>'PublicController@ssoLogin']);


Route::get('/', 'PublicController@home')->name('public_home');
// Route::get('/tentang', 'PublicController@tentang')->name('public_tentang');
// Route::get('/mentor', 'PublicController@mentor')->name('public_mentor');
Route::get('/mentor/{mentorId}', 'PublicController@mentorSingle')->name('public_mentor_single');
// Route::get('/kontak', 'PublicController@kontak')->name('public_kontak');
// Route::get('/event', 'PublicController@event')->name('public_event');
// Route::get('/galeri', 'PublicController@gallery')->name('public_gallery');
Route::get('/newsletter', 'PublicController@newsletter')->name('public_newsletter');
Route::get('/save-newsletter', 'PublicController@save_newsletter')->name('save_newsletter');
Route::get('/read/{category}/{slug}', 'PublicController@read_post')->name('read_post');

Route::get('/user-setting', 'memberController@userSetting')->name('user_setting');
Route::post('/user-setting/save','memberController@saveUserSetting')->name('user_setting_save');
Route::post('/user-setting/UpdateProfilePict','memberController@updateProfilePict')->name('user_update_profile_pict');
Route::get('user-setting/detail','memberController@detailUserEdit')->name('user_setting.detail');
Route::post('user-setting/detail/save','memberController@goSaveMetaUser')->name('user-setting.detail.go-update');

Route::post('/send-email', 'PublicController@messages_store_act')->name('sendemailcontact');

// Route::get('/mentor/page/{page}', 'PublicController@mentor_archive');
// Route::get('/event/page/{page}', 'PublicController@event_archive');

// Route::get('/gallery/page/{page}', 'PublicController@gallery_archive');
Route::get('/search-galeri/', 'PublicController@searchGallery')->name('search_gallery');
Route::get('/galeri/{slug}', 'PublicController@singleGallery')->name('single_gallery');
Route::get('/gallery-category/{slug}', 'PublicController@galleryCatArchive')->name('gallery_cat_archive');
Route::get('/gallery-tag/{slug}', 'PublicController@galleryTagArchive')->name('gallery_tag_archive');

Route::get('gallery-sitemap.xml', 'PublicController@gallery_sitemap')->name('gallery_sitemap');
Route::get('event-sitemap.xml', 'PublicController@event_sitemap')->name('event_sitemap');
Route::get('sitemap.xml', 'PublicController@index_sitemap')->name('index_sitemap');
Route::get('/delete',function(){
	App\Helpers\PublicHelper::clear_all();
	return Redirect::to('/');
});

// Auth::routes();

Route::get('sample','SampleController@sample');
// Route::get('loginadmin','SampleController@admin');
Route::get('am','SampleController@addmentor');

Route::get('appreg','SampleController@appReg');
Route::get('appupd','SampleController@appUpd');
Route::get('/{slug}', 'PublicController@single_page')->name('public_single_page');

Route::get('event/{slug}','PublicController@eventSingle')->name('public_single_event');
