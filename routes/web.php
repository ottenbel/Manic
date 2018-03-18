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
	Route::prefix('collection')->group(function () {
		Route::get('/create', 'CollectionController@create')->Name('create_collection');
		Route::post('/', 'CollectionController@store')->Name('store_collection');
		Route::get('/{collection}/edit', 'CollectionController@edit')->Name('edit_collection');
		Route::patch('/{collection}', 'CollectionController@update')->Name('update_collection');
		Route::delete('/{collection}', 'CollectionController@destroy')->Name('delete_collection');
		Route::get('/{collection}/export', 'CollectionController@export')->Name('export_collection');
		Route::get('/{collection}', 'CollectionController@show')->Name('show_collection');
	});
//End Collection controller routes

//Volume controller routes
	Route::prefix('volume')->group(function () {
		Route::get('/create/{collection}', 'VolumeController@create')->Name('create_volume');
		Route::post('/', 'VolumeController@store')->Name('store_volume');
		Route::get('/{volume}/edit', 'VolumeController@edit')->Name('edit_volume');
		Route::patch('/{volume}', 'VolumeController@update')->Name('update_volume');
		Route::delete('/{volume}', 'VolumeController@destroy')->Name('delete_volume');
		Route::get('/{volume}/export', 'VolumeController@export')->Name('export_volume');
	});
//End Volume controller routes

//Chapter controller routes
	Route::prefix('chapter')->group(function () {
		Route::get('/create/{collection}', 'ChapterController@create')->Name('create_chapter');
		Route::post('/', 'ChapterController@store')->Name('store_chapter');
		Route::get('/{chapter}/edit', 'ChapterController@edit')->Name('edit_chapter');
		Route::patch('/{chapter}', 'ChapterController@update')->Name('update_chapter');
		Route::delete('/{chapter}', 'ChapterController@destroy')->Name('delete_chapter');
		Route::get('/{chapter}/export', 'ChapterController@export')->Name('export_chapter');
		Route::get('/{chapter}/overview', 'ChapterController@overview')->Name('overview_chapter');
		Route::get('/{chapter}/{page?}', 'ChapterController@show')->Name('show_chapter');
	});
//End Chapter controller routes

Route::namespace('TagObjects\Tag')->group(function () {
	//Tag controller routes
		Route::prefix('tag')->group(function () {
			Route::get('/create', 'TagController@create')->Name('create_tag');
			Route::post('/', 'TagController@store')->Name('store_tag');
			Route::get('/{tag}/edit', 'TagController@edit')->Name('edit_tag');
			Route::patch('/{tag}', 'TagController@update')->Name('update_tag');
			Route::delete('/{tag}', 'TagController@destroy')->Name('delete_tag');
			Route::get('/', 'TagController@index')->Name('index_tag');
			Route::get('/{tag}', 'TagController@show')->Name('show_tag');
		});
	//End tag controller routes
	
	//Tag Alias controller routes
		Route::prefix('tag_alias')->group(function () {
			Route::get('/', 'TagAliasController@index')->Name('index_tag_alias');
			Route::post('/{tag}', 'TagAliasController@store')->Name('store_tag_alias');
			Route::delete('/{tagAlias}', 'TagAliasController@destroy')->Name('delete_tag_alias');
		});
	//End tag alias controller routes
});	

Route::namespace('TagObjects\Artist')->group(function () {
	//Artist controller routes
		Route::prefix('artist')->group(function () {
			Route::get('/create', 'ArtistController@create')->Name('create_artist');
			Route::post('/', 'ArtistController@store')->Name('store_artist');
			Route::get('/{artist}/edit', 'ArtistController@edit')->Name('edit_artist');
			Route::patch('/{artist}', 'ArtistController@update')->Name('update_artist');
			Route::delete('/{artist}', 'ArtistController@destroy')->Name('delete_artist');
			Route::get('/', 'ArtistController@index')->Name('index_artist');
			Route::get('/{artist}', 'ArtistController@show')->Name('show_artist');
		});
	//End artist controller routes
	
	//Artist alias controller routes			
		Route::prefix('artist_alias')->group(function () {
			Route::post('/{artist}', 'ArtistAliasController@store')->Name('store_artist_alias');
			Route::delete('/{artistAlias}', 'ArtistAliasController@destroy')->Name('delete_artist_alias');
			Route::get('/', 'ArtistAliasController@index')->Name('index_artist_alias');
		});
	//End artist alias controller routes
});

Route::namespace('TagObjects\Series')->group(function () {
	//Series controller routes
		Route::prefix('series')->group(function () {
			Route::get('/create', 'SeriesController@create')->Name('create_series');
			Route::post('/', 'SeriesController@store')->Name('store_series');
			Route::get('/{series}/edit', 'SeriesController@edit')->Name('edit_series');
			Route::patch('/{series}', 'SeriesController@update')->Name('update_series');
			Route::delete('/{series}', 'SeriesController@destroy')->Name('delete_series');
			Route::get('/', 'SeriesController@index')->Name('index_series');
			Route::get('/{series}', 'SeriesController@show')->Name('show_series');
		});
	//End series controller routes
	
	//Series alias controller routes
		Route::prefix('series_alias')->group(function () {
			Route::post('/{series}', 'SeriesAliasController@store')->Name('store_series_alias');
			Route::delete('/{seriesAlias}', 'SeriesAliasController@destroy')->Name('delete_series_alias');
			Route::get('/', 'SeriesAliasController@index')->Name('index_series_alias');
		});
	//End series alias controller routes
});

Route::namespace('TagObjects\Scanalator')->group(function () {
	//Scanalator controller routes
		Route::prefix('scanalator')->group(function () {
			Route::get('/create', 'ScanalatorController@create')->Name('create_scanalator');
			Route::post('/', 'ScanalatorController@store')->Name('store_scanalator');
			Route::get('/{scanalator}/edit', 'ScanalatorController@edit')->Name('edit_scanalator');
			Route::patch('/{scanalator}', 'ScanalatorController@update')->Name('update_scanalator');
			Route::delete('/{scanalator}', 'ScanalatorController@destroy')->Name('delete_scanalator');
			Route::get('/', 'ScanalatorController@index')->Name('index_scanalator');
			Route::get('/{scanalator}', 'ScanalatorController@show')->Name('show_scanalator');
		});
	//End scanalator controller routes
	
	//Scanalator alias controller routes
		Route::prefix('scanalator_alias')->group(function () {
			Route::post('/{scanalator}', 'ScanalatorAliasController@store')->Name('store_scanalator_alias');
			Route::delete('/{scanalatorAlias}', 'ScanalatorAliasController@destroy')->Name('delete_scanalator_alias');
			Route::get('/', 'ScanalatorAliasController@index')->Name('index_scanalator_alias');
		});
	//End scanalator alias controller routes
});

Route::namespace('TagObjects\Character')->group(function () {
	//Character controller routes	
		Route::prefix('character')->group(function () {
			Route::get('/create/{series?}', 'CharacterController@create')->Name('create_character');
			Route::post('/', 'CharacterController@store')->Name('store_character');
			Route::get('/{character}/edit', 'CharacterController@edit')->Name('edit_character');
			Route::patch('/{character}', 'CharacterController@update')->Name('update_character');
			Route::delete('/{character}', 'CharacterController@destroy')->Name('delete_character');
			Route::get('/', 'CharacterController@index')->Name('index_character');
			Route::get('/{character}', 'CharacterController@show')->Name('show_character');
		});
	//End Character controller routes
	
	//Character alias controller routes
		Route::prefix('character_alias')->group(function () {
			Route::post('/{character}', 'CharacterAliasController@store')->Name('store_character_alias');
			Route::delete('/{characterAlias}', 'CharacterAliasController@destroy')->Name('delete_character_alias');
			Route::get('/', 'CharacterAliasController@index')->Name('index_character_alias');
		});
	//End character alias controller routes
});

//Search controller routes
	Route::post('/search', 'SearchController@search')->Name('process_search');
//End search controller routes

Auth::routes();

//User dashboards
Route::namespace('User\User')->prefix('user')->group(function () {
	Route::get('/', 'UserDashboardController@main')->Name('user_dashboard_main');
	Route::get('/configuration', 'UserDashboardController@configuration')->Name('user_dashboard_configuration_main');
});

Route::namespace('Configuration')->prefix('user/configuration')->group(function () {
	//User pagination settings
		Route::group(['middleware' => 'permission:Edit Personal Pagination Settings'], function(){
			Route::get('/pagination', 'PaginationController@edit')->Name('user_dashboard_configuration_pagination');
			Route::patch('/pagination', 'PaginationController@update')->Name('user_update_configuration_pagination');
			Route::delete('/pagination', 'PaginationController@reset')->Name('user_reset_configuration_pagination');
		});
	//End user pagination settings
	
	//User rating restriction settings
		Route::group(['middleware' => 'permission:Edit Personal Rating Restriction Settings'], function(){
			Route::get('/rating_restriction', 'RatingRestrictionController@edit')->Name('user_dashboard_configuration_rating_restriction');
			Route::patch('/rating_restriction', 'RatingRestrictionController@update')->Name('user_update_configuration_rating_restriction');
			Route::delete('/rating_restriction', 'RatingRestrictionController@reset')->Name('user_reset_configuration_rating_restriction');
		});
	//End user rating restriction settings
	
	//User placeholder settings
		Route::group(['middleware' => 'permission:Edit Personal Placeholder Settings'], function(){
			Route::get('/placeholders', 'PlaceholderController@edit')->Name('user_dashboard_configuration_placeholders');
			Route::patch('/placeholders/', 'PlaceholderController@update')->Name('user_update_configuration_placeholders');
			Route::delete('/placeholders/', 'PlaceholderController@reset')->Name('user_reset_configuration_placeholders');
		});
	//End user placeholder settings
});

//Admin dashboard

Route::namespace('User\Admin')->prefix('admin')->group(function () {
	Route::get('/', 'AdminDashboardController@main')->Name('admin_dashboard_main');
	Route::get('/configuration', 'AdminDashboardController@configuration')->Name('admin_dashboard_configuration_main');
});

Route::namespace('Configuration')->prefix('admin/configuration')->group(function () {
	//Site pagination settings
		Route::group(['middleware' => 'permission:Edit Global Pagination Settings'], function(){
			Route::get('/pagination', 'PaginationController@edit')->Name('admin_dashboard_configuration_pagination');
			Route::patch('/pagination', 'PaginationController@update')->Name('admin_update_configuration_pagination');
		});
	//End site pagination settings
	
	//Site placeholder settings
		Route::group(['middleware' => 'permission:Edit Global Rating Restriction Settings'], function(){
			Route::get('/placeholders', 'PlaceholderController@edit')->Name('admin_dashboard_configuration_placeholders');
			Route::patch('/placeholders', 'PlaceholderController@update')->Name('admin_update_configuration_placeholders');
		});
	//End site placeholder settings

	//Site rating restriction settings
		Route::group(['middleware' => 'permission:Edit Global Placeholder Settings'], function(){	
			Route::get('/rating_restriction', 'RatingRestrictionController@edit')->Name('admin_dashboard_configuration_rating_restriction');
			Route::patch('/rating_restriction', 'RatingRestrictionController@update')->Name('admin_update_configuration_rating_restriction');
		});	
	//End site rating restriction settings
});

Route::namespace('RolesAndPermissions')->group(function () {
	//Permissions
		Route::prefix('user/permission')->group(function () {
			Route::get('/', 'PermissionsController@index')->Name('user_index_permission');
		});
		
		Route::prefix('admin/permission')->group(function () {
			Route::group(['middleware' => 'permission:Create Permission|Edit Permission|Delete Permission'], function(){	
				Route::get('/', 'PermissionsController@index')->Name('admin_index_permission');
			});	
			
			Route::group(['middleware' => 'permission:Create Permission'], function(){
				Route::get('/create', 'PermissionsController@create')->Name('admin_create_permission');
				Route::post('/', 'PermissionsController@store')->Name('admin_store_permission');
			});

			Route::group(['middleware' => 'permission:Edit Permission'], function(){
				Route::get('/{permission}/edit', 'PermissionsController@edit')->Name('admin_edit_permission');
				Route::patch('/{permission}', 'PermissionsController@update')->Name('admin_update_permission');
			});

			Route::group(['middleware' => 'permission:Delete Permission'], function(){
				Route::delete('/{permission}', 'PermissionsController@destroy')->Name('admin_delete_permission');
			});
		});
	//End Permissions

	//Roles
		Route::prefix('user/role')->group(function () {
			Route::get('/', 'RolesController@index')->Name('user_index_role');
			Route::get('/{role}', 'RolesController@show')->Name('user_show_role');
		});
		
		Route::prefix('admin/role')->group(function () {
			Route::group(['middleware' => 'permission:Create Role'], function(){
				Route::get('/create', 'RolesController@create')->Name('admin_create_role');
				Route::post('/', 'RolesController@store')->Name('admin_store_role');
			});
			
			Route::group(['middleware' => 'permission:Edit Role'], function(){
				Route::get('/{role}/edit', 'RolesController@edit')->Name('admin_edit_role');
				Route::patch('/{role}', 'RolesController@update')->Name('admin_update_role');
			});
				
			Route::group(['middleware' => 'permission:Delete Role'], function(){
				Route::delete('/{role}', 'RolesController@destroy')->Name('admin_delete_role');
			});
			
			Route::group(['middleware' => 'permission:Create Role|Edit Role|Delete Role'], function(){	
				Route::get('/', 'RolesController@index')->Name('admin_index_role');
				Route::get('/{role}', 'RolesController@show')->Name('admin_show_role');
			});
		});
	//End Roles
});

Route::namespace('User\Admin')->prefix('admin/user')->group(function () {	
	//User administration
		Route::get('/', 'AdminUserController@index')->Name('admin_index_user');
		Route::get('/{user}', 'AdminUserController@show')->Name('admin_show_user');
		
		Route::get('/{user}/rolesandpermissions/edit', 'UserRolesAndPermissionsController@edit')->Name('admin_edit_user_roles_and_permissions');
		Route::patch('/{user}/rolesandpermissions', 'UserRolesAndPermissionsController@update')->Name('admin_update_user_roles_and_permissions');	
	//End user administration
});

Route::namespace('Auth')->prefix('user/password')->group(function () {
	Route::get('/', 'UpdatePasswordController@edit')->Name('edit_password');
	Route::patch('/', 'UpdatePasswordController@update')->Name('update_password');
});

Route::namespace('User\User\Favourites\Collection')->prefix('user/favourites/collection')->group(function () {
	Route::post('/{collection}', 'CollectionFavouritesController@store')->Name('store_collection_favourite');
	Route::delete('/{collection}', 'CollectionFavouritesController@destroy')->Name('delete_collection_favourite');
	Route::get('/', 'CollectionFavouritesController@index')->Name('index_collection_favourite');
});

Route::namespace('User\User\Blacklists\Collection')->prefix('user/blacklists/collection')->group(function () {
	Route::post('/{collection}', 'CollectionBlacklistController@store')->Name('store_collection_blacklist');
	Route::delete('/{collection}', 'CollectionBlacklistController@destroy')->Name('delete_collection_blacklist');
	Route::get('/', 'CollectionBlacklistController@index')->Name('index_collection_blacklist');
});