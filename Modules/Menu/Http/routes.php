<?php

Route::group(['middleware' => ['web', 'backend'], 'prefix' => '/admin/blog/menu', 'namespace' => 'Modules\Menu\Http\Controllers'], function()
{
    Route::get('/', 'MenuController@index')
    		->name('panel.menu__index');
    Route::get('/get-menu/{menu_type}', 'MenuController@get_menu')
    		->name('panel.menu__index__ajaxget');
    Route::post('save-menu/{menu_type}', 'MenuController@save_menu')
    		->name('panel.menu__update');
    Route::get('search-page-component/{search}', 'MenuController@search_page_component')
    		->name('panel.page__index__searchmenu');
    Route::get('search-category-component/{search}', 'MenuController@search_category_component')
    		->name('panel.category__index__searchmenu');
    // Route::get('/mobile', 'MenuController@menu_mobile')
    // 		->name('panel.menu__index__mobile');
    // Route::post('save-menu-mobile', 'MenuController@save_menu_mobile')
    // 		->name('panel.menu__save__mobile');
});
