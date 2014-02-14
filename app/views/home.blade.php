@extends('layouts.master')

@section('content')
	<div class="row" style="padding:20px;">
		<div class="col-md-8">
			@include('searchform')

	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
    </div>
 	</div>
 	</div>

		</div>
		<div class="col-md-4">
		</div>
	</div>
	<div class="row" style="padding:20px;">
		<div class="col-md-8"  id="results-row">
			@include('results')
		</div>
		<div class="col-md-4">
			@include('facets')
		</div>
	</div>
@stop