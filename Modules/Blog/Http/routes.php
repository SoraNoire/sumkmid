<?php

Route::group(['middleware' => ['web','admin'], 'prefix' => 'admin/blog', 'namespace' => 'Modules\Blog\Http\Controllers'], function()
{
    // Route::get('/', 'BlogController@index');
    Route::get('/index', 'BlogController@index');
    Route::get('/posts', 'BlogController@index');
    Route::get('/show/{slug}', 'BlogController@show_post');


    // Route::get('/get-posts', 'BlogController@get_posts');
    // Route::get('/create-post', 'BlogController@create_post');
    // Route::get('/edit-post/{id}', 'BlogController@edit_post');
    // Route::get('/delete-post/{id}', 'BlogController@destroy_post');
    // Route::post('/store-post', 'BlogController@store_post');
    // Route::post('/update-post/{id}', 'BlogController@update_post');
    // Route::post('/bulk-delete-post/', 'BlogController@bulk_delete_post');
     //posts

    Route::get('/posts', ['as'=> 'posts', 'uses' => 'BlogController@index']);
    Route::get('/ajaxposts', ['as'=> 'ajaxposts', 'uses' => 'BlogController@ajaxPosts']);
    Route::get('/post/add', ['as'=> 'addpost', 'uses' => 'BlogController@addPost']);
    Route::post('/post/add', ['as'=> 'storepost', 'uses' => 'BlogController@addPostPost']);
    Route::get('/post/{id}/view', ['as'=> 'viewpost', 'uses' => 'BlogController@viewPost']);
    Route::post('/post/{id}/update', ['as'=> 'updatepost', 'uses' => 'BlogController@updatePost']);
    Route::get('/post/{id}/remove', ['as'=> 'removepost', 'uses' => 'BlogController@removePost']);
    Route::post('/post/massdelete', ['as'=> 'massdeletepost', 'uses' => 'BlogController@massdeletePost']);



    Route::get('/add-category-post/{name}/{parent}', 'BlogController@store_category_ajax');
    Route::get('/get-category-post/{post_id}', 'BlogController@get_all_category');
    Route::get('/get-category-parent/{category_id}', 'BlogController@get_category_parent');
    
    Route::post('/store-file', 'BlogController@store_file');
    Route::get('/delete-file/{postId}/{fileName}', 'BlogController@destroy_file');

    // Route::get('/category', 'BlogController@category');
    // Route::get('/get-category', 'BlogController@get_category');
    // Route::get('/create-category', 'BlogController@create_category');
    // Route::get('/edit-category/{id}', 'BlogController@edit_category');
    // Route::get('/delete-category/{id}', 'BlogController@destroy_category');
    // Route::post('/store-category', 'BlogController@store_category');
    // Route::post('/update-category/{id}', 'BlogController@update_category');
    // Route::post('/bulk-delete-category/', 'BlogController@bulk_delete_category');


    // category

    Route::get('/categories', ['as'=> 'categories', 'uses' => 'BlogController@categories']);
    Route::get('/ajaxcategories', ['as'=> 'ajaxcategories', 'uses' => 'BlogController@ajaxCategories']);
    Route::get('/category/add', ['as'=> 'addcategory', 'uses' => 'BlogController@addCategory']);
    Route::post('/category/add', ['as'=> 'storecategory', 'uses' => 'BlogController@addCategoryPost']);
    Route::post('/category/ajaxadd', ['as'=> 'storecategoryajax', 'uses' => 'BlogController@addCategoryPost']);
    Route::get('/category/{id}/view', ['as'=> 'viewcategory', 'uses' => 'BlogController@viewCategory']);
    Route::post('/category/{id}/update', ['as'=> 'updatecategory', 'uses' => 'BlogController@updateCategory']);
    Route::get('/category/{id}/remove', ['as'=> 'removecategory', 'uses' => 'BlogController@removeCategory']);
    Route::post('/category/massdelete', ['as'=> 'massdeletecategory', 'uses' => 'BlogController@massdeleteCategory']);

    //tags

    Route::get('/tags', ['as'=> 'tags', 'uses' => 'BlogController@tags']);
    Route::get('/ajaxtags', ['as'=> 'ajaxtags', 'uses' => 'BlogController@ajaxTags']);
    Route::get('/tag/add', ['as'=> 'addtag', 'uses' => 'BlogController@addTag']);
    Route::post('/tag/add', ['as'=> 'storetag', 'uses' => 'BlogController@addTagPost']);
    Route::get('/tag/{id}/view', ['as'=> 'viewtag', 'uses' => 'BlogController@viewTag']);
    Route::post('/tag/{id}/update', ['as'=> 'updatetag', 'uses' => 'BlogController@updateTag']);
    Route::get('/tag/{id}/remove', ['as'=> 'removetag', 'uses' => 'BlogController@removeTag']);
    Route::post('/tag/massdelete', ['as'=> 'massdeletetag', 'uses' => 'BlogController@massdeleteTag']);


    // Route::get('/tag', 'BlogController@tag');
    // Route::get('/get-tag', 'BlogController@get_tag');
    // Route::get('/create-tag', 'BlogController@create_tag');
    // Route::get('/edit-tag/{id}', 'BlogController@edit_tag');
    // Route::get('/delete-tag/{id}', 'BlogController@destroy_tag');
    // Route::post('/store-tag', 'BlogController@store_tag');
    // Route::post('/update-tag/{id}', 'BlogController@update_tag');
    // Route::post('/bulk-delete-tag/', 'BlogController@bulk_delete_tag');

    Route::get('/media', 'BlogController@media');
    Route::get('/get-media', 'BlogController@get_media');
    Route::get('/delete-media/{id}', 'BlogController@destroy_media');
    Route::post('/store-media', 'BlogController@store_media');
    Route::post('/bulk-delete-media/', 'BlogController@bulk_delete_media');

     //pages

    Route::get('/pages', ['as'=> 'pages', 'uses' => 'BlogController@pages']);
    Route::get('/ajaxpages', ['as'=> 'ajaxpages', 'uses' => 'BlogController@ajaxPages']);
    Route::get('/page/add', ['as'=> 'addpage', 'uses' => 'BlogController@addPage']);
    Route::post('/page/add', ['as'=> 'storepage', 'uses' => 'BlogController@addPagePost']);
    Route::get('/page/{id}/view', ['as'=> 'viewpage', 'uses' => 'BlogController@viewPage']);
    Route::post('/page/{id}/update', ['as'=> 'updatepage', 'uses' => 'BlogController@updatePage']);
    Route::get('/page/{id}/remove', ['as'=> 'removepage', 'uses' => 'BlogController@removePage']);
    Route::post('/page/massdelete', ['as'=> 'massdeletepage', 'uses' => 'BlogController@massdeletePage']);

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
Route::group(['middleware' => 'web', 'prefix' => 'doc-ui', 'namespace' => 'Modules\Blog\Http\Controllers'], function(){
    Route::get('/','DocController@index');
});

Route::group(['middleware' => 'web', 'prefix' => 'blogs', 'namespace' => 'Modules\Blog\Http\Controllers'], function(){
    Route::get('/','FrontController@index');
    Route::get('/read/{slug_id}','FrontController@single_post');
});
