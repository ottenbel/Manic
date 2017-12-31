@extends('layouts.app')

@section('title')
	Admin Dashboard
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	@if((Auth::user()->can('Edit Global Pagination Settings')) || (Auth::user()->can('Edit Global Placeholder Settings')) || (Auth::user()->can('Edit Global Rating Restriction Settings')))
		<div class="row">
			<a href="{{route('admin_dashboard_configuration_main')}}"><b>Configuration</b></a>
		</div>
	@endif
	
	
</div>
@endsection

@section('footer')

@endsection