$( document ).ready(function() {
  $.get( "data/countries.html", function( data ) {
    $( "#edit-submitted-location" ).replaceWith( data );
    $('#edit-submitted-location').selectToAutocomplete();
    $('.tt-hint').remove();
  });
  $countries = $('#edit-submitted-location');
	$countries.prop("autocomplete", "off");
	$countries.addClass("typeahead");

	$countries.typeahead({
	  name: 'countries',
	  prefetch: '/data/countries.json',
	  limit: 10
	});

});


