<?php

namespace App\Http\Requests\TagObjects\Series\Alias;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\TagObjects\Series\SeriesAlias;

class StoreSeriesAliasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		$isGlobalAlias = $this->get('is_global_alias');
		$isPersonalAlias = $this->get('is_personal_alias');
		
		if ($isGlobalAlias){ return $this->user()->can('create', [SeriesAlias::class, true]); }
		else if ($isPersonalAlias) { return $this->user()->can('create', [SeriesAlias::class, false]); }
		else { return false; }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		$isGlobalAlias = $this->get('is_global_alias');
		$isPersonalAlias = $this->get('is_personal_alias');
		
		if ($isGlobalAlias) 
		{
			return [
				'global_alias' => 'required|unique:series,name|unique:series_alias,alias,null,null,user_id,NULL|regex:/^[^,:-]+$/',
			];
		}
		else if ($isPersonalAlias)
		{
			return [
				'personal_alias' => 'required|unique:series,name|unique:series_alias,alias,null,null,user_id,'.$this->user()->id.'|regex:/^[^,:-]+$/',
			];
		}
    }
}
