<?php



Route::group(['middleware' => 'web', 'as'=>'mentor', 'namespace' => 'Modules\Mentor\Http\Controllers'], function()
{
    Route::get('/mentors', 'MentorController@frontIndex');
});

/**
 * route for mentor's backend 
 *
 * @author 
 **/

Route::group(['middleware' => 'web', 'prefix' => 'admin/mentors', 'as'=>'mentor', 'namespace' => 'Modules\Mentor\Http\Controllers'], function()
{
    Route::get('/', 'MentorController@index');
    Route::resource('/m', 'MentorController');
});
