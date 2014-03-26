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

$(document).ready(function(event) {

  var frm = $('#big-search');

  // Main search form submit event.
  frm.submit(function(event) {

    event.preventDefault();

    submitSearchForm(frm);

  });

  // Main search form reset button.
  $(document).on('click', "#reset", function(event) {
    reset();
  });

  // Modal entity detail button.
  $(document).on('click', "#entityDetailLink", function(event) {

    event.preventDefault();

    $('#myModal').clone().attr('id','myModalDefault').appendTo('#appWindow');
    $('#myModal').modal({remote: $(this).attr("href")});
  });

  // Clear the data from modals when they are hidden.
  $(document).on('hidden.bs.modal', function (event) {

      $('#myModal').remove();
      $('#myModalDefault').attr('id','myModal');
  });

  // Facet link clicks.
  $('body').delegate( 'a.facet-link', 'click', function(event) {

    var facets = JSON.parse($("#facet").val());

    // Remove facet.
    if ($(this).hasClass('active')) {

      $(this).removeClass('active');

      var index = $.inArray($(this).attr("facet-id"), facets);
      if (index>=0) facets.splice(index, 1);

    }
    // Add new facet.
    else {

      $(this).addClass("active");

      facets.push($(this).attr("facet-id"));
    }

    $("#facet").val(JSON.stringify(facets));

    // Reset results page to 0.
    $("#from").val( 0 );

    // // Remove pagination links.
    $( ".pagination" ).empty();

    $('html, body').animate({scrollTop:0}, 300);

    submitSearchForm(frm);
  });

  // Pagination link clicks.
  $(document).on('click', ".pagination li", function(event) {

    event.preventDefault();

    $(".pagination li").removeClass("active");

    var targetLinks = $('a[href="' + event.target + '"]');

    $( targetLinks ).each(function( index ) {
      if( $.isNumeric($( this ).text())) {
        $( this ).parent().addClass("active");
      }
    });

    var pageNumber = event.target.search.replace(/^.*?\=/, '');
    
    $("#from").val( pageNumber );
    submitSearchForm(frm);

    $('html, body').animate({scrollTop:0}, 300);

  });

});

var submitSearchForm = function(frm) {

  $( "#results" ).empty();

  var opts = {
    lines: 13, // The number of lines to draw
    length: 20, // The length of each line
    width: 10, // The line thickness
    radius: 30, // The radius of the inner circle
    corners: 1, // Corner roundness (0..1)
    top: '150', // Top position relative to parent in px
  };
  var target = document.getElementById('resultsSpinner');
  var spinner = new Spinner(opts).spin(target);

  $( "#searchSubmit" ).addClass("disabled");
  $( "#reset" ).addClass("disabled");

  $.ajax({
    type: frm.attr('method'),
    url: frm.attr('action'),
    data: frm.serialize(),
    dataType: "json",
    context: this
  })
    // using the done promise callback
    .done(function(data) {

      var results = data.results;
      var facets = data.facets;
      var links = data.links;

      $( ".results" ).replaceWith(results);
      $( ".list-group-facets" ).replaceWith(facets);

      if (links) {
        $( ".pagination" ).replaceWith(links);
      }
      else {
        $( ".pagination" ).empty();
      }

      spinner.stop();

      $('#resultsSpinner').remove();

      $( ".results" ).removeClass('hidden');
      $( ".list-group-facets" ).removeClass('hidden');
      $( ".links" ).removeClass('hidden');

      $( "#searchSubmit" ).removeClass("disabled");
      $( "#reset" ).removeClass("disabled");

    })

    .fail(function(data) {
      spinner.stop();
      $('#resultsSpinner').remove();
      reset();
    });
};


var reset = function() {

    $( "#searchSubmit" ).removeClass("disabled");
    $( "#reset" ).removeClass("disabled");

    $( "#results" ).empty();
    $( "#facets-list" ).empty();
    $( ".pagination" ).empty();

    $( "#facet" ).val('[]');
    $( "#search-query" ).val('');
    $( "#from" ).val(0);
};
