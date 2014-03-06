<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title">{{ $entity['name'] }}</h4>
</div>
<div class="modal-body">
	<dl class="dl-horizontal">
		<dt>Last Updated</dt>
		<dd>{{ date('Y-M-d', $entity['timestamp']); }}</dd>
		<dt>Source</dt>
		<dd><a href={{ $entity['sourceLink'] }} target="_blank">FedBizOps.gov</a></dd>
		<dt>Goods or Services</dt>
		<dd>{{ $entity['goodsOrServices'] }}</dd>
		@if ($entity['awardValueNumeric'])
			<dt>Award Value</dt>
			<dd>{{ money_format('$%i', $entity['awardValueNumeric']) }}</dd>
		@endif
		@if ($entity['setAsideType'])
			<dt>Set-Aside Type</dt>
			<dd>{{$entity['setAsideType']}}</dd>
		@endif
	</dl>
	@if (!empty($entity['files']))
		<table class="table table-bordered">
		  <thead>
		    <tr>
		      <th>Name</th>
		      <th>Description</th>
		      <th>Download</th>
		    </tr>
		  </thead>
		  <tbody>
				@foreach ($entity['files'] as $package)
					@foreach ($package as $entry)
							@foreach ($entry as $item)
							    <tr>
							      <td>{{ $item['name'] }}</td>
							      <td>{{ $item['description'] }}</td>
							      <td><a href={{ $item['uri'] }} target="_blank">Open</a></td>
							    </tr>
							@endforeach
					@endforeach
				@endforeach
			</tbody>
		</table>
	@endif

</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>