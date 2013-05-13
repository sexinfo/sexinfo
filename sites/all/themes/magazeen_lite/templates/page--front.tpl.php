<?php include 'header.php' ?>

<?php include 'navigation.php' ?>

<div class="container">
  <?php if ($breadcrumb): print $breadcrumb; endif; ?>

  <?php
    # "has been successfully updated", etc
    if ($show_messages && $messages): print $messages; endif;
  ?>

  <?php print render($page['help']); ?>

</div>

<div class="carousel-container">
  <?php include 'modules/carousel.php' ?>
</div>



<div class="container front-container">

  <div class="row">
    <?php include 'modules/box_grid.php' ?>
  </div>

</div>


<?php include 'footer.php' ?>



<script type="text/javascript">
  (function ($) {

    /*----------------------------
      Image carousel module
    ----------------------------*/
    var loopRotate   = true, // Automatic cycling is disabled once user clicks on a carousel link
        numLinks     = 8,
        image_height = 250,
        $navLinks    = $('#carousel-nav a');

    function rotate(id) {
      var offset = Math.abs(id-1),
        distance = image_height * offset;

      $('#window-inner').animate({
        top: -distance
      }, 550);
    }

    var i = 1;

    function cycle() {
      // Rotate the image frame
      rotate(i);
      $navLinks.removeClass('current');

      // Loop through all links in nav list
      // If the link's rel attribute (used as an identifier) matches current frame, add class 'current'
      $navLinks.each(function(idx, link) {
        if ( $(link).attr("rel") == i ) {
          $(link).addClass("current");
        }
      });

      // Increment the current frame and reset to first if at end
      i++;
      if (i == (numLinks+1)) {
        i = 1;
      }
    }


    $navLinks.click(function() {
      clearInterval(loop);              // Disable auto cycle on click
      $navLinks.removeClass('current'); // Remove current class from all tabs
      $(this).addClass('current');
      rotate( $(this).attr('rel') );
      return false;                     // Prevent browser jump to anchor link
    });


    if (loopRotate) {
      // Cycle the frame every 5 seconds if the user hasn't clicked a link
      var loop = setInterval(cycle, 5000);
    }



    /*----------------------------
      Sliding FAQ captions
    ----------------------------*/
    $(".caption-slide").hover(
      function () {
        $(this).animate({ bottom: '0' }, 300);     // Slide up
      },
      function () {
        $(this).animate({ bottom: '-40px' }, 300); // Slide down
      }
    );

  })(jQuery);
</script>
