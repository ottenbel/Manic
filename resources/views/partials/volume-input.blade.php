<div class="form-group">
	@if(!empty($volume) && ($volume->cover_image != null))
		<div id="cover" class="col-md-4">
			<a href="/volume/{{$volume->id}}"><img src="{{asset($volume->cover_image->name)}}" class="img-thumbnail" height="100px" width="100%"></a>
		</div>
	@endif
	{{ Form::label('cover', 'Cover Image') }}
	{{ Form::file('image') }}
	@if(!empty($volume) && ($volume->cover_image != null))
		{{ Form::label('delete_cover', 'Remove Cover Image') }}
		{{ Form::checkbox('delete_cover', Input::old('delete_cover'), array('class' => 'form-control')) }}
	@endif
	@if ($errors->has('image'))
		<div class ="alert alert-danger" id="image_errors">{{$errors->first('image')}}</div>
	@endif
</div>

<div class="form-group">
	{{ Form::label('number', 'Number') }}
	@if((!empty($volume)) && ($volume->number != null) && (Input::old('number') == null))
		{{ Form::text('number', $volume->number, array('class' => 'form-control')) }}
	@else
		{{ Form::text('number', Input::old('number'), array('class' => 'form-control')) }}
	@endif
	@if($errors->has('number'))
		<div class ="alert alert-danger" id="name_errors">{{$errors->first('number')}}</div>
	@endif
</div>

<div class="form-group">
	{{ Form::label('name', 'Name') }}
	@if((!empty($volume)) && ($volume->name != null) && (Input::old('name') == null))
		{{ Form::text('name', $volume->name, array('class' => 'form-control')) }}
	@else
		{{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
	@endif
</div>