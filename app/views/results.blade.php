<div class="results">
	@if (!isset($hits))	
	@elseif (count($hits) === 0)
		<div class='no-results'><h1>No Results</h1></div>
	@else
		@foreach ($hits as $hit)
			<div class="panel panel-default result">
				<div class="result-heading alt">
					<a href="#">
						{{ HTML::image("assets/agency/" . $hit['agencies'][0]['_id'] . '.png', $hit['agencies'][0]['name']) }}
					</a>
				<h2>{{ $hit['name'] }}</h2>
				<p class="lead">{{ $hit['agencies'][0]['name'] }}</p>
				</div>

				<div class="panel-body">
				</div>
			</div>
		@endforeach
	@endif
</div>