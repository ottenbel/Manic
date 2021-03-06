<?php

namespace App\Http\Controllers\Configuration;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\WebController;
use Route;
use Auth;
use Input;
use DB;
use ConfigurationLookupHelper;
use App\Models\Configuration\ConfigurationPagination;

class PaginationController extends WebController
{
	public function __construct()
    {
		parent::__construct();
		
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
		$paginationValues = null;
		//Authentication check
		if (Route::is('user_dashboard_configuration_pagination'))
		{ 
			$this->authorize([ConfigurationPagination::class, false]); 
			$paginationValues = ConfigurationPagination::where('user_id', '=', Auth::user()->id)->orderBy('priority')->get();
		}
		else if (Route::is('admin_dashboard_configuration_pagination'))
		{ 
			$this->authorize([ConfigurationPagination::class, true]); 
			$paginationValues = ConfigurationPagination::where('user_id', '=', null)->orderBy('priority')->get();
		}
		
		$this->GetFlashedMessages($request);
		
		return View('configuration.pagination.edit', array('paginationValues' => $paginationValues, 'messages' => $this->messages));
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
									'pagination_values.*' => 'required|integer|min:2', 
									'pagination_values_helpers.*' => 'required|string|max:250'
									]);
		$paginationValues = null;
		
		//Authentication check
		if (Route::is('user_update_configuration_pagination'))
		{ 
			$this->authorize([ConfigurationPagination::class, false]);
			$paginationValues = Auth::user()->pagination_configuration()->orderBy('priority')->get();
		}
		else if (Route::is('admin_update_configuration_pagination'))
		{ 
			$this->authorize([ConfigurationPagination::class, true]); 
			$paginationValues = ConfigurationPagination::where('user_id', '=', null)->orderBy('priority')->get();
		}
		
		DB::beginTransaction();
		try
		{
			//Update all values
			for ($i = 0; $i < $paginationValues->count(); $i++)
			{
				$paginationValue = $paginationValues[$i];
				
				$value = Input::get("pagination_values.".$paginationValue->key);
				$helper = Input::get("pagination_values_helpers.".$paginationValue->key);
				
				if (($paginationValue->value != $value) || ($paginationValue->description != $helper))
				{
					$paginationValue->value = $value;
					$paginationValue->description = $helper;
					$paginationValue->save();
				}
			}
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$this->AddWarningMessage("Unable to successfully update pagination configuration changes to the database.", ['error' => $e]);
			if (Route::is('user_update_configuration_pagination'))
			{
				return redirect()->route('user_dashboard_configuration_pagination')->with(["messages" => $this->messages])->withInput();
			}
			else if (Route::is('admin_update_configuration_pagination'))
			{
				return redirect()->route('admin_dashboard_configuration_pagination')->with(["messages" => $this->messages])->withInput();
			}
		}
		
		DB::commit();
		
		if (Route::is('user_update_configuration_pagination'))
		{
			$this->AddSuccessMessage("Successfully updated pagination configuration settings for user.");
			return redirect()->route('user_dashboard_configuration_pagination')->with("messages", $this->messages);
		}
		else if (Route::is('admin_update_configuration_pagination'))
		{
			$this->AddSuccessMessage("Successfully updated pagination configuration settings for site.");
			return redirect()->route('admin_dashboard_configuration_pagination')->with("messages", $this->messages);
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
		$this->authorize(ConfigurationPagination::class); 
		
		DB::beginTransaction();
		try
		{
			$userPaginationValues = Auth::user()->pagination_configuration()->orderBy('priority')->get();
			$sitePaginationValues = ConfigurationPagination::where('user_id', '=', null)->orderBy('priority')->get();
		
			for ($i = 0; $i < $userPaginationValues->count(); $i++)
			{
				$userPagination = $userPaginationValues[$i];
				$globalPagination = $sitePaginationValues->where('key', $userPagination->key)->first();
				 
				 if (($userPagination->value != $globalPagination->value) 
					 || ($userPagination->description != $globalPagination->description))
				 {
					$userPagination->value = $globalPagination->value;
					$userPagination->description = $globalPagination->description;
					$userPagination->save();
				 }
			}
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			
			$this->AddWarningMessage("Unable to successfully reset pagination configuration settings based on site configuration.", ['error' => $e]);
			return redirect()->route('user_dashboard_configuration_pagination')->with(["messages" => $this->messages]);
		}
		
		DB::commit();
		
		$this->AddSuccessMessage("Successfully reset pagination configuration settings for user to site defaults.");
		return redirect()->route('user_dashboard_configuration_pagination')->with("messages", $this->messages);
    }
}