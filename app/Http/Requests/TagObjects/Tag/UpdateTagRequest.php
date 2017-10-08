<?php

namespace App\Http\Requests\TagObjects\Tag;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTagRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		return $this->user()->can('update', $this->tag);
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
					Rule::unique('tags')->ignore($this->tag->id),
					'regex:/^[^,:-]+$/'],
				'url' => 'URL',
		];
    }
}
