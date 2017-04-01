@extends('layouts.app')

@section('title')
	Edit Series - {{{$tagObject->name}}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<h1>Edit Series</h1>
	
	<form method="POST" action="/series/{{$tagObject->id}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		{{method_field('PATCH')}}
		
		{{ Form::hidden('series_id', $tagObject->id) }}
		
		@include('partials.tag-object-input')
		
		{{ Form::submit('Update Series', array('class' => 'btn btn-primary')) }}
	</form>
	
	@if(($global_aliases->count() > 0) || (Auth::user()))
		<h3>Global Aliases</h3>
		
		@if(Auth::user())
			<form method="POST" action="/series_alias" enctype="multipart/form-data">
				{{ csrf_field() }}
				{{ Form::hidden('global_alias', true) }}
				
				@include('partials.global-alias-input')
				
				{{ Form::submit('Create Global Series Alias', array('class' => 'btn btn-primary')) }}
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
		
		<form method="POST" action="/series_alias" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{ Form::hidden('global_alias', false) }}
			
			@include('partials.personal-alias-input')
			
			{{ Form::submit('Create Personal Series Alias', array('class' => 'btn btn-primary')) }}
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