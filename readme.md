# Manic

Manic is a reworking of a previous unreleased project of mine. It is built using the Laravel framework with various other components for functionality.

Manic is an open source project for hosting and sharing comics/manga/etc. It was inspired by various websites with a similar concept online. I was unable to find an open source project with a similar concept so I decided to create Manic in order to fill that open niche.

## Project Dependencies

* PHP >= 7.0
	* ImageMagick
	* Zip
* Composer

## Installation Instructions

1. With composer:

	```shell
	composer install
	```
2. Create and configure `.env`.
	* `.env.example` is given as an example. Feel free to copy and edit.
3. With php:
	```shell
	php artisan key:generate 
	```
4. Modify `database/seeds/UserSeeder.php` to use desired email address.
5. With php:
	```shell
	php artisan migrate --seed
	```
6. Create a soft link between the storage directory and the public directory with php:
	```shell
	php artisan storage:link
	```
7. Create an the following directory structure under the app/public directory ensuring the correct ownership.
	```
	app/public
	└── images
		├── full
		├── thumbs
		├── tmp
		└── export
			├── chapters
			├── volumes
			└── collections
	```
8. Set up a cron job to call the Laravel scheduler.
9. Copy the relevant directories (bootstrap, font awesome, jquery, jquery-ui) from the components directory to the relevant subdirectories (css, js) under the public directory.