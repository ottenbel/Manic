<table class="table table-striped">
	@foreach($roles as $role)
		<tr>
			<td class="col-xs-10">
			{{$role->name}}
			</td>
			<td class="col-xs-2">
				@if(Route::is('admin_*'))
					<a class="btn btn-success btn-sm" href="{{route('admin_show_role', $role)}}"><i class="fa fa-object-group" aria-hidden="true"></i> Show</a>
				@elseif(Route::is('user_*'))
					<a class="btn btn-success btn-sm" href="{{route('user_show_role', $role)}}"><i class="fa fa-object-group" aria-hidden="true"></i> Show</a>
				@endif
			</td>
		</tr>
	@endforeach
</table>