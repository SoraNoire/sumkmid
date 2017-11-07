<?php

Route::group(['middleware' => 'web', 'prefix' => 'admin/blog/gallery', 'namespace' => 'Modules\Gallery\Http\Controllers'], function()
{
    Route::get('/', 'GalleryController@index');
    Route::get('/index', 'GalleryController@index');

    Route::get('/show/{slug}', 'GalleryController@show_gallery');
    Route::get('/get-gallery', 'GalleryController@get_gallery');
    Route::get('/create-gallery', 'GalleryController@create_gallery');
    Route::get('/edit-gallery/{id}', 'GalleryController@edit_gallery');
    Route::get('/delete-gallery/{id}', 'GalleryController@destroy_gallery');
    Route::post('/store-gallery', 'GalleryController@store_gallery');
    Route::post('/update-gallery/{id}', 'GalleryController@update_gallery');
    Route::post('/bulk-delete-gallery/', 'GalleryController@bulk_delete_gallery');
    Route::get('/add-category-gallery/{name}/{parent}', 'GalleryController@store_category_ajax');
    Route::get('/get-category-gallery/', 'GalleryController@get_all_category');
    Route::get('/get-category-parent/', 'GalleryController@get_category_parent');

    Route::get('/category', 'GalleryController@category');
    Route::get('/get-category', 'GalleryController@get_category');
    Route::get('/create-category', 'GalleryController@create_category');
    Route::get('/edit-category/{id}', 'GalleryController@edit_category');
    Route::get('/delete-category/{id}', 'GalleryController@destroy_category');
    Route::post('/store-category', 'GalleryController@store_category');
    Route::post('/update-category/{id}', 'GalleryController@update_category');
    Route::post('/bulk-delete-category/', 'GalleryController@bulk_delete_category');

    Route::get('/tag', 'GalleryController@tag');
    Route::get('/get-tag', 'GalleryController@get_tag');
    Route::get('/create-tag', 'GalleryController@create_tag');
    Route::get('/edit-tag/{id}', 'GalleryController@edit_tag');
    Route::get('/delete-tag/{id}', 'GalleryController@destroy_tag');
    Route::post('/store-tag', 'GalleryController@store_tag');
    Route::post('/update-tag/{id}', 'GalleryController@update_tag');
    Route::post('/bulk-delete-tag/', 'GalleryController@bulk_delete_tag');
});
