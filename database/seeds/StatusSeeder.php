<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Status;

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
		$status->id = "727fbd10-f582-11e6-8d90-b7ce614a8bf5";
		$status->name = "In Progress";
		$status->priority = 0;
		$status->created_by = $user->id;
		$status->updated_by = $user->id;
		$status->save();
		
		$status = new Status;
		$status->id = "728126a0-f582-11e6-92e2-958261649644";
		$status->name = "Complete";
		$status->priority = 1;
		$status->created_by = $user->id;
		$status->updated_by = $user->id;
		$status->save();
		
		$status = new Status;
		$status->id = "728198f0-f582-11e6-bd1b-a7750535a415";
		$status->name = "Cancelled";
		$status->priority = 2;
		$status->created_by = $user->id;
		$status->updated_by = $user->id;
		$status->save();
		
		$status = new Status;
		$status->id = "7281e0b0-f582-11e6-a14d-a1b50c45303f";
		$status->name = "Hiatus";
		$status->priority = 3;
		$status->created_by = $user->id;
		$status->updated_by = $user->id;
		$status->save();
    }
}