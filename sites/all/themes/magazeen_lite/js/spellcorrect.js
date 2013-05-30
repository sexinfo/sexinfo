// sift3: http://siderite.blogspot.com/2007/04/super-fast-and-accurate-string-distance.html
// JavaScript implementation taken from the Kicksend/mailcheck project, licensed under MIT
// https://github.com/kicksend/mailcheck
sift3Distance = function(s1, s2) {
  if (s1 == null || s1.length === 0) {
    if (s2 == null || s2.length === 0) {
      return 0;
    } else {
      return s2.length;
    }
  }

  if (s2 == null || s2.length === 0) { return s1.length; }

  var c         = 0,
      offset1   = 0,
      offset2   = 0,
      lcs       = 0,
      maxOffset = 5;

  while ((c + offset1 < s1.length) && (c + offset2 < s2.length)) {
    if (s1.charAt(c + offset1) == s2.charAt(c + offset2)) {
      lcs++;
    } else {
      offset1 = 0;
      offset2 = 0;
      for (var i=0; i<maxOffset; i++) {
        if ((c + i < s1.length) && (s1.charAt(c + i) == s2.charAt(c))) {
          offset1 = i;
          break;
        }
        if ((c + i < s2.length) && (s1.charAt(c) == s2.charAt(c + i))) {
          offset2 = i;
          break;
        }
      }
    }
    c++;
  }

  return (s1.length + s2.length) /2 - lcs;
}


// Build the HTML for a suggested query link
function suggestionLink(text) {
  return "<a href='/sexinfo/search/node/"+ encodeURI(text) +"'>"+ text +"</a>"
}


// Attempt to spell-correct queries in the search bar, in a lovely n^2 fashion.
// Compares input words to popular words using the sift3Distance function,
// and looks for close matches
$(function() {
  var rawInput  = $("#edit-keys").val(),
      threshold = 3;

  if (rawInput) {
    var input = rawInput.split(' '),
        anySuggestions = false,
        closestWords   = Array.prototype.slice.call(input); // Clone array

    SexInfo.getPopularWords(function(words) {
      for (var i=0, ilen=input.length; i<ilen; i++) {
        var minDist = 99;

        for (var j=0, wlen=words.length; j<wlen; j++) {
          // Reject short words and exact matches in wordlist
          if (input[i].length <= 2 || $.inArray(input[i], words) > -1) continue;

          dist = sift3Distance(input[i], words[j]);
          if (dist < minDist) {
            anySuggestions = true;
            minDist = dist;

            if (minDist < threshold) {
              closestWords[i] = words[j];
            }
          }

        }
      }

      if (anySuggestions) {
        var suggestion = closestWords.join(" ");
        $("#search-suggestions").html("Did you mean: " + suggestionLink(suggestion));
      }

    });
  }

});
