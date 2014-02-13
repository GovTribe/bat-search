<table class="table results-table table-hover">
	<tbody>
		@if (count($hits) === 0)	
			<tr>
				<td><h3>No Results</h3></td>
			</tr>
		@else
			@foreach ($hits as $hit)
			<tr>
				<td>(agency logo)</td>
				<td>{{ $hit['name'] }}</td>
				<td>John Smith<br>
					Contracting Officer<br>
					+1 (202) 555-5555<br>
					jsmith@government.gov</td>
				<td>Awarded Vendors:<br>
					Seed Tech Industries<br>
					Microjoint Inc.</td>
			</tr>
			@endforeach
		@endif
	</tbody>
</table>
