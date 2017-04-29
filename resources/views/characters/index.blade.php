@extends('layouts.app')

@section('title')
	Characters - Page {{$characters->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
	<div class="container">
		@if($characters->count() != 0)
			<div>
				<div>
					<b>Sort By:</b>
					@if($list_type == "usage")
						<b><a href="{{route('index_character')}}?type=usage&order={{$list_order}}">Character Usage</a></b> <a href="{{route('index_character')}}?type=alphabetic&order={{$list_order}}">Alphabetic</a>
					@elseif ($list_type == "alphabetic")
						<a href="{{route('index_character')}}?type=usage&order={{$list_order}}">Character Usage</a> <b><a href="{{route('index_character')}}?type=alphabetic&order={{$list_order}}">Alphabetic</a></b>
					@endif
				</div>
				
				<div>
					<b>Display Order:</b>
					@if($list_order == "asc")
						<b><a href="{{route('index_character')}}?type={{$list_type}}&order=asc">Ascending</a></b> <a href="{{route('index_character')}}?type={{$list_type}}&order=desc">Descending</a>
					@elseif($list_order == "desc")
						<a href="{{route('index_character')}}?type={{$list_type}}&order=asc">Ascending</a> <b><a href="{{route('index_character')}}?type={{$list_type}}&order=desc">Descending</a></b>
					@endif
				</div>
			</div>

		@foreach($characters as $character)
			@if((($loop->iteration - 1) % 3) == 0)
				<div class="row">
			@endif
			
			<div class="col-xs-4">
				<span class="primary_characters"><a href="{{route('show_character', ['character' => $character])}}">{{{$character->name}}} <span class="character_count">({{$character->usage_count()}})</span></a></span>
			</div>
			
			@if((($loop->iteration - 1) % 3) == 2)			
				</div>
			@endif
		@endforeach
		<br/>
		<br/>
		{{ $characters->links() }}
	@else
		@can('create', App\Models\TagObjects\Character\Character::class)
			<div class="text-center">
				No characters have been found in the database. Add a new character <a href = "{{route('create_character')}}">here.</a>
			</div>
			<br/>
		@endcan
		
		@cannot('create', App\Models\TagObjects\Character\Character::class)
			<div class="text-center">
				No characters have been found in the database.
			</div>
			<br/>
		@endcan
	@endif
</div>
@endsection

@section('footer')

@endsection