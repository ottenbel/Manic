<?php

namespace App\Http\Requests\TagObjects\Series;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\TagObjects\Series\Series;


class StoreSeriesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		return $this->user()->can('create', Series::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		return [
			'name' => 'required|unique:series|regex:/^[^,:-]+$/',
			'url' => 'URL',
		];
    }
}
