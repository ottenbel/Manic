<?php

use Illuminate\Database\Seeder;
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
		self::SeedRatingRow("72829560-f582-11e6-ab42-cfcb29f46a55", "Unrated", 0);
		self::SeedRatingRow("7282fb00-f582-11e6-8806-15d9ed6122ac", "General", 1);
		self::SeedRatingRow("728395d0-f582-11e6-8644-23d69886f960", "Teen", 2);
		self::SeedRatingRow("7283f6e0-f582-11e6-b932-f75810f658cc", "Mature", 3);
		self::SeedRatingRow("72842560-f582-11e6-bcd0-9b5d4efc44d8", "Explicit", 4);
    }
	
	private static function SeedRatingRow($id, $name, $priority)
	{
		$rating = Rating::where('id', '=', $id)->first();
		if ($rating == null)
		{
			$rating = new Rating();
			$rating->id = $id;
			$rating->fill(['name' => $name, 'priority' => $priority]);
			$rating->save();
		}
	}
}