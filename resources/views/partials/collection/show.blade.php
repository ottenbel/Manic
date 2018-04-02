<div id = "volumes">
	@if($volumes->count() > 0)
		<br/>
		@foreach($volumes as $volume)
			@if($loop->last)
				@if ($isChapter == true)
					@include('partials.volume.show', ['volume' => $volume, 'chapterOnly' => false, 'openAccordion' => false])
				@else
					@include('partials.volume.show', ['volume' => $volume, 'chapterOnly' => false, 'openAccordion' => true])
				@endif
			@else
				@include('partials.volume.show', ['volume' => $volume, 'chapterOnly' => false, 'openAccordion' => false])
			@endif
		@endforeach
	@endif
</div>