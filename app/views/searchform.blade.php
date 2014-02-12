{{ Form::open(array('action' => 'SearchController@query')) }}
	<input name="query" type="text" class="form-control input-lg" placeholder="Start typing to search" style="border:none; height:100px; font-size:2em; outline:none; box-shadow:none !important">
{{ Form::close() }}
