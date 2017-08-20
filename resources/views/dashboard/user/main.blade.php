@extends('layouts.app')

@section('title')
	User Dashboard
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
    <div class="row">
        <a href="{{route('user_dashboard_configuration_main')}}"><b>Configuration</b></a>
    </div>
</div>
@endsection

@section('footer')

@endsection