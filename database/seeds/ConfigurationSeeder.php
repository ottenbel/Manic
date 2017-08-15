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
		
		$user = User::first();
		SeedingHelper::SeedPaginationTable();
		SeedingHelper::SeedPaginationTable($user);
    }
}