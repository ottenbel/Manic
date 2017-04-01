<div class="form-group">
	{{ Form::label('personal_alias', 'Alias') }}
	@if((!empty($personalAliasObject)) && ($personalAliasObject->alias != null) && (Input::old('personal_alias') == null))
		{{ Form::text('personal_alias', $personalAliasObject->alias, array('class' => 'form-control')) }}
	@else
		{{ Form::text('personal_alias', Input::old('personal_alias'), array('class' => 'form-control')) }}
	@endif
	@if($errors->has('personal_alias'))
		<div class ="alert alert-danger" id="name_errors">{{$errors->first('personal_alias')}}</div>
	@endif
</div>