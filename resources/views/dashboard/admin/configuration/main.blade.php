@extends('layouts.app')

@section('title')
	Admin Configuration
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
    <div class="row">
		<a href="{{route('admin_dashboard_configuration_pagination')}}"><b>Pagination</b></a>
    </div>
</div>
@endsection

@section('footer')

@endsection