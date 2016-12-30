<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		//Do the production seeding
        $this->call(UserSeeder::class);		
		$this->call(StatusSeeder::class);
		$this->call(RatingSeeder::class);
		$this->call(LanguageSeeder::class);
    }
}
