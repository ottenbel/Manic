<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Rating;

class RatingSeeder extends Seeder
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
		
		$rating = new Rating;
		$rating->name = "Unrated";
		$rating->priority = 0;
		$rating->created_by = $user->id;
		$rating->updated_by = $user->id;
		$rating->save();
		
		$rating = new Rating;
		$rating->name = "General";
		$rating->priority = 1;
		$rating->created_by = $user->id;
		$rating->updated_by = $user->id;
		$rating->save();
		
		$rating = new Rating;
		$rating->name = "Teen";
		$rating->priority = 2;
		$rating->created_by = $user->id;
		$rating->updated_by = $user->id;
		$rating->save();
		
		$rating = new Rating;
		$rating->name = "Mature";
		$rating->priority = 3;
		$rating->created_by = $user->id;
		$rating->updated_by = $user->id;
		$rating->save();
		
		$rating = new Rating;
		$rating->name = "Explicit";
		$rating->priority = 4;
		$rating->created_by = $user->id;
		$rating->updated_by = $user->id;
		$rating->save();
    }
}