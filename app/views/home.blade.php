@extends('layouts.master')

@section('content')
	<div class="row" style="padding:20px;">
		<div class="col-md-4">
			@include('gettingstarted')
		</div>
		<div class="col-md-6">
			@include('searchform')
		</div>
	</div>
	<div class="row" style="padding:20px;">
		<div class="col-md-4">
			@include('facets')
		</div>
		<div class="col-md-8">
			@include('results')
		</div>
	</div>
@stop