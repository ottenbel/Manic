<?php

namespace App\Http\Controllers\Configuration;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Route;
use Auth;
use Input;
use ConfigurationLookupHelper;
use App\Models\Configuration\ConfigurationPlaceholder;

class PlaceholderController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request)
    {
		$placeholderValues = null;
		//Authentication check
		if (Route::is('user_dashboard_configuration_placeholders'))
		{ 
			$this->authorize([ConfigurationPlaceholder::class, false]); 
			$placeholderValues = ConfigurationPlaceholder::where('user_id', '=', Auth::user()->id)->orderBy('priority')->get();
		}
		else if (Route::is('admin_dashboard_configuration_placeholders'))
		{ 
			$this->authorize([ConfigurationPlaceholder::class, true]); 
			$placeholderValues = ConfigurationPlaceholder::where('user_id', '=', null)->orderBy('priority')->get();
		}
		
		$artists = $placeholderValues->filter(function ($item) {return false !== stristr($item->key, 'artist');});
		$characters = $placeholderValues->filter(function ($item) {return false !== stristr($item->key, 'character');});
		$scanalators = $placeholderValues->filter(function ($item) {return false !== stristr($item->key, 'scanalator');});
		$series = $placeholderValues->filter(function ($item) {return false !== stristr($item->key, 'series');});
		$tags = $placeholderValues->filter(function ($item) {return false !== stristr($item->key, 'tag');});
		$collections = $placeholderValues->filter(function ($item) {return false !== stristr($item->key, 'collection');});
		$volumes = $placeholderValues->filter(function ($item) {return false !== stristr($item->key, 'volume');});
		$chapters = $placeholderValues->filter(function ($item) {return false !== stristr($item->key, 'chapter');});
		
		$flashed_success = $request->session()->get('flashed_success');
		$flashed_data = $request->session()->get('flashed_data');
		$flashed_warning = $request->session()->get('flashed_warning');
		
		return View('configuration.placeholder.edit', array('artists' => $artists, 'characters' => $characters, 'scanalators' => $scanalators, 'series' => $series, 'tags' => $tags, 'collections' => $collections, 'volumes' => $volumes, 'chapters' => $chapters, 'flashed_success' => $flashed_success, 'flashed_data' => $flashed_data, 'flashed_warning' => $flashed_warning));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request)
    {
		$this->validate($request, [
									'placeholder_values.*' => 'string',
									'placeholder_values_helpers.*' => 'string|max:250'
									]);
		$placeholderValues = null;
		
		//Authentication check
		if (Route::is('user_update_configuration_placeholders'))
		{ 
			$this->authorize([ConfigurationPlaceholder::class, false]);
			$placeholderValues = Auth::user()->placeholder_configuration()->orderBy('priority')->get();
		}
		else if (Route::is('admin_update_configuration_pagination'))
		{ 
			$this->authorize([ConfigurationPlaceholder::class, true]); 
			$placeholderValues = ConfigurationPlaceholder::where('user_id', '=', null)->orderBy('priority')->get();
		}
		
		//Update all values
		//foreach ($placeholderValues as $placeholderValue)
		for ($i = 0; $i < $placeholderValues->count(); $i++)
		{
			$placeholderValue = $placeholderValues[$i];
			
			$value = Input::get("placeholder_values.".$placeholderValue->key);
			$helper = Input::get("placeholder_values_helpers.".$placeholderValue->key);
			
			if (($placeholderValue->value != $value) || ($placeholderValue->description != $helper))
			{
				$placeholderValue->value = $value;
				$placeholderValue->description = $helper;
				$placeholderValue->updated_by = Auth::user()->id;
				$placeholderValue->save();
			}
		}
		
		if (Route::is('user_update_configuration_placeholders'))
		{
			return redirect()->route('user_dashboard_configuration_placeholders')->with("flashed_success", array("Successfully updated placeholder configuration settings for user."));
		}
		else if (Route::is('admin_update_configuration_placeholders'))
		{
			return redirect()->route('admin_dashboard_configuration_placeholders')->with("flashed_success", array("Successfully updated placeholder configuration settings for site."));
		}
    }

    /**
     * Reset values based on site-wide settings.
     *
     * @param  int  $id
     * @return Response
     */
    public function reset(Request $request)
    { 
		$this->authorize(ConfigurationPlaceholder::class); 
		
		$userPlaceholderValues = Auth::user()->placeholder_configuration()->orderBy('priority')->get();
		$sitePlaceholderValues = ConfigurationPlaceholder::where('user_id', '=', null)->orderBy('priority')->get();
		
		for ($i = 0; $i < $userPlaceholderValues->count(); $i++)
		{
			$userPlaceholder = $userPlaceholderValues[$i];
			$globalPagination = $sitePlaceholderValues->where('key', $userPlaceholder->key)->first();
			 
			 if (($userPlaceholder->value != $globalPagination->value) 
				 || ($userPlaceholder->description != $globalPagination->description))
			 {
				$userPlaceholder->value = $globalPagination->value;
				$userPlaceholder->description = $globalPagination->description;
				$userPlaceholder->updated_by = Auth::user()->id;
				$userPlaceholder->save();
			 }
		}
		
		return redirect()->route('user_dashboard_configuration_placeholders')->with("flashed_success", array("Successfully reset placeholder configuration settings for user to site defaults."));
    }
}