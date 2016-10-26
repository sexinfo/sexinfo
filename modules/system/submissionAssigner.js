'use strict';
var $ = jQuery;

/*
 * Constants
 */
var OPEN_DAY    = 4; // Thursday
var OPEN_HOUR   = 17; // 5pm
var CLOSE_DAY   = 2; // Tuesday
var CLOSE_HOUR  = 16; // 4pm
var USERNAME_SELECTOR = "#toolbar-user > li.account.first > a > strong";
var TABLE_SELECTOR = "#update_results > table.sticky-enabled.tableheader-"
    +"processed.sticky-table > tbody";
var ASSIGNED_STATUSES = ["Assigned", "Editing", "Edited", "Sent"];
var USER_MENU_SELECTOR = "#custom-webform-comments-statusform > div > "
    +"div.form-item.form-type-select.form-item-username";

/*
 * Main method.
 */
$(document).ready(function() {
    var currentLocation = location.pathname;

    // Only runs on the view page for a single submission.
    if(currentLocation.includes('/sexinfo/node/22/submission/') === true) {
        postMessage();
        var secondTabElement = $("#branding > ul > li:nth-child(2) > a")[0];

        // If user is an administrator.
        if(secondTabElement.innerHTML === "Edit") {
            console.log("Hello, Admin!");
            // Do nothing
        }
        // If user is a writer.
        else {
            console.log("Hello, Writer!");
            // Writers can only specify themselves
            $(USER_MENU_SELECTOR).remove();
            var assigned = alreadyAssigned();
            // If assignment is closed or assigned
            if(!canAssign(new Date()) || assigned) {
                console.log("Sorry, you can't assign right now. :(");
                $('#edit-status > option:nth-child(2)').remove(); // Assigned
                $('#edit-status > option:nth-child(1)').remove(); // Unassigned
            }
            else {
                console.log("Yay! You can assign yourself!");
            }
        }
    }
});

/*
 * Checks if submission is already assigned to someone else. If a status of
 * "assigned appears before "not assigned", then it is assigned.
 */
var alreadyAssigned = function() {
    var tableElement = $(TABLE_SELECTOR)[0];
    // Fresh submission
    if (tableElement.children[0].children[0].className === "empty message") {
        console.log("This is fresh.");
        return false;
    }

    // Initialize variables
    var status = undefined;
    var assignedUser = undefined;
    var row = undefined;
    var username = $(USERNAME_SELECTOR).html(); // of the current user
    for (var index in tableElement.children) {
        row = tableElement.children[index];
        status = row.children[0].innerHTML;
        assignedUser = row.children[2].innerText;
        // If assigned to someone else.
        if(ASSIGNED_STATUSES.indexOf(status) !== -1
                && username !== assignedUser) {
            return true;
        }
        else if (status === "Not Assigned") {
            return false;
        }
    }
    return false;
}

/*
 * Check if it is currently within the assignment time interval.
 */
var canAssign = function(today) {
    //console.log(today);
    // Check if day is between Thursday and Tuesday
    // Cannot Assign

    /* 
    //For assigning between Thursday and Tues
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
    */

    //Currently can assign whenever so always return true
    return true;
}

/*
 * Injects disclaimer message below the dropdown menu.
 */
var postMessage = function() {
    //var message = "Note: Submissions can only be assigned between Thursdays at"
    //    + " 5pm and Tuesdays at 4pm.";
    var message = "Note: Submissions can now be assigned anytime"
    var messageElement = document.createElement('div');
    messageElement.innerHTML = '<small>' + message + '</small>';
    var statusElement = $('#custom-webform-comments-statusform > div > '
        + 'div.form-item.form-type-select.form-item-status')[0];
    statusElement.appendChild(messageElement);
}
