<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
	<link href="/css/tag.css" rel="stylesheet">
	<link href="/css/volume.css" rel="stylesheet">
	<link href="/css/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<!--Font awesome-->
	
     <!-- Scripts -->
    <script src="/js/app.js"></script>
	<script src="/js/volume.js"></script>
	<!-- The version of Jquery installed through composer doesn't appear to include the necessary .css file-->
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
	<script src="/js/jquery/jquery.min.js"></script>
	<script src="/js/jquery-ui/jquery-ui.min.js"></script>
	
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
	
	@yield('head')
	
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        @include('partials.right-navbar')
                    </ul>
                </div>
            </div>
        </nav>
		
		@if(!empty($flashed_warning))
			<div class="alert alert-warning" role="alert">
			{{{$flashed_warning}}}
			</div>
		@endif
		
		{{-- Add code so that any page can display data that has been flashed to session. --}}
		@if(!empty($flashed_data))
			<div class="alert alert-info" role="alert">
			{{{$flashed_data}}}
			</div>
		@endif
		
        @yield('content')
    </div>

	@yield('footer')
</body>
</html>
