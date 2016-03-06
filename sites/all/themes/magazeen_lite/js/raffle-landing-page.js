$(document).ready(function() {
    var currentLocation = location.pathname;

    // Only runs on the view page for a single submission.
    if(currentLocation.includes('/sexinfo/raffle-landing-page') === true) {
        console.log("On Raffle Landing Page");
        var instagrams = $("#block-instagram_block-instagram_block")[0];
        $("#instagram-block").append(instagrams);
        console.log(instagrams);
        //$("#block-instagram_block-instagram_block").remove();
        var twitterFeed = $('#twitter-widget-0')[0];
        twitterFeed.style.width = "350px";

    }

})
