<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|

*/

Route::get('/', function () {
    return view('welcome');
});
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {
  Route::get('news/create', 'Admin\Newscontroller@add');
  Route::post('news/create', 'Admin\Newscontroller@create');
  Route::get('news', 'Admin\Newscontroller@index');
  Route::get('news/edit', 'Admin\Newscontroller@edit');
  Route::post('news/edit', 'Admin\Newscontroller@update');
  Route::get('news/delete', 'Admin\Newscontroller@delete');
  Route::get('profile/create','Admin\ProfileController@add');
  Route::get('profile/edit','Admin\ProfileController@edit');
  Route::post('profile/create','Admin\ProfileController@create');
  Route::post('profile/edit', 'Admin\ProfileController@update');
  Route::get('profile', 'Admin\ProfileController@index');
  Route::get('profile/delete', 'Admin\ProfileController@delete');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'NewsController@index');
Route::get('/', 'ProfileController@index');
