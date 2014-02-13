<div class="list-group" id="facets-list">
@if (isset($facets))
	@foreach ($facets as $type => $data)
		@if (empty($data))
			<li class="list-group-item active">
				<blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">No {{ Str::title($type) }}</blockquote>
			</li>
		@else
			<li href="#" class="list-group-item active">
				<blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">{{ Str::title($type) }}</blockquote>
			</li>
			@foreach ($data as $name => $count)
				<a href="#" class="list-group-item">
				<span class="badge">{{$count}}</span>
				<blockquote style="margin: 0 0 0 40px; border: none; padding: 0px;">{{$name}}</blockquote>
				</a>
			@endforeach
		@endif
	@endforeach
@endif
</div>
