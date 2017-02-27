@extends('layouts.app')

@section('title')
	{{$tag->name}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<h2>{{{$tag->name}}}</h2>
	
	@if($tag->usage_count())
		<div>
			@if(($tag->primary_usage_count() > 0) && ($tag->secondary_usage_count() > 0))
				{{$tag->name}} is used a total of <b>{{$tag->usage_count()}}</b> times across the site.  <b>{{$tag->primary_usage_count()}}</b> times as a <b>primary tag</b> and <b>{{$tag->secondary_usage_count()}}</b> times as a <b>secondary tag</b>.
			@elseif($tag->primary_usage_count() > 0)
				{{$tag->name}} is used a total of <b>{{$tag->primary_usage_count()}}</b> times across the site as a primary tag.
			@elseif($tag->secondary_usage_count() > 0)
				{{$tag->name}} is used a total of <b>{{$tag->secondary_usage_count()}}</b> times across the site as a secondary tag.
			@else
				{{$tag->name}} is not associated with any collections.  
			@endif	
		</div>
	@endif
	
	@if(($tag->description != null) && ($tag->description != ""))
		<br/>	
		<div id="tag_info">
			{!!html_entity_decode(nl2br($tag->description))!!}
		</div>
	@endif
	
	
	@if($tag->url != null)
		<br/>
		<div>
			<span class="source_tag"><a href="{{$tag->url}}">Link to additional information</a></span>
		</div>
	@endif
</div>
@endsection

@section('footer')

@endsection