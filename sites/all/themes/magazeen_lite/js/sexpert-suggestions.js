var words = [];

// Query the search API and dispatch success/error handlers
function fetchSuggestions() {
  var $field = $("#edit-submitted-message");
  var input = $field.val();

  var queryWords = input.split(' ');

  for(var i = 0, len = queryWords.length; i < len; i++){
    if($.inArray(queryWords[i], words) == -1){
      queryWords.splice(i, 1); //delete word from query
    }
  }

  var search = queryWords.join(" OR ")

  $.get('/sexinfo/search/node/' + search)
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
    $suggestions = $("<div id='suggestions'>")

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

  $.getJSON('/sexinfo/words.json').then(function(json){
    words = json.words;
  });

  $("#edit-submitted-message").keyup(function() {
    if (timeout) clearTimeout(timeout);
    timeout = setTimeout(fetchSuggestions, 300);
  });

});
