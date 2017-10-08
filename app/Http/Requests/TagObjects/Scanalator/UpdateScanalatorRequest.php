<?php

namespace App\Http\Requests\TagObjects\Scanalator;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateScanalatorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		return $this->user()->can('update', $this->scanalator);
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
					Rule::unique('scanalators')->ignore($this->scanalator->id),
					'regex:/^[^,:-]+$/'],
				'url' => 'URL',
		];
    }
}
