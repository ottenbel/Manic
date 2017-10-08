<?php

namespace App\Http\Requests\TagObjects\Artist;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateArtistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		return $this->user()->can('update', $this->artist);
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
				Rule::unique('artists')->ignore($this->artist->id),
				'regex:/^[^,:-]+$/'],
			'url' => 'URL',
		];
    }
}
