@extends('layouts.app')

@section('title')
	Character Aliases - Page {{$aliases->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
	<div class="container">
		<div>
			@if(Auth::user())
				<div>
					<b>Sort By:</b>
					@if($list_type == "global")
						<b><a href="/character_alias?type=global&order={{$list_order}}">Global</a></b>
						<a href="/character_alias?type=personal&order={{$list_order}}">Personal</a>
						<a href="/character_alias?type=mixed&order={{$list_order}}">Mixed</a>
					@elseif ($list_type == "personal")
						<a href="/character_alias?type=global&order={{$list_order}}">Global</a>
						<b><a href="/character_alias?type=personal&order={{$list_order}}">Personal</a></b>
						<a href="/character_alias?type=mixed&order={{$list_order}}">Mixed</a>
					@elseif ($list_type == "mixed")
						<a href="/character_alias?type=global&order={{$list_order}}">Global</a>
						<a href="/character_alias?type=personal&order={{$list_order}}">Personal</a>
						<b><a href="/character_alias?type=mixed&order={{$list_order}}">Mixed</a></b>
					@endif	
				</div>
			@endif
			
			<div>
				<b>Display Order:</b>
				@if($list_order == "asc")
					<b><a href="/character_alias?type={{$list_type}}&order=asc">Ascending</a></b> <a href="/character_alias?type={{$list_type}}&order=desc">Descending</a>
				@elseif($list_order == "desc")
					<a href="/character_alias?type={{$list_type}}&order=asc">Ascending</a> <b><a href="/character_alias?type={{$list_type}}&order=desc">Descending</a></b>
				@endif
			</div>
		</div>
		
		@foreach($aliases as $alias)
			@if((($loop->iteration - 1) % 3) == 0)
				<div class="row">
			@endif
			
			@if($alias->user_id == null)
				<div class="col-xs-4">
					<span class="global_character_alias"><a href="/character/{{$alias->character_id}}">{{{$alias->alias}}}</a></span>
				</div>
			@else
				<div class="col-xs-4">
					<span class="personal_character_alias"><a href="/character/{{$alias->character_id}}">{{{$alias->alias}}}</a></span>
				</div>
			@endif
			
			@if((($loop->iteration - 1) % 3) == 2)			
				</div>
			@endif
		@endforeach
	</div>
	<br/>
	<br/>
	{{ $aliases->links() }}
@endsection

@section('footer')

@endsection