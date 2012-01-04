(function ($) {

// Behavior to load FlexSlider
Drupal.behaviors.flexslider = {
  attach: function(context, settings) {
    $('.flexslider-content', context).once('flexslider', function() {
      $(this).each(function() {
        var $this = $(this);
        var id = $this.attr('id');
        var optionset = settings.flexslider.instances[id];
        if (optionset) {
          $this.flexslider(settings.flexslider.optionsets[optionset]);
        }
        else {
          $this.flexslider();
        }
      });
    });
  }
};

}(jQuery));
