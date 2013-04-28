// Query the search API and dispatch success/error handlers
function fetchSuggestions() {
  var $field = $("#edit-submitted-message");
  $.get('/sexinfo/search/node/' + $field.val())
  .success(showSuggestions)
  .error(error);
}


// Parse node links out of our search result html and
// display them on the form
function showSuggestions(html) {
  var $results = $(html).find('.search-result');

  // Bit of a hack here - clear out suggestions if we already have some,
  // else create a new div to contain them
  var $suggestions = $("#suggestions");
  if ($suggestions.length)
    $suggestions.empty();
  else
    $suggestions = $('<div>').attr('id', 'suggestions');

  // Wrap each link in a <div> and append it to our suggestions container
  $.each($results, function(i, el) {
    var $link      = $(el).find('h4 a'),
        $container = $('<div>');
    $container.append($link);
    $suggestions.append($container);
  });

  $("#webform-component-message").append($suggestions);
}

// TODO: what to do here?
function error() {
  console.log("An error occurred");
}


$(function() {
  // Wait a bit after keyup before hitting the API
  var timeout = -1;

  $("#edit-submitted-message").keyup(function() {
    if (timeout) clearTimeout(timeout);
    timeout = setTimeout(fetchSuggestions, 300);
  });

});
