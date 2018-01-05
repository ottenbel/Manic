<div class="form-group">
	{{ Form::label('name', 'Name') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{$configuration->description}}"></i>
	@if((!empty($role)) && ($role->name != null) && (Input::old('name') == null))
		{{ Form::text('name', $role->name, array('class' => 'form-control', 'placeholder' => $configuration->value)) }}
	@else
		{{ Form::text('name', Input::old('name'), array('class' => 'form-control', 'placeholder' => $configuration->value)) }}
	@endif
	@if($errors->has('name'))
		<div class ="alert alert-danger" id="name_errors">{{$errors->first('name')}}</div>
	@endif	
</div>

<h2>Permissions</h2>
<div>
	@foreach($permissions as $permission)
		@if(($loop->index % 2) == 0)
			<div class="row">
		@endif
		
		<div class="form-group col-xs-6">
			{{ Form::label($permission->name, $permission->name) }}
			@if(Input::old("$permission->name") == null)
				{{ Form::checkbox("$permission->name", null, $permission->hasValue)}}
			@else
				{{ Form::checkbox("$permission->name", null, Input::old("$permission->name"))}}
			@endif
			
			@if($errors->has("$permission->name"))
				<div class ="alert alert-danger" id="paginationValueError">
					{{$errors->first("$permission->name")}}
				</div>
			@endif
		</div>
		
		@if(($loop->index % 2) == 1)
			</div>
		@endif
	@endforeach
</div>
