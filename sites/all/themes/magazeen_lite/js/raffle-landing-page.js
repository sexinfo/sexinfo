$(window).load(function() {
    var currentLocation = location.pathname;

    // Only runs on the view page for a single submission.
    if(currentLocation.includes('/sexinfo/raffle-landing-page') === true) {
        console.log("On Raffle Landing Page");
        var instagrams = $("#block-instagram_block-instagram_block")[0];
        $("#instagram-block").append(instagrams);

        /*
        // Remove the footer from the Twitter feed
        var twitterFrame = $('#twitter-widget-0')[0].contentDocument;
        $(twitterFrame).ready(function() {
            console.log("Twitter iframe loaded");
            var twitterDoc = $('#twitter-widget-0')[0].contentDocument.children[0];
            console.log(twitterDoc);
            $(twitterDoc.children[1].children[0].children[2])[0].remove();
        })*/
    }

})
