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
		@if (!empty($entity['contractNumbers']))
			<dt>Contract Number</dt>
			<dd>{{$entity['contractNumbers'][0]}}</dd>
		@endif
		@if (!empty($entity['solicitationNumbers']))
			<dt>Solicitation Number</dt>
			<dd>{{$entity['solicitationNumbers'][0]}}</dd>
		@endif
		@if ($entity['POPs'])
			<dt>Place of Performance</dt>
			<dd>{{ $entity['POPs'] }}</dd>
		@endif
		@if (isset($entity['contractingOfficeAddress']))
			<dt>Contracting Office Address</dt>
			<dd>{{ $entity['contractingOfficeAddress'] }}</dd>
		@endif
	</dl>
	@if (!empty($entity['files']))
		<table class="table table-bordered modal-table">
		  <thead>
		    <tr>
		      <th>Description</th>
		      <th></th>
		    </tr>
		  </thead>
		  <tbody>
				@foreach ($entity['files'] as $package)
					@foreach ($package as $entry)
							@foreach ($entry as $item)
							    <tr>
							      <td>
							      	@if ($item['description'])
							      		{{ str_limit($item['description'], 200) }}
							      	@else
							      		{{ str_limit($item['name'], 50) }}
							      	@endif
							      </td>
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