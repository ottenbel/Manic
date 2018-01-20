@extends('layouts.app')

@section('title')
	Permissions - Page {{$permissions->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<h1>Permissions</h1>
	<div class="row">
		@if($permissions->count() == 0)
			@can('create', Spatie\Permission\Models\Permission::class)
				<div class="text-center">
					No permissions have been found in the database. Add a new permission <a href = "{{route('admin_create_permission')}}">here.</a>
				</div>
			@endcan
			@cannot('create', Spatie\Permission\Models\Permission::class)
				<div class="text-center">
					No permissions have been found in the database.
				</div>
			@endcan
		@else
			@include('partials.rolesAndPermissions.permissions.permission-index')
		@endif
	</div>
	{{ $permissions->links() }}
</div>
@endsection

@section('footer')

@endsection