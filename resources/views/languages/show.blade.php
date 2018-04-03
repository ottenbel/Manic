@extends('layouts.app')

@section('title')
	{{$language->name}}
@endsection

@section('head')
	<script src="/js/confirmdelete.js"></script>
@endsection

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<h2>{{{$language->name}}}</h2>
			</div>
			<div class="row col-md-4">
				<span style="float:right">
					<a class="btn btn-success btn-sm" href="{{route('index_collection', ['search' => 'language:' . $language->name])}}"><i class="fa fa-search" aria-hidden="true"></i> Search</a>
				</span>
				@if(Auth::check() && (Auth::user()->can('delete', $language)) && (Auth::user()->cannot('update', $language)))
					<span style="float:right">
						<form method="POST" action="{{route('delete_language', ['language' => $language])}}">
							{{ csrf_field() }}
							{{method_field('DELETE')}}
							
							{{ Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete Language', array('type' => 'submit', 'class' => 'btn btn-sm btn-danger', 'onclick' =>'ConfirmDelete(event)')) }}
						</form>
					</span>
				@endif
			</div>
		</div>
		
		@if(($language->description != null) && ($language->description != ""))
			<div><strong>Description:</strong></div>
			<div id="{{$language->name}}_description">
				{{$language->description}}
			</div>
			<br/>
		@endif
		
		<div><strong>Usage:</strong></div>
		<div>
			@if($usageCount > 0)
				{{$usageCount}} collections are set with {{$language->name}} as the associated language.
			@else
				{{$language->name}} is not associated with any collections.
			@endif
		</div>
		
		@if($language->url != null)
			<br/>
			<div>
				<span class="source_tag"><a href="{{$language->url}}">Link to additional information</a></span>
			</div>
		@endif
	
	</div>
@endsection

@section('footer')

@endsection