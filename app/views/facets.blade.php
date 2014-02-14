<div class="list-group" id="facets-list">
@if (isset($facets))
	@foreach ($facets as $type => $data)
		@if (!empty($data))
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">{{ Str::title($type) }}</h3>
				</div>
				<div class="list-group">
				@foreach ($data as $name => $count)
						<a href="#" id={{ str_replace(' ', '-', $type.'xxx'.$name) }} class="list-group-item">
						<span class="badge pull-left" style="margin-right:16px">
						{{$count}}
						</span>
						{{ $name }}
						</a>
				@endforeach
				</div>
			</div>				
		@endif
	@endforeach
@endif
</div>