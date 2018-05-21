<table class="table table-striped">
	@foreach($roles as $role)
		<tr>
			<td class="col-xs-10">
			{{$role->name}}
			</td>
			<td class="col-xs-2">
				@if(Auth::check() && (Auth::user()->can('create', Spatie\Permission\Models\Permission::class) || Auth::user()->can('create', Spatie\Permission\Models\Role::class) || Auth::user()->can('update', $role) || (Auth::user()->can('delete', $role))))
					<a class="btn btn-success btn-sm" href="{{route('admin_show_role', $role)}}"><i class="fa fa-object-group" aria-hidden="true"></i> Show</a>
				@else
					<a class="btn btn-success btn-sm" href="{{route('user_show_role', $role)}}"><i class="fa fa-object-group" aria-hidden="true"></i> Show</a>
				@endif
			</td>
		</tr>
	@endforeach
</table>