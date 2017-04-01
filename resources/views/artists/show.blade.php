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
	
	@if(($global_aliases->count() > 0) || (Auth::user()))
		<h3>Global Aliases</h3>
	
		@if(Auth::user())
			<form method="POST" action="/artist_alias" enctype="multipart/form-data">
				{{ csrf_field() }}
				{{ Form::hidden('global_alias', true) }}
				
				@include('partials.global-alias-input')
				
				{{ Form::submit('Create Global Artist Alias', array('class' => 'btn btn-primary')) }}
			</form>
		@endif
		
		@foreach($global_aliases as $global_alias)
			<div class="row">
				<div class="col-xs-12">
					<span class="alias_tag"><a>{{$global_alias->alias}}</a></span>
				</div>
			</div>
		@endforeach
		
		{{ $global_aliases->links() }}
	@endif
	
	@if(Auth::user())
		<h3>Personal Aliases</h3>
		
		<form method="POST" action="/artist_alias" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{ Form::hidden('global_alias', false) }}
			
			@include('partials.personal-alias-input')
			
			{{ Form::submit('Create Personal Artist Alias', array('class' => 'btn btn-primary')) }}
		</form>

		@foreach($personal_aliases as $personal_alias)
			<div class="row">
				<div class="col-xs-12">
					<span class="alias_tag"><a>{{$personal_alias->alias}}</a></span>
				</div>
			</div>
		@endforeach
		
		{{ $personal_aliases->links() }}
	@endif
	
	
</div>
@endsection

@section('footer')

@endsection