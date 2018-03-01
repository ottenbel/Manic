<?php

namespace App\Http\Requests\User\User\Blacklists\Collection;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User\CollectionBlacklist;

class StoreCollectionBlacklistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		return $this->user()->can('create', CollectionBlacklist::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		$collectionID = $this->only(['collection_id']);
		
		return [];
    }
}
