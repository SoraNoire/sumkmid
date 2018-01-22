<?php
Config::set('admin.domain',env('ADMIN_DOMAIN','manage.sahabatumkm.id'));
Route::group(['domain'=>config('admin.domain'),'middleware' => ['web', 'backend'], 'prefix' => 'admin/blog', 'namespace' => 'Modules\Blog\Http\Controllers'], function()
{
    Route::get('/', 'BlogController@dashboard')
            ->name('panel.dashboard');
    Route::get('/index', 'BlogController@dashboard')
            ->name('panel.dashboard__index');
    // Route::get('/posts', 'BlogController@index');
            // ->name('panel.post__index');
    // Route::get('/show/{slug}', 'BlogController@show_post')
            // ->name('panel.post__view');


    Route::get('/trash', 'BlogController@trash')
            ->name('panel.post.trash__index');
    Route::get('/ajaxtrashposts', 'BlogController@ajaxtrashPosts')
            ->name('panel.post.trash__index__ajax');
    Route::get('/trash/{id}/delete', 'BlogController@deleteTrash')
            ->name('panel.post.trash__delete');
    Route::get('/trash/{id}/restore', 'BlogController@restoreTrash')
            ->name('panel.post.trash__update__restore');
    Route::post('/trash/massdelete', 'BlogController@massdeleteTrash')
            ->name('panel.post.trash__delete__mass');
    Route::post('/trash/massrestore', 'BlogController@massrestoreTrash')
            ->name('panel.post.trash__update__restore__mass');
    Route::get('/trash/empty', 'BlogController@emptyTrash')
            ->name('panel.post.trash__delete__clear');

    // Route::get('/get-posts', 'BlogController@get_posts');
    // Route::get('/create-post', 'BlogController@create_post');
    // Route::get('/edit-post/{id}', 'BlogController@edit_post');
    // Route::get('/delete-post/{id}', 'BlogController@destroy_post');
    // Route::post('/store-post', 'BlogController@store_post');
    // Route::post('/update-post/{id}', 'BlogController@update_post');
    // Route::post('/bulk-delete-post/', 'BlogController@bulk_delete_post');
     //posts

    // Route::get('/posts', 'BlogController@index')
    //         ->name('panel.post__index');
    // Route::get('/ajaxposts', 'BlogController@ajaxPosts')
    //         ->name('panel.post__index__ajax');
    // Route::get('/post/add', 'BlogController@addPost')
    //         ->name('panel.post__add');
    // Route::post('/post/add', 'BlogController@addPostPost')
    //         ->name('panel.post__save');
    // Route::get('/post/{id}/view', 'BlogController@viewPost')
    //         ->name('panel.post__view');
    // Route::post('/post/{id}/update', 'BlogController@updatePost')
    //         ->name('panel.post__update');
    // Route::get('/post/{id}/remove', 'BlogController@removePost')
    //         ->name('panel.post__delete');
    // Route::post('/post/massdelete', 'BlogController@massdeletePost')
    //         ->name('panel.post__delete__mass');

    Route::get('/add-category-post/{name}/{parent}', 'BlogController@store_category_ajax')
            ->name('panel.category__add__ajax__post');
    Route::get('/get-category-post/{post_id}', 'BlogController@get_all_category')
            ->name('panel.category__index__get');
    Route::get('/get-category-parent/{category_id}', 'BlogController@get_category_parent')
            ->name('panel.category__index__getparent');
    
    // Route::get('/files', 'BlogController@files')
    //         ->name('panel.file__index');
    // Route::get('/get-files', 'BlogController@get_file')
    //         ->name('panel.file__index__ajax');
    // Route::post('/store-file', 'BlogController@store_file')
    //         ->name('panel.file__save');
    // Route::get('/delete-file/{fileName}', 'BlogController@destroy_file')
    //         ->name('panel.file__delete');
    // Route::post('/bulk-delete-file/', 'BlogController@bulk_delete_file')
    //         ->name('panel.file__delete__mass');
    // Route::get('/edit-file/{id}', 'BlogController@edit_file')
    //         ->name('panel.file__view');
    // Route::post('/update-file/{id}', 'BlogController@update_file')
    //         ->name('panel.file__update');

    // Route::get('/category', 'BlogController@category');
    // Route::get('/get-category', 'BlogController@get_category');
    // Route::get('/create-category', 'BlogController@create_category');
    // Route::get('/edit-category/{id}', 'BlogController@edit_category');
    // Route::get('/delete-category/{id}', 'BlogController@destroy_category');
    // Route::post('/store-category', 'BlogController@store_category');
    // Route::post('/update-category/{id}', 'BlogController@update_category');
    // Route::post('/bulk-delete-category/', 'BlogController@bulk_delete_category');


    // category

    Route::get('/categories', 'BlogController@categories')
            ->name('panel.category__index');    
    Route::get('/ajaxcategories', 'BlogController@ajaxCategories')
            ->name('panel.category__index__ajax');
    Route::get('/category/add', 'BlogController@addCategory')
            ->name('panel.category__add');
    Route::post('/category/add', 'BlogController@addCategoryPost')
            ->name('panel.category__save');
    Route::post('/category/ajaxadd', 'BlogController@addCategoryPost')
            ->name('panel.category__add__ajax');
    Route::get('/category/{id}/view', 'BlogController@viewCategory')
            ->name('panel.category__view');
    Route::post('/category/{id}/update', 'BlogController@updateCategory')
            ->name('panel.category__update');
    Route::get('/category/{id}/remove', 'BlogController@removeCategory')
            ->name('panel.category__delete');
    Route::post('/category/massdelete', 'BlogController@massdeleteCategory')
            ->name('panel.category__delete__mass');

    //tags

    Route::get('/tags', 'BlogController@tags')
            ->name('panel.tag__index');
    Route::get('/ajaxtags', 'BlogController@ajaxTags')
            ->name('panel.tag__index__ajax');
    Route::get('/tag/add', 'BlogController@addTag')
            ->name('panel.tag__add');
    Route::post('/tag/add', 'BlogController@addTagPost')
            ->name('panel.tag__save');
    Route::get('/tag/{id}/view', 'BlogController@viewTag')
            ->name('panel.tag__view');
    Route::post('/tag/{id}/update', 'BlogController@updateTag')
            ->name('panel.tag__update');
    Route::get('/tag/{id}/remove', 'BlogController@removeTag')
            ->name('panel.tag__delete');
    Route::post('/tag/massdelete', 'BlogController@massdeleteTag')
            ->name('panel.tag__delete__mass');


    // Route::get('/tag', 'BlogController@tag');
    // Route::get('/get-tag', 'BlogController@get_tag');
    // Route::get('/create-tag', 'BlogController@create_tag');
    // Route::get('/edit-tag/{id}', 'BlogController@edit_tag');
    // Route::get('/delete-tag/{id}', 'BlogController@destroy_tag');
    // Route::post('/store-tag', 'BlogController@store_tag');
    // Route::post('/update-tag/{id}', 'BlogController@update_tag');
    // Route::post('/bulk-delete-tag/', 'BlogController@bulk_delete_tag');

    Route::get('/media', 'BlogController@media')
            ->name('panel.media__index');
    Route::get('/get-media', 'BlogController@get_media')
            ->name('panel.media__index__ajax');
    Route::post('/get-media', 'BlogController@get_media')
            ->name('panel.media__index__ajax__post');
    Route::get('/delete-media/{id}', 'BlogController@destroy_media')
            ->name('panel.media__delete');
    Route::post('/store-media', 'BlogController@store_media')
            ->name('panel.media__save');
    Route::post('/bulk-delete-media/', 'BlogController@bulk_delete_media')
            ->name('panel.media__delete__mass');

     //pages

    Route::get('/pages', 'BlogController@pages')
            ->name('panel.page__index');
    Route::get('/ajaxpages', 'BlogController@ajaxPages')
            ->name('panel.page__index__ajax');
    Route::get('/page/add', 'BlogController@addPage')
            ->name('panel.page__add');
    Route::post('/page/add', 'BlogController@addPagePost')
            ->name('panel.page__save');
    Route::get('/page/{id}/view', 'BlogController@viewPage')
            ->name('panel.page__view');
    Route::post('/page/{id}/update', 'BlogController@updatePage')
            ->name('panel.page__update');
    Route::get('/page/{id}/remove', 'BlogController@removePage')
            ->name('panel.page__delete');
    Route::post('/page/massdelete', 'BlogController@massdeletePage')
            ->name('panel.page__delete__mass');

    Route::get('/site-setting', 'BlogController@site_setting_view')
            ->name('panel.setting.site__index');
    Route::post('/save-setting', 'BlogController@site_setting_save')
            ->name('panel.setting.site__update');
    Route::post('/save-program', 'BlogController@save_program')
            ->name('panel.setting.site__update__program');

    Route::get('/slider','BlogController@head_slider')
            ->name('panel.slider__index');
    Route::get('/new-slider','BlogController@new_slider_view')
            ->name('panel.slider__add');
    Route::post('/act-new-slider','BlogController@new_slider_act')
            ->name('panel.slider__save');
    Route::get('/edit-slider/{id}','BlogController@edit_slider_view')
            ->name('panel.slider__view');
    Route::post('/edit-slider/{id}/act_edit_slider','BlogController@edit_slider_act')
            ->name('panel.slider__update');
    Route::get('/hapus-slider/{id}','BlogController@hapus_slider')
            ->name('panel.slider__delete');

    // Route::get('/pages', 'BlogController@pages');
    // Route::get('/page/{slug}', 'BlogController@show_page');
    // Route::get('/get-pages', 'BlogController@get_pages');
    // Route::get('/create-page', 'BlogController@create_page');
    // Route::get('/edit-page/{id}', 'BlogController@edit_page');
    // Route::get('/delete-page/{id}', 'BlogController@destroy_page');
    // Route::post('/store-page', 'BlogController@store_page');
    // Route::post('/update-page/{id}', 'BlogController@update_page');
    // Route::post('/bulk-delete-page/', 'BlogController@bulk_delete_page');
});
