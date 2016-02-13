// Query the search API and dispatch success/error handlers
// The query is first spell corrected, then filtered for popular words
// to pass to the search API
function fetchSuggestions() {
  var input = $("#edit-submitted-message").val().split(' ');

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


// TODO: not much to do here.
function error() {
  console.error("An error occurred");
}

//bind change on select and check its value
$(function() {
  // Wait a bit after keyup before hitting the API
  var timeout = -1;

  $("#edit-submitted-message").keyup(function() {
    if (timeout) clearTimeout(timeout);
    timeout = setTimeout(fetchSuggestions, 300);
  });

  //console.log($("#edit-submitted-gender"));

  $("#edit-submitted-gender").on("change", function(){
    console.log("gender changed");
    if ($(this).find("option:selected").val() == "3"){ // "Other"
      {
        console.log("showing other!");
      $("#webform-component-gender-field").slideDown(500);
      }
    }
    else if ($(this).find("option:selected").val() != "3")
    {
      $("#webform-component-gender-field").slideUp(300);
    }
  });
});




  //$(this).toggleClass("#webform-component-gender-field.other");
  //document.getElementById('#webform-component-gender-field').style.display='inline';
//});
