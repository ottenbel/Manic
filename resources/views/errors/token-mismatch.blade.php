@extends('layouts.app')

@section('title')
	CSRF Token Mismatch Error
@endsection

@section('head')

@endsection

@section('content')
<div class="container text-center">
	A token mismatch has occured.  Please go back and refresh the page before trying to submit the form again.
	<br/>
	If you are encountering this page while trying to upload images you are likely hitting server limits.  Try uploading the image set in pieces.
</div>
@endsection

@section('footer')

@endsection