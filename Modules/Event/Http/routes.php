<?php
Config::set('admin.domain',env('ADMIN_DOMAIN','manage.sahabatumkm.id'));
Route::group(['domain'=>config('admin.domain'),'middleware' => ['web','backend'], 'prefix' => 'admin/blog/event', 'namespace' => 'Modules\Event\Http\Controllers'], function()
{


    Route::get('/', 'EventController@index')
            ->name('panel.event__index');
    Route::get('/ajaxevents', 'EventController@ajaxEvents')
            ->name('panel.event__index__ajax');
    Route::get('/add', 'EventController@addEvent')
            ->name('panel.event__add');
    Route::post('/add', 'EventController@addEventPost')
            ->name('panel.event__save');
    Route::get('/{id}/view', 'EventController@viewEvent')
            ->name('panel.event__view');
    Route::post('/{id}/update', 'EventController@updateEvent')
            ->name('panel.event__update');
    Route::get('/{id}/remove', 'EventController@removeEvent')
            ->name('panel.event__delete');
    Route::post('/massdelete', 'EventController@massdeleteEvent')
            ->name('panel.event__delete__mass');

    Route::get('/get-mentoring', 'EventController@ajaxMentoring')
            ->name('panel.mentoring__index__ajax');

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

Route::group(['domain'=>config('admin.domain'),'middleware' => ['web','backend'], 'prefix' => 'admin/blog/mentoring', 'namespace' => 'Modules\Event\Http\Controllers'], function()
{

    Route::get('/', 'EventController@mentoring')
            ->name('panel.mentoring__index');
    Route::get('/get-mentoring', 'EventController@ajaxMentoring')
            ->name('panel.mentoring__index__ajax');
    Route::get('/add', 'EventController@addMentoring')
            ->name('panel.mentoring__add');
    Route::post('/add', 'EventController@addMentoringPost')
            ->name('panel.mentoring__save');
    Route::get('/{id}/view', 'EventController@viewMentoring')
            ->name('panel.mentoring__view');
    Route::post('/{id}/update', 'EventController@updateMentoring')
            ->name('panel.mentoring__update');
    Route::get('/{id}/remove', 'EventController@removeMentoring')
            ->name('panel.mentoring__delete');
    Route::post('/massdelete', 'EventController@massdeleteMentoring')
            ->name('panel.mentoring__delete__mass');

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
