@extends('layouts.app')

@section('title')
	Permissions - Page {{$permissions->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<div class="row">
		@if($permissions->count() == 0)
			@can('create', Spatie\Permission\Models\Permission::class)
				<div class="text-center">
					No permissions have been found in the database. Add a new permission <a href = "{{route('create_permission')}}">here.</a>
				</div>
			@endcan
			@cannot('create', Spatie\Permission\Models\Permission::class)
				<div class="text-center">
					No permissions have been found in the database.
				</div>
			@endcan
		@else
			<table class="table table-striped">
				@foreach($permissions as $permission)
				<tr>
					@can('update', $permission)
					<td class="col-xs-10">
						{{$permission->name}}
					</td>
					<td>
						<a class="btn btn-success btn-sm" href="{{route('edit_permission', $permission)}}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
					</td>
					@endcan
					@cannot('update', $permission)
						<td class="col-xs-12">
							{{$permission->name}}
						</td>
					@endcan
				</tr>
				@endforeach
			</table>
		@endif
	</div>
	{{ $permissions->links() }}
</div>
@endsection

@section('footer')

@endsection