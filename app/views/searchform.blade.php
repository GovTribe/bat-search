{{ Form::open(array('action' => 'SearchController@query', 'id' => 'big-search', 'class' => 'form-inline')) }}
<div class="form-group">
	<input name="facet" type="hidden" value="" id="facet">
	<div class="col-lg-10">
		<input name="query" type="text" id="search-query" class="form-control input-lg" placeholder="Search GovTribe with a keyword or keywords" autofocus="autofocus">
	</div>
</div>
<button type="submit" class="btn btn-primary">Go</button>
<button type="reset" id="reset" class="btn btn-danger">Reset</button>
{{ Form::close() }}