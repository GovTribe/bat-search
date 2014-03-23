<div class="list-group list-group-facets hidden" id="facets-list">
@if (isset($facets))
	@foreach ($facets as $type => $data)
		@if (!empty($data))
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">{{ $type }}</h3>
				</div>
				<div class="list-group">
				@foreach ($data as $name => $count)
						<?php 
							$facetId = base64_encode($type . ':' . $name); 
							$active = in_array($facetId, $activeFacets) ? 'active' : null;
						?>
						<a href="#" class="list-group-item facet-link {{ $active }}" facet-id="{{ $facetId }}">
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