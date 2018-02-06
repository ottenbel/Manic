@extends('layouts.app')

@section('title')
	Update Password
@endsection

@section('content')
<div class="container">
    <div class="row">
		<form class="form-horizontal" role="form" method="POST" action="{{ Route('update_password') }}">
			{{ csrf_field() }}
			{{ method_field('PATCH') }}
			
			<div class="form-group">
				<div class="row">
					{{ Form::label('password', 'Password') }}
					{{ Form::password('password', array('class' => 'form-control')) }}
					@if($errors->has('password'))
						<div class ="alert alert-danger" id="name_errors">{{$errors->first('password')}}</div>
					@endif
				</div>
			</div>

			<div class="form-group">
				<div class="row">
					{{ Form::label('newPassword', 'New Password') }}
					{{ Form::password('newPassword', array('class' => 'form-control')) }}
					@if($errors->has('newPassword'))
						<div class ="alert alert-danger" id="name_errors">{{$errors->first('newPassword')}}</div>
					@endif
				</div>
			</div>
			
			<div class="form-group">
				<div class="row">
					{{ Form::label('confirmNewPassword', 'Confirm New Password') }}
					{{ Form::password('confirmNewPassword', array('class' => 'form-control')) }}
					@if($errors->has('confirmNewPassword'))
						<div class ="alert alert-danger" id="name_errors">{{$errors->first('confirmNewPassword')}}</div>
					@endif
				</div>
			</div>
					
			<button type="submit" class="btn btn-primary">
				Change Password
			</button>
		</form>
	</div>
</div>
            
@endsection
