<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', 'Auth\RegisterController@register')->name('api.register');

Route::get('/post/{id}', 'PostsController@getPost')->name('post.getPost');
Route::post('/post', 'PostsController@create')->name('post.create');
Route::put('/post/{id}', 'PostsController@edit')->name('post.edit');
Route::delete('/post/{id}', 'PostsController@delete')->name('post.delete');
Route::get('/posts/{page?}', 'PostsController@index')->name('posts');

Route::get('categories', 'CategoriesController@getCategories')->name('categories');
