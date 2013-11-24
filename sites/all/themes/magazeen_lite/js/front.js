$(function() {

  /*----------------------------
    Image carousel module
  ----------------------------*/
  var i = 1,
      loopRotate   = true, // Automatic cycling is disabled once user clicks on a carousel link
      numLinks     = 8,
      imageHeight  = 250,
      $navLinks    = $('#carousel-nav a'),
      currentClass = 'current';


  // Scroll the reel
  function rotate(id) {
    var offset = Math.abs(id-1),
      distance = imageHeight * offset;

    $('#window-inner').animate({
      top: -distance
    }, 550);
  }


  // Rotate the image frame and update links
  function cycle() {
    rotate(i);
    $navLinks.removeClass(currentClass);

    $navLinks.each(function(idx, link) {
      if (parseInt($(link).data("num")) === i)
        $(link).addClass(currentClass);
    });

    // Increment the current frame and reset to first if at end
    i++;
    if (i == (numLinks+1)) {
      i = 1;
    }
  }


  // Slide to selected link and disable auto cycling afterwards
  $navLinks.click(function() {
    clearInterval(loop);
    $navLinks.removeClass(currentClass);
    $(this).addClass(currentClass);
    rotate(parseInt($(this).data('num')));
    return false;
  });


  if (loopRotate) {
    // Cycle the frame every 5 seconds if the user hasn't clicked a link
    var loop = setInterval(cycle, 5000);
  }



  /*----------------------------
    Sliding FAQ captions
  ----------------------------*/
  $(".faq-image").hover(
    function () {
      $(".caption-slide").animate({ bottom: '0' }, 300);     // Slide up
    },
    function () {
      $(".caption-slide").animate({ bottom: '-40px' }, 300); // Slide down
    }
  );

});
