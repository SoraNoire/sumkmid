<?php

Route::group(['middleware' => ['web','admin'], 'prefix' => 'admin/blog/event', 'namespace' => 'Modules\Event\Http\Controllers'], function()
{


    Route::get('/', ['as'=> 'events', 'uses' => 'EventController@index']);
    Route::get('/ajaxevents', ['as'=> 'ajaxevents', 'uses' => 'EventController@ajaxEvents']);
    Route::get('/add', ['as'=> 'addevent', 'uses' => 'EventController@addEvent']);
    Route::post('/add', ['as'=> 'storeevent', 'uses' => 'EventController@addEventPost']);
    Route::get('/{id}/view', ['as'=> 'viewevent', 'uses' => 'EventController@viewEvent']);
    Route::post('/{id}/update', ['as'=> 'updateevent', 'uses' => 'EventController@updateEvent']);
    Route::get('/{id}/remove', ['as'=> 'removeevent', 'uses' => 'EventController@removeEvent']);
    Route::post('/massdelete', ['as'=> 'massdeleteevent', 'uses' => 'EventController@massdeleteEvent']);

    // Route::get('/', 'EventController@index');
    // Route::get('/index', 'EventController@index');
    // Route::get('/show/{slug}', 'EventController@show_event');
    // Route::get('/get-events', 'EventController@get_events');
    // Route::get('/create-event', 'EventController@create_event');
    // Route::get('/edit-event/{id}', 'EventController@edit_event');
    // Route::get('/delete-event/{id}', 'EventController@destroy_event');
    // Route::post('/store-event', 'EventController@store_event');
    // Route::post('/update-event/{id}', 'EventController@update_event');
    // Route::post('/bulk-delete-event/', 'EventController@bulk_delete_event');
    // Route::get('/add-category-event/{name}', 'EventController@store_category_ajax');
    // Route::get('/get-category-event/', 'EventController@get_list_category');

    // Route::get('/category', 'EventController@category');
    // Route::get('/get-category', 'EventController@get_category');
    // Route::get('/create-category', 'EventController@create_category');
    // Route::get('/edit-category/{id}', 'EventController@edit_category');
    // Route::get('/delete-category/{id}', 'EventController@destroy_category');
    // Route::post('/store-category', 'EventController@store_category');
    // Route::post('/update-category/{id}', 'EventController@update_category');
    // Route::post('/bulk-delete-category/', 'EventController@bulk_delete_category');
});
