<?php

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		if(Language::all()->count() == 0) //Only seed languages the first go round
		{
			//Do the production seeding		
			self::SeedLanguageRow("7284a880-f582-11e6-aaed-cfe39dc94866", "English", "The English language.", "https://en.wikipedia.org/wiki/English_language");
			self::SeedLanguageRow("7284dee0-f582-11e6-a912-63a614f95ab2", "Japanese", "The Japanese language.", "https://en.wikipedia.org/wiki/Japanese_language");
			self::SeedLanguageRow("72850f30-f582-11e6-a96b-5d62cdd2507a", "Chinese", "The Chinese language.", "https://en.wikipedia.org/wiki/Chinese_language");
			self::SeedLanguageRow("72853e40-f582-11e6-991b-af20f01b47a1", "French", "The French language.", "https://en.wikipedia.org/wiki/French_language");
			self::SeedLanguageRow("72856e10-f582-11e6-8b22-c1a32120a925", "Spanish", "The Spanish language.", "https://en.wikipedia.org/wiki/Spanish_language");
			self::SeedLanguageRow("7285ea40-f582-11e6-a71d-7ba51e8aff47", "German", "The German language.", "https://en.wikipedia.org/wiki/German_language");
			self::SeedLanguageRow("72863e90-f582-11e6-8f24-cf8bf31fb4d2", "Russian", "The Russian language.", "https://en.wikipedia.org/wiki/Russian_language");
		}
    }
	
	private static function SeedLanguageRow($id, $name, $description, $url)
	{		
		$language = Language::where('id', '=', $id)->first();
		if ($language == null)
		{
			$language = new Language();
			$language->id = $id;
			$language->fill(["name" => $name, "description" => $description, "url" => $url]);
			$language->save();
		}
	}
}