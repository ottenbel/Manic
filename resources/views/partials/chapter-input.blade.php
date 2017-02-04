<div class="form-group">
	{{ Form::label('volume_id', 'Volume') }}
	@if((!empty($chapter)) && ($chapter->volume != null) && (Input::old('volume') == null))
		{{ Form::select('volume_id', $volumes, $chapter->volume->id) }}
	@else
		{{ Form::select('volume_id', $volumes, Input::old('volume')) }}
	@endif
</div>

<div class="form-group">
	{{ Form::label('number', 'Number') }}
	@if((!empty($chapter)) && ($chapter->number != null) && (Input::old('number') == null))
		{{ Form::text('number', $chapter->number) }}
	@else
		{{ Form::text('number', Input::old('number')) }}
	@endif
	@if($errors->has('number'))
		<div class ="alert alert-danger" id="name_errors">{{$errors->first('number')}}</div>
	@endif
</div>

<div class="form-group">
	{{ Form::label('name', 'Name') }}
	@if((!empty($chapter)) && ($chapter->name != null) && (Input::old('name') == null))
		{{ Form::text('name', $chapter->name) }}
	@else
		{{ Form::text('name', Input::old('name')) }}
	@endif
</div>

<div class="form-group">
	{{ Form::label('scanalator_primary', 'Primary Scanalators') }}
	@if((!empty($chapter)) && ($chapter->primary_scanalators != null) && (Input::old('scanalator_primary') == null))
		{{ Form::text('scanalator_primary', collect($chapter->primary_scanalators->pluck('name'))->implode(", ")) }}
	@else
		{{ Form::text('scanalator_primary', Input::old('scanalator_primary')) }}
	@endif
	
	{{ Form::label('scanalator_secondary', 'Secondary Scanalators') }}
	@if((!empty($chapter)) && ($chapter->secondary_scanalators != null) && (Input::old('scanalator_secondary') == null))
		{{ Form::text('scanalator_secondary', collect($chapter->secondary_scanalators->pluck('name'))->implode(', ')) }}
	@else
		{{ Form::text('scanalator_secondary', Input::old('scanalator_secondary')) }}
	@endif
</div>

<div class="form-group">
	{{ Form::label('source', 'Source URL') }}
	@if((!empty($chapter)) && ($chapter->source != null) && (Input::old('source') == null))
		{{ Form::text('source', $chapter->source) }}
	@else
		{{ Form::text('source', Input::old('source')) }}
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