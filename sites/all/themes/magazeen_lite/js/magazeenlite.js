// Suck it Drupal
window.$ = jQuery;

// Global SexInfo object
window.SexInfo = {};


// Cache popular words once we fetch them from the server
SexInfo.popularWords = [];


// Parse a list of popular words stored at data/words.json
// As this is a deferred action, you must use a callback
// instead of a return value.
//
// handler    - Callback function invoked with word list
// errHandler - Optional callback function invoked in case of AJAX failure
//
// Ex:
//
//   SexInfo.getPopularWords(function(words) {
//     console.log(words);
//   });
//
// Ex:
//
//   SexInfo.getPopularWords(function(words) {
//     console.log(words);
//   }, function() { console.log("Error!") });
//
// See jQuery deferred.then: http://api.jquery.com/deferred.then/
//
SexInfo.getPopularWords = function(handler, errHandler) {
  if (SexInfo.popularWords.length) {
    handler(SexInfo.popularWords);
  } else {
    $.getJSON('/sexinfo/data/words.json').then(function(json) {
      SexInfo.popularWords = json.words
      handler(json.words);
    }, errHandler);
  }
}


// Take a string or array of words and select all that among the most popular words
// stored at data/words.json
// Invokes a callback with the result words
//
// rawInput - String or Array[String] consisting of input to be filtered
// callback - Function to be invoked with filtered content on completion
//
// Ex:
//
//   SexInfo.filterPopularWords("my penis hurts", function(words) {
//     console.log(words); // => ["penis"]
//   });
//
SexInfo.filterPopularWords = function(rawInput, callback) {
  SexInfo.getPopularWords(function(words) {
    var result = [],
        input  = (typeof rawInput === 'string') ? rawInput.split(' ') : rawInput;

    for(var i=0, len=input.length; i<len; i++) {
      var word = input[i];
      if($.inArray(word, words) > -1) {
        result.push(word);
      }
    }

    callback(result);
  });
}



$(function() {
  // Hide author info
  $('.node-info').empty();
})
