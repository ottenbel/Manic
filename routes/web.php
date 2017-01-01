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

//Collection controller routes
Route::get('/', 'CollectionController@index');

Route::group(['middleware' => 'auth'], function(){
	Route::get('/collection/create', 'CollectionController@create');
	Route::post('/collection', 'CollectionController@store');
	Route::get('/collection/{id}/edit', 'CollectionController@edit');
});

Route::get('/collection/{id}', 'CollectionController@show');
//End Collection controller routes

Auth::routes();

Route::get('/home', 'HomeController@index');
