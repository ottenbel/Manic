@extends('layouts.app')

@section('title')
	{{$tag->name}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<h2>{{{$tag->name}}}</h2>
	
	<div>
		@if($tag->usage_count())		
			@if(($tag->primary_usage_count() > 0) && ($tag->secondary_usage_count() > 0))
				{{$tag->name}} is used a total of <b>{{$tag->usage_count()}}</b> times across the site.  <b>{{$tag->primary_usage_count()}}</b> times as a <b>primary tag</b> and <b>{{$tag->secondary_usage_count()}}</b> times as a <b>secondary tag</b>.
			@elseif($tag->primary_usage_count() > 0)
				{{$tag->name}} is used a total of <b>{{$tag->primary_usage_count()}}</b> times across the site as a primary tag.
			@elseif($tag->secondary_usage_count() > 0)
				{{$tag->name}} is used a total of <b>{{$tag->secondary_usage_count()}}</b> times across the site as a secondary tag.  
			@endif	
		@else
			{{$tag->name}} is not associated with any collections.
		@endif
	</div>
	
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
	
	<br/>
	
	@if(($global_aliases->count() > 0) || (Auth::user()))
		<h3>Global Aliases</h3>
		
		@if(Auth::user())
			<form method="POST" action="/tag_alias" enctype="multipart/form-data">
				{{ csrf_field() }}
				{{ Form::hidden('is_global_alias', true) }}
				
				@include('partials.global-alias-input')
				
				{{ Form::submit('Create Global Tag Alias', array('class' => 'btn btn-primary')) }}
			</form>
		@endif
		
		@if($global_aliases->count() > 0)
			<div>
				<b>Display Order:</b>
				@if($global_list_order == "asc")
					<b><a href="/tag?global_order=asc">Ascending</a></b> <a href="/tag?global_order=desc">Descending</a>
				@elseif($global_list_order == "desc")
					<a href="/tag?global_order=asc">Ascending</a> <b><a href="/tag?global_order=desc">Descending</a></b>
				@endif
			</div>
		
			@foreach($global_aliases as $global_alias)
				<div class="row">
					<div class="col-xs-12">
						<span class="alias_tag"><a>{{$global_alias->alias}}</a></span>
					</div>
				</div>
			@endforeach
			
			{{ $global_aliases->links() }}
		@endif
	@endif
	
	@if(Auth::user())
		<h3>Personal Aliases</h3>
		
		<form method="POST" action="/tag_alias" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{ Form::hidden('is_global_alias', false) }}
			
			@include('partials.personal-alias-input')
			
			{{ Form::submit('Create Personal Tag Alias', array('class' => 'btn btn-primary')) }}
		</form>
		
		@if($personal_aliases->count() > 0)
			<div>
				<b>Display Order:</b>
				@if($personal_list_order == "asc")
					<b><a href="/tag?personal_order=asc">Ascending</a></b> <a href="/tag?personal_order=desc">Descending</a>
				@elseif($personal_list_order == "desc")
					<a href="/tag?personal_order=asc">Ascending</a> <b><a href="/tag?personal_order=desc">Descending</a></b>
				@endif
			</div>
		
			@foreach($personal_aliases as $personal_alias)
				<div class="row">
					<div class="col-xs-12">
						<span class="alias_tag"><a>{{$personal_alias->alias}}</a></span>
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