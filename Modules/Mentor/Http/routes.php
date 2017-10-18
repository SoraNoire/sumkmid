<?php



Route::group(['middleware' => 'web', 'as'=>'mentor', 'namespace' => 'Modules\Mentor\Http\Controllers'], function()
{
    Route::get('/mentors', 'MentorController@frontIndex');
    Route::get('/mentors/profile/edit', 'MentorController@editMyProfile');
    Route::post('/mentors/profile/update', ['as'=>'.update','uses'=>'MentorController@updateMyProfile']);
});

/**
 * route for mentor's backend 
 *
 * @author 
 **/

Route::group(['middleware' => 'web', 'prefix' => 'admin/mentors', 'as'=>'mentor', 'namespace' => 'Modules\Mentor\Http\Controllers'], function()
{
    Route::get('/', 'MentorController@index');
    Route::get('/add', ['as'=>'.add','uses'=>'MentorController@add']);
    Route::resource('/m', 'MentorController');
});
