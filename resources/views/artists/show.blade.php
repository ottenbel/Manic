@extends('layouts.app')

@section('title')
	{{$artist->name}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<h2>{{{$artist->name}}}</h2>
	
	<div>
		@if($artist->usage_count())
			@if(($artist->primary_usage_count() > 0) && ($artist->secondary_usage_count() > 0))
				{{$artist->name}} is used a total of <b>{{$artist->usage_count()}}</b> times across the site.  <b>{{$artist->primary_usage_count()}}</b> times as a <b>primary artist</b> and <b>{{$artist->secondary_usage_count()}}</b> times as a <b>secondary artist</b>.
			@elseif($artist->primary_usage_count() > 0)
				{{$artist->name}} is used a total of <b>{{$artist->primary_usage_count()}}</b> times across the site as a primary artist.
			@elseif($artist->secondary_usage_count() > 0)
				{{$artist->name}} is used a total of <b>{{$artist->secondary_usage_count()}}</b> times across the site as a secondary artist.  
			@endif	
		@else
			{{$artist->name}} is not associated with any collections.
		@endif
	</div>
	
	@if(($artist->description != null) && ($artist->description != ""))
		<br/>	
		<div id="artist_info">
			{!!html_entity_decode(nl2br($artist->description))!!}
		</div>
	@endif
	
	
	@if($artist->url != null)
		<br/>
		<div>
			<span class="source_tag"><a href="{{$artist->url}}">Link to additional information</a></span>
		</div>
	@endif
	
	<br/>
	
	@if(($global_aliases->count() > 0) || (Auth::user()))
		<h3>Global Aliases</h3>
	
		@if($global_aliases->count() > 0)
			<div>
				<b>Display Order:</b>
				@if($global_list_order == "asc")
					<b><a href="/artist/{{$artist->id}}?global_order=asc">Ascending</a></b> <a href="/artist/{{$artist->id}}?global_order=desc">Descending</a>
				@elseif($global_list_order == "desc")
					<a href="/artist/{{$artist->id}}?global_order=asc">Ascending</a> <b><a href="/artist/{{$artist->id}}?global_order=desc">Descending</a></b>
				@endif
			</div>
		
			@foreach($global_aliases as $global_alias)
				<div class="row">
					<div class="col-xs-12">
						<span class="global_artist_alias"><a>{{$global_alias->alias}}</a></span>
					</div>
				</div>
			@endforeach
			
			{{ $global_aliases->links() }}
			<br/>
		@endif
	
		@if(Auth::user())
			<form method="POST" action="/artist_alias/{{$artist->id}}" enctype="multipart/form-data">
				{{ csrf_field() }}
				{{ Form::hidden('is_global_alias', true) }}
				
				@include('partials.global-alias-input')
				
				{{ Form::submit('Create Global Artist Alias', array('class' => 'btn btn-primary')) }}
			</form>
		@endif
	@endif
	
	@if(Auth::user())
		<h3>Personal Aliases</h3>
		
		@if($personal_aliases->count() > 0)
			<div>
				<b>Display Order:</b>
				@if($personal_list_order == "asc")
					<b><a href="/artist/{{$artist->id}}?personal_order=asc">Ascending</a></b> <a href="/artist/{{$artist->id}}?personal_order=desc">Descending</a>
				@elseif($personal_list_order == "desc")
					<a href="/artist/{{$artist->id}}?personal_order=asc">Ascending</a> <b><a href="/artist/{{$artist->id}}?personal_order=desc">Descending</a></b>
				@endif
			</div>
		
			@foreach($personal_aliases as $personal_alias)
				<div class="row">
					<div class="col-xs-12">
						<span class="personal_artist_alias"><a>{{$personal_alias->alias}}</a></span>
					</div>
				</div>
			@endforeach
			
			{{ $personal_aliases->links() }}
			<br/>
		@endif
		
		<form method="POST" action="/artist_alias/{{$artist->id}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{ Form::hidden('is_personal_alias', true) }}
			
			@include('partials.personal-alias-input')
			
			{{ Form::submit('Create Personal Artist Alias', array('class' => 'btn btn-primary')) }}
		</form>
	@endif
	
</div>
@endsection

@section('footer')

@endsection