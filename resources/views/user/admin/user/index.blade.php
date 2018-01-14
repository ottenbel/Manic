@extends('layouts.app')

@section('title')
	Users - Page {{$users->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
	<div class="container">
		@can('View User Index')
			<div class="row">
				<table class="table table-striped">
					@foreach($users as $user)
						<tr>
							<td class="col-xs-10">
								{{$user->name}}
							</td>
							@can('View User')
								<td class="col-xs-2">
									<a class="btn btn-success btn-sm" href="{{route('admin_show_user', $user)}}"><i class="fa fa-user" aria-hidden="true"></i> View</a>
								</td>
							@endcan
						</tr>
					@endforeach
				</table>
			</div>
			{{ $users->links() }}
		@endcan
		@cannot('View User Index')
			<h1>Error</h1>
			<div class="alert alert-danger" role="alert">
				User does not have the correct permissions in order to view the administrator user index.
		</div>
		@endcan
	</div>
@endsection

@section('footer')

@endsection