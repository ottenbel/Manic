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
		self::SeedRoleRow("ddce79e6-8f67-4f18-89d2-af160f1552c5", "User", 0);
		self::SeedRoleRow("d0ff589b-b2a7-4402-88ae-a8b2d1e868bf", "Editor", 1);
		self::SeedRoleRow("8a5cdf00-75e9-406c-902c-594030ed0a2f", "Administrator", 3);
		self::SeedRoleRow("75f5c752-9e8d-4969-bb4b-0e0bac28d4b5", "Owner", 4);
    }
	
	private static function SeedRoleRow($id, $name, $priority)
	{
		$role = Role::where('id', '=', $id)->first();
		if ($role == null)
		{
			$role = new Role();
			$role->id = $id;
			$role->fill(['name' => $name, 'priority' => $priority]);
			$role->save();
		}
	}
}