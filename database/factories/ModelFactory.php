<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Artist::class, function (Faker\Generator $faker){
	return [
	'name'=> $faker->word,
	'description'=> $faker->paragraph,
	'url'=> $faker->url,
	'created_by' => App\User::all()->random()->id,
	'updated_by'=> App\User::all()->random()->id];
});

$factory->define(App\Language::class, function (Faker\Generator $faker){
	return [
	'name'=> $faker->word,
	'description'=> $faker->paragraph,
	'url'=> $faker->url,
	'created_by' => App\User::all()->random()->id,
	'updated_by'=> App\User::all()->random()->id];
});

$factory->define(App\Scanalator::class, function (Faker\Generator $faker){
	return [
	'name'=> $faker->word,
	'description'=> $faker->paragraph,
	'url'=> $faker->url,
	'created_by' => App\User::all()->random()->id,
	'updated_by'=> App\User::all()->random()->id];
});

$factory->define(App\Series::class, function (Faker\Generator $faker){
	return [
	'name'=> $faker->word,
	'description'=> $faker->paragraph,
	'url'=> $faker->url,
	'created_by' => App\User::all()->random()->id,
	'updated_by'=> App\User::all()->random()->id];
});

$factory->define(App\Tag::class, function (Faker\Generator $faker){
	return [
	'name'=> $faker->word,
	'description'=> $faker->paragraph,
	'url'=> $faker->url,
	'created_by' => App\User::all()->random()->id,
	'updated_by'=> App\User::all()->random()->id];
});

$factory->define(App\Rating::class, function (Faker\Generator $faker){
	return [[
	'name' => 'Unrated', 
	'priority'=> 0,
	'created_by' => App\User::all()->random()->id,
	'updated_by'=> App\User::all()->random()->id],
	['name' => 'General', 
	'priority'=> 1,
	'created_by' => App\User::all()->random()->id,
	'updated_by'=> App\User::all()->random()->id],
	['name' => 'Teen', 
	'priority'=> 2,
	'created_by' => App\User::all()->random()->id,
	'updated_by'=> App\User::all()->random()->id],
	['name' => 'Mature', 
	'priority'=> 3,
	'created_by' => App\User::all()->random()->id,
	'updated_by'=> App\User::all()->random()->id],
	['name' => 'Adult', 
	'priority'=> 4,
	'created_by' => App\User::all()->random()->id,
	'updated_by'=> App\User::all()->random()->id]
	];
});

$factory->define(App\Status::class, function (Faker\Generator $faker){
	return [
	['name'=> 'In Progress',
	'priority'=>0,
	'created_by' => App\User::all()->random()->id,
	'updated_by'=> App\User::all()->random()->id],
	['name'=> 'Complete',
	'priority'=>1,
	'created_by' => App\User::all()->random()->id,
	'updated_by'=> App\User::all()->random()->id],
	['name'=> 'Cancelled',
	'priority'=>2,
	'created_by' => App\User::all()->random()->id,
	'updated_by'=> App\User::all()->random()->id],
	['name'=> 'Hiatus',
	'priority'=>3,
	'created_by' => App\User::all()->random()->id,
	'updated_by'=> App\User::all()->random()->id]
	];
});

$factory->define(App\Collection::class, function (Faker\Generator $faker){
	return [
	'name'=> $faker->sentence,
	'description' => $faker->paragraph,
	'canonical' => $faker->boolean,
	'language' => App\Language::all()->random()->id,
	'created_by' => App\User::all()->random()->id,
	'updated_by'=> App\User::all()->random()->id
	];
});

$factory->define(App\Volume::class, function (Faker\Generator $faker){
	$collection = App\Collection::all()->random();
	return [
	['collection_id' => $collection->id,
	'number' => 1,
	'name' => $faker->sentence,
	'created_by' => App\User::all()->random()->id,
	'updated_by'=> App\User::all()->random()->id],

	['collection_id' => $collection->id,
	'number' => 2,
	'name' => $faker->sentence,
	'created_by' => App\User::all()->random()->id,
	'updated_by'=> App\User::all()->random()->id],
	
	['collection_id' => $collection->id,
	'number' => 3,
	'name' => $faker->sentence,
	'created_by' => App\User::all()->random()->id,
	'updated_by'=> App\User::all()->random()->id]];
	});
	
$factory->define(App\Chapter::class, function (Faker\Generator $faker){
	$volume  = App\Volume::all()->random();
	return [
	['volume_id' => $volume->id,
	'number' => 1,
	'name' => $faker->sentence,
	'source' => $faker->url,
	'created_by' => App\User::all()->random()->id,
	'updated_by'=> App\User::all()->random()->id],
	['volume_id' => $volume->id,
	'number' => 2,
	'name' => $faker->sentence,
	'source' => $faker->url,
	'created_by' => App\User::all()->random()->id,
	'updated_by'=> App\User::all()->random()->id],
	['volume_id' => $volume->id,
	'number' => 3,
	'name' => $faker->sentence,
	'source' => $faker->url,
	'created_by' => App\User::all()->random()->id,
	'updated_by'=> App\User::all()->random()->id]
	];
});