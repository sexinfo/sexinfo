// Add a class of 'img-reveal' to any <img> to automatically add toggle controls

$(function() {
    var $imgs = $('.img-reveal');
    $imgs.wrap("<div class='img-reveal-container'></div>");
    $imgs.css('display', 'none');

    $(document).on('click', '.img-reveal-container', function() {
      $(this).find('img').toggle();
      $(this).toggleClass('img-reveal-container-revealed');
    });
});

