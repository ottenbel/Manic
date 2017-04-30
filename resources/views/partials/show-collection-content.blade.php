<div id = "volumes">
	@if($volumes->count() > 0)
		<br/>
		@foreach($volumes->orderBy('volume_number', 'asc')->get() as $volume)
			@include('partials.show-volume-chapters', ['volume' => $volume, 'chapterOnly' => false])
		@endforeach
	@endif
</div>