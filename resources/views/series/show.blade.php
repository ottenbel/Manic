@extends('layouts.app')

@section('title')
	{{$series->name}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<h2>{{{$series->name}}}</h2>
	
	@if($series->usage_count())
		<div>
			@if(($series->primary_usage_count() > 0) && ($series->secondary_usage_count() > 0))
				{{$series->name}} is used a total of <b>{{$series->usage_count()}}</b> times across the site.  <b>{{$series->primary_usage_count()}}</b> times as a <b>primary series</b> and <b>{{$series->secondary_usage_count()}}</b> times as a <b>secondary series</b>.
			@elseif($series->primary_usage_count() > 0)
				{{$series->name}} is used a total of <b>{{$series->primary_usage_count()}}</b> times across the site as a primary series.
			@elseif($series->secondary_usage_count() > 0)
				{{$series->name}} is used a total of <b>{{$series->secondary_usage_count()}}</b> times across the site as a secondary series.
			@else
				{{$series->name}} is not associated with any collections.  
			@endif	
		</div>
	@endif
	
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
</div>
@endsection

@section('footer')

@endsection