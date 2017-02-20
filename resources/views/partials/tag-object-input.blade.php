<div class="form-group">
	{{ Form::label('name', 'Name') }}
	@if((!empty($tagObject)) && ($tagObject->name != null) && (Input::old('name') == null))
		{{ Form::text('name', $tagObject->name, array('class' => 'form-control')) }}
	@else
		{{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
	@endif
	@if($errors->has('name'))
		<div class ="alert alert-danger" id="name_errors">{{$errors->first('name')}}</div>
	@endif
</div>

<div class="form-group">
	{{ Form::label('description', 'Description') }}
	@if((!empty($tagObject)) && ($tagObject->description != null) && (Input::old('description') == null))
		{{ Form::textarea('description', $tagObject->description, array('class' => 'form-control')) }}
	@else
		{{ Form::textarea('description', Input::old('description'), array('class' => 'form-control')) }}
	@endif
<div>

<div class="form-group">
	{{ Form::label('url', 'Source') }}
	@if((!empty($tagObject)) && ($tagObject->url != null) && (Input::old('url') == null))
		{{ Form::text('url', $tagObject->url, array('class' => 'form-control')) }}
	@else
		{{ Form::text('url', Input::old('url'), array('class' => 'form-control')) }}
	@endif
	@if($errors->has('url'))
		<div class ="alert alert-danger" id="url_errors">{{$errors->first('url')}}</div>
	@endif
</div>