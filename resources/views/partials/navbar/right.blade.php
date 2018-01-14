<!-- search -->
<li>
<form method="POST" action="{{route('process_search')}}" enctype="multipart/form-data">
	{{ Form::text('query_string', "", array('id' => 'search', 'class' => 'form-control', 'placeholder' => 'Search...', 'style' => 'margin-top: 8px')) }}
</form>
</li>

@include('partials.navbar.components.right.tags-dropdown')

<!-- Authentication Links -->
@if (Auth::guest())
	@include('partials.navbar.components.right.guest')
@else
	@include('partials.navbar.components.right.collection')
	@include('partials.navbar.components.right.chapter')
	
	@include('partials.navbar.components.right.tagObjects.artist')
	@include('partials.navbar.components.right.tagObjects.character')
	@include('partials.navbar.components.right.tagObjects.series')
	@include('partials.navbar.components.right.tagObjects.scanalator')
	@include('partials.navbar.components.right.tagObjects.tag')
	
	@include('partials.navbar.components.right.rolesAndPermissions.permission')
	@include('partials.navbar.components.right.rolesAndPermissions.role')
	
	@include('partials.navbar.components.right.user.admin.user')
	
	@include('partials.navbar.components.right.create-dropdown')
	@include('partials.navbar.components.right.user')
@endif