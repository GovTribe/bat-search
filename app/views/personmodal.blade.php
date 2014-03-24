<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	<h4 class="modal-title">{{ $entity['name'] }}</h4>
</div>
<div class="modal-body">
	<dl class="dl-horizontal">
			<dt>Email</dt>
			<dd>{{ HTML::mailto($entity['mail'], $title = null, $attributes = array()) }}</dd>
			@if (isset($entity['phoneNumber']))
				<dt>Phone</dt>
				<dd>{{ $entity['phoneNumber'] }}</dd>	
			@endif
			@if (isset($entity['position']))
				<dt>Position</dt>
				<dd>{{ $entity['position'] }}</dd>	
			@endif
			<dt>Agency</dt>
			<dd>{{ $entity['agencies'][0]['name'] }}</dd>
			<dt>Office</dt>
			<dd>{{ $entity['offices'][0]['name'] }}</dd>
	</dl>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>