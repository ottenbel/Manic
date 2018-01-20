<table class="table table-striped">
	@foreach($permissions as $permission)
		<tr>
			@if((Auth::user()->can('update', $permission)) && Route::is('admin_*'))
			<td class="col-xs-10">
				{{$permission->name}}
			</td>
			<td class="col-xs-2">
				<a class="btn btn-success btn-sm" href="{{route('admin_edit_permission', $permission)}}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
			</td>
			@else
				<td class="col-xs-12">
					{{$permission->name}}
				</td>
			@endif
		</tr>
	@endforeach
</table>