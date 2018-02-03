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

//Artist controller routes
	Route::group(['middleware' => ['auth', 'permission:Create Artist']], function(){
		Route::get('/artist/create', 'TagObjects\Artist\ArtistController@create')->Name('create_artist');
		Route::post('/artist', 'TagObjects\Artist\ArtistController@store')->Name('store_artist');
	});

	Route::group(['middleware' => ['auth', 'permission:Edit Artist']], function(){
		Route::get('/artist/{artist}/edit', 'TagObjects\Artist\ArtistController@edit')->Name('edit_artist');
		Route::patch('/artist/{artist}', 'TagObjects\Artist\ArtistController@update')->Name('update_artist');
	});

	Route::group(['middleware' => ['auth', 'permission:Delete Artist']], function(){
		Route::delete('/artist/{artist}', 'TagObjects\Artist\ArtistController@destroy')->Name('delete_artist');
	});

	Route::get('/artist', 'TagObjects\Artist\ArtistController@index')->Name('index_artist');
	Route::get('/artist/{artist}', 'TagObjects\Artist\ArtistController@show')->Name('show_artist');

	//Artist alias controller routes
		Route::group(['middleware' => ['auth', 'permission:Create Personal Artist Alias|Create Global Artist Alias']], function(){
			Route::post('/artist_alias/{artist}', 'TagObjects\Artist\ArtistAliasController@store')->Name('store_artist_alias');
		});

		Route::group(['middleware' => ['auth', 'permission:Delete Personal Artist Alias|Delete Global Artist Alias']], function(){
			Route::delete('/artist_alias/{artistAlias}', 'TagObjects\Artist\ArtistAliasController@destroy')->Name('delete_artist_alias');
		});
			
		Route::get('/artist_alias', 'TagObjects\Artist\ArtistAliasController@index')->Name('index_artist_alias');
	//End artist alias controller routes
//End artist controller routes

//Series controller routes
	Route::group(['middleware' => ['auth', 'permission:Create Series']], function(){
		Route::get('/series/create', 'TagObjects\Series\SeriesController@create')->Name('create_series');
		Route::post('/series', 'TagObjects\Series\SeriesController@store')->Name('store_series');
	});
	
	Route::group(['middleware' => ['auth', 'permission:Edit Series']], function(){
		Route::get('/series/{series}/edit', 'TagObjects\Series\SeriesController@edit')->Name('edit_series');
		Route::patch('/series/{series}', 'TagObjects\Series\SeriesController@update')->Name('update_series');
	});
	
	Route::group(['middleware' => ['auth', 'permission:Delete Series']], function(){
		Route::delete('/series/{series}', 'TagObjects\Series\SeriesController@destroy')->Name('delete_series');
	});
	
	Route::get('/series', 'TagObjects\Series\SeriesController@index')->Name('index_series');
	Route::get('/series/{series}', 'TagObjects\Series\SeriesController@show')->Name('show_series');

	//Series alias controller routes
	Route::group(['middleware' => ['auth', 'permission:Create Personal Series Alias|Create Global Series Alias']], function(){
		Route::post('/series_alias/{series}', 'TagObjects\Series\SeriesAliasController@store')->Name('store_series_alias');
	});

	Route::group(['middleware' => ['auth', 'permission:Delete Personal Series Alias|Delete Global Series Alias']], function(){
		Route::delete('/series_alias/{seriesAlias}', 'TagObjects\Series\SeriesAliasController@destroy')->Name('delete_series_alias');
	});
	
	Route::get('/series_alias', 'TagObjects\Series\SeriesAliasController@index')->Name('index_series_alias');
	//End series alias controller routes
//End series controller routes

//Scanalator controller routes
	Route::group(['middleware' => ['auth', 'permission:Create Scanalator']], function(){
		Route::get('/scanalator/create', 'TagObjects\Scanalator\ScanalatorController@create')->Name('create_scanalator');
		Route::post('/scanalator', 'TagObjects\Scanalator\ScanalatorController@store')->Name('store_scanalator');
	});

	Route::group(['middleware' => ['auth', 'permission:Edit Scanalator']], function(){
		Route::get('/scanalator/{scanalator}/edit', 'TagObjects\Scanalator\ScanalatorController@edit')->Name('edit_scanalator');
		Route::patch('/scanalator/{scanalator}', 'TagObjects\Scanalator\ScanalatorController@update')->Name('update_scanalator');
	});

	Route::group(['middleware' => ['auth', 'permission:Delete Scanalator']], function(){
		Route::delete('/scanalator/{scanalator}', 'TagObjects\Scanalator\ScanalatorController@destroy')->Name('delete_scanalator');
	});
		
	Route::get('/scanalator', 'TagObjects\Scanalator\ScanalatorController@index')->Name('index_scanalator');
	Route::get('/scanalator/{scanalator}', 'TagObjects\Scanalator\ScanalatorController@show')->Name('show_scanalator');

	//Scanalator alias controller routes
	Route::group(['middleware' => ['auth', 'permission:Create Personal Scanalator Alias|Create Global Scanalator Alias']], function(){
		Route::post('/scanalator_alias/{scanalator}', 'TagObjects\Scanalator\ScanalatorAliasController@store')->Name('store_scanalator_alias');
	});
	
	Route::group(['middleware' => ['auth', 'permission:Delete Personal Scanalator Alias|Delete Global Scanalator Alias']], function(){
		Route::delete('/scanalator_alias/{scanalatorAlias}', 'TagObjects\Scanalator\ScanalatorAliasController@destroy')->Name('delete_scanalator_alias');
	});

	Route::get('/scanalator_alias', 'TagObjects\Scanalator\ScanalatorAliasController@index')->Name('index_scanalator_alias');
	//End scanalator alias controller routes
//End scanalator controller routes

//Character controller routes
	Route::group(['middleware' => ['auth', 'permission:Create Character']], function(){
		Route::get('/character/create/{series?}', 'TagObjects\Character\CharacterController@create')->Name('create_character');
		Route::post('/character', 'TagObjects\Character\CharacterController@store')->Name('store_character');
	});
	
	Route::group(['middleware' => ['auth', 'permission:Edit Character']], function(){
		Route::get('/character/{character}/edit', 'TagObjects\Character\CharacterController@edit')->Name('edit_character');
		Route::patch('/character/{character}', 'TagObjects\Character\CharacterController@update')->Name('update_character');
	});
	
	Route::group(['middleware' => ['auth', 'permission:Delete Character']], function(){
		Route::delete('/character/{character}', 'TagObjects\Character\CharacterController@destroy')->Name('delete_character');
	});
	
	Route::get('/character', 'TagObjects\Character\CharacterController@index')->Name('index_character');
	Route::get('/character/{character}', 'TagObjects\Character\CharacterController@show')->Name('show_character');

	//Character alias controller routes
	Route::group(['middleware' => ['auth', 'permission:Create Personal Character Alias|Create Global Character Alias']], function(){
		Route::post('/character_alias/{character}', 'TagObjects\Character\CharacterAliasController@store')->Name('store_character_alias');
	});
	
	Route::group(['middleware' => ['auth', 'permission:Delete Personal Character Alias|Delete Global Character Alias']], function(){
		Route::delete('/character_alias/{characterAlias}', 'TagObjects\Character\CharacterAliasController@destroy')->Name('delete_character_alias');
	});	
	
	Route::get('/character_alias', 'TagObjects\Character\CharacterAliasController@index')->Name('index_character_alias');
	//End character alias controller routes
//End Character controller routes

//Search controller routes
	Route::post('/search', 'SearchController@search')->Name('process_search');
//End search controller routes

Auth::routes();

//User dashboards
Route::group(['middleware' => 'auth'], function(){
	Route::get('/user', 'User\User\UserDashboardController@main')->Name('user_dashboard_main');
	Route::get('/user/configuration', 'User\User\UserDashboardController@configuration')->Name('user_dashboard_configuration_main');
});

Route::group(['middleware' => ['auth', 'permission:Edit Personal Pagination Settings']], function(){
	//User pagination settings
	Route::get('/user/configuration/pagination', 'Configuration\PaginationController@edit')->Name('user_dashboard_configuration_pagination');
	Route::patch('/user/configuration/pagination/', 'Configuration\PaginationController@update')->Name('user_update_configuration_pagination');
	Route::delete('/user/configuration/pagination/', 'Configuration\PaginationController@reset')->Name('user_reset_configuration_pagination');
});

Route::group(['middleware' => ['auth', 'permission:Edit Personal Rating Restriction Settings']], function(){
	//User rating restriction settings
	Route::get('/user/configuration/rating_restriction', 'Configuration\RatingRestrictionController@edit')->Name('user_dashboard_configuration_rating_restriction');
	Route::patch('/user/configuration/rating_restriction/', 'Configuration\RatingRestrictionController@update')->Name('user_update_configuration_rating_restriction');
	Route::delete('/user/configuration/rating_restriction/', 'Configuration\RatingRestrictionController@reset')->Name('user_reset_configuration_rating_restriction');
});

Route::group(['middleware' => ['auth', 'permission:Edit Personal Placeholder Settings']], function(){
	//User placeholder settings
	Route::get('/user/configuration/placeholders', 'Configuration\PlaceholderController@edit')->Name('user_dashboard_configuration_placeholders');
	Route::patch('/user/configuration/placeholders/', 'Configuration\PlaceholderController@update')->Name('user_update_configuration_placeholders');
	Route::delete('/user/configuration/placeholders/', 'Configuration\PlaceholderController@reset')->Name('user_reset_configuration_placeholders');
});

//Admin dashboard
Route::group(['middleware' => ['auth', 'permission:Edit Global Pagination Settings|Edit Global Placeholder Settings|Edit Global Rating Restriction Settings']], function(){
	Route::get('/admin', 'User\Admin\AdminDashboardController@main')->Name('admin_dashboard_main');
	Route::get('/admin/configuration', 'User\Admin\AdminDashboardController@configuration')->Name('admin_dashboard_configuration_main');
});

Route::group(['middleware' => ['auth', 'permission:Edit Global Pagination Settings']], function(){
	//Site pagination settings
	Route::get('/admin/configuration/pagination', 'Configuration\PaginationController@edit')->Name('admin_dashboard_configuration_pagination');
	Route::patch('/admin/configuration/pagination/', 'Configuration\PaginationController@update')->Name('admin_update_configuration_pagination');
});
	
Route::group(['middleware' => ['auth', 'permission:Edit Global Rating Restriction Settings']], function(){
	//Site placeholder settings
	Route::get('/admin/configuration/placeholders', 'Configuration\PlaceholderController@edit')->Name('admin_dashboard_configuration_placeholders');
	Route::patch('/admin/configuration/placeholders/', 'Configuration\PlaceholderController@update')->Name('admin_update_configuration_placeholders');
});

Route::group(['middleware' => ['auth', 'permission:Edit Global Placeholder Settings']], function(){	
	//Site rating restriction settings
	Route::get('/admin/configuration/rating_restriction', 'Configuration\RatingRestrictionController@edit')->Name('admin_dashboard_configuration_rating_restriction');
	Route::patch('/admin/configuration/rating_restriction/', 'Configuration\RatingRestrictionController@update')->Name('admin_update_configuration_rating_restriction');
});	

//Permissions
	Route::group(['middleware' => ['auth', 'permission:Create Permission|Edit Permission|Delete Permission']], function(){	
		Route::get('/admin/permission', 'RolesAndPermissions\PermissionsController@index')->Name('admin_index_permission');
	});	

	Route::group(['middleware' => 'auth'], function(){
		Route::get('/user/permission', 'RolesAndPermissions\PermissionsController@index')->Name('user_index_permission');
	});
	
	Route::group(['middleware' => ['auth', 'permission:Create Permission']], function(){
		Route::get('/admin/permission/create', 'RolesAndPermissions\PermissionsController@create')->Name('admin_create_permission');
		Route::post('/admin/permission', 'RolesAndPermissions\PermissionsController@store')->Name('admin_store_permission');
	});

	Route::group(['middleware' => ['auth', 'permission:Edit Permission']], function(){
		Route::get('/admin/permission/{permission}/edit', 'RolesAndPermissions\PermissionsController@edit')->Name('admin_edit_permission');
		Route::patch('/admin/permission/{permission}', 'RolesAndPermissions\PermissionsController@update')->Name('admin_update_permission');
	});

	Route::group(['middleware' => ['auth', 'permission:Delete Permission']], function(){
		Route::delete('/admin/permission/{permission}', 'RolesAndPermissions\PermissionsController@destroy')->Name('admin_delete_permission');
	});
//End Permissions

//Roles
	Route::group(['middleware' => ['auth', 'permission:Create Role']], function(){
		Route::get('/admin/role/create', 'RolesAndPermissions\RolesController@create')->Name('admin_create_role');
		Route::post('/admin/role', 'RolesAndPermissions\RolesController@store')->Name('admin_store_role');
	});
	
	Route::group(['middleware' => ['auth', 'permission:Edit Role']], function(){
		Route::get('/admin/role/{role}/edit', 'RolesAndPermissions\RolesController@edit')->Name('admin_edit_role');
		Route::patch('/admin/role/{role}', 'RolesAndPermissions\RolesController@update')->Name('admin_update_role');
	});
		
	Route::group(['middleware' => ['auth', 'permission:Delete Role']], function(){
		Route::delete('/admin/role/{role}', 'RolesAndPermissions\RolesController@destroy')->Name('admin_delete_role');
	});
	
	Route::group(['middleware' => ['auth', 'permission:Create Role|Edit Role|Delete Role']], function(){	
		Route::get('/admin/role', 'RolesAndPermissions\RolesController@index')->Name('admin_index_role');
		Route::get('/admin/role/{role}', 'RolesAndPermissions\RolesController@show')->Name('admin_show_role');
	});
	
	Route::group(['middleware' => 'auth', ], function(){
		Route::get('/user/role', 'RolesAndPermissions\RolesController@index')->Name('user_index_role');
		Route::get('/user/role/{role}', 'RolesAndPermissions\RolesController@show')->Name('user_show_role');
	});
	
	Route::get('/admin/user', 'User\Admin\AdminUserController@index')->Name('admin_index_user');
	Route::get('/admin/user/{user}', 'User\Admin\AdminUserController@show')->Name('admin_show_user');
	
	Route::get('/admin/user/{user}/rolesandpermissions/edit', 'User\Admin\UserRolesAndPermissionsController@edit')->Name('admin_edit_user_roles_and_permissions');
	Route::patch('/admin/user/{user}/rolesandpermissions', 'User\Admin\UserRolesAndPermissionsController@update')->Name('admin_update_user_roles_and_permissions');
	
	
//End Roles