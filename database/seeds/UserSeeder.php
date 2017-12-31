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
		$user = User::first();
		if ($user == null)
		{
			$user = new User;
			$user->name = 'admin';
			$user->email = 'admin@email.com';
			$user->password = bcrypt('password');
			$user->save();
			$user->assignRole('user');
			$user->assignRole('editor');
			$user->assignRole('administrator');
			$user->assignRole('owner');
		}
    }
}