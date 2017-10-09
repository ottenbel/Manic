<?php

namespace App\Http\Requests\TagObjects\Artist;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\TagObjects\Artist\Artist;

class StoreArtistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		return $this->user()->can('create', Artist::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		return [
			'name' => 'required|unique:artists|regex:/^[^,:-]+$/',
			'url' => 'URL',
		];
    }
}
