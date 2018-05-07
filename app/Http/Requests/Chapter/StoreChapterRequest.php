<?php

namespace App\Http\Requests\Chapter;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Chapter\Chapter;
use App\Models\Volume\Volume;

class StoreChapterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		return $this->user()->can('create', Chapter::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		$volumeID = $this->only(['volume_id']);
		$volume = Volume::where('id', '=', $volumeID)->firstOrFail();
		
		$lowerChapterLimit = 0;
		$upperChapterLimit = 0;
		
		if (($volume->previous_volume()->first() != null) && ($volume->previous_volume()->first()->last_chapter()->first() != null))
		{
			$lowerChapterLimit = $volume->previous_volume()->first()->last_chapter()->first()->chapter_number;
		}
		if (($volume->next_volume()->first() != null) && ($volume->next_volume()->first()->first_chapter()->first() != null))
		{
			$upperChapterLimit = $volume->next_volume()->first()->first_chapter()->first()->chapter_number;
		}
		
		if (($lowerChapterLimit != 0) && ($upperChapterLimit != 0))
		{
			return [
			'volume_id' => 'required|exists:volumes,id',
			'chapter_number' => ['required',
						'integer',
						"between:$lowerChapterLimit,$upperChapterLimit",
						Rule::unique('chapters')->where(function ($query) use ($volumeID){
							$query->where('volume_id', $volumeID);})
						],
			'scanalator_primary' => 'regex:/^[^:-]+$/',
			'scanalator_secondary' => 'regex:/^[^:-]+$/',
			'source' => 'URL',
			'images' => 'required',
			'images.*' => 'mimetypes:jpeg,bmp,png,gif,zip'];
		}
		else if ($lowerChapterLimit != 0)
		{
			$lowerChapterLimit = $lowerChapterLimit + 1;
			return [
			'volume_id' => 'required|exists:volumes,id',
			'chapter_number' => ['required',
						'integer',
						"min:$lowerChapterLimit",
						Rule::unique('chapters')->where(function ($query) use ($volumeID){
							$query->where('volume_id', $volumeID);})
						],
			'scanalator_primary' => 'regex:/^[^:-]+$/',
			'scanalator_secondary' => 'regex:/^[^:-]+$/',
			'source' => 'URL',
			'images' => 'required',
			'images.*' => 'mimes:jpeg,bmp,png,gif,zip'];
		}
		else if ($upperChapterLimit != 0)
		{
			$upperChapterLimit = $upperChapterLimit - 1;
			return [
			'volume_id' => 'required|exists:volumes,id',
			'chapter_number' => ['required',
						'integer',
						"between:0,$upperChapterLimit",
						Rule::unique('chapters')->where(function ($query) use ($volumeID){
							$query->where('volume_id', $volumeID);})
						],
			'scanalator_primary' => 'regex:/^[^:-]+$/',
			'scanalator_secondary' => 'regex:/^[^:-]+$/',
			'source' => 'URL',
			'images' => 'required',
			'images.*' => 'mimes:jpeg,bmp,png,gif,zip'];
		}
		else
		{
			return [
			'volume_id' => 'required|exists:volumes,id',
			'chapter_number' => ['required',
						'integer',
						'min:0',
						Rule::unique('chapters')->where(function ($query) use ($volumeID){
							$query->where('volume_id', $volumeID);})
						],
			'scanalator_primary' => 'regex:/^[^:-]+$/',
			'scanalator_secondary' => 'regex:/^[^:-]+$/',
			'source' => 'URL',
			'images' => 'required',
			'images.*' => 'mimes:jpeg,bmp,png,gif,zip'];
		}
    }
}
