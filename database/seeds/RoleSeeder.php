<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		//Do the production seeding 
		
		$role = new Role;
		$role->id = "ddce79e6-8f67-4f18-89d2-af160f1552c5";
		$role->name = "User";
		$role->priority = 0;
		$role->save();
		
		$role = new Role;
		$role->id = "d0ff589b-b2a7-4402-88ae-a8b2d1e868bf";
		$role->name = "Editor";
		$role->priority = 1;
		$role->save();
		
		$role = new Role;
		$role->id = "8a5cdf00-75e9-406c-902c-594030ed0a2f";
		$role->name = "Administrator";
		$role->priority = 3;
		$role->save();
		
		$role = new Role;
		$role->id = "75f5c752-9e8d-4969-bb4b-0e0bac28d4b5";
		$role->name = "Owner";
		$role->priority = 4;
		$role->save();
    }
}