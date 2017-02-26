@extends('layouts.app')

@section('title')
	{{$scanalator->name}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<h2>{{{$scanalator->name}}}</h2>
	
	@if($scanalator->usage_count()->count())
		<div>
			@if(($scanalator->primary_usage_count()->count() > 0) && ($scanalator->secondary_usage_count()->count() > 0))
				{{$scanalator->name}} is used a total of <b>{{$scanalator->usage_count()->count()}}</b> times across the site.  <b>{{$scanalator->primary_usage_count()->count()}}</b> times as a <b>primary scanalator</b> and <b>{{$scanalator->secondary_usage_count()->count()}}</b> times as a <b>secondary scanalator</b>.
			@elseif($scanalator->primary_usage_count()->count() > 0)
				{{$scanalator->name}} is used a total of <b>{{$scanalator->primary_usage_count()->count()}}</b> times across the site as a primary scanalator.
			@elseif($scanalator->secondary_usage_count()->count() > 0)
				{{$scanalator->name}} is used a total of <b>{{$scanalator->secondary_usage_count()->count()}}</b> times across the site as a secondary scanalator.
			@else
				{{$scanalator->name}} is not associated with any collections.  
			@endif	
		</div>
	@endif
	
	@if(($scanalator->description != null) && ($scanalator->description != ""))
		<br/>	
		<div id="scanalator_info">
			{!!html_entity_decode(nl2br($scanalator->description))!!}
		</div>
	@endif
	
	
	@if($scanalator->url != null)
		<br/>
		<div>
			<span class="source_tag"><a href="{{$scanalator->url}}">Link to additional information</a></span>
		</div>
	@endif
</div>
@endsection

@section('footer')

@endsection