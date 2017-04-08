@extends('layouts.app')

@section('title')
Edit Character - {{{$tagObject->name}}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<h1>Edit Character</h1>
	<h2>Associated With <a href="/series/{{$tagObject->series->id}}">{{$tagObject->series->name}}</a></h2>
	
	<form method="POST" action="/character/{{$tagObject->id}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		{{method_field('PATCH')}}
		
		{{ Form::hidden('character_id', $tagObject->id) }}
		
		@include('partials.tag-object-input')
		
		{{ Form::submit('Update Character', array('class' => 'btn btn-primary')) }}
	</form>
	
	<br/>
	
	@if(($global_aliases->count() > 0) || (Auth::user()))
		<h3>Global Aliases</h3>
		
		@if(Auth::user())
			<form method="POST" action="/character_alias/{{$tagObject->id}}" enctype="multipart/form-data">
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
					<b><a href="/character/{{$tagObject->id}}?global_order=asc">Ascending</a></b> <a href="/character/{{$tagObject->id}}?global_order=desc">Descending</a>
				@elseif($global_list_order == "desc")
					<a href="/character/{{$tagObject->id}}?global_order=asc">Ascending</a> <b><a href="/character/{{$tagObject->id}}?global_order=desc">Descending</a></b>
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
		
		<form method="POST" action="/character_alias/{{$tagObject->id}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{ Form::hidden('is_global_alias', false) }}
			
			@include('partials.personal-alias-input')
			
			{{ Form::submit('Create Personal Character Alias', array('class' => 'btn btn-primary')) }}
		</form>
		
		@if($personal_aliases->count() > 0)
			<div>
				<b>Display Order:</b>
				@if($personal_list_order == "asc")
					<b><a href="/character/{{$tagObject->id}}?personal_order=asc">Ascending</a></b> <a href="/character/{{$tagObject->id}}?personal_order=desc">Descending</a>
				@elseif($personal_list_order == "desc")
					<a href="/character/{{$tagObject->id}}?personal_order=asc">Ascending</a> <b><a href="/character/{{$tagObject->id}}?personal_order=desc">Descending</a></b>
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