{{ Form::open(array('action' => 'SearchController@query', 'id' => 'big-search', 'class' => 'form-inline')) }}
<div class="form-group">
	<input name="facets" type="hidden" value="[]" id="facet">
	<input name="size" type="hidden" value="10" id="size">
	<input name="from" type="hidden" value="0" id="from">
	<div class="col-lg-10">
		<input name="query" type="text" id="search-query" class="form-control input-lg" placeholder="Search GovTribe with a keyword or keywords" autofocus="autofocus">
	</div>
</div>
<button type="submit" id="searchSubmit" class="btn btn-primary">Go</button>
<button type="reset" id="reset" class="btn btn-danger">Reset</button>
{{ Form::close() }}