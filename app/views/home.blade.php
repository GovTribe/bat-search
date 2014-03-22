@extends('layouts.master')

@section('content')
<div class="row pagehead">
	<div class="col-md-8 affix-top">
		@include('searchform')
	</div>
	<div class="col-md-4">
	</div>
</div>
<hr>
<div class="row" style="padding:20px;">
	<div class="col-md-8">
		@include('results')
	</div>
	<div class="col-md-4">
		@include('facets')
	</div>
</div>
@stop