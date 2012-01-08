(function ($) {
  // @todo convert to use Drupal.behaviors
  // @todo add configuration options

  Drupal.behaviors.flexslider_views_slideshow = {
    attach: function (context) {
      $('.flexslider_views_slideshow_main:not(.flexslider_views_slideshow-processed)', context).addClass('flexslider_views_slideshow-processed').each(function() {
        // Get the ID of the slideshow
        var fullId = '#' + $(this).attr('id');

        // Create settings container
        var settings = Drupal.settings.flexslider_views_slideshow[fullId];

        //console.log(settings);

        // @todo map the settings from the form to their javascript equivalents
        settings.targetId = fullId;
        
        settings.loaded = false;

        // Assign default settings
        settings.opts = {
          animation:settings.animation,
          slideDirection:settings.slidedirection,
          slideshow:settings.slideshow,
          slideshowSpeed:settings.slideshowSpeed,
          animationDuration:settings.animationduration,
          directionNav:settings.directionnav,
          controlNav:settings.controlnav,
          keyboardNav:settings.keyboardnav,
          mousewheel:settings.mousewheel,
          prevText:settings.prevtext,
          nextText:settings.nexttext,
          pausePlay:settings.pauseplay,
          pauseText:settings.pausetext,
          playText:settings.playtext,
          randomize:settings.randomize,
          slideToStart:settings.slidetostart,
          animationLoop:settings.animationloop,
          pauseOnAction:settings.pauseonaction,
          pauseOnHover:settings.pauseonhover,
          controlsContainer:settings.controlscontainer,
          manualControls:settings.manualcontrols
        };

        Drupal.flexsliderViews.load(fullId);
      });
    }
  };


  // Initialize the flexslider object
  Drupal.flexsliderViews = Drupal.flexsliderViews || {};

  // Load mapping from Views Slideshow to FlexSlider
  Drupal.flexsliderViews.load = function(fullId) {
    var settings = Drupal.settings.flexslider_views_slideshow[fullId];

    // Ensure the slider isn't already loaded
    if (!settings.loaded) {
      $(settings.targetId + " .flexslider").flexslider(settings.opts);
      console.log(settings.targetId + " .flexslider");
      settings.loaded = true;
    }
  }

  // Pause mapping from Views Slideshow to FlexSlider
  Drupal.flexsliderViews.pause = function (options) {
    console.log('pause called');
    console.log(options);
    $('#flexslider_views_slideshow_main_' + options.slideshowID + ' .flexslider').pause();
  }

  // Play mapping from Views Slideshow to FlexSlider
  Drupal.flexsliderViews.play = function (options) {
    $('#flexslider_views_slideshow_main_' + options.slideshowID + ' .flexslider').play();
    console.log('play called');
  }
  // @todo add support for jquery mobile page init
})(jQuery);