// Query the search API and dispatch success/error handlers
// The query is first spell corrected, then filtered for popular words
// to pass to the search API
function fetchSuggestions() {
  var input = $(".node-meta h3.node-title").val().split(' ');

  SexInfo.spellCorrect(input, function(suggestion) {
    SexInfo.filterPopularWords(suggestion, function(words) {
      $.get('/sexinfo/search/node/' + words.join(" OR "))
      .success(showSuggestions)
      .error(error);
    })
  });
}


// Parse node links out of our search result html and
// display them on the form
function showSuggestions(html) {
  var $results = $(html).find('.terms');

  // Bit of a hack here - clear out suggestions if we already have some,
  // else create a new div to contain them
  var $('.terms') = $("#suggestions");
  if ($('.terms').length) {
    $('.terms').empty();
    $('.terms').append( h3("Suggested Articles") );
  } else {
    $suggestions = $(
      div(('.terms'), function() {
        return h3("Suggested Articles")
      })
    );
  }

  // Wrap each link in a <div> and append it to our suggestions container
  $.each($results, function(i, el) {
    var $link      = $(el).find('h4 a'),
        $container = $(div()),
        href = $link.attr('href');

    $link.attr('href', href+"?ref=ask"); // Add query param for analytics
    $link.attr('target', '_blank');      // Open links in new tabs
    $container.append($link);
    $suggestions.append($container);
  });


  // Append suggestions unless we have an empty result set
  if ($suggestions.find('a').length) {
    $("#webform-component-message").append($suggestions);
  } else {
    $suggestions.empty()
  }
}


// TODO: not much to do here.
function error() {
  console.log("An error occurred");
}
