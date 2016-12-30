<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Language;

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
		$language->name = "English";
		$language->description = "The English language.";
		$language->url = "https://en.wikipedia.org/wiki/English_language";
		$language->created_by = $user->id;
		$language->updated_by = $user->id;
		$language->save();
		
		$language = new Language;
		$language->name = "Japanese";
		$language->description = "The Japanese language.";
		$language->url = "https://en.wikipedia.org/wiki/Japanese_language";
		$language->created_by = $user->id;
		$language->updated_by = $user->id;
		$language->save();
		
		$language = new Language;
		$language->name = "Chinese";
		$language->description = "The Chinese language.";
		$language->url = "https://en.wikipedia.org/wiki/Chinese_language";
		$language->created_by = $user->id;
		$language->updated_by = $user->id;
		$language->save();
		
		$language = new Language;
		$language->name = "French";
		$language->description = "The French language.";
		$language->url = "https://en.wikipedia.org/wiki/French_language";
		$language->created_by = $user->id;
		$language->updated_by = $user->id;
		$language->save();
		
		$language = new Language;
		$language->name = "Spanish";
		$language->description = "The Spanish language.";
		$language->url = "https://en.wikipedia.org/wiki/Spanish_language";
		$language->created_by = $user->id;
		$language->updated_by = $user->id;
		$language->save();
		
		$language = new Language;
		$language->name = "German";
		$language->description = "The German language.";
		$language->url = "https://en.wikipedia.org/wiki/German_language";
		$language->created_by = $user->id;
		$language->updated_by = $user->id;
		$language->save();
		
		$language = new Language;
		$language->name = "Russian";
		$language->description = "The Russian language.";
		$language->url = "https://en.wikipedia.org/wiki/Russian_language";
		$language->created_by = $user->id;
		$language->updated_by = $user->id;
		$language->save();
    }
}