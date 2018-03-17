<?php

namespace App\Http\Requests\Chapter;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Volume;

class UpdateChapterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
		return $this->user()->can('update', $this->chapter);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
		$volumeID = $this->only(['volume_id']);
		$chapterID = $this->only(['chapter_id']);
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
						Rule::unique('chapters')->where(function ($query) use ($volumeID, $chapterID){
							$query->where('volume_id', $volumeID)
								->where('id', '!=', $chapterID);
							})
						],
			'scanalator_primary' => 'regex:/^[^:-]+$/',
			'scanalator_secondary' => 'regex:/^[^:-]+$/',
			'source' => 'URL',
			'images.*' => 'mimes:jpeg,bmp,png,gif,zip',
			'chapter_pages.*' => 'required|integer|min:0',
			'delete_pages.*' => 'boolean'];
		}
		else if ($lowerChapterLimit != 0)
		{
			$lowerChapterLimit = $lowerChapterLimit + 1;
			return [
			'volume_id' => 'required|exists:volumes,id',
			'chapter_number' => ['required',
						'integer',
						"min:$lowerChapterLimit",
						Rule::unique('chapters')->where(function ($query) use ($volumeID, $chapterID){
							$query->where('volume_id', $volumeID)
								->where('id', '!=', $chapterID);
							})
						],
			'scanalator_primary' => 'regex:/^[^:-]+$/',
			'scanalator_secondary' => 'regex:/^[^:-]+$/',
			'source' => 'URL',
			'images.*' => 'mimes:jpeg,bmp,png,gif,zip',
			'chapter_pages.*' => 'required|integer|min:0',
			'delete_pages.*' => 'boolean'];
		}
		else if ($upperChapterLimit != 0)
		{
			$upperChapterLimit = $upperChapterLimit - 1;
			return [
			'volume_id' => 'required|exists:volumes,id',
			'chapter_number' => ['required',
						'integer',
						"between:0,$upperChapterLimit",
						Rule::unique('chapters')->where(function ($query) use ($volumeID, $chapterID){
							$query->where('volume_id', $volumeID)
								->where('id', '!=', $chapterID);
							})
						],
			'scanalator_primary' => 'regex:/^[^:-]+$/',
			'scanalator_secondary' => 'regex:/^[^:-]+$/',
			'source' => 'URL',
			'images.*' => 'mimes:jpeg,bmp,png,gif,zip',
			'chapter_pages.*' => 'required|integer|min:0',
			'delete_pages.*' => 'boolean'];
		}
		else
		{
			return [
			'volume_id' => 'required|exists:volumes,id',
			'chapter_number' => ['required',
						'integer',
						'min:0',
						Rule::unique('chapters')->where(function ($query) use ($volumeID, $chapterID){
							$query->where('volume_id', $volumeID)
								->where('id', '!=', $chapterID);
							})
						],
			'scanalator_primary' => 'regex:/^[^:-]+$/',
			'scanalator_secondary' => 'regex:/^[^:-]+$/',
			'source' => 'URL',
			'images.*' => 'mimes:jpeg,bmp,png,gif,zip',
			'chapter_pages.*' => 'required|integer|min:0',
			'delete_pages.*' => 'boolean'];
		}
    }
}
