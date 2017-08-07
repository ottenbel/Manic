<div class="form-group">
	{{ Form::label('name', 'Name') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{Config::get($nameHelpPlaceholder)}}"></i>
	@if((!empty($tagObject)) && ($tagObject->name != null) && (Input::old('name') == null))
		{{ Form::text('name', $tagObject->name, array('class' => 'form-control', 'placeholder' => Config::get($namePlaceholder))) }}
	@else
		{{ Form::text('name', Input::old('name'), array('class' => 'form-control', 'placeholder' => Config::get($namePlaceholder))) }}
	@endif
	@if($errors->has('name'))
		<div class ="alert alert-danger" id="name_errors">{{$errors->first('name')}}</div>
	@endif
</div>

<!-- Display textbox for child tag objects-->
@if(Route::is('edit_series'))
	<!-- Display the children that can't be removed from the given series (where children != 0 AND usage count != 0) -->
	@if($lockedChildren->count() > 0)
	<div class="row">
		<div class="col-xs-2">
			<b>Locked Children</b>
		</div>
		<!-- Display the children that can be removed from the given series (where children == 0 AND usage count == 0) -->
		<div class="col-xs-2">
			@foreach($lockedChildren as $lockedChild)
				<span class="primary_series"><a href="{{route('edit_series', ['series' => $lockedChild])}}">{{{$lockedChild->name}}} <span class="series_count">({{$lockedChild->usage_count()}})</span></a></span>
			@endforeach
		</div>
	</div>
	@endif
	<div class="form-group">
		{{ Form::label($child, 'New Children') }}
		<i class="fa fa-question-circle" aria-hidden="true" title="{{Config::get($childHelpPlaceholder)}}"></i>
		@if(($freeChildren->count() > 0) && (Input::old('description') == null))
			{{ Form::text($child, collect($freeChildren->pluck('name'))->implode(', '), array('class' => 'form-control', 'placeholder' => Config::get($childPlaceholder))) }}
		@else
			{{ Form::text($child, Input::old($child), array('class' => 'form-control', 'placeholder' => Config::get($childPlaceholder))) }}
		@endif
	</div>
@else
	<div class="form-group">
		{{ Form::label($child, 'Children') }}
		<i class="fa fa-question-circle" aria-hidden="true" title="{{Config::get($childHelpPlaceholder)}}"></i>
		@if((!empty($tagObject)) && ($tagObject->children()->count() > 0) && (Input::old($child) == null))
			{{ Form::text($child, collect($tagObject->children->pluck('name'))->implode(', '), array('class' => 'form-control', 'placeholder' => Config::get($childPlaceholder))) }}
		@else
			{{ Form::text($child, Input::old($child), array('class' => 'form-control', 'placeholder' => Config::get($childPlaceholder))) }}
		@endif
	</div>
@endif

<div class="form-group">
	{{ Form::label('short_description', 'Short Description') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{Config::get($shortDescriptionHelpPlaceholder)}}"></i>
	@if((!empty($tagObject)) && ($tagObject->short_description != null) && (Input::old('short_description') == null))
		{{ Form::text('short_description', $tagObject->short_description, array('class' => 'form-control', 'placeholder' => Config::get($shortDescriptionPlaceholder), 'maxLength' => 150)) }}
	@else
		{{ Form::text('short_description', Input::old('short_description'), array('class' => 'form-control', 'placeholder' => Config::get($shortDescriptionPlaceholder), 'maxLength' => 150)) }}
	@endif
</div>

<div class="form-group">
	{{ Form::label('description', 'Description') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{Config::get($descriptionHelpPlaceholder)}}"></i>
	@if((!empty($tagObject)) && ($tagObject->description != null) && (Input::old('description') == null))
		{{ Form::textarea('description', $tagObject->description, array('class' => 'form-control', 'placeholder' => Config::get($descriptionPlaceholder))) }}
	@else
		{{ Form::textarea('description', Input::old('description'), array('class' => 'form-control', 'placeholder' => Config::get($descriptionPlaceholder))) }}
	@endif
<div>

<div class="form-group">
	{{ Form::label('url', 'Source') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{Config::get($sourceHelpPlaceholder)}}"></i>
	@if((!empty($tagObject)) && ($tagObject->url != null) && (Input::old('url') == null))
		{{ Form::text('url', $tagObject->url, array('class' => 'form-control', 'placeholder' => Config::get($sourcePlaceholder))) }}
	@else
		{{ Form::text('url', Input::old('url'), array('class' => 'form-control', 'placeholder' => Config::get($sourcePlaceholder))) }}
	@endif
	@if($errors->has('url'))
		<div class ="alert alert-danger" id="url_errors">{{$errors->first('url')}}</div>
	@endif
</div>