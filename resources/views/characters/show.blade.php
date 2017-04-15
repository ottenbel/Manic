@extends('layouts.app')

@section('title')
	{{$character->name}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<h2>{{{$character->name}}}</h2>
	<h4>Associated With Collection: <a href="/series/{{$character->series->id}}">{{$character->series->name}}</a></h4>
	
	<div>
		@if($character->usage_count())
			@if(($character->primary_usage_count() > 0) && ($character->secondary_usage_count() > 0))
				{{$character->name}} is used a total of <b>{{$character->usage_count()}}</b> times across the site.  <b>{{$character->primary_usage_count()}}</b> times as a <b>primary character</b> and <b>{{$character->secondary_usage_count()}}</b> times as a <b>secondary character</b>.
			@elseif($character->primary_usage_count() > 0)
				{{$character->name}} is used a total of <b>{{$character->primary_usage_count()}}</b> times across the site as a primary character.
			@elseif($character->secondary_usage_count() > 0)
				{{$character->name}} is used a total of <b>{{$character->secondary_usage_count()}}</b> times across the site as a secondary character.
			@endif
		@else
			{{$character->name}} is not associated with any collections.  
		@endif
	</div>
	
	@if(($character->description != null) && ($character->description != ""))
		<br/>	
		<div id="character_info">
			{!!html_entity_decode(nl2br($character->description))!!}
		</div>
	@endif
	
	
	@if($character->url != null)
		<br/>
		<div>
			<span class="source_tag"><a href="{{$character->url}}">Link to additional information</a></span>
		</div>
	@endif
	
	<br/>
	
	@if(($global_aliases->count() > 0) || (Auth::user()))
		<h3>Global Aliases</h3>
		
		@if(Auth::user())
			<form method="POST" action="/character_alias/{{$character->id}}" enctype="multipart/form-data">
				{{ csrf_field() }}
				{{ Form::hidden('is_global_alias', true) }}
				
				@include('partials.global-alias-input')
				
				{{ Form::submit('Create Global Character Alias', array('class' => 'btn btn-primary')) }}
			</form>
		@endif
		
		@if($global_aliases->count() > 0)
			<div>
				<b>Display Order:</b>
				@if($global_list_order == "asc")
					<b><a href="/character/{{$character->id}}?global_order=asc">Ascending</a></b> <a href="/character/{{$character->id}}?global_order=desc">Descending</a>
				@elseif($global_list_order == "desc")
					<a href="/character/{{$character->id}}?global_order=asc">Ascending</a> <b><a href="/character/{{$character->id}}?global_order=desc">Descending</a></b>
				@endif
			</div>
		
			@foreach($global_aliases as $global_alias)
				<div class="row">
					<div class="col-xs-12">
						<span class="global_character_alias"><a>{{$global_alias->alias}}</a></span>
					</div>
				</div>
			@endforeach
			
			{{ $global_aliases->links() }}
		@endif
	@endif
	
	@if(Auth::user())
		<h3>Personal Aliases</h3>
		
		<form method="POST" action="/character_alias/{{$character->id}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{ Form::hidden('is_personal_alias', true) }}
			
			@include('partials.personal-alias-input')
			
			{{ Form::submit('Create Personal Character Alias', array('class' => 'btn btn-primary')) }}
		</form>

		@if($personal_aliases->count() > 0)
			<div>
				<b>Display Order:</b>
				@if($personal_list_order == "asc")
					<b><a href="/character/{{$character->id}}?personal_order=asc">Ascending</a></b> <a href="/character/{{$character->id}}?personal_order=desc">Descending</a>
				@elseif($personal_list_order == "desc")
					<a href="/character/{{$character->id}}?personal_order=asc">Ascending</a> <b><a href="/character/{{$character->id}}?personal_order=desc">Descending</a></b>
				@endif
			</div>
		
			@foreach($personal_aliases as $personal_alias)
				<div class="row">
					<div class="col-xs-12">
						<span class="personal_character_alias"><a>{{$personal_alias->alias}}</a></span>
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