<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rating;

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
		$rating->id = "72829560-f582-11e6-ab42-cfcb29f46a55";
		$rating->name = "Unrated";
		$rating->priority = 0;
		$rating->created_by = $user->id;
		$rating->updated_by = $user->id;
		$rating->save();
		
		$rating = new Rating;
		$rating->id = "7282fb00-f582-11e6-8806-15d9ed6122ac";
		$rating->name = "General";
		$rating->priority = 1;
		$rating->created_by = $user->id;
		$rating->updated_by = $user->id;
		$rating->save();
		
		$rating = new Rating;
		$rating->id = "728395d0-f582-11e6-8644-23d69886f960";
		$rating->name = "Teen";
		$rating->priority = 2;
		$rating->created_by = $user->id;
		$rating->updated_by = $user->id;
		$rating->save();
		
		$rating = new Rating;
		$rating->id = "7283f6e0-f582-11e6-b932-f75810f658cc";
		$rating->name = "Mature";
		$rating->priority = 3;
		$rating->created_by = $user->id;
		$rating->updated_by = $user->id;
		$rating->save();
		
		$rating = new Rating;
		$rating->id = "72842560-f582-11e6-bcd0-9b5d4efc44d8";
		$rating->name = "Explicit";
		$rating->priority = 4;
		$rating->created_by = $user->id;
		$rating->updated_by = $user->id;
		$rating->save();
    }
}