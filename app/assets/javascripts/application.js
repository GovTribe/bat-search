// This is a manifest file that'll be compiled into application.js, which will include all the files
// listed below.
//
// Any JavaScript/Coffee file within this directory, lib/assets/javascripts, vendor/assets/javascripts,
// can be referenced here using a relative path.
//
// It's not advisable to add code directly here, but if you do, it'll appear in whatever order it 
// gets included (e.g. say you have require_tree . then the code will appear after all the directories 
// but before any files alphabetically greater than 'application.js' 
//
// The available directives right now are require, require_directory, and require_tree
//
//= require ../components/jquery/jquery.min.js
//= require ../components/bootstrap/dist/js/bootstrap.min.js
//= require_tree .

var frm = $('#big-search');
$('#big-search').submit(function (ev) {
	$.ajax({
		type: frm.attr('method'),
		url: frm.attr('action'),
		data: frm.serialize(),
		dataType: "json",
		success: function (data) {
			
			$( ".results-table" ).replaceWith(data.results);
			$( ".list-group" ).replaceWith(data.facets);

			$('#facets-list').on('click', 'a', function(){
					
					var clickedFacetValue = $(this).attr("id");
					var currentFacetValue = frm.find('input[name="facet"]').val();
					if (clickedFacetValue === currentFacetValue) {
						console.log('Reset to base query');
						frm.find('input[name="facet"]').val('');
					} else {
						console.log('Setting Facet Value');
						frm.find('input[name="facet"]').val(clickedFacetValue);
						console.log(frm.find('input[name="facet"]').val());
					}
								 $.ajax({
										type: frm.attr('method'),
										url: frm.attr('action'),
										data: frm.serialize(),
										dataType: "json",
										success: function (data) {
										
										$( ".results-table" ).replaceWith(data.results);
										
										
										}
								});
				
				});
			}
	});
	ev.preventDefault();
});