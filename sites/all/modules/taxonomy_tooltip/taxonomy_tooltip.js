(function ($) {
  Drupal.behaviors.taxonomy_tooltip = {
    attach: function(context, settings) {
      $('span.taxonomy-tooltip-element').mouseover(function(event) {
        var tooltip = $('.taxonomy-tooltip.' + $(this).attr('rel'));
        var parent = $(this).parent();

        // Delete duplicates
        if (tooltip.length > 1) {
          $(tooltip[0].remove());
        }

        // Check if tooltip element already attached
        // If so, just position it
        if (parent.has(tooltip[0])) {
          positionTooltip(this, tooltip);
          tooltip.show();
        //
        } else {
          //$('span.taxonomy-tooltip-element').after(tooltip[0]);
          positionTooltip(this, tooltip);
          tooltip.show();
        }
      }).mouseout(function() {
        $('.taxonomy-tooltip.' + $(this).attr('rel')).hide();
      });
    }
  }
})(jQuery);

function positionTooltip(termElement, tooltip) {
  var termRect = $(termElement).position();
  var tPosX = termRect.left - 30;
  var tPosY = termRect.top + 20;
  tooltip.css({
    'position': 'absolute',
    'top': tPosY + 'px',
    'left': tPosX + 'px'
  });
};
