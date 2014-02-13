<ul class="list-group facets-list" id="facets">
@if (isset($facets))
	@foreach ($facets as $type => $data)
		@if (empty($data))
			<li class="list-group-item active">
				<blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">No {{ Str::title($type) }}</blockquote>
			</li>
		@else
			<li class="list-group-item active">
				<blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">{{ Str::title($type) }}</blockquote>
			</li>
			@foreach ($data as $name => $count)
				<li class="list-group-item">
				<span class="badge">{{$count}}</span>
				<blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">{{$name}}</blockquote>
				</li>
			@endforeach
		@endif
	@endforeach
@endif
</ul>
