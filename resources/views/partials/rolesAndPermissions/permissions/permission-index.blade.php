<table class="table table-striped">
	@foreach($permissions as $permission)
		<tr>
			@can('update', $permission)
			<td class="col-xs-10">
				{{$permission->name}}
			</td>
			<td class="col-xs-2">
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