<?php

namespace App\Http\Requests\TagObjects\Character;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCharacterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		return $this->user()->can('update', $this->character);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		return [
			'name' => ['required',
					Rule::unique('characters')->ignore($this->character->id),
					'regex:/^[^,:-]+$/'],
				'url' => 'URL',
		];
    }
}
