// Add a class of 'img-reveal' to any <img> to automatically add toggle controls

$(function() {
    var $imgs = $('.img-reveal');
    $imgs.wrap("<div class='img-reveal-container'></div>");
    $imgs.css('visibility', 'hidden');

    $(document).on('click', '.img-reveal-container', function() {
      var $img = $(this).find('img');
      if ($img.css('visibility') == 'hidden')
        $img.css('visibility', 'visible')
      else
        $img.css('visibility', 'hidden')
    });

    // $(".img-reveal").fadeTo(500, 0); $(".img-reveal").fadeTo(500, 1);
});

