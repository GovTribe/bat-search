@extends('layouts.master')

@section('content')
	<div class="row" style="padding:20px;">
		<div class="col-md-8">
			@include('searchform')
		</div>
		<div class="col-md-4">
		</div>
	</div>
	<div class="row" style="padding:20px;">
		<div class="col-md-8">
			@include('results')

		</div>
		<div class="col-md-4">
			@include('facets')
		</div>
	</div>
@stop