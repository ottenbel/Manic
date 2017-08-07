<div class="form-group">
	<div class="row">
		@if(!empty($collection) && ($collection->cover_image != null))
			<div id="cover" class="col-md-4">
				<a href="{{route('show_collection', ['collection' => $collection])}}"><img src="{{asset($collection->cover_image->name)}}" class="img-thumbnail" height="100px" width="100%"></a>
			</div>
		@endif
		<div id="cover_edit" class="col-md-8">
			{{ Form::label('cover', 'Cover Image') }}
			<i class="fa fa-question-circle" aria-hidden="true" title="{{Config::get('constants.placeholders.help.collections.cover')}}"></i>
			{{ Form::file('image') }}
			@if(!empty($collection) && ($collection->cover_image != null))
				{{ Form::label('delete_cover', 'Remove Cover Image') }}
				{{ Form::checkbox('delete_cover', null, Input::old('delete_cover')) }}
			@endif
		</div>
	</div>
	@if ($errors->has('image'))
		<div class ="alert alert-danger" id="image_errors">{{$errors->first('image')}}</div>
	@endif
</div>

<div class="form-group">
	{{ Form::label('name', 'Name') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{Config::get('constants.placeholders.help.collections.name')}}"></i>
	@if((!empty($collection)) && ($collection->name != null) && (Input::old('name') == null))
		{{ Form::text('name', $collection->name, array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.collections.name'))) }}
	@else
		{{ Form::text('name', Input::old('name'), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.collections.name'))) }}
	@endif
	@if($errors->has('name'))
		<div class ="alert alert-danger" id="name_errors">{{$errors->first('name')}}</div>
	@endif
</div>

<div class="form-group">
	{{ Form::label('description', 'Description') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{Config::get('constants.placeholders.help.collections.description')}}"></i>
	@if((!empty($collection)) && ($collection->description != null) && (Input::old('description') == null))
		{{ Form::textarea('description', $collection->description, array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.collections.description'))) }}
	@else
		{{ Form::textarea('description', Input::old('description'), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.collections.description'))) }}
	@endif
<div>

<div class="form-group">
	{{ Form::label('parent_id', 'Parent Collection') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{Config::get('constants.placeholders.help.collections.parent_id')}}"></i>
	@if((!empty($collection)) && ($collection->parent_id != null) && (Input::old('parent_id') == null))
		{{ Form::text('parent_id', $collection->parent_id, array('class' => 'form-control')) }}
	@else
		{{ Form::text('parent_id', Input::old('parent_id'), array('class' => 'form-control')) }}
	@endif	
	
	@if($errors->has('parent_id'))
		<div class ="alert alert-danger" id="parent_id_errors">{{$errors->first('parent_id')}}</div>
	@endif
</div>

<div class="form-group">
	{{ Form::label('artist_primary', 'Primary Artists') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{Config::get('constants.placeholders.help.collections.artist_primary')}}"></i>
	@if((!empty($collection)) && ($collection->primary_artists != null) && (Input::old('artist_primary') == null))
		{{ Form::text('artist_primary', collect($collection->primary_artists->pluck('name'))->implode(", "), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.collections.primaryArtists'))) }}
	@else
		{{ Form::text('artist_primary', Input::old('artist_primary'), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.collections.primaryArtists'))) }}
	@endif
	@if($errors->has('artist_primary'))
		<div class ="alert alert-danger" id="name_errors">{{$errors->first('artist_primary')}}</div>
	@endif
	
	{{ Form::label('artist_secondary', 'Secondary Artists') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{Config::get('constants.placeholders.help.collections.artist_secondary')}}"></i>
	@if((!empty($collection)) && ($collection->secondary_artists != null) && (Input::old('artist_secondary') == null))
		{{ Form::text('artist_secondary', collect($collection->secondary_artists->pluck('name'))->implode(', '), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.collections.secondaryArtists'))) }}
	@else
		{{ Form::text('artist_secondary', Input::old('artist_secondary'), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.collections.secondaryArtists'))) }}
	@endif
	@if($errors->has('artist_secondary'))
		<div class ="alert alert-danger" id="name_errors">{{$errors->first('artist_secondary')}}</div>
	@endif
</div>

<div class="form-group">
	{{ Form::label('series_primary', 'Series Primary') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{Config::get('constants.placeholders.help.collections.series_primary')}}"></i>
	@if((!empty($collection)) && ($collection->primary_series != null) && (Input::old('series_primary') == null))
		{{ Form::text('series_primary', collect($collection->primary_series->pluck('name'))->implode(', '), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.collections.primarySeries'))) }}
	@else
		{{ Form::text('series_primary', Input::old('series_primary'), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.collections.primarySeries'))) }}
	@endif
	@if($errors->has('series_primary'))
		<div class ="alert alert-danger" id="name_errors">{{$errors->first('series_primary')}}</div>
	@endif
	
	{{ Form::label('series_secondary', 'Series Secondary') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{Config::get('constants.placeholders.help.collections.series_secondary')}}"></i>
	@if((!empty($collection)) && ($collection->secondary_series != null) && (Input::old('series_secondary') == null))
		{{ Form::text('series_secondary', collect($collection->secondary_series->pluck('name'))->implode(', '), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.collections.secondarySeries'))) }}
	@else
		{{ Form::text('series_secondary', Input::old('series_secondary'), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.collections.secondarySeries'))) }}
	@endif
	@if($errors->has('series_secondary'))
		<div class ="alert alert-danger" id="name_errors">{{$errors->first('series_secondary')}}</div>
	@endif
</div>

<div class="form-group">
	{{ Form::label('character_primary', 'Characters Primary') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{Config::get('constants.placeholders.help.collections.character_primary')}}"></i>
	@if((!empty($collection)) && ($collection->primary_characters != null) && (Input::old('character_primary') == null))
		{{ Form::text('character_primary', collect($collection->primary_characters->pluck('name'))->implode(', '), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.collections.primaryCharacters'))) }}
	@else
		{{ Form::text('character_primary', Input::old('character_primary'), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.collections.primaryCharacters'))) }}
	@endif
	
	@if($errors->has('character_primary'))
		<div class ="alert alert-danger" id="character_primary_errors">{{$errors->first('character_primary')}}</div>
	@endif
	
	{{ Form::label('character_secondary', 'Characters Secondary') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{Config::get('constants.placeholders.help.collections.character_secondary')}}"></i>
	@if((!empty($collection)) && ($collection->secondary_characters != null) && (Input::old('character_secondary') == null))
		{{ Form::text('character_secondary', collect($collection->secondary_characters->pluck('name'))->implode(', '), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.collections.secondaryCharacters'))) }}
	@else
		{{ Form::text('character_secondary', Input::old('character_secondary'), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.collections.secondaryCharacters'))) }}
	@endif
	
	@if($errors->has('character_secondary'))
		<div class ="alert alert-danger" id="character_secondary_errors">{{$errors->first('character_secondary')}}</div>
	@endif
</div>

<div class="form-group">
	{{ Form::label('tag_primary', 'Tags Primary') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{Config::get('constants.placeholders.help.collections.tag_primary')}}"></i>
	@if((!empty($collection)) && ($collection->primary_tags != null) && (Input::old('tag_primary') == null))
		{{ Form::text('tag_primary', collect($collection->primary_tags->pluck('name'))->implode(', '), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.collections.primaryTags'))) }}
	@else
		{{ Form::text('tag_primary', Input::old('tag_primary'), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.collections.primaryTags'))) }}
	@endif
	@if($errors->has('tag_primary'))
		<div class ="alert alert-danger" id="character_secondary_errors">{{$errors->first('tag_primary')}}</div>
	@endif
	
	{{ Form::label('tag_secondary', 'Tags Secondary') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{Config::get('constants.placeholders.help.collections.tag_secondary')}}"></i>
	@if((!empty($collection)) && ($collection->secondary_tags != null) && (Input::old('tag_secondary') == null))
		{{ Form::text('tag_secondary', collect($collection->secondary_tags->pluck('name'))->implode(', '), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.collections.secondaryTags'))) }}
	@else
		{{ Form::text('tag_secondary', Input::old('tag_secondary'), array('class' => 'form-control', 'placeholder' => Config::get('constants.placeholders.collections.secondaryTags'))) }}
	@endif
	@if($errors->has('tag_secondary'))
		<div class ="alert alert-danger" id="character_secondary_errors">{{$errors->first('tag_secondary')}}</div>
	@endif
</div>

<div class="form-group">
	{{ Form::label('canonical', 'Canonical') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{Config::get('constants.placeholders.help.collections.canonical')}}"></i>
	@if((!empty($collection)) && ($collection->canonical != null) && (Input::old('canonical') == null))
		{{ Form::checkbox('canonical', $collection->canonical, array('class' => 'form-control')) }}
	@else
		{{ Form::checkbox('canonical', null, Input::old('canonical')) }}
	@endif
</div>

<div class="form-group">
	{{ Form::label('language', 'Language') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{Config::get('constants.placeholders.help.collections.language')}}"></i>
	@if((!empty($collection)) && ($collection->language != null) && (Input::old('language') == null))
		{{ Form::select('language', $languages, $collection->language->id) }}
	@else
		{{ Form::select('language', $languages, Input::old('language')) }}
	@endif
</div>

<div class="form-group">
	{{ Form::label('ratings', 'Rating: ') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{Config::get('constants.placeholders.help.collections.ratings')}}"></i>
	@if((!empty($collection)) && ($collection->rating != null) && (Input::old('ratings') == null))
		{{ Form::select('ratings', $ratings, $collection->rating->id) }}
	@else
		{{ Form::select('ratings', $ratings, Input::old('ratings')) }}
	@endif
</div>

<div class="form-group">
	{{ Form::label('statuses', 'Status: ') }}
	<i class="fa fa-question-circle" aria-hidden="true" title="{{Config::get('constants.placeholders.help.collections.statuses')}}"></i>
	@if((!empty($collection)) && ($collection->status != null) && (Input::old('statuses') == null))
		{{ Form::select('statuses', $statuses, $collection->status->id) }}
	@else
		{{ Form::select('statuses', $statuses, Input::old('statuses')) }}
	@endif
</div>