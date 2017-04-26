@extends('layouts.app')

@section('title')
Edit Volume - {{{$volume->name}}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	@can('update', $volume)
		<h1>Edit Volume</h1>
		
		<form method="POST" action="{{route('update_volume', ['volume' => $volume])}}" enctype="multipart/form-data">
			{{ csrf_field() }}
			{{method_field('PATCH')}}
			
			{{ Form::hidden('collection_id', $volume->collection_id) }}
			{{ Form::hidden('volume_id', $volume->id) }}
			
			@include('partials.volume-input', array('volume' => $volume))
			
			<div id = "chapters">
				@foreach($volume->chapters()->orderBy('chapter_number', 'asc')->get() as $chapter)
					<div id="chapter">
						@if($chapter->name != null && $chapter->name != "")
							<a href="/chapter/{{$chapter->id}}/edit">Chapter {{$chapter->chapter_number}} - {{$chapter->name}}</a>
						@else
							<a href="/chapter/{{$chapter->id}}/edit">Chapter {{$chapter->chapter_number}}</a>
						@endif
					</div>
				@endforeach
			</div>
			<br/>
			
			{{ Form::submit('Update Volume', array('class' => 'btn btn-primary')) }}
		</form>
	@endcan
	
	@cannot('update', $volume)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to edit volume.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection