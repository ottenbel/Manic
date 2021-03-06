<?php

use Illuminate\Database\Seeder;
use App\Models\User;

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
		$this->call(RolesAndPermissionsSeeder::class);
        $this->call(UserSeeder::class);		
		$this->call(StatusSeeder::class);
		$this->call(RatingSeeder::class);
		$this->call(LanguageSeeder::class);
		$this->call(ConfigurationSeeder::class);
    }
}
