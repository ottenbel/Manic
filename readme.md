# Project Description

Manic is a reworking of a previous unreleased project of mine. It is built using the Laravel framework with various other components for functionality.

Manic is an open source project for hosting and sharing comics/manga/etc. It was inspired by various websites with a similar concept online. I was unable to find an open source project with a similar concept so I decided to create Manic in order to fill that open niche.

# Installation Instructions

1. Run composer install
2. Run php artisan key:generate
3. Copy .env.example to .env
	1. Configure .env file to match your environment
4. Modify the UserSeeder.php file to use desired email address instead of the default admin@email.com address
5. Run php artisan migrate --seed
6. Create a soft link between the storage directory and the public directory by running the following command. 
	1. php artisan storage:link
7. Create an images directory under the app/public directory. Set the owner and group to www-data
	1. Create a directory named full under the app/public/images directory. Set the owner and group to www-data.
	2. Create a directory named thumb under the app/public/images directory. Set the owner and group to www-data. 
8. Set up a cron job to call the Laravel scheduler.
9. Copy the relevant directories (bootstrap, font awesome, jquery, jquery-ui) from the components directory to the relevant subdirectories (css, js) under the public directory
10. Sign in to the newly created administrator account and change your password