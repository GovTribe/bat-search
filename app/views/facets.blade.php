<div class="list-group" id="facets-list">
@if (isset($facets))
	@foreach ($facets as $type => $data)
		@if (!empty($data))
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">{{ Str::title($type) }}</h3>
				</div>
				<ul class="list-group">
				@foreach ($data as $name => $count)
					<li class="list-group-item">
						<a href="#" id={{ str_replace(' ', '-', $type.'xxx'.$name) }}>
							<span class="badge">
								{{$count}}
							</span>
							{{ $name }} 
						</a>
					</li>
				@endforeach
				</ul>
			</div>				
		@endif
	@endforeach
@endif
</div>