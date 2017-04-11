<?php

use Illuminate\Database\Seeder;
use App\Models\User;
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
		//Do the production seeding
		$user = User::first();
		
		$language = new Language;
		$language->id = "7284a880-f582-11e6-aaed-cfe39dc94866";
		$language->name = "English";
		$language->description = "The English language.";
		$language->url = "https://en.wikipedia.org/wiki/English_language";
		$language->created_by = $user->id;
		$language->updated_by = $user->id;
		$language->save();
		
		$language = new Language;
		$language->id = "7284dee0-f582-11e6-a912-63a614f95ab2";
		$language->name = "Japanese";
		$language->description = "The Japanese language.";
		$language->url = "https://en.wikipedia.org/wiki/Japanese_language";
		$language->created_by = $user->id;
		$language->updated_by = $user->id;
		$language->save();
		
		$language = new Language;
		$language->id = "72850f30-f582-11e6-a96b-5d62cdd2507a";
		$language->name = "Chinese";
		$language->description = "The Chinese language.";
		$language->url = "https://en.wikipedia.org/wiki/Chinese_language";
		$language->created_by = $user->id;
		$language->updated_by = $user->id;
		$language->save();
		
		$language = new Language;
		$language->id = "72853e40-f582-11e6-991b-af20f01b47a1";
		$language->name = "French";
		$language->description = "The French language.";
		$language->url = "https://en.wikipedia.org/wiki/French_language";
		$language->created_by = $user->id;
		$language->updated_by = $user->id;
		$language->save();
		
		$language = new Language;
		$language->id = "72856e10-f582-11e6-8b22-c1a32120a925";
		$language->name = "Spanish";
		$language->description = "The Spanish language.";
		$language->url = "https://en.wikipedia.org/wiki/Spanish_language";
		$language->created_by = $user->id;
		$language->updated_by = $user->id;
		$language->save();
		
		$language = new Language;
		$language->id = "7285ea40-f582-11e6-a71d-7ba51e8aff47";
		$language->name = "German";
		$language->description = "The German language.";
		$language->url = "https://en.wikipedia.org/wiki/German_language";
		$language->created_by = $user->id;
		$language->updated_by = $user->id;
		$language->save();
		
		$language = new Language;
		$language->id = "72863e90-f582-11e6-8f24-cf8bf31fb4d2";
		$language->name = "Russian";
		$language->description = "The Russian language.";
		$language->url = "https://en.wikipedia.org/wiki/Russian_language";
		$language->created_by = $user->id;
		$language->updated_by = $user->id;
		$language->save();
    }
}