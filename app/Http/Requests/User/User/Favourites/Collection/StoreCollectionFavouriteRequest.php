<?php

namespace App\Http\Requests\User\User\Favourites\Collection;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User\CollectionFavourite;

class StoreCollectionFavouriteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		return $this->user()->can('create', CollectionFavourite::class);
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
