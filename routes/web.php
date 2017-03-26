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
	Route::get('/tag/create', '\TagObjects\Tag\TagController@create');
	Route::post('/tag', '\TagObjects\Tag\TagController@store');
	Route::get('/tag/{tag}/edit', '\TagObjects\Tag\TagController@edit');
	Route::patch('/tag/{tag}', '\TagObjects\Tag\TagController@update');
});

Route::get('/tag', '\TagObjects\Tag\TagController@index');
Route::get('/tag/{tag}', '\TagObjects\Tag\TagController@show');
//End tag controller routes

//Artist controller routes
Route::group(['middleware' => 'auth'], function(){
	Route::get('/artist/create', '\TagObjects\Artist\ArtistController@create');
	Route::post('/artist', '\TagObjects\Artist\ArtistController@store');
	Route::get('/artist/{artist}/edit', '\TagObjects\Artist\ArtistController@edit');
	Route::patch('/artist/{artist}', '\TagObjects\Artist\ArtistController@update');
});

Route::get('/artist', '\TagObjects\Artist\ArtistController@index');
Route::get('/artist/{artist}', '\TagObjects\Artist\ArtistController@show');
//End artist controller routes

//Series controller routes
Route::group(['middleware' => 'auth'], function(){
	Route::get('/series/create', '\TagObjects\Series\SeriesController@create');
	Route::post('/series', '\TagObjects\Series\SeriesController@store');
	Route::get('/series/{series}/edit', '\TagObjects\Series\SeriesController@edit');
	Route::patch('/series/{series}', '\TagObjects\Series\SeriesController@update');
});

Route::get('/series', '\TagObjects\Series\SeriesController@index');
Route::get('/series/{series}', '\TagObjects\Series\SeriesController@show');
//End series controller routes

//Scanalator controller routes
Route::group(['middleware' => 'auth'], function(){
	Route::get('/scanalator/create', '\TagObjects\Scanalator\ScanalatorController@create');
	Route::post('/scanalator', '\TagObjects\Scanalator\ScanalatorController@store');
	Route::get('/scanalator/{scanalator}/edit', '\TagObjects\Scanalator\ScanalatorController@edit');
	Route::patch('/scanalator/{scanalator}', '\TagObjects\Scanalator\ScanalatorController@update');
});

Route::get('/scanalator', '\TagObjects\Scanalator\ScanalatorController@index');
Route::get('/scanalator/{scanalator}', '\TagObjects\Scanalator\ScanalatorController@show');
//End scanalator controller routes

//Character controller routes
Route::group(['middleware' => 'auth'], function(){
	Route::get('/character/create/{series?}', '\TagObjects\Character\CharacterController@create');
	Route::post('/character', '\TagObjects\Character\CharacterController@store');
	Route::get('/character/{character}/edit', '\TagObjects\Character\CharacterController@edit');
	Route::patch('/character/{character}', '\TagObjects\Character\CharacterController@update');
});

Route::get('/character', '\TagObjects\Character\CharacterController@index');
Route::get('/character/{character}', '\TagObjects\Character\CharacterController@show');
//End Character controller routes

Auth::routes();

Route::get('/home', 'HomeController@index');
