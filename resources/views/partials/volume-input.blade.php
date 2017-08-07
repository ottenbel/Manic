<div class="form-group">
	<div class="row">
		@if(!empty($volume) && ($volume->cover_image != null))
			<div id="cover" class="col-md-4">
				<img src="{{asset($volume->cover_image->name)}}" class="img-thumbnail" height="100px" width="100%">
			</div>
		@endif
		
		<div id="cover_edit" class="col-md-8">
			{{ Form::label('cover', 'Cover Image') }}
			<i class="fa fa-question-circle" aria-hidden="true" title="{{Config::get('constants.placeholders.help.volumes.cover')}}"></i>
			{{ Form::file('image') }}
			@if(!empty($volume) && ($volume->cover_image != null))
				{{ Form::label('delete_cover', 'Remove Cover Image') }}
				{{ Form::checkbox('delete_cover', null, Input::old('delete_cover')) }}
			@endif
		</div>
	</div>
	
	
	@if ($errors->has('image'))
		<div class ="alert alert-danger" id="image_errors">{{$errors->first('image')}}</div>
	@endif
</div>

<div class="form-group">
	{{ Form::label('volume_number', 'Number') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{Config::get('constants.placeholders.help.chapters.number')}}"></i>
	@if((!empty($volume)) && ($volume->volume_number != null) && (Input::old('volume_number') == null))
		{{ Form::text('volume_number', $volume->volume_number, array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.volumes.number'))) }}
	@else
		{{ Form::text('volume_number', Input::old('volume_number'), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.volumes.number'))) }}
	@endif
	@if($errors->has('volume_number'))
		<div class ="alert alert-danger" id="name_errors">{{$errors->first('volume_number')}}</div>
	@endif
</div>

<div class="form-group">
	{{ Form::label('name', 'Name') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{Config::get('constants.placeholders.help.chapters.name')}}"></i>
	@if((!empty($volume)) && ($volume->name != null) && (Input::old('name') == null))
		{{ Form::text('name', $volume->name, array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.volumes.name'))) }}
	@else
		{{ Form::text('name', Input::old('name'), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.volumes.name'))) }}
	@endif
</div>