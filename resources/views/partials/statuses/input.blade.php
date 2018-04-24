<div class="form-group">
	{{ Form::label('name', 'Name') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{$configurations['name']->description}}"></i>
	@if((!empty($status)) && ($status->name != null) && (Input::old('name') == null))
		{{ Form::text('name', $status->name, array('class' => 'form-control', 'placeholder' => $configurations['name']->value)) }}
	@else
		{{ Form::text('name', Input::old('name'), array('class' => 'form-control', 'placeholder' => $configurations['name']->value)) }}
	@endif
	
	@if($errors->has('name'))
		<div class ="alert alert-danger" id="name_errors">{{$errors->first('name')}}</div>
	@endif
</div>

<div class="form-group">
	{{ Form::label('priority', 'Priority') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{$configurations['priority']->description}}"></i>
	@if((!empty($status)) && ($status->priority != null) && (Input::old('priority') == null))
		{{ Form::text('priority', $status->priority, array('class' => 'form-control', 'placeholder' => $configurations['priority']->value)) }}
	@else
		{{ Form::text('priority', Input::old('priority'), array('class' => 'form-control', 'placeholder' => $configurations['priority']->value)) }}
	@endif

	@if($errors->has('priority'))
		<div class ="alert alert-danger" id="priority_errors">{{$errors->first('priority')}}</div>
	@endif
</div>

