<div class="form-group">
	{{ Form::label('name', 'Name') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{$configurations['name']->description}}"></i>
	@if((!empty($language)) && ($language->name != null) && (Input::old('name') == null))
		{{ Form::text('name', $language->name, array('class' => 'form-control', 'placeholder' => $configurations['name']->value)) }}
	@else
		{{ Form::text('name', Input::old('name'), array('class' => 'form-control', 'placeholder' => $configurations['name']->value)) }}
	@endif
	
	@if($errors->has('name'))
		<div class ="alert alert-danger" id="name_errors">{{$errors->first('name')}}</div>
	@endif
</div>

<div class="form-group">
	{{ Form::label('description', 'Description') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{$configurations['description']->description}}"></i>
	@if((!empty($language)) && ($language->description != null) && (Input::old('description') == null))
		{{ Form::text('description', $language->description, array('class' => 'form-control', 'placeholder' => $configurations['description']->value)) }}
	@else
		{{ Form::text('description', Input::old('description'), array('class' => 'form-control', 'placeholder' => $configurations['description']->value)) }}
	@endif
</div>

<div class="form-group">
	{{ Form::label('url', 'URL') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{$configurations['url']->description}}"></i>
	@if((!empty($language)) && ($language->url != null) && (Input::old('url') == null))
		{{ Form::text('url', $language->url, array('class' => 'form-control', 'placeholder' => $configurations['url']->value)) }}
	@else
		{{ Form::text('url', Input::old('url'), array('class' => 'form-control', 'placeholder' => $configurations['url']->value)) }}
	@endif
	
	@if($errors->has('url'))
		<div class ="alert alert-danger" id="url_errors">{{$errors->first('url')}}</div>
	@endif
</div>