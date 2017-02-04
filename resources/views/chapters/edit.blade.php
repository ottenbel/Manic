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
		
		{{ Form::hidden('volume_id', $chapter->volume_id) }}
		{{ Form::hidden('chapter_id', $chapter->id) }}
		
		@include('partials.chapter-input', array('chapter' => $chapter, 'volumes' => $volumes))		
		
		<table class="table table-responsive">
			@foreach($chapter->pages()->orderBy('page_number', 'asc')->get() as $page)
				@if(($loop->index % 4) == 0)
					<tr>
				@endif
					<td>
						<div>
							<img src="{{asset($page->name)}}" class="img-responsive img-rounded" alt="Page {{$chapter->page_number}}" height="150px" width="150px">
						</div>
						
						<div>
							<input type="checkbox" name="delete_pages[{{$page->id}}]" id = "delete_pages[{{$page->id}}]" value="{{Input::old('delete_pages.'.$page->id)}}"/>
							{{ Form::label("delete_pages[$page->id]", 'Delete Page') }}
						</div>
						
						@if(Input::old('chapter_pages.'.$page->id) == null)
							<div>
								<input type="text" name="chapter_pages[{{$page->id}}]" id = "chapter_page[{{$page->id}}]" value="{{$page->pivot->page_number}}"/>
							</div>
						@else
							<div>
								<input type="text" name="chapter_pages[{{$page->id}}]" id = "chapter_pages[{{$page->id}}]" value="{{Input::old('chapter_pages.'.$page->id)}}"/>
							</div>
						@endif
						
						@if($errors->has('chapter_pages.'.$page->id))
							<div class ="alert alert-danger" id="page_errors">			{{$errors->first('chapter_pages.'.$page->id)}}
							</div>
						@endif
						
						@if($errors->has('delete_pages.'.$page->id))
							<div class ="alert alert-danger" id="delete_errors">			{{$errors->first('delete_pages.'.$page->id)}}
							</div>
						@endif
						
					</td>
				@if(($loop->index % 4) == 3)
					</tr>
				@endif			
			@endforeach
		</table>
		
		@if($errors->has('page_uniqueness'))
			<div class ="alert alert-danger" id="delete_errors">
				{{$errors->first('page_uniqueness')}}
			</div>
		@endif
		
		@if($errors->has('page_requirement'))
			<div class ="alert alert-danger" id="delete_errors">
				{{$errors->first('page_requirement')}}
			</div>
		@endif
		
		{{ Form::submit('Update Chapter', array('class' => 'btn btn-primary')) }}
	</form>
</div>
@endsection

@section('footer')

@endsection