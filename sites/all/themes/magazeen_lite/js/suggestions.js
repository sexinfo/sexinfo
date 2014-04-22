// TODO: docs
function containsPregnancyKeywords(input) {
  var keywords = ['pregnant', 'pregnancy'];

  for (var i=0; i<input.length; i++) {
    for (var j=0; j<keywords.length; j++) {
      if (input[i] === keywords[j]) { return true; }
    }
  }
  return false;
}


// TODO: docs
function displayPregnancyTrap() {
  var linkText = 'Click here if you think you might be pregnant!',
      linkOptions = { 'class': 'pink-block-btn', 'href': "#" };

  var containerClass = '.pregnancy-suggestions-container',
      $suggestions = $(containerClass);

  if (!$suggestions.length) {
    // Only show suggestion box if one doesn't already exist
    var pregnancyBox = div(containerClass, a(linkOptions, linkText));
    $("#webform-component-message").append($(pregnancyBox));
  }
}


// TODO: docs
//
// Query the search API and dispatch success/error handlers
// The query is first spell corrected, then filtered for popular words
// to pass to the search API
function fetchSuggestions() {
    var input = $("#edit-submitted-message").val().split(' ');

  // TODO: check pregnancy keywords first
  if (containsPregnancyKeywords(input)) {
    displayPregnancyTrap();
  } else {
    SexInfo.spellCorrect(input, function(suggestion) {
      SexInfo.filterPopularWords(suggestion, function(words) {
        $.get('/sexinfo/search/node/' + words.join(' OR '), showSuggestions)
      })
    });
  }
}


// Parse node links out of our search result html and
// display them on the form
//
// html - A String of raw html returned from the search API
//
// Returns nothing
function showSuggestions(html) {
  var $results = $(html).find('.search-result');

  // Bit of a hack here - clear out suggestions if we already have some,
  // else create a new div to contain them
  var $suggestions = $("#suggestions");

  if ($suggestions.length) {
    $suggestions.empty();
    $suggestions.append( h3("Suggested Articles") );
  } else {
    $suggestions = $(
      div("#suggestions", function() {
        return h3("Suggested Articles")
      })
    );
  }

  // Wrap each link in a <div> and append it to our suggestions container
  $results.each(function() {
    var $link      = $(this).find('h4 a'),
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



$(function() {
  // Wait a bit after keyup before hitting the API
  var timeout = -1;

  $("#edit-submitted-message").keyup(function() {
    if (timeout) clearTimeout(timeout);
    timeout = setTimeout(fetchSuggestions, 300);
  });


  var $gender = $("#webform-component-gender-field")

  $("#edit-submitted-gender").on("change", function(){
    var selected = $(this).find("option:selected").val();

    if (selected == '3') { // 'Other'
      $gender.slideDown(500);
    } else {
      $gender.slideUp(300);
    }
  });
});
