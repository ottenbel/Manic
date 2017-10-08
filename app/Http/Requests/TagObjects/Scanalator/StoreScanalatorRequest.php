<?php

namespace App\Http\Requests\TagObjects\Scanalator;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\TagObjects\Scanalator\Scanalator;

class StoreScanalatorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		return $this->user()->can('create', Scanalator::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		return [
			'name' => 'required|unique:scanalators|regex:/^[^,:-]+$/',
			'url' => 'URL',
		];
    }
}
