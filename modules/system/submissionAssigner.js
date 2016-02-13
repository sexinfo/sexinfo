'use strict';
var $ = jQuery;

/*
 * Constants
 */
var OPEN_DAY    = 4; // Thursday
var OPEN_HOUR   = 17; // 5pm
var CLOSE_DAY   = 2; // Tuesday
var CLOSE_HOUR  = 16; // 4pm

$(document).ready(function() {
    var currentLocation = location.pathname;

    // Only runs on the view page for a single submission.
    if(currentLocation.includes('/sexinfo/node/22/submission/') === true) {
        postMessage();

        var secondTabElement = $("#branding > ul > li:nth-child(2) > a")[0];
        if(secondTabElement.innerHTML === "Edit") {
            console.log("Hello, Developer!");
            // Do nothing
        }
        else {
            console.log("Hello, Writer!");
            if(canAssign(new Date()) === false) {
                console.log("Sorry, you can't assign today. :(");
                $('#edit-status > option:nth-child(2)').remove();
            }
            else {
                console.log("Yay! You can assign yourself!");
            }
        }
    }
});

var canAssign = function(today) {
    //console.log(today);
    // Check if day is between Thursday and Tuesday
    // Cannot Assign
    if(today.getDay() <= OPEN_DAY && today.getDay() >= CLOSE_DAY) {
        if(today.getDay() === CLOSE_DAY && today.getHours() >= CLOSE_HOUR) {
            return false;
        }
        else if(today.getDay() === OPEN_DAY && today.getHours() < OPEN_HOUR) {
            return false;
        }
        return true;
    }
    // Can Assign
    else {
        return true;
    }
}

var postMessage = function() {
    var message = "Note: Submissions can only be assigned between Thursdays at"
        + " 5pm and Tuesdays at 4pm.";
    var messageElement = document.createElement('div');
    messageElement.innerHTML = '<small>' + message + '</small>';
    var statusElement = $('#custom-webform-comments-statusform > div > '
        + 'div.form-item.form-type-select.form-item-status')[0];
    statusElement.appendChild(messageElement);
}
