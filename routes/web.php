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
	Route::get('/collection/{collection}/edit', 'CollectionController@edit');
	Route::patch('/collection/{collection}', 'CollectionController@update');
});

Route::get('/collection/{collection}', 'CollectionController@show');
//End Collection controller routes

//Volume controller routes
Route::group(['middleware' => 'auth'], function(){
	Route::get('/volume/create/{collection}', 'VolumeController@create');
	Route::post('/volume', 'VolumeController@store');
	Route::get('/volume/{volume}/edit', 'VolumeController@edit');
	Route::patch('/volume/{volume}', 'VolumeController@update');
});
//End Volume controller routes

//Chapter controller routes
Route::group(['middleware' => 'auth'], function(){
	Route::get('/chapter/create/{collection}', 'ChapterController@create');
	Route::post('/chapter', 'ChapterController@store');
	Route::get('/chapter/{chapter}/edit', 'ChapterController@edit');
	Route::patch('/chapter/{chapter}', 'ChapterController@update');
});

Route::get('/chapter/{chapter}/{page?}', 'ChapterController@show');
//End Chapter controller routes

//Tag controller routes
Route::group(['middleware' => 'auth'], function(){
	Route::get('/tag/create', 'TagController@create');
	Route::post('/tag', 'TagController@store');
	Route::get('/tag/{tag}/edit', 'TagController@edit');
	Route::patch('/tag/{tag}', 'TagController@update');
});

Route::get('/tag', 'TagController@index');
Route::get('/tag/{tag}', 'TagController@show');

//End tag controller routes

//Artist controller routes
Route::group(['middleware' => 'auth'], function(){
	Route::get('/artist/create', 'ArtistController@create');
	Route::post('/artist', 'ArtistController@store');
	Route::get('/artist/{artist}/edit', 'ArtistController@edit');
	Route::patch('/artist/{artist}', 'ArtistController@update');
});

Route::get('/artist', 'ArtistController@index');
Route::get('/artist/{artist}', 'ArtistController@show');

//End artist controller routes

//Series controller routes
Route::group(['middleware' => 'auth'], function(){
	Route::get('/series/create', 'SeriesController@create');
	Route::post('/series', 'SeriesController@store');
	Route::get('/series/{series}/edit', 'SeriesController@edit');
	Route::patch('/series/{series}', 'SeriesController@update');
});

Route::get('/series', 'SeriesController@index');
Route::get('/series/{series}', 'SeriesController@show');

//End series controller routes

Auth::routes();

Route::get('/home', 'HomeController@index');
