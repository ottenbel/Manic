<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\User\Password\UpdatePasswordRequest;
use Auth;
use DB;
use App\Models\User;

class UpdatePasswordController extends WebController
{
	public function __construct()
    {
		parent::__construct();
        $this->middleware('auth');
    }
	
	public function edit(Request $request)
	{
		$this->GetFlashedMessages($request);
		return View('auth.passwords.edit', array('messages' => $this->messages));
	}
	
	public function update(UpdatePasswordRequest $request)
	{
		DB::beginTransaction();
		try
		{
			$user = Auth::user();
			$currentPassword = $request->get('password');
			$newPassword = $request->get('newPassword');
			
			if (Hash::check($currentPassword, $user->password))
			{
				$user->password = Hash::make($newPassword);
				$user->save();
			}
			else
			{
				//Thow an incorrect password error
				$this->AddWarningMessage("Password not updated - incorrect password provided.");
				return redirect()->route('edit_password')->with(["messages" => $this->messages]);
			}
		}
		catch (\Exception $e)
		{
			DB::rollBack();
			$this->AddWarningMessage("Unable to successfully update account password.", ['exception' => $e]);
			return redirect()->route('edit_password')->with(["messages" => $this->messages]);
		}
		DB::commit();
		
		//Write success message and return to user page
		$this->AddSuccessMessage("Successfully updated account password.", true);
		return redirect()->route('edit_password')->with(["messages" => $this->messages]);
	}
}