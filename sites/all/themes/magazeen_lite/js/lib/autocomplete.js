$( document ).ready(function() {
  // If javascript is enabled, replace the standard webform select stuff with our cool country selection!
  var target = $('#edit-submitted-location');
  if (target.length) {
    $.get('data/countries.html', function( data ) {

      // Replace the data!
      target.replaceWith( data );

      // Enables our autocomplete mode
      // Notice we refetch the node here because in the previous command
      // we replaced the entire node with our own html data
      $('#edit-submitted-location').selectToAutocomplete();

      // Removes the hint that blocks our select item from being seen
      $('.tt-hint').remove();
    });
  }
});
