@extends('layouts.app')

@section('title')
	Admin Dashboard
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
    <div class="row">
		<a href="{{route('admin_dashboard_configuration_main')}}"><b>Configuration</b></a>
    </div>
</div>
@endsection

@section('footer')

@endsection