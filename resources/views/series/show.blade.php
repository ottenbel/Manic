@extends('layouts.app')

@section('title')
	{{$series->name}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<h2>{{{$series->name}}}</h2>
	
	<div>
		@if($series->usage_count())
			@if(($series->primary_usage_count() > 0) && ($series->secondary_usage_count() > 0))
				{{$series->name}} is used a total of <b>{{$series->usage_count()}}</b> times across the site.  <b>{{$series->primary_usage_count()}}</b> times as a <b>primary series</b> and <b>{{$series->secondary_usage_count()}}</b> times as a <b>secondary series</b>.
			@elseif($series->primary_usage_count() > 0)
				{{$series->name}} is used a total of <b>{{$series->primary_usage_count()}}</b> times across the site as a primary series.
			@elseif($series->secondary_usage_count() > 0)
				{{$series->name}} is used a total of <b>{{$series->secondary_usage_count()}}</b> times across the site as a secondary series.  
			@endif	
		@else
			{{$series->name}} is not associated with any collections.
		@endif
	</div>
	
	@if(($series->description != null) && ($series->description != ""))
		<br/>	
		<div id="series_info">
			{!!html_entity_decode(nl2br($series->description))!!}
		</div>
	@endif
		
	@if($series->url != null)
		<br/>
		<div>
			<span class="source_tag"><a href="{{$series->url}}">Link to additional information</a></span>
		</div>
	@endif
	
	@if($series->characters->count())
		<h3>Associated Characters</h3>
		<div>
			<div>
				<div>
					<b>Sort By:</b>
					@if($character_list_type == "usage")
						<b><a href="/series/{{$series->id}}?character_type=usage&character_order={{$character_list_order}}">Character Usage</a></b> <a href="/series/{{$series->id}}?character_type=alphabetic&character_order={{$character_list_order}}">Alphabetic</a>
					@elseif ($character_list_type == "alphabetic")
						<a href="/series/{{$series->id}}?character_type=usage&character_order={{$character_list_order}}">Character Usage</a> <b><a href="/series/{{$series->id}}?character_type=alphabetic&character_order={{$character_list_order}}">Alphabetic</a></b>
					@endif
				</div>
				
				<div>
					<b>Display Order:</b>
					@if($character_list_order == "asc")
						<b><a href="/series/{{$series->id}}?character_type={{$character_list_type}}&character_order=asc">Ascending</a></b> <a href="/series/{{$series->id}}?character_type={{$character_list_type}}&character_order=desc">Descending</a>
					@elseif($character_list_order == "desc")
						<a href="/series/{{$series->id}}?character_type={{$character_list_type}}&character_order=asc">Ascending</a> <b><a href="/series/{{$series->id}}?character_type={{$character_list_type}}&character_order=desc">Descending</a></b>
					@endif
				</div>
			</div>
			
			@foreach($characters as $character)
				@if((($loop->iteration - 1) % 3) == 0)
					<div class="row">
				@endif
				
				<div class="col-xs-4">
					<span class="primary_characters"><a href="/character/{{$character->id}}">{{{$character->name}}} <span class="character_count">({{$character->usage_count()}})</span></a></span>
				</div>
				
				@if((($loop->iteration - 1) % 3) == 2)			
					</div>
				@endif
		@endforeach
		<br/>
		<br/>
		{{ $characters->links() }}
	</div>		
	@endif
	
	<br/>
	
	@if(($global_aliases->count() > 0) || (Auth::user()))
		<h3>Global Aliases</h3>
		
		@if(Auth::user())
			<form method="POST" action="/series_alias/{{$series->id}}" enctype="multipart/form-data">
				{{ csrf_field() }}
				{{ Form::hidden('is_global_alias', true) }}
				
				@include('partials.global-alias-input')
				
				{{ Form::submit('Create Global Series Alias', array('class' => 'btn btn-primary')) }}
			</form>
		@endif
		
		@if($global_aliases->count() > 0)
			<div>
				<b>Display Order:</b>
				@if($global_list_order == "asc")
					<b><a href="/series/{{$series->id}}?global_order=asc">Ascending</a></b> <a href="/series/{{$series->id}}?global_order=desc">Descending</a>
				@elseif($global_list_order == "desc")
					<a href="/series/{{$series->id}}?global_order=asc">Ascending</a> <b><a href="/series/{{$series->id}}?global_order=desc">Descending</a></b>
				@endif
			</div>
		
			@foreach($global_aliases as $global_alias)
				<div class="row">
					<div class="col-xs-12">
						<span class="global_series_alias"><a>{{$global_alias->alias}}</a></span>
					</div>
				</div>
			@endforeach
			
			{{ $global_aliases->links() }}
		@endif
	@endif
	
	@if(Auth::user())
		<h3>Personal Aliases</h3>
		
		<form method="POST" action="/series_alias/{{$series->id}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{ Form::hidden('is_personal_alias', true) }}
			
			@include('partials.personal-alias-input')
			
			{{ Form::submit('Create Personal Series Alias', array('class' => 'btn btn-primary')) }}
		</form>
	
		@if($personal_aliases->count() > 0)
			<div>
				<b>Display Order:</b>
				@if($personal_list_order == "asc")
					<b><a href="/series/{{$series->id}}?personal_order=asc">Ascending</a></b> <a href="/series/{{$series->id}}?personal_order=desc">Descending</a>
				@elseif($personal_list_order == "desc")
					<a href="/series/{{$series->id}}?personal_order=asc">Ascending</a> <b><a href="/series/{{$series->id}}?personal_order=desc">Descending</a></b>
				@endif
			</div>
		
			@foreach($personal_aliases as $personal_alias)
				<div class="row">
					<div class="col-xs-12">
						<span class="personal_series_alias"><a>{{$personal_alias->alias}}</a></span>
					</div>
				</div>
			@endforeach
			
			{{ $personal_aliases->links() }}
		@endif
	@endif
</div>
@endsection

@section('footer')

@endsection