<?php

Route::group(['middleware' => 'web', 'prefix' => 'admin/blog/menu', 'namespace' => 'Modules\Menu\Http\Controllers'], function()
{
    Route::get('/', 'MenuController@index');
    Route::post('/save-menu', 'MenuController@save_menu');
});
