<?php

namespace App\Http\Requests\Collection;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCollectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		return $this->user()->can('update', $this->collection);
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
						Rule::unique('collections')->ignore($this->collection->id)],
			'parent_id' => 'nullable|exists:collections,id',
			'artist_primary' => 'regex:/^[^:-]+$/',
			'artist_secondary' => 'regex:/^[^:-]+$/',
			'series_primary' => 'regex:/^[^:-]+$/',
			'series_secondary' => 'regex:/^[^:-]+$/',
			'character_primary' => 'regex:/^[^:-]+$/',
			'character_secondary' => 'regex:/^[^:-]+$/',
			'tag_primary' => 'regex:/^[^:-]+$/',
			'tag_secondary' => 'regex:/^[^:-]+$/',
			'rating' => 'nullable|exists:ratings,id',
			'status' => 'nullable|exists:statuses,id',
			'language' => 'nullable|exists:languages,id',
			'image' => 'nullable|image',
		];
    }
}
