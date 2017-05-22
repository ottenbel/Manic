<div class="form-group">
	{{ Form::label('name', 'Name') }}
	@if((!empty($tagObject)) && ($tagObject->name != null) && (Input::old('name') == null))
		{{ Form::text('name', $tagObject->name, array('class' => 'form-control', 'placeholder' => Config::get($namePlaceholder))) }}
	@else
		{{ Form::text('name', Input::old('name'), array('class' => 'form-control', 'placeholder' => Config::get($namePlaceholder))) }}
	@endif
	@if($errors->has('name'))
		<div class ="alert alert-danger" id="name_errors">{{$errors->first('name')}}</div>
	@endif
</div>

<!-- Display textbox for child tag objects-->
<div class="form-group">
	{{ Form::label($child, 'Children') }}
	
	@if((!empty($tagObject)) && ($tagObject->children()->count() > 0) && (Input::old($child) == null))
		{{ Form::text($child, collect($tagObject->children->pluck('name'))->implode(', '), array('class' => 'form-control', 'placeholder' => Config::get($childPlaceholder))) }}
	@else
		{{ Form::text($child, Input::old($child), array('class' => 'form-control', 'placeholder' => Config::get($childPlaceholder))) }}
	@endif
</div>

<div class="form-group">
	{{ Form::label('description', 'Description') }}
	@if((!empty($tagObject)) && ($tagObject->description != null) && (Input::old('description') == null))
		{{ Form::textarea('description', $tagObject->description, array('class' => 'form-control', 'placeholder' => Config::get($descriptionPlaceholder))) }}
	@else
		{{ Form::textarea('description', Input::old('description'), array('class' => 'form-control', 'placeholder' => Config::get($descriptionPlaceholder))) }}
	@endif
<div>

<div class="form-group">
	{{ Form::label('url', 'Source') }}
	@if((!empty($tagObject)) && ($tagObject->url != null) && (Input::old('url') == null))
		{{ Form::text('url', $tagObject->url, array('class' => 'form-control', 'placeholder' => Config::get($sourcePlaceholder))) }}
	@else
		{{ Form::text('url', Input::old('url'), array('class' => 'form-control', 'placeholder' => Config::get($sourcePlaceholder))) }}
	@endif
	@if($errors->has('url'))
		<div class ="alert alert-danger" id="url_errors">{{$errors->first('url')}}</div>
	@endif
</div>