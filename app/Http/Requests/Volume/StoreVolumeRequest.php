<?php

namespace App\Http\Requests\Volume;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Volume\Volume;

class StoreVolumeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		return $this->user()->can('create', Volume::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		$collectionID = $this->only(['collection_id']);
		
		return [
			'collection_id' => 'required|exists:collections,id',
			'volume_number' => ['required',
						'integer',
						'min:0',
						Rule::unique('volumes')->where(function ($query) use ($collectionID){
							$query->where('collection_id', $collectionID);
						})],
			'image' => 'nullable|image',
		];
    }
}
