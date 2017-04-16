@extends('layouts.app')

@section('title')
	Tag Aliases - Page {{$aliases->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
	<div class="container">
		@if($aliases->count() != 0)
			<div>
				@if(Auth::user())
					<div>
						<b>Sort By:</b>
						@if($list_type == "global")
							<b><a href="/tag_alias?type=global&order={{$list_order}}">Global</a></b>
							<a href="/tag_alias?type=personal&order={{$list_order}}">Personal</a>
							<a href="/tag_alias?type=mixed&order={{$list_order}}">Mixed</a>
						@elseif ($list_type == "personal")
							<a href="/tag_alias?type=global&order={{$list_order}}">Global</a>
							<b><a href="/tag_alias?type=personal&order={{$list_order}}">Personal</a></b>
							<a href="/tag_alias?type=mixed&order={{$list_order}}">Mixed</a>
						@elseif ($list_type == "mixed")
							<a href="/tag_alias?type=global&order={{$list_order}}">Global</a>
							<a href="/tag_alias?type=personal&order={{$list_order}}">Personal</a>
							<b><a href="/tag_alias?type=mixed&order={{$list_order}}">Mixed</a></b>
						@endif
					</div>
				@endif
				
				<div>
					<b>Display Order:</b>
					@if($list_order == "asc")
						<b><a href="/tag_alias?type={{$list_type}}&order=asc">Ascending</a></b> <a href="/tag_alias?type={{$list_type}}&order=desc">Descending</a>
					@elseif($list_order == "desc")
						<a href="/tag_alias?type={{$list_type}}&order=asc">Ascending</a> <b><a href="/tag_alias?type={{$list_type}}&order=desc">Descending</a></b>
					@endif
				</div>
			</div>
			
			@foreach($aliases as $alias)
				@if((($loop->iteration - 1) % 3) == 0)
					<div class="row">
				@endif
				
				@if($alias->user_id == null)
					<div class="col-xs-4">
						<span class="global_tag_alias"><a href="/tag/{{$alias->tag_id}}">{{{$alias->alias}}}</a></span>
					</div>
				@else
					<div class="col-xs-4">
						<span class="personal_tag_alias"><a href="/tag/{{$alias->tag_id}}">{{{$alias->alias}}}</a></span>
					</div>
				@endif
				
				@if((($loop->iteration - 1) % 3) == 2)			
					</div>
				@endif
			@endforeach	
			<br/>
			<br/>
			{{ $aliases->links() }}
		@else
			@if(Auth::user())
				<div class="text-center">
					No tag aliases have been found in the database. View tags in the database <a href = "{{url('/tag')}}">here.</a>
				</div>
				<br/>
			@else
				<div class="text-center">
					No tag aliases have been found in the database.
				</div>
				<br/>
			@endif
		@endif
	</div>
@endsection

@section('footer')

@endsection