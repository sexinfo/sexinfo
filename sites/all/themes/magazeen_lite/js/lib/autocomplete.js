$( document ).ready(function() {
	$countries = $('#edit-submitted-location');
	$countries.prop("autocomplete", "off");
	$countries.addClass("typeahead");
	

	var colors = ["red", "blue", "green", "yellow", "brown", "black"];

	  $countries.typeahead({      
	  name: 'countries',                                                                                   
	  prefetch: '/data/countries.json',                                         
	  limit: 10                                                                   
	});

});


