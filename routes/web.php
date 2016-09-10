<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();

Route::get('/', ['uses' => 'HomeController@index', 'as' => 'index']);
Route::get('/home', ['uses' => 'HomeController@home']);
Route::get('/about', ['uses' => 'PageController@about', 'as' => 'page.about']);
Route::get('/projects', ['uses' => 'HomeController@projects', 'as' => 'projects']);
Route::get('/search', ['uses' => 'HomeController@search', 'as' => 'search']);
Route::post('/upload', ['uses' => 'HomeController@upload', 'as' => 'upload']);


Route::get('post/{slug}', ['uses' => 'PostController@show', 'as' => 'post.show']);
Route::get('category/{name}', ['uses' => 'CategoryController@show', 'as' => 'category.show']);

Route::group(['prefix' => 'admin', ['middleware' => ['auth', 'admin']]], function () {

    Route::get('/', ['uses' => 'AdminController@index', 'as' => 'admin.index']);
    Route::get('/settings', ['uses' => 'AdminController@settings', 'as' => 'admin.settings']);
    Route::post('/settings', ['uses' => 'AdminController@saveSettings', 'as' => 'admin.save-settings']);


    Route::get('/posts', ['uses' => 'AdminController@posts', 'as' => 'admin.posts']);
    Route::get('/tags', ['uses' => 'AdminController@tags', 'as' => 'admin.tags']);
    Route::get('/users', ['uses' => 'AdminController@users', 'as' => 'admin.users']);
    Route::get('/pages', ['uses' => 'AdminController@pages', 'as' => 'admin.pages']);
    Route::get('/categories', ['uses' => 'AdminController@categories', 'as' => 'admin.categories']);

    Route::post('post/{post}/restore', ['uses' => 'PostController@restore', 'as' => 'post.restore']);
    Route::get('post/{slug}/preview', ['uses' => 'PostController@preview', 'as' => 'post.preview']);
    Route::post('post/{post}/publish', ['uses' => 'PostController@publish', 'as' => 'post.publish']);

    Route::delete('tag/{tag}', ['uses' => 'TagController@destroy', 'as' => 'tag.destroy']);
    Route::post('tag', ['uses' => 'TagController@store', 'as' => 'tag.store']);

    Route::resource('post', 'PostController', ['except' => ['show', 'index']]);
    Route::resource('category', 'CategoryController', ['except' => ['index', 'show', 'create']]);
    Route::resource('page', 'PageController', ['except' => ['show', 'index']]);

});
