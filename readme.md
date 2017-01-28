# Project Description

Manic is a reworking/improvement of a previous unreleased project called Manga-Nest built using the Laravel framework.

Manic is an open source website for hosting and sharing manga.  It was inspired by seeing various sites with a similar concept around the internet.  As I was unable to find a site which provided it's source code so that I could run it on my own machine I decided to build my own.

# Installation Instructions

1. Run composer install
2. Run php artisan key:generate
3. Copy .env.example to .env
3a. Configure .env file to match your environment
4. Modify the UserSeeder.php file to use desired email address instead of the default admin@email.com address
5. Run php artisan migrate --seed
6. Create a soft link between the storage directory and the public directory ln -s /path/to/laravel/storage/app/public /path/to/laravel/public/storage (http://stackoverflow.com/questions/30191330/laravel-5-how-to-access-image-uploaded-in-storage-within-view/30192351#30192351)
7. Sign in to the newly created administrator account and change your password