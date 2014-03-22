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

  frm.submit(function(event) {

    event.preventDefault();

    submitSearchForm(frm);

  });

  $(document).on('click', "#reset", function(event) {

    reset();

  });  

  $(document).on('click', "#entityDetailLink", function(event) {

    event.preventDefault();
    $('#myModal').clone().attr('id','myModalDefault').appendTo('#appWindow');
    $('#myModal').modal({remote: $(this).attr("href")});

  });

  $(document).on('hidden.bs.modal', function (event) {

      $('#myModal').remove();
      $('#myModalDefault').attr('id','myModal');

  });

  $('body').delegate( 'a.facet-link', 'click', function(event) {

    if ($(this).hasClass('active')) {
      $(this).removeClass('active');
      $("#facet").val('');
    }
    else {
      $(".facet-link").removeClass("active");
      $(this).addClass("active");
      $("#facet").val($(this).attr("id"));
    }

    submitSearchForm(frm);

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

      $( ".results" ).replaceWith(results);
      $( ".list-group-facets" ).replaceWith(facets);

      spinner.stop();

      $( ".results" ).removeClass('hidden');
      $( ".list-group-facets" ).removeClass('hidden');
    })

    .fail(function(data) {
      console.log(data);
    });
};


var reset = function() {

    $( "#results" ).empty();
    $( "#facets-list" ).empty();
    $( "#facet" ).val('');
    $( "#search-query" ).val('');

};
