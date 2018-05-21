<table class="table table-striped">
	@foreach($permissions as $permission)
		<tr>
			@if((Auth::user()->can('update', $permission)) || (Auth::user()->can('delete', $permission) && (Auth::user()->cannot('update', $permission))))
			<td class="col-xs-12">
				{{$permission->name}}
			
				<span style="float:right">
					@if(Auth::user()->can('update', $permission))
					<a class="btn btn-success btn-sm" href="{{route('admin_edit_permission', $permission)}}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
					@elseif(Auth::user()->can('delete', $permission) && (Auth::user()->cannot('update', $permission)))
						<form method="POST" action="{{route('admin_delete_permission', ['permission' => $permission])}}">
						{{ csrf_field() }}
						{{method_field('DELETE')}}
						
						{{ Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete Permission', array('type' => 'submit', 'class' => 'btn btn-danger', 'onclick' =>'ConfirmDelete(event)')) }}
					</form>
					@endif
				</span>
			</td>
			@else
				<td class="col-xs-12">
					{{$permission->name}}
				</td>
			@endif
		</tr>
	@endforeach
</table>