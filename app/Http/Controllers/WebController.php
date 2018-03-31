<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class WebController extends Controller
{
	protected $paginationKey;
	protected $placeholderStub;
	protected $placeheldFields;
	protected $messages;
	
	public function __construct()
	{
		$this->messages = ['success' => [], 'data' => [], 'warning' => []];
	}
	
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
	
	protected function GetFlashedMessages(Request $request)
	{
		$messagesFromRequest = $request->session()->get('messages');
		$this->messages['success'] = $messagesFromRequest['success'];
		$this->messages['data'] = $messagesFromRequest['data'];
		$this->messages['warning'] = $messagesFromRequest['warning'];
	}
	
	protected function AddSuccessMessage($message)
	{
		array_push($this->messages['success'], $message);
	}
	
	protected function AddDataMessage($message)
	{
		array_push($this->messages['data'], $message);
	}
	
	protected function AddWarningMessage($message)
	{
		array_push($this->messages['warning'], $message);
	}
}