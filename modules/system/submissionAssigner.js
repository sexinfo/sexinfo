'use strict';
var $ = jQuery;
$(document).ready(function() {
    var currentLocation = location.pathname;

    // Only runs on the view page for a single submission.
    if(currentLocation.includes('/sexinfo/node/22/submission/') === true) {
        var secondTabElement = $("#branding > ul > li:nth-child(2)")[0];
        if(secondTabElement.innerText === "EDIT") {
            console.log("Hello, Developer!");
        }
        else {
            console.log("Hello, Writer!");
        }
    }
});
