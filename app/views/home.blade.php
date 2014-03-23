@extends('layouts.master')

@section('content')
<div id="pagehead">
	<div class="row">
		<div class="col-md-8">
			@include('searchform')
		</div>
	</div>
	<div class="row">
		<div class="col-md-8">
			@include('links')
		</div>
	</div>
</div>
<hr>
<div class="row" style="padding:20px;">
	<span id="number-of-results"></span>
	<div class="col-md-8">
		@include('results')
	</div>
	<div class="col-md-4">
		@include('facets')
	</div>
</div>
<div class="row">
	<div class="col-md-8">
		@include('links')
	</div>
</div>
@stop