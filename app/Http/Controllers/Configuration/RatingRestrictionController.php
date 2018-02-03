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
use App\Models\Configuration\ConfigurationRatingRestriction;

class RatingRestrictionController extends WebController
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
		$ratingRestrictions = null;
		//Authentication check
		if (Route::is('user_dashboard_configuration_rating_restriction'))
		{ 
			$this->authorize([ConfigurationRatingRestriction::class, false]); 
			$ratingRestrictions = ConfigurationRatingRestriction::where('user_id', '=', Auth::user()->id)->orderBy('priority')->get();
		}
		else if (Route::is('admin_dashboard_configuration_rating_restriction'))
		{ 
			$this->authorize([ConfigurationRatingRestriction::class, true]); 
			$ratingRestrictions = ConfigurationRatingRestriction::where('user_id', '=', null)->orderBy('priority')->get();
		}
		
		$messages = self::GetFlashedMessages($request);
		
		return View('configuration.rating-restriction.edit', array('ratingRestrictions' => $ratingRestrictions, 'messages' => $messages));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request)
    {
		$ratingRestrictions = null;
		
		//Authentication check
		if (Route::is('user_update_configuration_rating_restriction'))
		{ 
			$this->authorize([ConfigurationRatingRestriction::class, false]); 
			$ratingRestrictions = ConfigurationRatingRestriction::where('user_id', '=', Auth::user()->id)->orderBy('priority')->get();
		}
		else if (Route::is('admin_update_configuration_rating_restriction'))
		{ 
			$this->authorize([ConfigurationRatingRestriction::class, true]); 
			$ratingRestrictions = ConfigurationRatingRestriction::where('user_id', '=', null)->orderBy('priority')->get();
		}
		
		DB::beginTransaction();
		try
		{
			for ($i = 0; $i < $ratingRestrictions->count(); $i++)
			{
				$ratingRestriction = $ratingRestrictions[$i];
				
				$value = Input::has("rating_restriction_values.".$ratingRestriction->rating_id);
				
				if ($ratingRestriction->display != $value)
				{
					$ratingRestriction->display = $value;
					$ratingRestriction->save();
				}
			}
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$messages = self::BuildFlashedMessagesVariable(null, null, ["Unable to successfully update rating restriction settings based on site configuration."]);
			if (Route::is('user_update_configuration_rating_restriction'))
			{
				return redirect()->route('user_dashboard_configuration_rating_restriction')->with(["messages" => $messages])->withInput();
			}
			else if (Route::is('admin_update_configuration_rating_restriction'))
			{
				return redirect()->route('admin_dashboard_configuration_rating_restriction')->with(["messages" => $messages])->withInput();
			}
		}
		DB::commit();
		
		if (Route::is('user_update_configuration_rating_restriction'))
		{
			$messages = self::BuildFlashedMessagesVariable(["Successfully updated rating restriction configuration settings for user."], null, null);
			return redirect()->route('user_dashboard_configuration_rating_restriction')->with("messages", $messages);
		}
		else if (Route::is('admin_update_configuration_rating_restriction'))
		{
			$messages = self::BuildFlashedMessagesVariable(["Successfully updated rating restriction configuration settings for site."], null, null);
			return redirect()->route('admin_dashboard_configuration_rating_restriction')->with("messages", $messages);
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
		$this->authorize(ConfigurationRatingRestriction::class);
		
		DB::beginTransaction();
		try
		{
			$userRatingRestrictionValues = Auth::user()->rating_restriction_configuration()->orderBy('priority')->get();
			$siteRatingRestrictionValues = ConfigurationRatingRestriction::where('user_id', '=', null)->orderBy('priority')->get();
			
			for ($i = 0; $i < $userRatingRestrictionValues->count(); $i++)
			{
				$userRatingRestriction = $userRatingRestrictionValues[$i];
				$globalRatingRestriction = $siteRatingRestrictionValues->where('rating_id', $userRatingRestriction->rating_id)->first();
				 
				if ($userRatingRestriction->display != $globalRatingRestriction->display)
				{
					$userRatingRestriction->display = $globalRatingRestriction->display;
					$userRatingRestriction->save();
				}
			}
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$messages = self::BuildFlashedMessagesVariable(null, null, ["Unable to successfully reset rating restriction settings based on site configuration."]);
			return redirect()->route('user_dashboard_configuration_rating_restriction')->with(["messages" => $messages]);
		}
		DB::commit();
		
		$messages = self::BuildFlashedMessagesVariable(["Successfully reset rating restriction configuration settings for user to site defaults."], null, null);
		return redirect()->route('user_dashboard_configuration_rating_restriction')->with("messages", $messages);
    }
}