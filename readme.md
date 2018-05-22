# Manic

Manic is a reworking of a previous unreleased project of mine. It is built using the Laravel framework with various other components for functionality.

Manic is an open source project for hosting and sharing comics/manga/etc. It was inspired by various websites with similar concepts online. I was unable to find an open source project that would allow someone to set up such a site from scratch so I decided to create Manic in order to fill that open niche.

## Project Dependencies

* PHP >= 7.1.3
	* ImageMagick
	* Zip
* Memcached
* Composer
* Python2
* NodeJS
	* Either Yarn or NPM

## Installation Instructions

1. Create and configure `.env`.
	* `.env.example` is given as an example. Feel free to copy and edit.
2. Modify `database/seeds/UserSeeder.php` to use desired email address and password.
3. With composer:

	```shell
	composer install
	```
4. With yarn:
	```shell
	yarn install
	yarn run dev
	```
5. Set up a cron job to call the Laravel scheduler.

## Sources
* [GitLab](https://gitlab.com/ottenbel/Manic) (Primary Repository)
* [GitHub](https://github.com/ottenbel/Manic) (Repository Mirror)