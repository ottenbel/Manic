# Project Description

Manic is a reworking of a previous unreleased project of mine. It is built using the Laravel framework with verious other components for functionality.

Manic is an open source project for hosting and sharing comics/manga/etc.  It was inspired by seeing various websites with a similar concept online.  I was hoping to find some open source software so I could run a similar site locally.  I was unable to find a site which provided its source code so I decided to build my own and share it with the world.

# Installation Instructions

1. Run composer install
2. Run php artisan key:generate
3. Copy .env.example to .env
	1. Configure .env file to match your environment
4. Modify the UserSeeder.php file to use desired email address instead of the default admin@email.com address
5. Run php artisan migrate --seed
6. Create a soft link between the storage directory and the public directory ln -s /path/to/laravel/storage/app/public /path/to/laravel/public/storage (http://stackoverflow.com/questions/30191330/laravel-5-how-to-access-image-uploaded-in-storage-within-view/30192351#30192351)
7. Copy the relevant directories (bootstrap, font awesome, jquery, jquery-ui) from the components directory to the relevant subdirectories (css, js) under the public directory
8. Sign in to the newly created administrator account and change your password