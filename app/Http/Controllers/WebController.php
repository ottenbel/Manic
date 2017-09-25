<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WebController extends Controller
{
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