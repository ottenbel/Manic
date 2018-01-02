<div class="form-group">
	{{ Form::label('name', 'Name') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{$configuration->description}}"></i>
	@if((!empty($permission)) && ($permission->name != null) && (Input::old('name') == null))
		{{ Form::text('name', $permission->name, array('class' => 'form-control', 'placeholder' => $configuration->value)) }}
	@else
		{{ Form::text('name', Input::old('name'), array('class' => 'form-control', 'placeholder' => $configuration->value)) }}
	@endif
	@if($errors->has('name'))
		<div class ="alert alert-danger" id="name_errors">{{$errors->first('name')}}</div>
	@endif
</div>