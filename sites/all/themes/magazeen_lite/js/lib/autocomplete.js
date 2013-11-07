$( document ).ready(function() {
	$countries = $('#edit-submitted-location');
	$countries.prop("autocomplete", "off");
	$countries.addClass("typeahead");

	$countries.typeahead({      
	  name: 'countries',                                                                                   
	  prefetch: '/data/countries.json',                                         
	  limit: 10                                                                   
	});

});


