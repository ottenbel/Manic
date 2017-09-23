@extends('layouts.app')

@section('title')
	User Configuration
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
    <div class="row">
        <a href="{{route('user_dashboard_configuration_pagination')}}"><b>Pagination</b></a>
    </div>
	@if(Auth::user()->has_editor_permission())
		<div class="row">
			<a href="{{route('user_dashboard_configuration_placeholders')}}"><b>Placeholders</b></a>
		</div>
	@endif
</div>
@endsection

@section('footer')

@endsection