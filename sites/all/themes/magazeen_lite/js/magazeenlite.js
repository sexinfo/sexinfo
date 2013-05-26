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
