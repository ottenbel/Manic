@extends('layouts.app')

@section('title')
	{{$artist->name}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<h2>{{{$artist->name}}}</h2>
	
	@if($artist->usage_count())
		<div>
			@if(($artist->primary_usage_count() > 0) && ($artist->secondary_usage_count() > 0))
				{{$artist->name}} is used a total of <b>{{$artist->usage_count()}}</b> times across the site.  <b>{{$artist->primary_usage_count()}}</b> times as a <b>primary artist</b> and <b>{{$artist->secondary_usage_count()}}</b> times as a <b>secondary artist</b>.
			@elseif($artist->primary_usage_count() > 0)
				{{$artist->name}} is used a total of <b>{{$artist->primary_usage_count()}}</b> times across the site as a primary artist.
			@elseif($artist->secondary_usage_count() > 0)
				{{$artist->name}} is used a total of <b>{{$artist->secondary_usage_count()}}</b> times across the site as a secondary artist.
			@else
				{{$artist->name}} is not associated with any collections.  
			@endif	
		</div>
	@endif
	
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
</div>
@endsection

@section('footer')

@endsection