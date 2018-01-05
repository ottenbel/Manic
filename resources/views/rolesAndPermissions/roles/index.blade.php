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
			<table class="table table-striped">
				@foreach($roles as $role)
				<tr>
					<td class="col-xs-10">
					{{$role->name}}
					</td>
					<td class="col-xs-2">
						<a class="btn btn-success btn-sm" href="{{route('admin_show_role', $role)}}"><i class="fa fa-object-group" aria-hidden="true"></i> Show</a>
					</td>
				</tr>
				@endforeach
			</table>
		@endif
	</div>
	{{ $roles->links() }}
</div>
@endsection

@section('footer')

@endsection