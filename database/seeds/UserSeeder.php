<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		//Do the production seeding
		$user = new User;
		$user->name = 'admin';
		$user->email = 'admin@email.com';
		$user->password = bcrypt('password');
		$user->save();
    }
}