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
//= require ../components/spin.js/spin.js
//= require ../components/spin.js/jquery.spin.js
//= require_tree .
var frm = $('#big-search');
$('#big-search').submit(function (ev) {
	frm.find('input[name="facet"]').val('');
						
						var opts = {
						lines: 13, // The number of lines to draw
						length: 20, // The length of each line
						width: 10, // The line thickness
						radius: 30, // The radius of the inner circle
						corners: 1, // Corner roundness (0..1)
						rotate: 0, // The rotation offset
						direction: 1, // 1: clockwise, -1: counterclockwise
						color: '#000', // #rgb or #rrggbb or array of colors
						speed: 1, // Rounds per second
						trail: 60, // Afterglow percentage
						shadow: false, // Whether to render a shadow
						hwaccel: false, // Whether to use hardware acceleration
						className: 'spinner', // The CSS class to assign to the spinner
						zIndex: 2e9, // The z-index (defaults to 2000000000)
						top: '200', // Top position relative to parent in px
						left: 'auto' // Left position relative to parent in px
						};
						var target = document.getElementById('results-row');
						var spinner = new Spinner(opts).spin(target);
						
	
	$.ajax({
		type: frm.attr('method'),
		url: frm.attr('action'),
		data: frm.serialize(),
		dataType: "json",
		success: function (data) {
			spinner.stop();
			$( ".results" ).replaceWith(data.results);
			$( ".list-group-facets" ).replaceWith(data.facets);
		   

			$('#results').on('click', 'a', function(){
				        event.preventDefault()
				        $('#myModal').removeData("modal")
				        $('#myModal').modal({remote: $(this).attr("href")})
			});
		   
		   $('#myModal').on('hidden.bs.modal', function () {
				console.log('Hiding');
				$(this).removeData("bs.modal")
			})
			
			$('#facets-list').on('click', 'a', function(){

				$('#facets-list').children().has("a").each(function(){
					$(this).children().each(function(){
						$(this).children().each(function(){
							console.log($(this));
							$(this).removeClass("active");
						});
					});
				});
								 
				$(this).addClass("active");
				
				var clickedFacetValue = $(this).attr("id");
				var currentFacetValue = frm.find('input[name="facet"]').val();
				if (clickedFacetValue === currentFacetValue) {
					console.log('Reset to base query');
					frm.find('input[name="facet"]').val('');
				} else {
					frm.find('input[name="facet"]').val(clickedFacetValue);
				}
								 
				var opts = {
				lines: 13, // The number of lines to draw
				length: 20, // The length of each line
				width: 10, // The line thickness
				radius: 30, // The radius of the inner circle
				corners: 1, // Corner roundness (0..1)
				rotate: 0, // The rotation offset
				direction: 1, // 1: clockwise, -1: counterclockwise
				color: '#000', // #rgb or #rrggbb or array of colors
				speed: 1, // Rounds per second
				trail: 60, // Afterglow percentage
				shadow: false, // Whether to render a shadow
				hwaccel: false, // Whether to use hardware acceleration
				className: 'spinner', // The CSS class to assign to the spinner
				zIndex: 2e9, // The z-index (defaults to 2000000000)
				top: '200', // Top position relative to parent in px
				left: 'auto' // Left position relative to parent in px
				};
				var target = document.getElementById('results-row');
				var spinner = new Spinner(opts).spin(target);
								 
								 
								 $.ajax({
										type: frm.attr('method'),
										url: frm.attr('action'),
										data: frm.serialize(),
										dataType: "json",
										success: function (data) {
										spinner.stop();
										$( ".results" ).replaceWith(data.results);
										
										
										}
								});
				
				});
			}
	});
	ev.preventDefault();
});