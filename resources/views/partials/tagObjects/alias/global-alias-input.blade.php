<div class="form-group">
	@if((!empty($globalAliasObject)) && ($globalAliasObject->alias != null) && (Input::old('global_alias') == null))
		{{ Form::text('global_alias', $globalAliasObject->alias, array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.aliases.global'))) }}
	@else
		{{ Form::text('global_alias', Input::old('global_alias'), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.aliases.global'))) }}
	@endif
	@if($errors->has('global_alias'))
		<div class ="alert alert-danger" id="name_errors">{{$errors->first('global_alias')}}</div>
	@endif
</div>