<?php

namespace App\Http\Requests\User\User\Password;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		return 
		[
			'password' => 'required', 
			'newPassword' => 'required',
			'confirmNewPassword' => 'required|same:newPassword' 
		];
    }
}
