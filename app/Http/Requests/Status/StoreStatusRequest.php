<?php

namespace App\Http\Requests\Status;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Status;

class StoreStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		return $this->user()->can('create', Status::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		return [
			'name' => 'required|unique:statuses,name',
			'priority' => 'required|unique:statuses,priority|integer',
		];
    }
}
