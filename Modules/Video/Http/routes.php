<?php

Route::group(['middleware' => 'web', 'prefix' => 'admin/blog/video', 'namespace' => 'Modules\Video\Http\Controllers'], function()
{
    Route::get('/', 'VideoController@index');
    Route::get('/index', 'VideoController@index');

    Route::get('/show/{slug}', 'VideoController@show_video');
    Route::get('/get-videos', 'VideoController@get_videos');
    Route::get('/create-video', 'VideoController@create_video');
    Route::get('/edit-video/{id}', 'VideoController@edit_video');
    Route::get('/delete-video/{id}', 'VideoController@destroy_video');
    Route::post('/store-video', 'VideoController@store_video');
    Route::post('/update-video/{id}', 'VideoController@update_video');
    Route::post('/bulk-delete-video/', 'VideoController@bulk_delete_video');
    Route::get('/add-category-video/{name}/{parent}', 'VideoController@store_category_ajax');
    Route::get('/get-category-video/{video_id}', 'VideoController@get_all_category');
    Route::get('/get-category-parent/{category_id}', 'VideoController@get_category_parent');

    Route::get('/category', 'VideoController@category');
    Route::get('/get-category', 'VideoController@get_category');
    Route::get('/create-category', 'VideoController@create_category');
    Route::get('/edit-category/{id}', 'VideoController@edit_category');
    Route::get('/delete-category/{id}', 'VideoController@destroy_category');
    Route::post('/store-category', 'VideoController@store_category');
    Route::post('/update-category/{id}', 'VideoController@update_category');
    Route::post('/bulk-delete-category/', 'VideoController@bulk_delete_category');

    Route::get('/tag', 'VideoController@tag');
    Route::get('/get-tag', 'VideoController@get_tag');
    Route::get('/create-tag', 'VideoController@create_tag');
    Route::get('/edit-tag/{id}', 'VideoController@edit_tag');
    Route::get('/delete-tag/{id}', 'VideoController@destroy_tag');
    Route::post('/store-tag', 'VideoController@store_tag');
    Route::post('/update-tag/{id}', 'VideoController@update_tag');
    Route::post('/bulk-delete-tag/', 'VideoController@bulk_delete_tag');
});
