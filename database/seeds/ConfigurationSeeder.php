<?php

use Illuminate\Database\Seeder;
use App\Helpers\SeedingHelper;
use App\Models\User;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		//Do the production seeding
		
		$users = User::all();
		//Seed the site configuration defaults
		SeedingHelper::SeedPaginationTable();
		SeedingHelper::SeedRatingRestrictionTable();
		SeedingHelper::SeedPlaceholderTable();
		
		//Seed the user configuration
		foreach ($users as $user)
		{
			SeedingHelper::SeedPaginationTable($user);
			SeedingHelper::SeedRatingRestrictionTable($user);
			SeedingHelper::SeedPlaceholderTable($user);
		}
    }
}