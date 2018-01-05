@extends('layouts.app')

@section('title')
Create Permission - {{{$permission->name}}}
@endsection

@section('head')
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
<div class="container">
	@can('update', $permission)
	<div class="row">
		<div class="col-xs-8"><h1>Edit Permission</h1></div>
		@can('delete', $permission)
			<div class="col-xs-4 text-right">
				<form method="POST" action="{{route('admin_delete_permission', ['permission' => $permission])}}">
					{{ csrf_field() }}
					{{method_field('DELETE')}}
					
					{{ Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete Permission', array('type' => 'submit', 'class' => 'btn btn-danger', 'onclick' =>'ConfirmDelete(event)')) }}
				</form>
			</div>
		@endcan
	</div>
	
	<form method="POST" action="{{route('admin_update_permission', ['permission' => $permission])}}" enctype="multipart/form-data">
		{{ csrf_field() }}
		{{method_field('PATCH')}}
	
		@include('partials.rolesAndPermissions.permissions.permission-input')
		
		{{ Form::submit('Update Permission', array('class' => 'btn btn-primary')) }}
	</form>
	@endcan
	
	@cannot('update', $permission)
		<h1>Error</h1>
		<div class="alert alert-danger" role="alert">
			User does not have the correct permissions in order to edit permission.
		</div>
	@endcan
</div>
@endsection

@section('footer')

@endsection