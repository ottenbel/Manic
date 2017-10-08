<?php

namespace App\Http\Requests\TagObjects\Series;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSeriesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		return $this->user()->can('update', $this->series);
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
					Rule::unique('series')->ignore($this->series->id),
					'regex:/^[^,:-]+$/'],
				'url' => 'URL',
		];
    }
}
