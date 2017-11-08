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
	<!--Font awesome-->
	
     <!-- Scripts -->
    <script src="/js/app.js"></script>
	<script src="/js/volume.js"></script>
	<script src="/js/autocomplete/search.js"></script>
	<!-- The version of Jquery installed through composer doesn't appear to include the necessary .css file-->
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
	
	<link href="/css/search.css" rel="stylesheet">
	
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
	
	<script>
	  $( function() {
		$( document ).tooltip();
	  } );
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
                        @include('partials.navbar.left')
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        @include('partials.navbar.right')
                    </ul>
                </div>
            </div>
        </nav>
		
		<noscript>
			<div id="javascript_enabled" class="alert alert-warning" role="alert">
				Javascript is required for optimal site functionality.  Please enable Javascript in your browser for full functionality.
			</div>
		</noscript>
		
		{{-- Add code so that any page can display data that has been flashed to session. --}}
		@if(!empty($messages['success']))
			@foreach($messages['success'] as $success)
				<div class="alert alert-success alert-dismissable" role="alert">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
					{{{$success}}}
				</div>
			@endforeach
		@endif
		
		@if(!empty($messages['data']))
			@foreach($messages['data'] as $data)
				<div class="alert alert-info alert-dismissable" role="alert">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
					{{{$data}}}
				</div>
			@endforeach
		@endif
		
		@if(!empty($messages['warning']))
			@foreach($messages['warning'] as $warning)
				<div class="alert alert-warning alert-dismissable" role="alert">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
					{{{$warning}}}
				</div>
			@endforeach
		@endif
		
        @yield('content')
    </div>

	<br/>
	<br/>
	@yield('footer')
	
	 <div class="text-center">
                Running <a href="https://gitlab.com/ottenbel/Manic">Manic (Alpha)</a> - Developed by <a href="https://gitlab.com/Ottenbel">Ottenbel</a> and <a href="https://gitlab.com/ottenbel/Manic/graphs/master">Contributors</a> (2016 - 2017)
	</div>
	<br/>	
</body>
</html>
