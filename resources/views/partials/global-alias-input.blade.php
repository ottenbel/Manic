<div class="form-group">
	{{ Form::label('global_alias', 'Alias') }}
	@if((!empty($globalAliasObject)) && ($globalAliasObject->alias != null) && (Input::old('global_alias') == null))
		{{ Form::text('global_alias', $globalAliasObject->alias, array('class' => 'form-control')) }}
	@else
		{{ Form::text('global_alias', Input::old('global_alias'), array('class' => 'form-control')) }}
	@endif
	@if($errors->has('global_alias'))
		<div class ="alert alert-danger" id="name_errors">{{$errors->first('global_alias')}}</div>
	@endif
</div>