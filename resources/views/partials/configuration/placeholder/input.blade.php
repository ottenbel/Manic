<h2>{{$section}} </h2>
<button id="placeholder_value_button" class="accordion" type="button">
	Placeholder Configuration
</button>
<div id="placeholder_value_panel" class="volume_panel">
	@foreach($items as $item)
		<div class="form-group">
			{{ Form::label("placeholder_values[$item->key]", $item->key) }}
			<i class="fa fa-question-circle" aria-hidden="true" title="{{$item->description}}"></i>
			@if(Input::old('volume') == null)
				{{ Form::text("placeholder_values[$item->key]", $item->value, array('class' => 'form-control', 'placeholder' => $item->value)) }}
			@else
				{{ Form::text("placeholder_values[$item->key]", Input::old("placeholder_values[$item->key]"), array('class' => 'form-control', 'placeholder' => $item->value)) }}
			@endif
			@if($errors->has("placeholder_values.".$item->key))
				<div class ="alert alert-danger" id="placeholderValueError">{{$errors->first("placeholder_values.".$item->key)}}</div>
			@endif
		</div>
	@endforeach
</div>
<button id="pagination_value_helper_button" class="accordion" type="button">
	Placeholder Helper Configuration
</button>
<div id="pagination_value_helper_panel" class="volume_panel">
	@foreach($items as $item)
		<div class="form-group">
			{{ Form::label("placeholder_values_helpers[$item->key]", "$item->key"."_helper") }}
			<i class="fa fa-question-circle" aria-hidden="true" title="{{$item->description}}"></i>
			@if(Input::old('volume') == null)
				{{ Form::text("placeholder_values_helpers[$item->key]", $item->description, array('class' => 'form-control', 'placeholder' => $item->description)) }}
			@else
				{{ Form::text("placeholder_values_helpers[$item->key]", Input::old("placeholder_values_helpers[$item->key]"), array('class' => 'form-control', 'placeholder' => $item->description)) }}
			@endif
			@if($errors->has("placeholder_values_helpers.".$item->key))
				<div class ="alert alert-danger" id="characterError">{{$errors->first("placeholder_values_helpers.".$item->key)}}</div>
			@endif
		</div>
	@endforeach
</div>
<br/>