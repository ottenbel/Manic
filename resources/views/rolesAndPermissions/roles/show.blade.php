@extends('layouts.app')

@section('title')
	Role - {{$role->name}}
@endsection

@section('head')
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
<div class="container">
	@if(Auth::check() && (Auth::user()->can('delete', $role)) && (Auth::user()->cannot('update', $role)))
		<div class="col-md-10">
	@else
		<div class="col-md-12">
	@endif
		<h1>{{$role->name}}</h1>
	</div>
	
	@if(Auth::check() && (Auth::user()->can('delete', $role)) && (Auth::user()->cannot('update', $role)))
		<div class="col-xs-2 text-right">
			<form method="POST" action="{{route('admin_delete_role', ['role' => $role])}}">
				{{ csrf_field() }}
				{{method_field('DELETE')}}
				
				{{ Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete Role', array('type' => 'submit', 'class' => 'btn btn-danger', 'onclick' =>'ConfirmDelete(event)')) }}
			</form>
		</div>
	@endif
	
	@if($role->permissions->count() > 0)
		<div class="col-md-12">
			<h2>Permissions</h2>
		</div>
		<div>
			@include('partials.rolesAndPermissions.permissions.permission-index', ['permissions' => $role->permissions])
		</div>
	@endif
</div>
@endsection

@section('footer')

@endsection