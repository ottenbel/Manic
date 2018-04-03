@extends('layouts.app')

@section('title')
	Blacklist - Page {{$collections->currentPage()}}
@endsection

@section('head')

@endsection

@section('content')
<div class="container">
	<div class="row">		
		@include('partials.collection.index')
	</div>
	{{ $collections->links() }}
</div>
@endsection

@section('footer')

@endsection