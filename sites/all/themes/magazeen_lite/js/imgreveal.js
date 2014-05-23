// Add a class of 'img-reveal' to any <img> to automatically add toggle controls

$(function() {
    $(".img-reveal").wrap("<div class='img-reveal-container'></div>");

    $(document).on('click', '.img-reveal-container', function() {
      $(this).find('img').toggle();
      $(this).toggleClass('img-reveal-container-revealed');
    });
});

