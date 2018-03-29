<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class WebController extends Controller
{
	protected $paginationKey;
	protected $placeholderStub;
	protected $placeheldFields;
	
	protected function GetConfiguration()
	{
		$configurations = Auth::user()->placeholder_configuration()->where('key', 'like', $this->placeholderStub.'%')->get();
		$configurationsArray = [];
		
		foreach ($this->placeheldFields as $key)
		{
			$value = $configurations->where('key', '=', $this->placeholderStub.'_'.$key)->first();
			$configurationsArray = array_merge($configurationsArray, [$key => $value]);
		}
		
		return $configurationsArray;
	}
	
	protected static function GetFlashedMessages(Request $request)
	{
		$messages = $request->session()->get('messages');
		$success = $messages['success'];
		$data = $messages['data'];
		$warning = $messages['warning'];
		
		return array('success' => $success, 'data' => $data, 'warning' => $warning);
	}
	
	protected static function BuildFlashedMessagesVariable($success, $data, $warning)
	{
		return array('success' => $success, 'data' => $data, 'warning' => $warning);
	}
}