<?php

namespace App\Http\Requests\TagObjects\Character;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\TagObjects\Character\Character;

class StoreCharacterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		return $this->user()->can('create', Character::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		return [
			'name' => 'required|unique:characters|regex:/^[^,:-]+$/',
			'url' => 'URL',
		];
    }
}
