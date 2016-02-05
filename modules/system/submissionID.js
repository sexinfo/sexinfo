'use strict';

document.addEventListener("DOMContentLoaded", function(event) {
    var currentLocation = location.pathname;

    // Only runs on the view page for a single submission.
    if(currentLocation.includes('/sexinfo/node/22/submission/') === true) {
        var headingElement = document.getElementsByClassName("page-title")[0];
        var id = headingElement.innerHTML.split("#")[1];

        var submissionIdElement =
            document.getElementsByClassName("webform-component--submission-id")[0];
        submissionIdElement.innerHTML = submissionIdElement.innerHTML.concat(id);
    }
});

/*
 * This is the equivalent to $(document).ready()
 * Obtained from: http://stackoverflow.com/a/800010
 */
function bindReady() {
    if ( readyBound ) return;
    readyBound = true;
    if ( document.addEventListener ) {
        document.addEventListener( "DOMContentLoaded", function(){
            document.removeEventListener( "DOMContentLoaded", arguments.callee, false );
            jQuery.ready();
        }, false );
    } else if ( document.attachEvent ) {
        document.attachEvent("onreadystatechange", function(){
            if ( document.readyState === "complete" ) {
                document.detachEvent( "onreadystatechange", arguments.callee );
                jQuery.ready();
            }
        });
        if ( document.documentElement.doScroll && window == window.top ) (function(){
            if ( jQuery.isReady ) return;
            try {
                document.documentElement.doScroll("left");
            } catch( error ) {
                setTimeout( arguments.callee, 0 );
                return;
            }
            jQuery.ready();
        })();
    }
    jQuery.event.add( window, "load", jQuery.ready );
}
