// Suck it Drupal
window.$ = jQuery;

// Global SexInfo object
window.SexInfo = {}


// Parse a list of popular words stored at data/words.json
// As this is a deferred action, you must use a callback
// instead of a return value.
//
// Ex:
//
//   SexInfo.getPopularWords(function(words) {
//     console.log(words);
//   });
//
// You can optionally pass in an error handler:
//
//   SexInfo.getPopularWords(function(words) {
//     console.log(words);
//   }, function() { console.log("Error!") });
//
// See jQuery deferred.then: http://api.jquery.com/deferred.then/
//
SexInfo.getPopularWords = function(handler, errHandler) {
  $.getJSON('/sexinfo/data/words.json').then(function(json) {
    handler(json.words);
  }, errHandler);
}


// Take an array of words and select all that among the most popular words
// stored at data/words.json
// Invokes a callback with the result words
//
// Ex:
//
//   SexInfo.filterPopularWords("my penis hurts", function(words) {
//     console.log(words); // => ["penis"]
//   });
//
SexInfo.filterPopularWords = function(input, callback) {
  SexInfo.getPopularWords(function(words) {
    var result = [];

    for(var i=0, len=input.length; i<len; i++) {
      var word = input[i];
      if($.inArray(word, words) > -1) {
        result.push(word);
      }
    }

    callback(result);
  });
}
