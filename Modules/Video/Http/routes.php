<?php

Config::set('admin.domain',env('ADMIN_DOMAIN','manage.sahabatumkm.id'));
Route::group(['domain'=>config('admin.domain'),'middleware' => ['web','backend'], 'prefix' => 'admin/blog/video', 'namespace' => 'Modules\Video\Http\Controllers'], function()
{
    // Route::get('/', ['as'=>'video', 'uses'=> 'VideoController@index']);
    // Route::get('/index', ['as'=>'video', 'uses'=> 'VideoController@index']);

    // Route::get('/show/{slug}', ['as'=>'video', 'uses'=> 'VideoController@show_video']);
    // Route::get('/get-videos', ['as'=>'video', 'uses'=> 'VideoController@get_videos']);
    // Route::get('/create-video', ['as'=>'video', 'uses'=> 'VideoController@create_video']);
    // Route::get('/edit-video/{id}', ['as'=>'video', 'uses'=> 'VideoController@edit_video']);
    // Route::get('/delete-video/{id}', ['as'=>'video', 'uses'=> 'VideoController@destroy_video']);
    // Route::post('/store-video', ['as'=>'video', 'uses'=> 'VideoController@store_video']);
    // Route::post('/update-video/{id}', ['as'=>'video', 'uses'=> 'VideoController@update_video']);
    // Route::post('/bulk-delete-video/', ['as'=>'video', 'uses'=> 'VideoController@bulk_delete_video']);

    // Route::get('/add-category-video/{name}/{parent}', ['as'=>'video', 'uses'=> 'VideoController@store_category_ajax']);
    // Route::get('/get-category-video/{video_id}', ['as'=>'video', 'uses'=> 'VideoController@get_all_category']);
    // Route::get('/get-category-parent/{category_id}', ['as'=>'video', 'uses'=> 'VideoController@get_category_parent']);

    Route::get('/', 'VideoController@index')
            ->name('panel.video__index');
    // Route::get('/show/{slug}', 'VideoController@showVideo')
            // ->name('panel.video__view__single');
    Route::get('/ajaxvideos', 'VideoController@ajaxVideos')
            ->name('panel.video__index__ajax');
    Route::get('/add', 'VideoController@addVideo')
            ->name('panel.video__add');
    Route::post('/add', 'VideoController@addVideoPost')
            ->name('panel.video__save');
    Route::get('/{id}/edit', 'VideoController@viewVideo')
            ->name('panel.video__view');
    Route::post('/{id}/update', 'VideoController@updateVideo')
            ->name('panel.video__update');
    Route::get('/{id}/remove', 'VideoController@removeVideo')
            ->name('panel.video__delete');
    Route::post('/massdelete', 'VideoController@massDeleteVideo')
            ->name('panel.video__delete__mass');
    
    

    // Route::get('/category', ['as'=>'video', 'uses'=> 'VideoController@category');
    // Route::get('/get-category', ['as'=>'video', 'uses'=> 'VideoController@get_category');
    // Route::get('/create-category', ['as'=>'video', 'uses'=> 'VideoController@create_category');
    // Route::get('/edit-category/{id}', ['as'=>'video', 'uses'=> 'VideoController@edit_category');
    // Route::get('/delete-category/{id}', ['as'=>'video', 'uses'=> 'VideoController@destroy_category');
    // Route::post('/store-category', ['as'=>'video', 'uses'=> 'VideoController@store_category');
    // Route::post('/update-category/{id}', ['as'=>'video', 'uses'=> 'VideoController@update_category');
    // Route::post('/bulk-delete-category/', ['as'=>'video', 'uses'=> 'VideoController@bulk_delete_category');

    // Route::get('/tag', ['as'=>'video', 'uses'=> 'VideoController@tag');
    // Route::get('/get-tag', ['as'=>'video', 'uses'=> 'VideoController@get_tag');
    // Route::get('/create-tag', ['as'=>'video', 'uses'=> 'VideoController@create_tag');
    // Route::get('/edit-tag/{id}', ['as'=>'video', 'uses'=> 'VideoController@edit_tag');
    // Route::get('/delete-tag/{id}', ['as'=>'video', 'uses'=> 'VideoController@destroy_tag');
    // Route::post('/store-tag', ['as'=>'video', 'uses'=> 'VideoController@store_tag');
    // Route::post('/update-tag/{id}', ['as'=>'video', 'uses'=> 'VideoController@update_tag');
    // Route::post('/bulk-delete-tag/', ['as'=>'video', 'uses'=> 'VideoController@bulk_delete_tag');
});
