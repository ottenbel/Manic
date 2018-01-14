@extends('layouts.app')

@section('title')
	Roles - Page {{$roles->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<div class="row">
		@if($roles->count() == 0)
			@can('create', Spatie\Permission\Models\Role::class)
				<div class="text-center">
					No roles have been found in the database. Add a new role <a href = "{{route('admin_create_role')}}">here.</a>
				</div>
			@endcan
			@cannot('create', Spatie\Permission\Models\Role::class)
				<div class="text-center">
					No roles have been found in the database.
				</div>
			@endcan
		@else
			@include('partials.rolesAndPermissions.roles.role-index', ['roles' => $roles])
		@endif
	</div>
	{{ $roles->links() }}
</div>
@endsection

@section('footer')

@endsection