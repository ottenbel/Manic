<div class="form-group">
	{{ Form::label('cover', 'Cover Image') }}
	{{ Form::file('image') }}
	@if ($errors->has('image'))
		<div class ="alert alert-danger" id="image_errors">{{$errors->first('image')}}</div>
	@endif
</div>

<div class="form-group">
	{{ Form::label('name', 'Name') }}
	@if(($collection->name != null) && (Input::old('name') == null))
		{{ Form::text('name', $collection->name, array('class' => 'form-control')) }}
	@else
		{{ Form::text('name', Input::old('name'), array('class' => 'form-control')) }}
	@endif
	@if($errors->has('name'))
		<div class ="alert alert-danger" id="name_errors">{{$errors->first('name')}}</div>
	@endif
</div>

<div class="form-group">
	{{ Form::label('description', 'Description') }}
	@if(($collection->description != null) && (Input::old('description') == null))
		{{ Form::textarea('description', $collection->description, array('class' => 'form-control')) }}
	@else
		{{ Form::textarea('description', Input::old('description'), array('class' => 'form-control')) }}
	@endif
<div>

<div class="form-group">
	{{ Form::label('parent_id', 'Parent Collection') }}
	@if(($collection->parent_id != null) && (Input::old('parent_id') == null))
		{{ Form::text('parent_id', $collection->parent_id, array('class' => 'form-control')) }}
	@else
		{{ Form::text('parent_id', Input::old('parent_id'), array('class' => 'form-control')) }}
	@endif	
	
	@if($errors->has('parent_id'))
		<div class ="alert alert-danger" id="parent_id_errors">{{$errors->first('parent_id')}}</div>
	@endif
</div>

<div class="form-group">
	{{ Form::label('language', 'Language') }}
	@if(($collection->language != null) && (Input::old('language') == null))
		{{ Form::select('language', $collection->language, $languages) }}
	@else
		{{ Form::select('language', Input::old('language'), $languages) }}
	@endif
</div>

<div class="form-group">
	{{ Form::label('artist_primary', 'Primary Artists') }}
	@if(($collection->artist_primary != null) && (Input::old('artist_primary') == null))
		{{ Form::text('artist_primary', implode(', ', $collection->artist_primary), array('class' => 'form-control')) }}
	@else
		{{ Form::text('artist_primary', Input::old('artist_primary'), array('class' => 'form-control')) }}
	@endif
	
	{{ Form::label('artist_secondary', 'Secondary Artists') }}
	@if(($collection->artist_secondary != null) && (Input::old('artist_secondary') == null))
		{{ Form::text('artist_primary', implode(', ', $collection->artist_secondary), array('class' => 'form-control')) }}
	@else
		{{ Form::text('artist_secondary', Input::old('artist_secondary'), array('class' => 'form-control')) }}
	@endif
</div>

<div class="form-group">
	{{ Form::label('series_primary', 'Series Primary') }}
	
	@if(($collection->series_primary != null) && (Input::old('series_primary') == null))
		{{ Form::text('series_primary', implode(', ', $collection->series_primary), array('class' => 'form-control')) }}
	@else
		{{ Form::text('series_primary', Input::old('series_primary'), array('class' => 'form-control')) }}
	@endif
	
	{{ Form::label('series_secondary', 'Series Secondary') }}
	@if(($collection->series_secondary != null) && (Input::old('series_secondary') == null))
		{{ Form::text('series_secondary', implode(', ', $collection->series_secondary), array('class' => 'form-control')) }}
	@else
		{{ Form::text('series_secondary', Input::old('series_secondary'), array('class' => 'form-control')) }}
	@endif
</div>

<div class="form-group">
	{{ Form::label('tag_primary', 'Tags Primary') }}
	@if(($collection->tag_primary != null) && (Input::old('tag_primary') == null))
		{{ Form::text('tag_primary', implode(', ', $collection->tag_primary), array('class' => 'form-control')) }}
	@else
		{{ Form::text('tag_primary', Input::old('tag_primary'), array('class' => 'form-control')) }}
	@endif
	
	{{ Form::label('tag_secondary', 'Tags Secondary') }}
	@if(($collection->tag_secondary != null) && (Input::old('tag_secondary') == null))
		{{ Form::text('tag_secondary', implode(', ', $collection->secondary), array('class' => 'form-control')) }}
	@else
		{{ Form::text('tag_secondary', Input::old('tag_secondary'), array('class' => 'form-control')) }}
	@endif
</div>

<div class="form-group">
	{{ Form::label('canonical', 'Canonical') }}
	@if(($collection->canonical != null) && (Input::old('canonical') == null))
		{{ Form::checkbox('canonical', $collection->canonical, array('class' => 'form-control')) }}
	@else
		{{ Form::checkbox('canonical', Input::old('canonical'), array('class' => 'form-control')) }}
	@endif
</div>

<div class="form-group">
	{{ Form::label('rating', 'Rating: ') }}
	@foreach($ratings as $rating)
		@if(Input::old('ratings') != null)
			@if($rating->id == Input::old('ratings'))
				{{ Form::radio('ratings', $rating->id, true, array('id'=>"ratings-$rating->priority")) }}
			@else
				{{ Form::radio('ratings', $rating->id, false, array('id'=>"ratings-$rating->priority")) }}
			@endif
	
		@elseif($collection->rating != null)
			@if($rating->id == $collection->rating)
				{{ Form::radio('ratings', $rating->id, true, array('id'=>"ratings-$rating->priority")) }}
			@else
				{{ Form::radio('ratings', $rating->id, false, array('id'=>"ratings-$rating->priority")) }}
			@endif
		@else
			{{ Form::radio('ratings', $rating->id, false, array('id'=>"ratings-$rating->priority")) }}
		@endif
		
		{{ Form::label("ratings-$rating->priority", $rating->name) }}
	@endforeach
</div>

<div class="form-group">
	{{ Form::label('statuses', 'Status: ') }}
	@foreach($statuses as $status)
		@if(Input::old('statuses') != null)
			@if($status->id == Input::old('statuses'))
				{{ Form::radio('statuses', $status->id, true, array('id'=>"statuses-$status->priority")) }}
			@else
				{{ Form::radio('statuses', $status->id, false, array('id'=>"statuses-$status->priority")) }}
			@endif
		@elseif($collection->status != null)
			@if($status->id == $collection->status)
				{{ Form::radio('statuses', $status->id, true, array('id'=>"statuses-$status->priority")) }}
			@else
				{{ Form::radio('statuses', $status->id, false, array('id'=>"statuses-$status->priority")) }}
			@endif
		@else
			{{ Form::radio('statuses', $status->id, false, array('id'=>"statuses-$status->priority")) }}
		@endif
	
		{{ Form::label("statuses-$status->priority", $status->name) }}
	@endforeach
</div>