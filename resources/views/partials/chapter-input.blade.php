<div class="form-group">
	{{ Form::label('volume_id', 'Volume') }}
	@if((!empty($chapter)) && ($chapter->volume != null) && (Input::old('volume') == null))
		{{ Form::select('volume_id', $volumes, $chapter->volume->id) }}
	@else
		{{ Form::select('volume_id', $volumes, Input::old('volume')) }}
	@endif
</div>

@if(Route::is('create_chapter'))
	@include('partials.show-collection-content', ['volumes' => $collection->volumes(), 'editVolume' => false, 'editVolumeRoute' => 'edit_volume', 'chapterLinkRoute' => 'show_chapter', 'scanalatorLinkRoute' => 'show_scanalator', 'hideVolumes' => true])
@elseif(Route::is('edit_chapter'))
	@include('partials.show-collection-content', ['volumes' => $chapter->collection->volumes(), 'editVolume' => false, 'editVolumeRoute' => 'edit_volume', 'chapterLinkRoute' => 'edit_chapter', 'scanalatorLinkRoute' => 'edit_scanalator', 'hideVolumes' => true])
@endif
<br/>
<div class="form-group">
	{{ Form::label('chapter_number', 'Number') }}
	@if((!empty($chapter)) && ($chapter->chapter_number != null) && (Input::old('chapter_number') == null))
		{{ Form::text('chapter_number', $chapter->chapter_number, array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.chapters.number'))) }}
	@else
		{{ Form::text('chapter_number', Input::old('chapter_number'), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.chapters.number'))) }}
	@endif
	@if($errors->has('chapter_number'))
		<div class ="alert alert-danger" id="name_errors">{{$errors->first('chapter_number')}}</div>
	@endif
</div>

<div class="form-group">
	{{ Form::label('name', 'Name') }}
	@if((!empty($chapter)) && ($chapter->name != null) && (Input::old('name') == null))
		{{ Form::text('name', $chapter->name, array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.chapters.name'))) }}
	@else
		{{ Form::text('name', Input::old('name'), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.chapters.name'))) }}
	@endif
</div>

<div class="form-group">
	{{ Form::label('scanalator_primary', 'Primary Scanalators') }}
	@if((!empty($chapter)) && ($chapter->primary_scanalators != null) && (Input::old('scanalator_primary') == null))
		{{ Form::text('scanalator_primary', collect($chapter->primary_scanalators->pluck('name'))->implode(", "), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.chapters.primaryScanalators'))) }}
	@else
		{{ Form::text('scanalator_primary', Input::old('scanalator_primary'), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.chapters.primaryScanalators'))) }}
	@endif
	@if($errors->has('scanalator_primary'))
		<div class ="alert alert-danger" id="name_errors">{{$errors->first('scanalator_primary')}}</div>
	@endif
	
	{{ Form::label('scanalator_secondary', 'Secondary Scanalators') }}
	@if((!empty($chapter)) && ($chapter->secondary_scanalators != null) && (Input::old('scanalator_secondary') == null))
		{{ Form::text('scanalator_secondary', collect($chapter->secondary_scanalators->pluck('name'))->implode(', '), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.chapters.secondaryScanalators'))) }}
	@else
		{{ Form::text('scanalator_secondary', Input::old('scanalator_secondary'), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.chapters.secondaryScanalators'))) }}
	@endif
	@if($errors->has('scanalator_secondary'))
		<div class ="alert alert-danger" id="name_errors">{{$errors->first('scanalator_secondary')}}</div>
	@endif
</div>

<div class="form-group">
	{{ Form::label('source', 'Source URL') }}
	@if((!empty($chapter)) && ($chapter->source != null) && (Input::old('source') == null))
		{{ Form::text('source', $chapter->source, array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.chapters.source'))) }}
	@else
		{{ Form::text('source', Input::old('source'), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.chapters.source'))) }}
	@endif
	@if($errors->has('source'))
		<div class ="alert alert-danger" id="name_errors">{{$errors->first('source')}}</div>
	@endif
</div>

<div class="form-group">
	{{ Form::label('images', 'Pages') }}
	{{ Form::file('images[]', ['multiple' => 'multiple']) }}
	@if ($errors->has('images'))
		<div class ="alert alert-danger" id="image_errors">{{$errors->first('images')}}</div>
	@endif
	@if (count($errors->get('images.*')))
		<div class ="alert alert-danger" id="image_errors">All pages must be an image.</div>
	@endif
	
</div>