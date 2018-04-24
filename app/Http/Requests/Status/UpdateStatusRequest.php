<?php

namespace App\Http\Requests\Status;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		return $this->user()->can('update', $this->status);
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
			Rule::unique('statuses')->ignore($this->status->id)],
			'priority' => [
                'required', 
                'integer', 
                Rule::unique('statuses')->ignore($this->status->id)],
		];
    }
}
