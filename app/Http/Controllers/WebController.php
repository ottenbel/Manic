<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
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
		if ($messagesFromRequest['success'] != null)
			{ $this->messages['success'] = $messagesFromRequest['success']; }
		else
			{ $this->messages['success'] = []; }
		
		if ($messagesFromRequest['data'] != null)
			{ $this->messages['data'] = $messagesFromRequest['data']; }
		else
			{ $this->messages['data'] = []; }
		
		if ($messagesFromRequest['warning'] != null)
			{ $this->messages['warning'] = $messagesFromRequest['warning']; }
		else 
			{ $this->messages['warning'] = []; }
	}
	
	protected function AddSuccessMessage($message, $writeToLog = false, $contextualInformation = [])
	{	
		array_push($this->messages['success'], $message);
		if ($writeToLog)
		{
			Log::info($message, $contextualInformation);
		}
	}
	
	protected function AddDataMessage($message, $writeToLog = false, $contextualInformation = [])
	{
		array_push($this->messages['data'], $message);
		if ($writeToLog)
		{
			Log::warning($message, $contextualInformation);
		}
	}
	
	protected function AddWarningMessage($message, $contextualInformation = [])
	{
		array_push($this->messages['warning'], $message);
		Log::error($message, $contextualInformation);
	}
}