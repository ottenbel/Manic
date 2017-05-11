<div class="container">
	<h2>{{{$tagObject->name}}}</h2>
	
	@if(Route::is('show_character'))
		<h4>Associated With Series: <a href="{{route('show_series', ['series' => $tagObject->series()->first()])}}">{{$tagObject->series->name}}</a></h4>
	@endif
	
	<div>
		@if($tagObject->usage_count())
			@if(($tagObject->primary_usage_count() > 0) && ($tagObject->secondary_usage_count() > 0))
				{{$tagObject->name}} is used a total of <b>{{$tagObject->usage_count()}}</b> times across the site.  <b>{{$tagObject->primary_usage_count()}}</b> times as a <b>primary {{$tagObjectName}}</b> and <b>{{$tagObject->secondary_usage_count()}}</b> times as a <b>secondary {{$tagObjectName}}</b>.
			@elseif($tagObject->primary_usage_count() > 0)
				{{$tagObject->name}} is used a total of <b>{{$tagObject->primary_usage_count()}}</b> times across the site as a primary {{$tagObjectName}}.
			@elseif($tagObject->secondary_usage_count() > 0)
				{{$tagObject->name}} is used a total of <b>{{$tagObject->secondary_usage_count()}}</b> times across the site as a secondary {{$tagObjectName}}.  
			@endif	
		@else
			{{$tagObject->name}} is not associated with any {{$associatedType}}.
		@endif
	</div>
	
	@if(($tagObject->description != null) && ($tagObject->description != ""))
		<br/>	
		<div id="{{$tagObjectName}}_info">
			{!!html_entity_decode(nl2br($tagObject->description))!!}
		</div>
	@endif
	
	@if($tagObject->url != null)
		<br/>
		<div>
			<span class="source_tag"><a href="{{$tagObject->url}}">Link to additional information</a></span>
		</div>
	@endif
	
	@if(Route::is('show_series'))
		@if($tagObject->characters->count())
			<h3>Associated Characters</h3>
			<div>
				<div>
					<div>
						<b>Sort By:</b>
						@if($character_list_type == "usage")
							<b><a href="{{route('show_series', ['series' => $tagObject])}}?character_type=usage&character_order={{$character_list_order}}">Character Usage</a></b> <a href="{{route('show_series', ['series' => $tagObject])}}?character_type=alphabetic&character_order={{$character_list_order}}">Alphabetic</a>
						@elseif ($character_list_type == "alphabetic")
							<a href="{{route('show_series', ['series' => $tagObject])}}?character_type=usage&character_order={{$character_list_order}}">Character Usage</a> <b><a href="{{route('show_series', ['series' => $tagObject])}}?character_type=alphabetic&character_order={{$character_list_order}}">Alphabetic</a></b>
						@endif
					</div>
					
					<div>
						<b>Display Order:</b>
						@if($character_list_order == "asc")
							<b><a href="{{route('show_series', ['series' => $tagObject])}}?character_type={{$character_list_type}}&character_order=asc">Ascending</a></b> <a href="{{route('show_series', ['series' => $tagObject])}}?character_type={{$character_list_type}}&character_order=desc">Descending</a>
						@elseif($character_list_order == "desc")
							<a href="{{route('show_series', ['series' => $tagObject])}}?character_type={{$character_list_type}}&character_order=asc">Ascending</a> <b><a href="{{route('show_series', ['series' => $tagObject])}}?character_type={{$character_list_type}}&character_order=desc">Descending</a></b>
						@endif
					</div>
				</div>
				
				@if($characters->count() != 0)
					<br/>					
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
				@endif
			</div>		
		@endif
	@endif
	
	@if(($global_aliases->count() != 0) 
		|| ((Auth::user()) && (Auth::user()->can('create', [$classAliasModelPath, true]))))
		<br/>
		<h3>Global Aliases</h3>
	
		@if($global_aliases->count() != 0)
			<div>
				<b>Display Order:</b>
				@if($global_list_order == "asc")
					<b><a href="{{route($showRoute, [$tagObjectName => $tagObject])}}?global_order=asc">Ascending</a></b> <a href="{{route($showRoute, [$tagObjectName => $tagObject])}}?global_order=desc">Descending</a>
				@elseif($global_list_order == "desc")
					<a href="{{route($showRoute, [$tagObjectName => $tagObject])}}?global_order=asc">Ascending</a> <b><a href="{{route($showRoute, [$tagObjectName => $tagObject])}}?global_order=desc">Descending</a></b>
				@endif
			</div>
			<br/>
		
			@foreach($global_aliases as $global_alias)
				<div class="row">
					<div class="col-xs-8">
						<span class="{{$globalAliasDisplayClass}}"><a>{{$global_alias->alias}}</a></span>
					</div>
					@can('delete', $global_alias)
					<div class="col-xs-4 text-right">
						<form method="POST" action="{{route($deleteTagObjectRoute, [$aliasTagObjectName => $global_alias])}}">
							{{ csrf_field() }}
							{{method_field('DELETE')}}
							
							{{ Form::submit('Delete Alias', array('class' => 'btn btn-danger', 'onclick' =>'ConfirmDelete()')) }}
						</form>
					</div>
					@endcan
				</div>
			@endforeach
			
			{{ $global_aliases->links() }}
		@endif
	
		@can('create', [$classAliasModelPath, true])
			<br/>
			<form method="POST" action="{{route($storeAliasRoute, [$tagObjectName => $tagObject])}}" enctype="multipart/form-data">
				{{ csrf_field() }}
				{{ Form::hidden('is_global_alias', true) }}
				
				@include('partials.tagObjects.alias.global-alias-input')
				
				{{ Form::submit("Create Global $titleTagObjectName Alias", array('class' => 'btn btn-primary')) }}
			</form>
		@endcan
	@endif
	
	@if(Auth::user())
		<br/>
		<h3>Personal Aliases</h3>
		
		@if($personal_aliases->count() != 0)
			<div>
				<b>Display Order:</b>
				@if($personal_list_order == "asc")
					<b><a href="{{route($showRoute, [$tagObjectName => $tagObject])}}?personal_order=asc">Ascending</a></b> <a href="{{route($showRoute, [$tagObjectName => $tagObject])}}?personal_order=desc">Descending</a>
				@elseif($personal_list_order == "desc")
					<a href="{{route($showRoute, [$tagObjectName => $tagObject])}}?personal_order=asc">Ascending</a> <b><a href="{{route($showRoute, [$tagObjectName => $tagObject])}}?personal_order=desc">Descending</a></b>
				@endif
			</div>
			<br/>
			
			@foreach($personal_aliases as $personal_alias)
				
					<div class="row">
						@can('view', $personal_alias)
							<div class="col-xs-8">
								<span class="{{$personalAliasDisplayClass}}"><a>{{$personal_alias->alias}}</a></span>
							</div>
						@endcan
						@can('delete', $personal_alias)
							<div class="col-xs-4 text-right">
								<form method="POST" action="{{route($deleteTagObjectRoute, [$aliasTagObjectName => $personal_alias])}}"">
									{{ csrf_field() }}
									{{method_field('DELETE')}}
									
									{{ Form::submit('Delete Alias', array('class' => 'btn btn-danger', 'onclick' => 'ConfirmDelete()')) }}
								</form>
							</div>
						@endcan
					</div>
			@endforeach
			
			{{ $personal_aliases->links() }}
		@endif
		
		@can('create', [$classAliasModelPath, false])
			<br/>
			<form method="POST" action="{{route($storeAliasRoute, [$tagObjectName => $tagObject])}}" enctype="multipart/form-data">
				{{ csrf_field() }}
				{{ Form::hidden('is_personal_alias', true) }}
				
				@include('partials.tagObjects.alias.personal-alias-input')
				
				{{ Form::submit("Create Personal $titleTagObjectName Alias", array('class' => 'btn btn-primary')) }}
			</form>
		@endcan
	@endif	
</div>
