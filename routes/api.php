<?php

use Illuminate\Http\Request;

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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

//Internally used public API's
Route::post('/v1/artist/namesearch', 'API\V1\Artist\ArtistSearchAPIController@SearchByName');
Route::post('/v1/character/namesearch', 'API\V1\Character\CharacterSearchAPIController@SearchByName');
Route::post('/v1/collection/namesearch', 'API\V1\Collection\CollectionSearchAPIController@SearchByName');
Route::post('/v1/scanalator/namesearch', 'API\V1\Scanalator\ScanalatorSearchAPIController@SearchByName');
Route::post('/v1/series/namesearch', 'API\V1\Series\SeriesSearchAPIController@SearchByName');
Route::post('/v1/tag/namesearch', 'API\V1\Tag\TagSearchAPIController@SearchByName');