(function ($) {
  Drupal.behaviors.taxonomy_tooltip = {
    attach: function(context, settings) {
      $('span.taxonomy-tooltip-element', context).tooltip({
        bodyHandler: function() {
          return $('.taxonomy-tooltip.' + $(this).attr('rel')).html();
        }
      });
    }
  }
})(jQuery);
