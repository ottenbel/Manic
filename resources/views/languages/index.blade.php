@extends('layouts.app')

@section('title')
	Languages - Page {{$languages->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
	<div class="container">
		<div>
			<b>Display Order:</b>
			@if($list_order == "asc")
				<b><a href="{{route('index_language')}}?&order=asc">Ascending</a></b> <a href="{{route('index_language')}}?order=desc">Descending</a>
			@elseif($list_order == "desc")
				<a href="{{route('index_language')}}?order=asc">Ascending</a> <b><a href="{{route('index_language')}}?order=desc">Descending</a></b>
			@endif
		</div>
		
		<div>
			@if($languages->count() != 0)
				@foreach($languages as $language)
					@if((($loop->iteration - 1) % 3) == 0)
						<div class="row">
					@endif
					
					<div class="col-xs-4">
						@if($language->description != null)
							<span class="language_tag" title="{{$language->description}}">
						@else
							<span class="language_tag">
						@endif
							<a href="{{route('show_language', ['language' => $language])}}">{{$language->name}}</a>
						</span>
					</div>
					
					@if((($loop->iteration - 1) % 3) == 2)
						</div>
						<br/>
					@endif
				@endforeach
				<br/>
				<br/>
				{{ $languages->links() }}			
			@else				
				<br/>
				<div class="text-center">
					<p>No languages have been found in the database.</p>
					@can('create', App\Models\Language::class)
						<p>Add a new language <a href="{{create_language}}">here</a>.</p>
					@endcan
				</div>
			@endif
		</div>
	</div>
@endsection

@section('footer')

@endsection