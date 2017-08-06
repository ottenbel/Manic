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
Route::get('/', 'CollectionController@index')->Name('index_collection');

Route::group(['middleware' => 'auth'], function(){
	Route::get('/collection/create', 'CollectionController@create')->Name('create_collection');
	Route::post('/collection', 'CollectionController@store')->Name('store_collection');
	Route::get('/collection/{collection}/edit', 'CollectionController@edit')->Name('edit_collection');
	Route::patch('/collection/{collection}', 'CollectionController@update')->Name('update_collection');
	Route::delete('/collection/{collection}', 'CollectionController@destroy')->Name('delete_collection');
	Route::get('/collection/{collection}/export', 'CollectionController@export')->Name('export_collection');
});

Route::get('/collection/{collection}', 'CollectionController@show')->Name('show_collection');
//End Collection controller routes

//Volume controller routes
Route::group(['middleware' => 'auth'], function(){
	Route::get('/volume/create/{collection}', 'VolumeController@create')->Name('create_volume');
	Route::post('/volume', 'VolumeController@store')->Name('store_volume');
	Route::get('/volume/{volume}/edit', 'VolumeController@edit')->Name('edit_volume');
	Route::patch('/volume/{volume}', 'VolumeController@update')->Name('update_volume');
	Route::delete('/volume/{volume}', 'VolumeController@destroy')->Name('delete_volume');
	Route::get('/volume/{volume}/export', 'VolumeController@export')->Name('export_volume');
});
//End Volume controller routes

//Chapter controller routes
Route::group(['middleware' => 'auth'], function(){
	Route::get('/chapter/create/{collection}', 'ChapterController@create')->Name('create_chapter');
	Route::post('/chapter', 'ChapterController@store')->Name('store_chapter');
	Route::get('/chapter/{chapter}/edit', 'ChapterController@edit')->Name('edit_chapter');
	Route::patch('/chapter/{chapter}', 'ChapterController@update')->Name('update_chapter');
	Route::delete('/chapter/{chapter}', 'ChapterController@destroy')->Name('delete_chapter');
	Route::get('/chapter/{chapter}/export', 'ChapterController@export')->Name('export_chapter');
});

Route::get('/chapter/{chapter}/{page?}', 'ChapterController@show')->Name('show_chapter');
//End Chapter controller routes

//Tag controller routes
Route::group(['middleware' => 'auth'], function(){
	Route::get('/tag/create', 'TagObjects\Tag\TagController@create')->Name('create_tag');
	Route::post('/tag', 'TagObjects\Tag\TagController@store')->Name('store_tag');
	Route::get('/tag/{tag}/edit', 'TagObjects\Tag\TagController@edit')->Name('edit_tag');
	Route::patch('/tag/{tag}', 'TagObjects\Tag\TagController@update')->Name('update_tag');
	Route::delete('/tag/{tag}', 'TagObjects\Tag\TagController@destroy')->Name('delete_tag');
});

Route::get('/tag', 'TagObjects\Tag\TagController@index')->Name('index_tag');
Route::get('/tag/{tag}', 'TagObjects\Tag\TagController@show')->Name('show_tag');

//Alias controller routes
Route::group(['middleware' => 'auth'], function(){
	Route::post('/tag_alias/{tag}', 'TagObjects\Tag\TagAliasController@store')->Name('store_tag_alias');
	Route::delete('/tag_alias/{tagAlias}', 'TagObjects\Tag\TagAliasController@destroy')->Name('delete_tag_alias');
});

Route::get('/tag_alias', 'TagObjects\Tag\TagAliasController@index')->Name('index_tag_alias');
//End Alias controller routes

//End tag controller routes

//Artist controller routes
Route::group(['middleware' => 'auth'], function(){
	Route::get('/artist/create', 'TagObjects\Artist\ArtistController@create')->Name('create_artist');
	Route::post('/artist', 'TagObjects\Artist\ArtistController@store')->Name('store_artist');
	Route::get('/artist/{artist}/edit', 'TagObjects\Artist\ArtistController@edit')->Name('edit_artist');
	Route::patch('/artist/{artist}', 'TagObjects\Artist\ArtistController@update')->Name('update_artist');
	Route::delete('/artist/{artist}', 'TagObjects\Artist\ArtistController@destroy')->Name('delete_artist');
});

Route::get('/artist', 'TagObjects\Artist\ArtistController@index')->Name('index_artist');
Route::get('/artist/{artist}', 'TagObjects\Artist\ArtistController@show')->Name('show_artist');

//Alias controller routes
Route::group(['middleware' => 'auth'], function(){
	Route::post('/artist_alias/{artist}', 'TagObjects\Artist\ArtistAliasController@store')->Name('store_artist_alias');
	Route::delete('/artist_alias/{artistAlias}', 'TagObjects\Artist\ArtistAliasController@destroy')->Name('delete_artist_alias');
});

Route::get('/artist_alias', 'TagObjects\Artist\ArtistAliasController@index')->Name('index_artist_alias');
//End Alias controller routes

//End artist controller routes

//Series controller routes
Route::group(['middleware' => 'auth'], function(){
	Route::get('/series/create', 'TagObjects\Series\SeriesController@create')->Name('create_series');
	Route::post('/series', 'TagObjects\Series\SeriesController@store')->Name('store_series');
	Route::get('/series/{series}/edit', 'TagObjects\Series\SeriesController@edit')->Name('edit_series');
	Route::patch('/series/{series}', 'TagObjects\Series\SeriesController@update')->Name('update_series');
	Route::delete('/series/{series}', 'TagObjects\Series\SeriesController@destroy')->Name('delete_series');
});

Route::get('/series', 'TagObjects\Series\SeriesController@index')->Name('index_series');
Route::get('/series/{series}', 'TagObjects\Series\SeriesController@show')->Name('show_series');

//Alias controller routes
Route::group(['middleware' => 'auth'], function(){
	Route::post('/series_alias/{series}', 'TagObjects\Series\SeriesAliasController@store')->Name('store_series_alias');
	Route::delete('/series_alias/{seriesAlias}', 'TagObjects\Series\SeriesAliasController@destroy')->Name('delete_series_alias');
});

Route::get('/series_alias', 'TagObjects\Series\SeriesAliasController@index')->Name('index_series_alias');
//End Alias controller routes

//End series controller routes

//Scanalator controller routes
Route::group(['middleware' => 'auth'], function(){
	Route::get('/scanalator/create', 'TagObjects\Scanalator\ScanalatorController@create')->Name('create_scanalator');
	Route::post('/scanalator', 'TagObjects\Scanalator\ScanalatorController@store')->Name('store_scanalator');
	Route::get('/scanalator/{scanalator}/edit', 'TagObjects\Scanalator\ScanalatorController@edit')->Name('edit_scanalator');
	Route::patch('/scanalator/{scanalator}', 'TagObjects\Scanalator\ScanalatorController@update')->Name('update_scanalator');
	Route::delete('/scanalator/{scanalator}', 'TagObjects\Scanalator\ScanalatorController@destroy')->Name('delete_scanalator');
});

Route::get('/scanalator', 'TagObjects\Scanalator\ScanalatorController@index')->Name('index_scanalator');
Route::get('/scanalator/{scanalator}', 'TagObjects\Scanalator\ScanalatorController@show')->Name('show_scanalator');

//Alias controller routes
Route::group(['middleware' => 'auth'], function(){
	Route::post('/scanalator_alias/{scanalator}', 'TagObjects\Scanalator\ScanalatorAliasController@store')->Name('store_scanalator_alias');
	Route::delete('/scanalator_alias/{scanalatorAlias}', 'TagObjects\Scanalator\ScanalatorAliasController@destroy')->Name('delete_scanalator_alias');
});

Route::get('/scanalator_alias', 'TagObjects\Scanalator\ScanalatorAliasController@index')->Name('index_scanalator_alias');
//End Alias controller routes

//End scanalator controller routes

//Character controller routes
Route::group(['middleware' => 'auth'], function(){
	Route::get('/character/create/{series?}', 'TagObjects\Character\CharacterController@create')->Name('create_character');
	Route::post('/character', 'TagObjects\Character\CharacterController@store')->Name('store_character');
	Route::get('/character/{character}/edit', 'TagObjects\Character\CharacterController@edit')->Name('edit_character');
	Route::patch('/character/{character}', 'TagObjects\Character\CharacterController@update')->Name('update_character');
	Route::delete('/character/{character}', 'TagObjects\Character\CharacterController@destroy')->Name('delete_character');
});

Route::get('/character', 'TagObjects\Character\CharacterController@index')->Name('index_character');
Route::get('/character/{character}', 'TagObjects\Character\CharacterController@show')->Name('show_character');

//Alias controller routes
Route::group(['middleware' => 'auth'], function(){
	Route::post('/character_alias/{character}', 'TagObjects\Character\CharacterAliasController@store')->Name('store_character_alias');
	Route::delete('/character_alias/{characterAlias}', 'TagObjects\Character\CharacterAliasController@destroy')->Name('delete_character_alias');
});

Route::get('/character_alias', 'TagObjects\Character\CharacterAliasController@index')->Name('index_character_alias');
//End Alias controller routes

//End Character controller routes

//Search controller routes
Route::post('/search', 'SearchController@search')->Name('process_search');
//End search controller routes

Auth::routes();

Route::get('/home', 'HomeController@index');
