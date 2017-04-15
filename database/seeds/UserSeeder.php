<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$userRole = Config::get('constants.roles.owner');
		//Do the production seeding
		$role = Role::where('id', '=', $userRole)->first();
		
		$user = new User;
		$user->name = 'admin';
		$user->email = 'admin@email.com';
		$user->password = bcrypt('password');
		$user->api_token = str_random(60);
		$user->role_id = $role->id;
		$user->save();
    }
}