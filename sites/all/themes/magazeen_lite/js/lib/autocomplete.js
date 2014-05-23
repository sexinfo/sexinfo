$( document ).ready(function() {
  // If javascript is enabled, replace the standard webform select stuff with our cool country selection!
  if ($('#edit-submitted-location').length) {
    $.get('data/countries.html', function( data ) {
      $('#edit-submitted-location').replaceWith( data ); // Replace the data!
      $('#edit-submitted-location').selectToAutocomplete(); // Enables our autocomplete mode
      $('.tt-hint').remove(); // Removes the hint that blocks our select item from being seein
    });
  }
});
