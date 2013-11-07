$( document ).ready(function() {
	$('#edit-submitted-location').prop("autocomplete", "off");
	$('#edit-submitted-location').addClass("typeahead");
	

var colors = ["red", "blue", "green", "yellow", "brown", "black"];

	  $('#edit-submitted-location').typeahead({      
	  name: 'countries',                                                                                   
	  prefetch: '/data/countries.json',                                         
	  limit: 10                                                                   
	});

});


