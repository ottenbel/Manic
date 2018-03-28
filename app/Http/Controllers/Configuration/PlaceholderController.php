<?php

namespace App\Http\Controllers\Configuration;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\WebController;
use Route;
use Auth;
use DB;
use Input;
use ConfigurationLookupHelper;
use App\Models\Configuration\ConfigurationPlaceholder;

class PlaceholderController extends WebController
{
	public function __construct()
    {
		$this->middleware('auth');
	}
	
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
		
		$artists = $placeholderValues->filter(function ($item) {return false !== starts_with($item->key, 'artist');});
		$characters = $placeholderValues->filter(function ($item) {return false !== starts_with($item->key, 'character');});
		$scanalators = $placeholderValues->filter(function ($item) {return false !== starts_with($item->key, 'scanalator');});
		$series = $placeholderValues->filter(function ($item) {return false !== starts_with($item->key, 'series');});
		$tags = $placeholderValues->filter(function ($item) {return false !== starts_with($item->key, 'tag');});
		$collections = $placeholderValues->filter(function ($item) {return false !== starts_with($item->key, 'collection');});
		$volumes = $placeholderValues->filter(function ($item) {return false !== starts_with($item->key, 'volume');});
		$chapters = $placeholderValues->filter(function ($item) {return false !== starts_with($item->key, 'chapter');});
		$permissions = $placeholderValues->filter(function ($item) {return false !== starts_with($item->key, 'permission');});
		$roles = $placeholderValues->filter(function ($item) {return false !== starts_with($item->key, 'role');});
		$languages = $placeholderValues->filter(function ($item) {return false !== starts_with($item->key, 'language');});
		
		$messages = self::GetFlashedMessages($request);
		
		return View('configuration.placeholder.edit', array('artists' => $artists, 'characters' => $characters, 'scanalators' => $scanalators, 'series' => $series, 'tags' => $tags, 'collections' => $collections, 'volumes' => $volumes, 'chapters' => $chapters, 'permissions' => $permissions, 'roles' => $roles, 'languages' => $languages, 'messages' => $messages));
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
		else if (Route::is('admin_update_configuration_placeholders'))
		{ 
			$this->authorize([ConfigurationPlaceholder::class, true]); 
			$placeholderValues = ConfigurationPlaceholder::where('user_id', '=', null)->orderBy('priority')->get();
		}
		
		DB::beginTransaction();
		try
		{
			//Update all values
			for ($i = 0; $i < $placeholderValues->count(); $i++)
			{
				$placeholderValue = $placeholderValues[$i];
				
				$value = Input::get("placeholder_values.".$placeholderValue->key);
				$helper = Input::get("placeholder_values_helpers.".$placeholderValue->key);
				
				if (($placeholderValue->value != $value) || ($placeholderValue->description != $helper))
				{
					$placeholderValue->value = $value;
					$placeholderValue->description = $helper;
					$placeholderValue->save();
				}
			}
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$messages = self::BuildFlashedMessagesVariable(null, null, ["Unable to successfully update placeholder configuration settings based on site configuration."]);
			if (Route::is('user_update_configuration_placeholders'))
			{
				return redirect()->route('user_dashboard_configuration_placeholders')->with(["messages" => $messages])->withInput();
			}
			else if (Route::is('admin_update_configuration_placeholders'))
			{
				return redirect()->route('admin_dashboard_configuration_placeholders')->with(["messages" => $messages])->withInput();
			}
		}
		
		DB::commit();
		
		if (Route::is('user_update_configuration_placeholders'))
		{
			$messages = self::BuildFlashedMessagesVariable(["Successfully updated placeholder configuration settings for user."], null, null);
			return redirect()->route('user_dashboard_configuration_placeholders')->with("messages", $messages);
		}
		else if (Route::is('admin_update_configuration_placeholders'))
		{
			$messages = self::BuildFlashedMessagesVariable(["Successfully updated placeholder configuration settings for site."], null, null);
			return redirect()->route('admin_dashboard_configuration_placeholders')->with("messages", $messages);
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
		
		DB::beginTransaction();
		try
		{
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
					$userPlaceholder->save();
				 }
			}
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$messages = self::BuildFlashedMessagesVariable(null, null, ["Unable to successfully reset placeholder configuration settings based on site configuration."]);
			return redirect()->route('user_dashboard_configuration_placeholders')->with(["messages" => $messages]);
		}
		
		DB::commit();
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully reset placeholder configuration settings for user to site defaults."], null, null);
		return redirect()->route('user_dashboard_configuration_placeholders')->with("messages", $messages);
    }
}