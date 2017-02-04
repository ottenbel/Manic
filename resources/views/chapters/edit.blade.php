@extends('layouts.app')

@section('title')
Edit Chapter # {{$chapter->number}} {{{$chapter->name}}}
@endsection

@section('header')

@endsection

@section('content')
<div class="container">
	<h1>Edit Chapter</h1>
	
	<form method="POST" action="/chapter/{{$chapter->id}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		{{method_field('PATCH')}}
		
		@include('partials.chapter-input', array('chapter' => $chapter, 'volumes' => $volumes))
		
		
		<table class="table table-responsive">
			@foreach($chapter->pages()->orderBy('page_number', 'asc')->get() as $page)
				@if(($loop->index % 3) == 0)
					<tr>
				@endif
					<td>
						<div>
							<img src="{{asset($page->name)}}" class="img-responsive img-rounded" alt="Page {{$chapter->page_number}}" height="150px" width="150px">
						</div>
						
						<div>
							{{ Form::checkbox('delete_pages[]', null, Input::old("delete_page-$page->pivot->page_number"), array('id' => "delete_page-".$page->pivot->page_number)) }}
							{{ Form::label("delete_page-".$page->pivot->page_number, 'Delete Page') }}
						</div>
						
						@if(Input::old("chap_page[$page->pivot->page_number]") == null)
							<div>
								{{ Form::text("chap_page[$page->pivot->page_number]", $page->pivot->page_number) }}
							</div>
						@else
							<div>
								{{ Form::text("chap_page[$page->pivot->page_number]", Input::old("chap_page[$page->pivot->page_number]")) }}
							</div>
						@endif
						
					</td>
				@if(($loop->index % 3) == 2)
					</tr>
				@endif			
			@endforeach
		</table>
		
		{{ Form::submit('Update Chapter', array('class' => 'btn btn-primary')) }}
	</form>
</div>
@endsection

@section('footer')

@endsection