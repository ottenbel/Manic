@extends('layouts.app')

@section('title')
	{{$character->name}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<h2>{{{$character->name}}}</h2>
	<h3>Associated With <a href="/series/{{$character->series->id}}">{{$character->series->name}}</a></h3>
	
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
</div>
@endsection

@section('footer')

@endsection