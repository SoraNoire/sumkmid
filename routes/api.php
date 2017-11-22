<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('v1')->group(function () {

	Route::get('user', function () {
    	// Matches The "/admin/users" URL

	});

	Route::get('event', 'APIController@events');
	Route::get('event/{id}', 'APIController@event');

	Route::get('page', 'APIController@pages');
	Route::get('page/{id}', 'APIController@page');

	Route::get('gallery', 'APIController@galleries');
	Route::get('gallery/{id}', 'APIController@gallery');

	Route::get('video', 'APIController@videos');
	Route::get('video/{id}', 'APIController@video');

});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();


});
