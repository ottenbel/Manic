@extends('layouts.app')

@section('title')
	{{$scanalator->name}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<h2>{{{$scanalator->name}}}</h2>
	
	<div>
		@if($scanalator->usage_count())
			@if(($scanalator->primary_usage_count() > 0) && ($scanalator->secondary_usage_count() > 0))
				{{$scanalator->name}} is used a total of <b>{{$scanalator->usage_count()}}</b> times across the site.  <b>{{$scanalator->primary_usage_count()}}</b> times as a <b>primary scanalator</b> and <b>{{$scanalator->secondary_usage_count()}}</b> times as a <b>secondary scanalator</b>.
			@elseif($scanalator->primary_usage_count() > 0)
				{{$scanalator->name}} is used a total of <b>{{$scanalator->primary_usage_count()}}</b> times across the site as a primary scanalator.
			@elseif($scanalator->secondary_usage_count() > 0)
				{{$scanalator->name}} is used a total of <b>{{$scanalator->secondary_usage_count()}}</b> times across the site as a secondary scanalator.  
			@endif	
		@else
			{{$scanalator->name}} is not associated with any collections.
		@endif
	</div>
	
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
	
	<br/>
	
	@if(($global_aliases->count() > 0) || (Auth::user()))
		<h3>Global Aliases</h3>
		
		@if(Auth::user())
			<form method="POST" action="/scanalator_alias/{{$scanalator->id}}" enctype="multipart/form-data">
				{{ csrf_field() }}
				{{ Form::hidden('is_global_alias', true) }}
				
				@include('partials.global-alias-input')
				
				{{ Form::submit('Create Global Scanalator Alias', array('class' => 'btn btn-primary')) }}
			</form>
		@endif
		
		@if($global_aliases->count() > 0)
			<div>
				<b>Display Order:</b>
				@if($global_list_order == "asc")
					<b><a href="/scanalator/{{$scanalator->id}}?global_order=asc">Ascending</a></b> <a href="/scanalator/{{$scanalator->id}}?global_order=desc">Descending</a>
				@elseif($global_list_order == "desc")
					<a href="/scanalator/{{$scanalator->id}}?global_order=asc">Ascending</a> <b><a href="/scanalator/{{$scanalator->id}}?global_order=desc">Descending</a></b>
				@endif
			</div>
		
			@foreach($global_aliases as $global_alias)
				<div class="row">
					<div class="col-xs-12">
						<span class="global_scanalator_alias"><a>{{$global_alias->alias}}</a></span>
					</div>
				</div>
			@endforeach
			
			{{ $global_aliases->links() }}
		@endif
	@endif
	
	@if(Auth::user())
		<h3>Personal Aliases</h3>
		
		<form method="POST" action="/scanalator_alias/{{$scanalator->id}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{ Form::hidden('is_personal_alias', true) }}
			
			@include('partials.personal-alias-input')
			
			{{ Form::submit('Create Personal Scanalator Alias', array('class' => 'btn btn-primary')) }}
		</form>
	
		@if($personal_aliases->count() > 0)
			<div>
				<b>Display Order:</b>
				@if($personal_list_order == "asc")
					<b><a href="/scanalator/{{$scanalator->id}}?personal_order=asc">Ascending</a></b> <a href="/scanalator/{{$scanalator->id}}?personal_order=desc">Descending</a>
				@elseif($personal_list_order == "desc")
					<a href="/scanalator/{{$scanalator->id}}?personal_order=asc">Ascending</a> <b><a href="/scanalator/{{$scanalator->id}}?personal_order=desc">Descending</a></b>
				@endif
			</div>
		
			@foreach($personal_aliases as $personal_alias)
				<div class="row">
					<div class="col-xs-12">
						<span class="personal_scanalator_alias"><a>{{$personal_alias->alias}}</a></span>
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