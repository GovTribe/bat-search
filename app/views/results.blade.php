<span id="resultsSpinner" style="position: absolute;display: block;top: 50%;left: 50%;"></span>
<div class="results hidden" id="results">
	@if (!isset($hits))	
	@elseif (count($hits) === 0)
		<div class='no-results'><h1>No Results</h1></div>
	@else
		@foreach ($hits as $hit)
			<div class="panel panel-default" style="padding:10px;">
				<div class="row" style="padding:10px;">
					<div class="col-md-2">
						{{ HTML::image("assets/agency/" . $hit['agencies'][0]['_id'] . '.png', $hit['agencies'][0]['name'],  array('class' => 'img-responsive agency-logo'))}}
					</div>
					<div class="col-md-10">
						<a href={{'getModal/project/' . $hit['_id'] }} class="btn btn-info btn-sm pull-right" id="entityDetailLink">Details</a>
						<h2>{{ $hit['name'] }} <small>{{ $hit['status'] }} </small></h2>
						<p class="lead">{{ $hit['agencies'][0]['name'] }} <small style="font-size: .7em;">{{ Carbon\Carbon::createFromFormat('Y-m-d', $hit['timestamp'])->diffForHumans() }}</small></p>
					</div>
				</div>
				<div class="row" style="padding:10px;">
					<div class="col-md-12">
						<blockquote>
							<p style="font-size: .8em;">{{ $hit['synopsis'] }}</p>
						</blockquote>
					</div>
				</div>
				<hr>
				<div class="row" style="padding:10px;">
					<div class="col-md-3">
						<div class="vendors-callout">
							<h4>Vendors</h4>
							@if (!empty($hit['vendors']))
								<div class="list-group">
									@foreach ($hit['vendors'] as $vendor)
									<p class="list-group-item" style="border-style:none">{{ $vendor['name'] }}</p>
									@endforeach
								</div>
							@else
								<code>None</code>
							@endif
						</div>
					</div>
					<div class="col-md-3">
						<div class="contacts-callout">
							<h4>Agency Contacts</h4>
							@if (!empty($hit['people']))
								<div class="list-group">
									@foreach ($hit['people'] as $person)
										<a href={{'getModal/'. $person['type'] . '/' . $person['_id']}} class="modal-link list-group-item" id="entityDetailLink" style="border-style:none">{{ $person['name'] }}</a>
									@endforeach
								</div>
							@else
								<code>None</code>
							@endif
						</div>
					</div>
					</div>
				</div>

		@endforeach
	@endif
</div>