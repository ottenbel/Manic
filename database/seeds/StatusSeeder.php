<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Status;

class StatusSeeder extends Seeder
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
		
		$status = new Status;
		$status->name = "In Progress";
		$status->priority = 0;
		$status->created_by = $user->id;
		$status->updated_by = $user->id;
		$status->save();
		
		$status = new Status;
		$status->name = "Complete";
		$status->priority = 1;
		$status->created_by = $user->id;
		$status->updated_by = $user->id;
		$status->save();
		
		$status = new Status;
		$status->name = "Cancelled";
		$status->priority = 2;
		$status->created_by = $user->id;
		$status->updated_by = $user->id;
		$status->save();
		
		$status = new Status;
		$status->name = "Hiatus";
		$status->priority = 3;
		$status->created_by = $user->id;
		$status->updated_by = $user->id;
		$status->save();
    }
}