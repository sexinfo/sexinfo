$(document).ready(function() {
    var currentLocation = location.pathname;

    // Only runs on the raffle landing page
    if(currentLocation.includes('/sexinfo/raffle-landing-page') === true) {
        //console.log("On Raffle Landing Page");
        var instagramBlock = $("#block-instagram_block-instagram_block")[0];
        var instagramHeader = document.createElement('center');
        instagramHeader.innerHTML = "<h4>Instagram Feed</h4>";
        $(instagramBlock.children[0]).replaceWith(instagramHeader)
        $("#instagram-block").append(instagramBlock);

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
