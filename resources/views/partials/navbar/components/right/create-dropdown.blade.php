@if ((Auth::user()->can('create', App\Models\Collection::class)) 
	|| (Auth::user()->can('create', App\Models\TagObjects\Tag\Tag::class)) 
	|| (Auth::user()->can('create', App\Models\TagObjects\Artist\Artist::class)) 
	|| (Auth::user()->can('create', App\Models\TagObjects\Character\Character::class)) 
	|| (Auth::user()->can('create', App\Models\TagObjects\Scanalator\Scanalator::class)) 
	|| (Auth::user()->can('create', App\Models\TagObjects\Series\Series::class))
	|| (Auth::user()->can('create', App\Models\Language::class))
	|| (Auth::user()->can('create', App\Models\Status::class))
	|| (Auth::user()->can('create', App\Models\Rating::class)))
	<li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Create <span class="caret"></span></a>
		
		<ul class="dropdown-menu" role="menu">
			@can('create', App\Models\Collection::class)
				<li><a href="{{route('create_collection')}}"><i class="fa fa-archive" aria-hidden="true"></i> Collection</a></li>
			@endcan
			
			@if((Auth::user()->can('create', App\Models\TagObjects\Tag\Tag::class)) 
				|| (Auth::user()->can('create', App\Models\TagObjects\Artist\Artist::class)) 
				|| (Auth::user()->can('create', App\Models\TagObjects\Character\Character::class)) 
				|| (Auth::user()->can('create', App\Models\TagObjects\Scanalator\Scanalator::class)) 
				|| (Auth::user()->can('create', App\Models\TagObjects\Series\Series::class)))
				
				<div class="dropdown-divider"></div>
				<h6 class="dropdown-header">Tags</h6>
		
				@can('create', App\Models\TagObjects\Artist\Artist::class)
					<li><a href="{{ route('create_artist') }}"><i class="fa fa-paint-brush" aria-hidden="true"></i> Artist</a></li>
				@endcan
				
				@can('create', App\Models\TagObjects\Character\Character::class)
					<li><a href="{{ route('create_character') }}"><i class="fa fa-users" aria-hidden="true"></i> Character</a></li>
				@endcan
				
				@can('create', App\Models\TagObjects\Scanalator\Scanalator::class)
					<li><a href="{{ route('create_scanalator') }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Scanalator</a></li>
				@endcan
				
				@can('create', App\Models\TagObjects\Series\Series::class)
					<li><a href="{{ route('create_series') }}"><i class="fa fa-book" aria-hidden="true"></i> Series</a></li>
				@endcan
				
				@can('create', App\Models\TagObjects\Tag\Tag::class)
					<li><a href="{{route('create_tag')}}"><i class="fa fa-tags" aria-hidden="true"></i> Tag</a></li>
				@endcan
			@endif
			
			@can('create', App\Models\Language::class)
				<li><a href="{{route('create_language')}}">Language</a></li>
			@endcan

			@can('create', App\Models\Status::class)
				<li><a href="{{route('create_status')}}">Status</a></li>
			@endcan

			@can('create', App\Models\Rating::class)
				<li><a href="{{route('create_rating')}}">Rating</a></li>
			@endcan
		</ul>
	</li>
@endif